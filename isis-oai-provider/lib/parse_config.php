<?php

require_once(dirname(__FILE__) . '/config.php');

$CONFIG = parse_ini_file(dirname(__FILE__) . '/../config.ini', true);

foreach($CONFIG as $database) {
	if(!file_exists($MAPPING_DIR . '/' . $database['mapping'])) {
		die("O arquivo ${database['mapping']} não existe.");
	}

	$database_xrf = $database['path'] . '/' . $database['name'] . '.xrf';
	$database_mst = $database['path'] . '/' . $database['name'] . '.mst';
	//$database_cnt = $database['path'] . '/' . $database['name'] . '.cnt';
	if(!(file_exists($database_xrf) && file_exists($database_mst))) {
		die("As bases não existem, ou estão incompletas.");
	}
}

$DATABASES = array();
foreach($CONFIG as $key => $database) {
	$DATABASES[$key] = array();
	$DATABASES[$key]['setname'] = $key;
	$DATABASES[$key]['database'] = $database['path'] . '/' . $database['name'];
	$DATABASES[$key]['mapping'] = $MAPPING_DIR . '/' . $database['mapping'];
	$DATABASES[$key]['prefix'] = $database['prefix'];
}

print '<pre>';
print_r($DATABASES);

?>