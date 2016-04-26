<?php
$dbhost="10.200.5.206";
$dblogin="free_wifi";
$dbpass="FIFA2008";
$acc=new stdClass();
$operationResult=new stdClass();
$operationResult->err=true;
$operationResult->message="undefined";
#$acc->phone_number="9033012553";
#$acc->phone_number="9379922618";
$acc->phone_number=isset($_GET["phone_number"])?$_GET["phone_number"]:"";
$acc->phone_number=substr($acc->phone_number,-10);
$acc->country_code="7";
$acc->pass_key="";
$passkeyPlaceHolder="%%passkey%%";
$file = fopen("/var/log/sin/wifi_free_new.log","a");
fwrite($file,"######Send sms".$acc->phone_number."\n");
fclose($file);

$messagePattern="Пароль доступа к сети ТТК для вашего телефона ".$passkeyPlaceHolder."";
#$messagePattern="Password for your number is ".$passkeyPlaceHolder."";
if(mb_strlen($acc->phone_number)==10){
    $phoneNum=array();
    $db = mysql_connect($dbhost,$dblogin,$dbpass) or die(mysql_error());
    mysql_select_db("free_wifi",$db) or die(mysql_error());
    mysql_query("SET NAMES 'utf8'",$db);
    $acc=getAccountAttrbutes($acc,$db);
    if(empty($acc->pass_key)){
	createAccount($acc,$db);
	$acc=getAccountAttrbutes($acc,$db);
    }
    $phoneNum[]="7".$acc->phone_number;
    $message=str_replace($passkeyPlaceHolder,$acc->pass_key,$messagePattern);
    //echo $message;
    otpravka_sms_gsmgw($phoneNum,$message);
    $operationResult->err=false;
    $operationResult->message="The password for your phone was sent!";
}else {
    $operationResult->message="Phone number format is wrong";
}
echo json_encode($operationResult);

//echo "  "."7".$acc->phone_number."  ".$acc->pass_key;

function getAccountAttrbutes($acc,$db){
    $query="select * from phone_accounts where country_code='".$acc->country_code."' and phone_number='".$acc->phone_number."'";
//    echo $query."\n";
    $result=mysql_query($query,$db);
    while ($row = mysql_fetch_object($result)) {
	$acc->pass_key=$row->pass_key;
    }
    return $acc;
}
function createAccount($acc,$db){
    $pass_key=generatePassword();
    $query="insert into phone_accounts (country_code,phone_number,pass_key) VALUES ('".$acc->country_code."','".$acc->phone_number."','".$pass_key."')";
//    echo $query."\n";
    $result=mysql_query($query,$db);
}

function generatePassword($length = 6) {
//    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $chars = '0123456789';
    $Firstchars = '123456789';//Первая цифра не должна быть нулем

    $count = mb_strlen($chars);

    for ($i = 0, $result = ''; $i < $length; $i++) {
        $result .= randomChar($i==0?randomChar($Firstchars):randomChar($chars));
    }

    return $result;
}

function randomChar($charList){
    $count = mb_strlen($charList);
    $index = rand(0, $count - 1);
    return mb_substr($charList, $index, 1);
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
//                        'sim_id' => '1',
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
                $response = json_decode($result, true);
                //print_r ($response);

                $mess_otpravka.="<font color=green>SMS отправлено на номер ".$value."<br></font>";
                $number_all.=$value.", ";
        }

        return $mess_otpravka."_____".$number_all;
}


?>
