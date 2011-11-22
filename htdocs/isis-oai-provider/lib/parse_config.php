<?php

require_once(dirname(__FILE__) . '/Log.php');


$config_file = dirname(__FILE__) . "/../oai-config.php";
if(!file_exists($config_file)) {
	die("ERROR: config file does not exists!");
}

$CONFIG = parse_ini_file($config_file, true);

define('LOG_DIR', $CONFIG['ENVIRONMENT']['DATABASE_PATH'] . $CONFIG['ENVIRONMENT']['DIRECTORY'] . '/log/');

// Log name example 201111.log
$log = new Log();
$log->setFileName(date('Ym') . '.log');

?>