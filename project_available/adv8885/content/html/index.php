<?php
include_once("common.php");
echo $remote_ip." ".$remote_port;
echo $services;
$static_content_url_PLACEHOLDER=$project_conf['static_content_url_PLACEHOLDER'];
$script_url_PLACEHOLDER=$project_conf['script_url_PLACEHOLDER'];

$static_content_url=$project_conf['static_content_url'];
$scripts_url=$project_conf['scripts_url'];

$content =file_get_contents("".$project_conf['index_content']);

$content = str_replace($static_content_url_PLACEHOLDER,$portal_conf['portal_permanent_url'].$project_conf['project_name'].$static_content_url,$content);
$content = str_replace($script_url_PLACEHOLDER,$scripts_url_url,$content);

echo $content; 


if(isset($manager)){
    //рассчитать PBHK (ключи идентифицирующий сессию)
    $PBHK=$manager->calcPBHKbyIpPort($remote_ip,$remote_port);
    //Получить из Браса  логин с которым авторизировался абонент (обычно нобходимо для логирования событий)
    // при обращении к скрипту мы не знаем логина а имеем только ключ сессии
    $sessionLogin=$manager->getLogin4PBHK($PBHK);
    // Удаляет сервисы (в переменной наименования сервисов через запятую)
    echo $PBHK."".$services;
    $manager->removeServicesPBHK($PBHK,$services);
    // Добавляет сервисы к сессии (в переменной наименования сервисов через запятую)
    //$manager->addServicesPBHK($PBHK,$services);
    // Дает комманду Брасу провести попытку авторизации сессии с указанным логином и паролем.
    //$manager->sessionLogonPBHK($PBHK,$login,$password);
    saveDb($project_conf,$sessionLogin);
}else{
 echo "Manager is null!!!!";
}


function saveDb($conf,$login){
        $db= new mysqli($conf['db_host'],$conf['db_login'],$conf['db_password'],$conf['db_name']);
        $query="update ".$conf['actions_table']." as ttk_actions set ttk_actions.last_shown=now()  where   ttk_actions.login='".$login."'";
        echo $query;
    //    write2File($query);
        $db->query($query);
}


?>
