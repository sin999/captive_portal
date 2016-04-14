<?php

class NasManagerFactory{
    var $nasManagerByPBHKIp=array();
    var $nasManagerByNasIp=array();
    public function __construct($project_config,$project_path){
	$files = glob($project_path.$project_config['nases_confs_dir'].'*.{conf}', GLOB_BRACE);
	foreach($files as $nas_conf_file_name) {
	    $nas_conf=parse_ini_file($nas_conf_file_name);
	    $nasManager=new NasManager($nas_conf,$project_config);
	    //Создаем  ссылку  на соданый  менеджер, для каждого айпи используемого на нем в качестве PBHK
	    foreach($nasManager->getPhbkIPArray() as $ip){
		$this->nasManagerByPBHKIp[$ip]=$nasManager;
	    }

	    foreach($nasManager->getNasIPArray() as $ip){
		$this->nasManagerByNasIp[$ip]=$nasManager;
	    }

	    
	}
    }

    public function getManagerForIP($ip){
    	return isset($this->nasManagerByPBHKIp[$ip])?$this->nasManagerByPBHKIp[$ip]:null;
    }

    public function getManagerForNasIp($ip){
    	return isset($this->nasManagerByNasIp[$ip])?$this->nasManagerByNasIp[$ip]:null;
    }    
    
}

#$arr=NasManager::createManagersArray("../nas_available");
#print_r($arr);
class NasManager {
    const NAS_KEY_IP_PORT_FIELD_NAME="nas_key_address_port";
    const PHBK_ADDRESS_FIELD_NAME="pbhk_address_";
    const NAS_IP_ADDRESS_FIELD_NAME="nas_ip_";

    public static function createPBHK_Ip2NasManagerHashArrayold($conf,$nas_confs_dir) {
	$nas_utils_conf=$conf;
	$managerArray= array();
	$files = glob($nas_confs_dir.'/*.{conf}', GLOB_BRACE);
	foreach($files as $nasConfFileName) {
	    $nas_conf=parse_ini_file($nasConfFileName);
	    $nasManager=new NasManager($nas_conf,$nas_utils_conf);
	    foreach($nasManager->getPhbkIPArray() as $ip){
		$managerArray[$ip]=$nasManager;
	    }
	}
	return $managerArray;	
    }
    
    public static function createPBHK_Ip2NasManagerHashArray($conf,$nas_confs_dir) {
	    $managerHash=array();
	    $managerArray= NasManager::createNasManagerArray($conf,$nas_confs_dir);
	    foreach($managerArray as $nasManager){
		foreach($nasManager->getPhbkIPArray() as $ip){
		    $managerHash[$ip]=$nasManager;
		}
	    }
	return $managerHash;	
    }

    public static function createNAS_Ip2NasManagerHashArray($conf,$nas_confs_dir) {
	    $managerHash=array();
	    $managerArray= NasManager::createNasManagerArray($conf,$nas_confs_dir);
	    foreach($managerArray as $nasManager){
		foreach($nasManager->getNasIPArray() as $ip){
		    $managerHash[$ip]=$nasManager;
		}
	    }
	return $managerHash;	
    }

    public static function createNasManagerArray($conf,$nas_confs_dir) {
	$nas_utils_conf=$conf;
	$managerArray= array();
	$files = glob($nas_confs_dir.'/*.{conf}', GLOB_BRACE);
	foreach($files as $nasConfFileName) {
	    $nas_conf=parse_ini_file($nasConfFileName);
	    $nasManager=new NasManager($nas_conf,$nas_utils_conf);
	    $managerArray[]=$nasManager;
	}
	return $managerArray;	
    }
    

    
    var $nas_conf;
    var $nas_utils_conf;
    
    function __construct($nas_conf,$project_conf) {
	$this->nas_conf=$nas_conf;	
	$this->nas_utils_conf=parse_ini_file($project_conf["commons_path"]."".$project_conf["common_conf"]);	
    }
    
    ##private
    function getNasIPArray(){
	$nasIPArr=array();
	foreach($this->nas_conf as $fname => $val){
	    if($this->isFieldNameMatchPattern($fname,self::NAS_IP_ADDRESS_FIELD_NAME)){
		$nasIPArr[]=$val;
	    }
	}
	return $nasIPArr;
    }
    
    ##private
    function getPhbkIPArray(){
	$phbkIPArr=array();
	foreach($this->nas_conf as $fname => $val){
	    if($this->isFieldNameMatchPattern($fname,self::PHBK_ADDRESS_FIELD_NAME)){
		$phbkIPArr[]=$val;
	    }
	}
	return $phbkIPArr;
    }
    
    ##private
    function isFieldNameMatchPattern($fname,$pattern){
#	return 0==substr_compare($fname,self::PHBK_ADDRESS_FIELD_NAME,0,strlen(self::PHBK_ADDRESS_FIELD_NAME));
	return 0==substr_compare($fname,$pattern,0,strlen($pattern));
    }
    
    ##public
    function calcPBHKbyIpPort($ip,$port){
	$bundle=((int)($port/pow(2,$this->nas_conf['pbhk_length'])));
	return $ip.":".$bundle;
    }
    
    ##public
    function removeServicesPBHK($PBHK,$services){
	$command="sudo ".$this->nas_utils_conf['nas_utils_path'].$this->nas_utils_conf['coa_service_manager']." -nas ".$this->nas_conf['nas_key_address_port']." -phbk ".$PBHK."  -del ".$services." > /dev/null 2>/dev/null &";
//	echo $command;
	exec($command);
    }
    
    ##public
    function applyServicesPBHK($PBHK,$services){
	$command="sudo ".$this->nas_utils_conf['nas_utils_path'].$this->nas_utils_conf['coa_service_manager']." -nas ".$this->nas_conf['nas_key_address_port']." -phbk ".$PBHK."  -add ".$services." > /dev/null 2>/dev/null &";
	exec($command);
    }

    ##public
    function removeServicesSessionId($sess_id,$services){
	$command="sudo ".$this->nas_utils_conf['nas_utils_path'].$this->nas_utils_conf['coa_service_manager']." -nas ".$this->nas_conf['nas_key_address_port']." -sessid ".$sess_id."  -del ".$services." > /dev/null 2>/dev/null &";
	exec($command);
    }

    ##public
    function applyServicesSessionId($sess_id,$services){
	$command="sudo ".$this->nas_utils_conf['nas_utils_path'].$this->nas_utils_conf['coa_service_manager']." -nas ".$this->nas_conf['nas_key_address_port']." -sessid ".$sess_id."  -add ".$services." > /dev/null 2>/dev/null &";
//	echo $command;
	exec($command);
    }
    
    ##public
    function sessionLogonPBHK($PBHK,$login,$password){
	$command="sudo ".$this->nas_utils_conf['nas_utils_path'].$this->nas_utils_conf['logon_manager']." -nas ".$this->nas_conf['nas_key_address_port']." -phbk ".$PBHK." -login ".$login."  -password ".$password." > /dev/null 2>/dev/null &";
//	echo $command;
        return exec($command);
    }
    
    #public
    function getLogin4PBHK($PBHK){
	$command="sudo ".$this->nas_utils_conf['nas_utils_path'].$this->nas_utils_conf['get_session_params4PBHK']." -nas ".$this->nas_conf['nas_key_address_port']." -phbk ".$PBHK." -field sess_login"; 
        return exec($command);
    }

    #public
    function getIpAddress4PBHK($PBHK){
	$command="sudo ".$this->nas_utils_conf['nas_utils_path'].$this->nas_utils_conf['get_session_params4PBHK']." -nas ".$this->nas_conf['nas_key_address_port']." -phbk ".$PBHK." -field sess_ip"; 
        return exec($command);
    }
    
}

?>
