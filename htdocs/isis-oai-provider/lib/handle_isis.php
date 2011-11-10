<?php

$config = parse_ini_file( '../oai-config.php', false);

//Receber por parâmetro
$database = '/home/projects/isis-oai-provider/bases/lilacs';
$pft = '/home/projects/isis-oai-provider/htdocs/isis-oai-provider/map/lilacs.pft';

$isis_script_url = "http://" . $_SERVER['SERVER_NAME'] . $config['CGI-BIN_DIRECTORY'] . $config['DIRECTORY'] . 'wxis.exe/'  . $config['DIRECTORY'] . '?IsisScript=list.xis&database=' . $database . "&pft=" . $pft;

die($isis_script_url);

$output = file_get_contents ($isis_script_url);

echo $output;

?>