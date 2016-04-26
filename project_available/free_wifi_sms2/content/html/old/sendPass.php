<?php
$phoneNum=array();
$phoneNum[]="79033012553";
#$phoneNum[]="79871501132";
#$phoneNum[]="79379922618";
$message="testsms";
otpravka_sms_gsmgw($phoneNum,$message);





function otpravka_sms_gsmgw1($matches1,$smstxt) {


    $login="sms";
    $password="123";
    $host="10.10.50.252";
    $dbname="sms";
    $db= new mysqli($host,$login,$password,$dbname);
    foreach($matches1 as $phone){
	$query="INSERT INTO smsin (tel, text, prioritet, way, user) VALUES ('".$phone."',  '".$smstxt."', '1', '1',  'FreeWiFi'); ";
	$db->query($query);
    }
}


function otpravka_sms_gsmgw($matches1,$smstxt) {
	$mess_otpravka="";
	$number_all="";
	foreach ($matches1 as $key => $value) {
		$q = array(
			'destination' => "+".$value,
			'text' => $smstxt,
			'secret' => 'qazwsxedc',
			'priority' => '0',
			'sim_id' => '1',
			'remark' => 'sakura2',
		);
		$q_json=json_encode($q);
		$result = file_get_contents('http://10.10.102.10/api/sms/', false, stream_context_create(array(
			'http' => array(
				'method'  => 'POST',
				'header'  => 'Content-type: application/json; charset=UTF-8',
				'content' => $q_json
			)
		)));
		echo $result;
		$response = json_decode($result, true); 
		//print_r ($response);
		
		$mess_otpravka.="<font color=green>SMS отправлено на номер ".$value."<br></font>";
		$number_all.=$value.", ";
	}
	
	return $mess_otpravka."_____".$number_all;
}
?>