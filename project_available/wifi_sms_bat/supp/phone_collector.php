#!/usr/bin/php
<?php

$login="radius";
$password="FIFA2008";
$host="127.0.0.1";
$dbname="radius";
$db= new mysqli($host,$login,$password,$dbname);
$actionId="ACTION1";
$remote_port=$_SERVER['HTTP_X_REAL_PORT'];
#$bundle=(int)($remote_port/16);
$bundle=(int)($remote_port/4);
$PortBundleId=$_SERVER['HTTP_X_REAL_IP'].":".$bundle;
$hasPhone=isset($_POST['hasphone'])?$_POST['hasphone']:'false';
$phone=isset($_POST['phone'])?$_POST['phone']:"";
$phone="7".substr($phone,-10);
$login=getLoginNas($PortBundleId);
#$login=getLogin($PortBundleId,$db);
#write2File($login." ".$PortBundleId);
if(!empty($login)){
saveDb($db,$PortBundleId,$phone,$hasPhone,$actionId,$login);
}else{
    write2File(" login not found! phbk -> ".$PortBundleId."\n");
}

exec("sudo  /usr/local/sin/isg_man/removeReclama.php ".$PortBundleId);
echo "pbhk ".$PortBundleId;


function getLogin($PHBK,$db){
#    $login = getLoginDb($PHBK,$db);

    if(empty($login)){
	$login=getLoginNas($PHBK);
    }
    return $login;
}
function getLoginDb($PHBK,$db){
    $query="select username from radacct where cisco_account_info='S".$PHBK."' order by lastupdate asc limit 1";
    $result = $db->query($query);
    $login='';
    while($row = $result->fetch_assoc()){
	$login=$row['username'];
    }
    return $login;
}

function getLoginNas($PHBK){
    return system("sudo   /usr/local/sin/nas_utils/getLogin4PHPK.sh S".$PHBK);
}


function saveDb($db,$PortBundleId,$phone,$hasPhone,$actionId,$login){
	$query="update  ttk_actions set ttk_actions.result=".($hasPhone=='true'?1:0).",ttk_actions.ret_val='".$phone."'  where  action_id='".$actionId."' and  ttk_actions.login='".$login."' ";
	write2File($query);
	$db->query($query);
}

function write2File($str){
    date_default_timezone_set("Europe/Samara");
    $log=fopen("/var/log/sin/collector.log","a");
    fwrite($log," ".date('Y-m-d h.i.s')."  ".$str. "\n");
}

?>
