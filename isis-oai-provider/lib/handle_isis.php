<?php
require_once(dirname(__FILE__) . '/config.php');

//Receber por parâmetro
$database = '/home/projects/isis-oai-provider/bases/lilacs';
$pft = '/home/projects/isis-oai-provider/htdocs/isis-oai-provider/map/lilacs.pft';

$isis_script_url = $WXIS_URL.'/isis-oai-provider/wxis/?IsisScript=list.xis&database=' . $database . "&pft=" . $pft;

$output = file_get_contents ($isis_script_url);

echo $output;

?>