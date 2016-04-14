#!/usr/bin/php
<?php
$login="radius";
$password="FIFA2008";
$host="127.0.0.1";
$dbname="radius";
$db= new mysqli($host,$login,$password,$dbname);

$remote_port=$_SERVER['HTTP_X_REAL_PORT'];
$bundle=(int)($remote_port/4);
$PortBundleId=$_SERVER['HTTP_X_REAL_IP'].":".$bundle;
$hasPhone=isset($_POST['hasphone'])?$_POST['hasphone']:'false';
$phone=isset($_POST['phone'])?$_POST['phone']:"";
#$phone="7".substr($phone,-10);
#saveDb($phone);
#saveDb($db,$PortBundleId,$phone,$hasPhone);
#exec("sudo /usr/local/sin/nas_utils/coa_service_manager.sh -nas JcDDcW8g@10.207.35.10:3799 -add REGINFUFA020000 -phbk ".$PortBundleId." > /var/log/service-add.log");
#exec("sudo /usr/local/sin/nas_utils/coa_service_manager.sh -nas JcDDcW8g@10.207.35.10:3799 -del L4REDIRECT_UNAUTH_VATRUSHKA,BASE_ACCESS -phbk ".$PortBundleId." > /var/log/service-del.log");
#exec("sudo /usr/local/sin/nas_utils/logon.sh  PHONE_USER_".$phone." 111 ". $PortBundleId."  > /aaa");
exec("sudo /usr/local/sin/nas_utils/logon.sh -nas cisco123@10.207.35.10:3799 -phbk".$PortBundleId." -login ".$phone." -password 111 > /aaa");

function saveDb($phone){
    global $db;
    $query="insert into Free_WiFI_Phone (phone) values('".$phone."') ON DUPLICATE KEY UPDATE lastupdate=CURRENT_TIMESTAMP";
    $result = $db->query($query);
}

?>
