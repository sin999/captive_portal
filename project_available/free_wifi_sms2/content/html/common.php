<?php
$project_path = dirname(dirname(dirname($_SERVER["SCRIPT_FILENAME"]."")));

//Константы текущего проекта
$project_conf=parse_ini_file($project_path."/project.conf");
//Общие константы всего портала
$portal_conf=parse_ini_file($project_conf['commons_path'].$project_conf['common_conf']);

$services = exec($project_path.$project_conf["utils_path"].'/services.sh');
#Получаем айпи адрес и порт абонента (так как скрипт находится под прокси, последний передает занчения в приведенных в коде переменных)
$remote_ip=$_SERVER['HTTP_X_REAL_IP'];
$remote_port=$_SERVER['HTTP_X_REAL_PORT'];
$send_log=$project_path.$project_conf["log_path"]."send.log";
$auth_log=$project_path.$project_conf["log_path"]."auth.log";
#Инстанирование списка менеджеров NAS (для каждого из директории /projectPath/nasAvailable/)
include_once($project_path.$project_conf["utils_path"].$project_conf["NasManagerFactory"]);
$nm_factoty=new NasManagerFactory($project_conf,$project_path);
#Получаем менджер для адреса с которого обращается клиент
#Механизм PBHK подменяет адрес клиента на айпи идетифицирующий Брас с которого произошел редирект
$manager=$nm_factoty->getManagerForIP($remote_ip);

# Заголовок запрещающий кэширование страницы
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.

//echo $remote_ip." ".$remote_port."";
//echo json_encode($manager);

//Следующий блок необходимо включить в свой файл php и раскомментировать необходимые действия
/*
if(isset($manager)){
    //рассчитать PBHK (ключи идентифицирующий сессию)
    $PBHK=$manager->calcPBHKbyIpPort($remote_ip,$remote_port);
    //Получить из Браса  логин с которым авторизировался абонент (обычно нобходимо для логирования событий)
    // при обращении к скрипту мы не знаем логина а имеем только ключ сессии
    $sessionLogin=$manager->getLogin4PBHK($PBHK);
    // Удаляет сервисы (в переменной наименования сервисов через запятую)
    $manager->removeServicesPBHK($PBHK,$services);
    // Добавляет сервисы к сессии (в переменной наименования сервисов через запятую)
    $manager->addServicesPBHK($PBHK,$services);
    // Дает комманду Брасу провести попытку авторизации сессии с указанным логином и паролем.
    $manager->sessionLogonPBHK($PBHK,$login,$password);
}

echo $services;
*/

?>
