<?php

require_once(dirname(__FILE__) . '/lib/parse_config.php');
require_once(dirname(__FILE__) . '/lib/OAIServer.php');

// default verb: Identify
$verb = ($_REQUEST['verb'] == "")? "Identify" : $_REQUEST['verb'];

// usado no lugar de ITem Factory, pois necessita se passado por referencia.
$empty_array = array();

$repository_description = array(
    "Name" => $CONFIG['INFORMATION']['NAME'], // nome da bvs
    "AdminEmail" => array($CONFIG['INFORMATION']['EMAIL']), // email admin
    "EarliestDate" => "1990-01-01", // verificar na base (??)
    "DateGranularity" => "DATE", // ??
    "IDPrefix" => "", // ??
    "IDDomain" => "", // ??
    "BaseURL" => "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'],
);

$server = new OAIServer($repository_description, &$empty_array, NULL, 0);
$response = $server->GetResponse();

// show XML
header("Content-type: text/xml");
print $response;

?>