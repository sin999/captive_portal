<?php
$login="radius";
$password="FIFA2008";
$host="127.0.0.1";
$dbname="radius";
$db= new mysqli($host,$login,$password,$dbname);

$remote_port=$_SERVER['HTTP_X_REAL_PORT'];
$bundle=(int)($remote_port/4);
$PortBundleId=$_SERVER['HTTP_X_REAL_IP'].":".$bundle;
$hasPhone=isset($_GET['hasphone'])?$_GET['hasphone']:'false';
$phone=isset($_GET['phone'])?$_GET['phone']:"";
//$phone="7".substr($phone,-10);
$passkey=isset($_GET['passkey'])?$_GET['passkey']:"";
$command = "sudo /usr/local/sin/nas_utils/logon.sh -nas JcDDcW8g@10.207.35.10:3799 -phbk ".$PortBundleId." -login ".$phone." -password ".$passkey."";
$out=array();
exec($command,$out);
$res="";
foreach($out as $line){
    $res.=$line."\n";
}
$file = fopen("/var/log/sin/wifi_free_new.log","a");
fwrite($file,"###\n");
fwrite($file,$command."\n");
fwrite($file,$res."\n");
fwrite($file,"Phone>".$phone."\n");
fwrite($file,"Passkey>".$passkey."\n");
fwrite($file,"PortBundle>".$PortBundleId."\n");
fwrite($file,"###\n");
fclose($file);
echo $res;
?>
