#!/usr/bin/php
<?php
if(count($argv)>1){
    $ip=$argv['1'];
    $common_content=file_get_contents("./common_content.tp");
    echo str_replace("####-ip-address-####",$ip,$common_content);
    //$content =file_get_contents($argv['1']);
    $content =file_get_contents("./template.tp");
    $content= str_replace("####-ip-address-####",$ip,$content);
    for($i=8880;$i<8890;$i++){
	echo str_replace("####-port-####",$i,$content);
    }
} else {
    echo "usage generate.php ipaddres";
}

?>
