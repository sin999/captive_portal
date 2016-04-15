<?php
include_once("common.php");


$phone=isset($_GET['phone'])?$_GET['phone']:"";
$phone="7".substr($phone,-10);
$login="PHONE_USER_".$phone;
$password = "111";


if(isset($manager)){
    //echo json_encode($manager);
    //рассчитать PBHK (ключи идентифицирующий сессию)
    $PBHK=$manager->calcPBHKbyIpPort($remote_ip,$remote_port);
    //Получить из Браса  логин с которым авторизировался абонент (обычно нобходимо для логирования событий)
    // при обращении к скрипту мы не знаем логина а имеем только ключ сессии
    //$sessionLogin=$manager->getLogin4PBHK($PBHK);
    // Удаляет сервисы (в переменной наименования сервисов через запятую)
    //$manager->removeServicesPBHK($PBHK,$services);
    // Добавляет сервисы к сессии (в переменной наименования сервисов через запятую)
    //$manager->addServicesPBHK($PBHK,$services);
    // Дает комманду Брасу провести попытку авторизации сессии с указанным логином и паролем.
    $manager->sessionLogonPBHK($PBHK,$login,$password);
    saveDb($project_conf,$phone);
}else{
 echo "Manager is null!!!!";
}



function saveDb($conf,$phone){
    $db= new mysqli($conf['db_host'],$conf['db_login'],$conf['db_password'],$conf['db_name']);
    $query="insert into Free_WiFI_Phone (phone) values('".$phone."') ON DUPLICATE KEY UPDATE lastupdate=CURRENT_TIMESTAMP";
//    echo $query;
    $result = $db->query($query);
}

?>
