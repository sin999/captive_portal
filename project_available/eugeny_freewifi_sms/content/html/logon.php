<?php
include_once("common.php");

$hasPhone=isset($_GET['hasphone'])?$_GET['hasphone']:'false';
$phone=isset($_GET['phone'])?$_GET['phone']:"";
//$phone="7".substr($phone,-10);
//$passkey=isset($_GET['passkey'])?$_GET['passkey']:"";
$login=$phone="7".substr($phone,-10);
$password=isset($_GET['passkey'])?$_GET['passkey']:"";

if(isset($manager)){
    //рассчитать PBHK (ключи идентифицирующий сессию)
    $PBHK=$manager->calcPBHKbyIpPort($remote_ip,$remote_port);
    //Получить из Браса  логин с которым авторизировался абонент (обычно нобходимо для логирования событий)
    // при обращении к скрипту мы не знаем логина а имеем только ключ сессии
    //$sessionLogin=$manager->getLogin4PBHK($PBHK);
    // Удаляет сервисы (в переменной наименования сервисов через запятую)
    //echo $PBHK."".$services;
    //$manager->removeServicesPBHK($PBHK,$services);
    // Добавляет сервисы к сессии (в переменной наименования сервисов через запятую)
    //$manager->addServicesPBHK($PBHK,$services);
    // Дает комманду Брасу провести попытку авторизации сессии с указанным логином и паролем.
    $manager->sessionLogonPBHK($PBHK,$login,$password);
    //saveDb($project_conf,$sessionLogin);
}else{
 echo "Manager is null!!!!";
}

/*
$command = "sudo /usr/local/sin/nas_utils/logon.sh -nas JcDDcW8g@10.207.35.10:3799 -phbk ".$PortBundleId." -login ".$phone." -password ".$passkey."";
$out=array();
exec($command,$out);

//Логирование результата

$res="";
foreach($out as $line){
    $res.=$line."\n";
}
*/
/*
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
*/


?>
