# coding: utf-8
#!/usr/bin/env python

import os
import logging
import json
import time
import urllib2 as urllib
from lxml import etree
from StringIO import StringIO
from datetime import datetime


# GLOBAL CONFIGURATIONS
# directories
DATA_DIR = "data"
LOG_DIR = "log"
# token's file
RES_TOKEN_STORAGE_FILE = 'tokens.json'
# provider url's
BASE_URL = 'http://bvsms-bases.saude.bvs.br/isis-oai-provider/'
# metadata default
METADATA_PREFIX = 'oai_dc'


# LOGGING CONFIGURATIONS
LOG_FILE_ERROR = 'error_%s.log' % datetime.isoformat(datetime.now())
logger = logging.getLogger('HARVESTER')
logger.setLevel(logging.DEBUG)

fh = logging.FileHandler(os.path.join(LOG_DIR, LOG_FILE_ERROR))
fh.setLevel(logging.ERROR)

# create console handler with a higher log level
ch = logging.StreamHandler()
ch.setLevel(logging.DEBUG)

# create formatter and add it to the handlers
formatter = logging.Formatter('[%(asctime)s](%(levelname)s) %(message)s')
ch.setFormatter(formatter)
fh.setFormatter(formatter)

# add the handlers to logger
logger.addHandler(ch)
logger.addHandler(fh)

# recursively function that get all the repository
def harvesting_recursive(baseurl, metadata_prefix=None, set=None, token=None):

	if not token:
		url = "%s/?verb=ListRecords&metadataPrefix=%s&set=%s" % (baseurl, metadata_prefix, set)
		logger.info("init harvest for %s" % set)
	else:
		logger.info("getting token %s" % token)
		url = "%s/?verb=ListRecords&resumptionToken=%s" % (baseurl, token)

	data = urllib.urlopen(url).read()
	
	try:
		xml = etree.parse(StringIO(data))
	except Exception, e:
		e = unicode(e)
		logger.error("set: %s, " % (set, e))
		return
	
	root = xml.getroot()
	old_token = token

	for e in root:
		if 'error' in e.tag:
			logger.info("finish %s." % set)
			return token

		if 'ListRecords' in e.tag:
			for sub in e:
				if 'resumptionToken' in sub.tag:
					token = sub.text
					# se nao vier token, é pq veio algum registro vazio
					if not token:
						token = old_token
						logger.info("finish %s." % set)
						return token

	setdir = os.path.join(DATA_DIR, set)
	try:
		os.mkdir(setdir)
	except:
		pass

	if old_token:
		filename = "oai%s.xml" % old_token.replace("-_-", "-")
	else:
		filename = "oai%s.xml" % token.replace("-_-", "-")
		filename = filename.replace('20', '0')

	with open(os.path.join(setdir, filename), 'w') as output:
		output.write(data)

	# recursiving..
	child = harvesting_recursive(baseurl, set=set, token=token)
	return child


initial_time = time.mktime(datetime.now().timetuple())

# creating directory and files, if then not exists.
if not os.path.exists(LOG_DIR):
	os.mkdir(LOG_DIR)

if not os.path.exists(RES_TOKEN_STORAGE_FILE):
	handle = open(RES_TOKEN_STORAGE_FILE, 'w')
	handle.close()

if not os.path.exists(DATA_DIR):
	os.mkdir(DATA_DIR)

with open(RES_TOKEN_STORAGE_FILE) as handle:
	tokens = json.loads(handle.read())

# list sets
url = "%s/?verb=ListSets" % BASE_URL
xml = urllib.urlopen(url).read()

try:
	xml = etree.parse(StringIO(xml))
except Exception, e:
	e = unicode(e)
	logger.error(e)

root = xml.getroot()
sets = []
for e in root:
	if not 'ListSets' in e.tag: continue

	for set in e:
		for item in set:
			if not 'setSpec' in item.tag: continue
			sets.append(item.text)

if not tokens:
	for set in sets:
		tokens[set] = None
	
	with open(RES_TOKEN_STORAGE_FILE, 'w') as output:
		output.write(json.dumps(tokens, indent=2))

for set, resumption_token in tokens.items():
	if resumption_token:
		token = harvesting_recursive(BASE_URL, token=resumption_token)
	else:
		token = harvesting_recursive(BASE_URL, METADATA_PREFIX, set)
	tokens[set] = token

with open(RES_TOKEN_STORAGE_FILE, 'w') as output:
	output.write(json.dumps(tokens, indent=2))

endtime = time.mktime(datetime.now().timetuple())

total_time = initial_time - endtime
logger.info("elapsed time: %s." % total_time)



# TODO enviar email se der erro em alguma base