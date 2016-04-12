#!/usr/bin/php
<?php

$conf=parse_ini_file("../../project.conf");
$common_conf=parse_ini_file($conf['commons_path'].$conf['common_conf']);
include_once($conf['commons_path'].$conf['php_nas_manager']);
$nasListDir="../../".$conf['nases_confs_dir'];
$remote_ip=$_SERVER['HTTP_X_REAL_IP'];
$remote_port=$_SERVER['HTTP_X_REAL_PORT'];

$nasArr=NasManager::createPBHK_Ip2NasManagerHashArray($common_conf,$nasListDir);
$nas=isset($nasArr[$remote_ip])?$nasArr[$remote_ip]:null;
$phone=isset($_POST['phone'])?$_POST['phone']:"";
$phone="7".substr($phone,-10);

$login="PHONE_USER_".$phone;
$password = "111";


if($nas!=null){
    $PBHK=$nas->calcPBHKbyIpPort($remote_ip,$remote_port);
    echo "NAS Has been found! ".$PBHK." login".$login." pass ".$password;    
    $nas->sessionLogonPBHK($PBHK,$login,$password);
    saveDb($phone,$conf);    
}else {
    echo "NAS Has not been found! ".$PBHK;    
}




/*
$conf=parse_ini_file("../../adv.conf");
/*
$login="radius";
$password="FIFA2008";
$host="127.0.0.1";
$dbname="radius";
$db= new mysqli($conf['db_host'],$conf['db_login'],$conf['db_password'],$conf['db_name']);

$remote_port=$_SERVER['HTTP_X_REAL_PORT'];
$bundle=(int)($remote_port/pow(2,$conf['phbk_length']));
$PortBundleId=$_SERVER['HTTP_X_REAL_IP'].":".$bundle;
$hasPhone=isset($_POST['hasphone'])?$_POST['hasphone']:'false';
$phone=isset($_POST['phone'])?$_POST['phone']:"";
$phone="7".substr($phone,-10);
saveDb($phone);

$command="sudo /usr/local/sin/nas_utils/logon.sh -nas ".$conf['target_nas']." -phbk ".$PortBundleId." -login PHONE_USER_".$phone." -password 111 > /var/log/service-del.log";
$result = exec($command);
echo $phone."  ".pow(2,$conf['phbk_length'])."    >>   ".$command."   >> ".$result;
*/
function saveDb($phone,$conf){

    $db= new mysqli($conf['db_host'],$conf['db_login'],$conf['db_password'],$conf['db_name']);
    $query="insert into Free_WiFI_Phone (phone) values('".$phone."') ON DUPLICATE KEY UPDATE lastupdate=CURRENT_TIMESTAMP";
    $result = $db->query($query);
}

?>
