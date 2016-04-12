<?php

$conf=parse_ini_file("../../project.conf");
$common_conf=parse_ini_file($conf['commons_path'].$conf['common_conf']);
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.


$static_content_url_PLACEHOLDER=$conf['static_content_url_PLACEHOLDER'];
$script_url_PLACEHOLDER=$conf['###script_url_PLACEHOLDER'];

$static_content_url=$conf['static_content_url'];
$scripts_url=$conf['scripts_url'];

$content =file_get_contents("".$conf['index_content']);

$content = str_replace($static_content_url_PLACEHOLDER,$common_conf['portal_permanent_url'].$conf['project_name'].$static_content_url,$content);
$content = str_replace($script_url_PLACEHOLDER,$scripts_url_url,$content);

echo $content; 
include_once("remove_reclama.php");

?>
