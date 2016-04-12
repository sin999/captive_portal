#!/usr/bin/php
<?php
date_default_timezone_set("Europe/Moscow");
$action_apply="apply";
$action_remove="remove";
$conf=parse_ini_file("../../project.conf");
$common_conf=parse_ini_file($conf['commons_path'].$conf['common_conf']);
include_once($conf['commons_path'].$conf['php_nas_manager']);
$nasListDir="../../".$conf['nases_confs_dir'];

$action=$argv['1'];
$nasArr=NasManager::createNAS_Ip2NasManagerHashArray($common_conf,$nasListDir);

while($f = fgets(STDIN)){    
    if($target=parseSrting($f)){
	if(isset($nasArr[$target->nas_ip_address])){
	    if($action==$action_remove){
		$nasArr[$target->nas_ip_address]->removeServicesSessionId($target->sess_id,$conf['services']);
		echo " > ".date("F j, Y, g:i a")." to ".$target->sess_id." (".trim($target->user_name).")  services removed ".$conf['services']."\n";
	    }else{
		$nasArr[$target->nas_ip_address]->applyServicesSessionId($target->sess_id,$conf['services']);
		echo " > ".date("F j, Y, g:i a")." to ".$target->sess_id." (".trim($target->user_name).") services applyed ".$conf['services']."\n";
	    }
	}
    }
}


function parseSrting($str){
    $return=null;
    $field_arr=preg_split('/[\t]+/', $str);
    if(count($field_arr)>1){
	$return=new stdClass();
	$return->sess_id=$field_arr['0'];
	$return->nas_ip_address=trim($field_arr['1']);
	$return->user_name=$field_arr['2'];

    }
    return $return;
}


?>
