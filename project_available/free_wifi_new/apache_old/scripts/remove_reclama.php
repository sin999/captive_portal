<?php

$nas="cisco123@10.8.8.6:3799";
$login="radius";
$password="FIFA2008";
$host="127.0.0.1";
$dbname="radius";
$db= new mysqli($host,$login,$password,$dbname);
$remote_port=$_SERVER['HTTP_X_REAL_PORT'];
#$bundle=(int)($remote_port/16);
$bundle=(int)($remote_port/4);
$PortBundleId=$_SERVER['HTTP_X_REAL_IP'].":".$bundle;
#$PortBundleId="S109.233.173.254:275";
saveDb($db,getLoginNas($PortBundleId));
removeReclama($PortBundleId);
#echo $PortBundleId;
#$login="09K0002";



function getData($login,$db){
    $query="select *  from ttk_actions1 where login='".$login."'";
#    echo $query;
    $result = $db->query($query);
    $data='';
    while($row = $result->fetch_assoc()){
        $data=$row['account_data'];
    }
    return base64_decode($data);
}

function getLoginNas($PHBK){
    return exec("sudo /usr/local/sin/nas_utils/getLogin4PHPK.sh S".$PHBK);
}

function removeReclama($PHBK){
global $nas;
$command="sudo /usr/local/sin/nas_utils/coa_service_manager.sh -nas ".$nas." -phbk ".$PHBK."  -del L4REDIRECT_RECLAMA,L4REDIRECT_PBHK > /dev/null 2>/dev/null &";
echo $command." \n";
        exec($command);
}


function saveDb($db,$login){
        $query="update ttk_actions as ttk_actions set ttk_actions.last_shown=now()  where   ttk_actions.login='".$login."' ";
        write2File($query);
        $db->query($query);
}

function write2File($str){
    date_default_timezone_set("Europe/Samara");
    $log=fopen("/var/log/sin/collector.log","a");
    fwrite($log," ".date('Y-m-d h.i.s')."  ".$str. "\n");
}



?>
