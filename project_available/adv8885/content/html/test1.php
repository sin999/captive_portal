<?php
$conf=parse_ini_file("../../action.conf");
$common_conf=parse_ini_file($conf['commons_path'].$conf['common_conf']);

include_once($conf['commons_path'].$conf['php_nas_manager']);

$nasListDir="../../".$conf['nases_confs_dir'];
$nasArr=NasManager::createPBHK_Ip2NasManagerHashArray($common_conf,$nasListDir);

print_r($nasArr);

$nasArr=NasManager::createNAS_Ip2NasManagerHashArray($common_conf,$nasListDir);

print_r($nasArr);


?>
