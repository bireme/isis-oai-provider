;<?php /*

; EXAMPLE:
; This is a example of database configuration.

; Name of database. (This needs to be a same name of database file, includes match cases, localized in DATABASE_PATH, setted in oai-config.php)
;[lilacs]

; Name of set that isis-oai-provider will be showed in ListSets verb.
;name=LILACS

; Description of set, that will be showed in ListSets verb.
;description="Latin American and Caribbean Health Sciences"

; Path of directory that contains the database's files.
;path=/home/moa/project/bireme/vhost/isis-oai-provider/bases/isis-oai-provider

; Filename that contains the format that will be used to do the mapping of contents. (Located in wxis/pft/ directory)
;mapping=lilacs.pft

; Prefix insert in field date. (This need to be inserted in .fst file that will be used to make the invertion of database) 
;prefix=oai_date_

; Key length of database invertion (LILACS is 16/60)
;isis_key_length=1660

; Field in database that contains identifier information of register.
;identifier_field=2

; Field in database that contains datestamp information of register.
;datestamp_field=980


; Configuration of direve.(mst|xrf) database.
[direve]
name=direve
description="Events directory"
path=/home/projects/isis-oai-provider/bases/isis-oai-provider
mapping=direve.i2x
prefix=oai_date_
isis_key_length=1030
identifier_field=10
identifier_field=507


;*/ ?>