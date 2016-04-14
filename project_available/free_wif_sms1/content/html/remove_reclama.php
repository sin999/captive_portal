<?php
$conf=parse_ini_file("../../project.conf");
$common_conf=parse_ini_file($conf['commons_path'].$conf['common_conf']);
include_once($conf['commons_path'].$conf['php_nas_manager']);
$nasListDir="../../".$conf['nases_confs_dir'];

$remote_ip=$_SERVER['HTTP_X_REAL_IP'];
$remote_port=$_SERVER['HTTP_X_REAL_PORT'];

$nasArr=NasManager::createPBHK_Ip2NasManagerHashArray($common_conf,$nasListDir);
$nas=isset($nasArr[$remote_ip])?$nasArr[$remote_ip]:null;

if($nas!=null){
    $PBHK=$nas->calcPBHKbyIpPort($remote_ip,$remote_port);
    $sessionLogin=$nas->getLogin4PBHK($PBHK);
#    $sessionIp=$nas->getIpAddress4PBHK($PBHK);

    $nas->removeServicesPBHK($PBHK,$conf['services']);
    saveDb($conf,$sessionLogin);
#    $nas->sessionLogonPBHK($PBHK,$login,$password);
}

function saveDb($conf,$login){
	$db= new mysqli($conf['db_host'],$conf['db_login'],$conf['db_password'],$conf['db_name']);
        $query="update ".$conf['actions_table']." as ttk_actions set ttk_actions.last_shown=now()  where   ttk_actions.login='".$login."'";
	echo $query;
    //    write2File($query);
        $db->query($query);
}
                        



?>
