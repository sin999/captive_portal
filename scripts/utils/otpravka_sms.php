<?php
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
