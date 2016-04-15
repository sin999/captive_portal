<?php
include_once("common.php");
include_once($project_path.$project_conf["utils_path"].$portal_conf["send_sms_utils"]);
include_once($project_path.$project_conf["utils_path"]."");

$passkeyPlaceHolder="%%passkey%%";
$messagePattern="Пароль доступа к сети ТТК для вашего телефона ".$passkeyPlaceHolder."";

$dbname="free_wifi";
#$dbname=$project_conf['db_name'];

$acc=new stdClass();
$operationResult=new stdClass();
$operationResult->err=true;
$operationResult->message="undefined";
#$acc->phone_number="9033012553";
#$acc->phone_number="9379922618";
$acc->phone_number=isset($_GET["phone_number"])?$_GET["phone_number"]:"";
$acc->phone_number=substr($acc->phone_number,-10);
$acc->country_code="7";
$acc->pass_key="";
$file = fopen("/var/log/sin/wifi_free_new.log","a");
fwrite($file,"######Send sms".$acc->phone_number."\n");
fclose($file);

if(mb_strlen($acc->phone_number)==10){
    $phoneNum=array();
    $db = mysql_connect($project_conf['db_host'],$project_conf['db_login'],$project_conf['db_password']) or die(mysql_error());
    mysql_select_db($dbname,$db) or die(mysql_error());
    mysql_query("SET NAMES 'utf8'",$db);
    $acc=getAccountAttrbutes($acc,$db);
    if(empty($acc->pass_key)){
	createAccount($acc,$db);
	$acc=getAccountAttrbutes($acc,$db);
    }
    $phoneNum[]="7".$acc->phone_number;
    $message=str_replace($passkeyPlaceHolder,$acc->pass_key,$messagePattern);
    //echo $message;
    otpravka_sms_gsmgw($phoneNum,$message);
    $operationResult->err=false;
    $operationResult->message="The password for your phone was sent!";
}else {
    $operationResult->message="Phone number format is wrong";
}
echo json_encode($operationResult);

//echo "  "."7".$acc->phone_number."  ".$acc->pass_key;

function getAccountAttrbutes($acc,$db){
    $query="select * from phone_accounts where country_code='".$acc->country_code."' and phone_number='".$acc->phone_number."'";
//    echo $query."\n";
    $result=mysql_query($query,$db);
    while ($row = mysql_fetch_object($result)) {
	$acc->pass_key=$row->pass_key;
    }
    return $acc;
}
function createAccount($acc,$db){
    $pass_key=generatePassword();
    $query="insert into phone_accounts (country_code,phone_number,pass_key) VALUES ('".$acc->country_code."','".$acc->phone_number."','".$pass_key."')";
//    echo $query."\n";
    $result=mysql_query($query,$db);
}


?>
