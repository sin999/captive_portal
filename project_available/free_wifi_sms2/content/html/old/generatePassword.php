<?php

echo generatePassword();
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

?>
