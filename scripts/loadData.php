#!/usr/bin/php
<?php
$conf=parse_ini_file("../../project.conf");
/*
$login="radius";
$password="FIFA2008";
$host="127.0.0.1";
$dbname="radius";
*/
if(isset($argv) and count($argv)>1 ){
    $file2load= $argv['1'];
    $db=  new mysqli($conf['db_host'],$conf['db_login'],$conf['db_password'],$conf['db_name']);
    $actionTable=$conf['actions_table'];
    $actionId=$conf['action_name'];

    $skipeStrings=1;
    $loginOffSet=0;
    $strNum=0;
    if (($handle = fopen($file2load, "r")) !== FALSE) {
	    while (($line = fgetcsv($handle,4000,";","\"")) !== FALSE) {
		if($strNum>=$skipeStrings){
		    $login=$line[$loginOffSet];
		    insertLogin($db,$actionTable,$actionId,$login);
    		    echo $login."\n";
    		}
    		$strNum++;
	    }
	fclose($handle);
    }
} else {
    echo "usage: ./loadData.php ./file2load.csv \n(Укажите файл для загрузки) \n";
}

function insertLogin($db,$actionTable,$actionId,$login){
    $query="insert into ".$actionTable."(login,action_id) values('".$login."','".$actionId."') ON DUPLICATE KEY UPDATE ".$actionTable.".last_update = now()";
    $db->query($query);
}


?>
