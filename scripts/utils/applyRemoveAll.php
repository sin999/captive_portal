#!/usr/bin/php
<?php
if(count($argv)<2){
    echo "Project path is requeared!";
    exit();
}


$project_path=$argv['1'];

date_default_timezone_set("Europe/Moscow");
$action_apply="apply";
$action_remove="remove";
$project_conf=parse_ini_file($project_path."/project.conf");
$common_conf=parse_ini_file($project_conf['commons_path'].$project_conf['common_conf']);
include_once($project_conf['NasManagerFactory']);
$nasListDir="$project_path/".$project_conf['nases_confs_dir'];
$services = exec('./services.sh');
$action=$argv['2'];
#$nasArr=NasManager::createNAS_Ip2NasManagerHashArray($common_conf,$nasListDir);
$nm_factoty=new NasManagerFactory($project_conf,$project_path);

while($f = fgets(STDIN)){    
    if($target=parseSrting($f)){
	if(($manager=$nm_factoty->getManagerForNasIp($target->nas_ip_address))!=null){
	    if($action==$action_remove){
		$manager->removeServicesSessionId($target->sess_id,$services);
		echo " > ".date("F j, Y, g:i a")." to ".$target->sess_id." (".trim($target->user_name).")  services removed ".$services."\n";
	    }else{
		$manager->applyServicesSessionId($target->sess_id,$services);
		echo " > ".date("F j, Y, g:i a")." to ".$target->sess_id." (".trim($target->user_name).") services applyed ".$services."\n";
	    }
	}else{
	    echo "Manager hasnot been found!";
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
