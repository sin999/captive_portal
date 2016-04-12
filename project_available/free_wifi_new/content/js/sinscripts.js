function testf(test_text){
    alert(' < This text was put to test function.' + test_text);
}

function openAccessPhone(strURL,phone){
    phone=normolisePhone(phone);
    if(isPhoneWellFormated(phone)){

	sendAjax(strURL,'phone=' + phone);
    }
    else {
	notifyPhoneIsWrong(phone);
    }
}


function openAccess(isPhonePut){
    sendAjax("/v1e12wcngdas1v8efp7nxq3evce2jkrzyshg8s16/phone_collector_free.php",isPhonePut);
}

function isPhoneWellFormated(phone){
    return (/^((8|\+7|7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{10}$/.test(phone));
}

function normolisePhone(phone){
    return phone.replace(/\D/g,'');
}

function notifyPhoneIsWrong(phone){
        document.getElementById("notification").innerHTML="Номер должен соответствовать формату<br> +7 (347) 123 45 67(городские)<br> или +7(9xx) xxx-xx-xx (мобильные)";
        document.getElementById("phone_div").style.background = '#ffc0c0';;
}


function sendAjax(strURL,q_str){
        var xmlHttpReq = false;
        var self = this;
        // Mozilla/Safari
        if (window.XMLHttpRequest) {
            self.xmlHttpReq = new XMLHttpRequest();
        }
        // IE
        else if (window.ActiveXObject) {
            self.xmlHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
        }
        self.xmlHttpReq.open('POST', strURL, true);
        self.xmlHttpReq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        self.xmlHttpReq.onreadystatechange = function() {
            if (self.xmlHttpReq.readyState == 4) {
                updatepage(self.xmlHttpReq.responseText);
            }
        }
    //    var q_str='phone='+(isPhoneWellFormated(phone)?'':phone)+'&'+'hasphone='+(isPhoneWellFormated?'false':'true');
        self.xmlHttpReq.send(q_str);
        updatepage()
        setTimeout('location.reload(true)',3000);
}


function updatepage(){
    document.getElementById("form-dostup").innerHTML = 'В течении пяти секунд произодет перенаправление на запрошенную страницу';
}

