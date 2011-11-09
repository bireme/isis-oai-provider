<?php
require_once(dirname(__FILE__) . '/config.php');

#$database = "direve";
#$map_type = "ini";

$database = "lilacs";
$map_type = "pft";

$map_file = realpath(dirname(__FILE__) . '/../map/') . '/' . $database . '.' . $map_type;

#print $map_file;

if ($map_type == 'ini'){
	$MAP = parse_ini_file( $map_file, true);	
} elseif ($map_type == 'pft'){
	$MAP = file_get_contents($map_file);
}else{
	die("Invalid or unsupported map type.");
}


print '<pre>';
print_r($MAP);

?>