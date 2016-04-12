/**
 * Created by 1 on 12.12.2015.
 */
var freeForm={};
var TTKForm={};
var formStates=new FormStates();
var phoneNumberPlaceHolder="%%typedPhoneNumber%%"
window.onload = function() {


    //freeForm=new AuthForm({
    headerForm = new Tab({formRootContainerId: 'header'});
    freeForm=new StrangerForm({
        formRootContainerId:'others',
        formMessageContainerId:'messagesOthers',
        phoneNumberContainerId:'iphon',
        passwordContainenrId:'icode'
    });
    TTKForm=new AuthForm({
        formRootContainerId:'clients',
        formMessageContainerId:'messagesTTK',
        loginContainenrId:'ilogin',
        passwordContainenrId:'ipassw'
    });
    var tabSelector=new TabSelector();
    tabSelector.addTab(freeForm,"#onothers");
    tabSelector.addTab(TTKForm,"#onclients");
    tabSelector.addTab(headerForm,"img");

    tabSelector.selectTab(headerForm);
    //$("#onclients").click(function(){tabSelector.selectTab(TTKForm)})
    //$("#onothers").click(function(){tabSelector.selectTab(freeForm)})
//    $("#loginclient").click(onLoginClientClick);
//    $("#sendcode").click(onClickSendPassword);
//    $("#loginother").click(onLoginOtherClick);
    $("#loginclient").on('click touchstart',onLoginClientClick);
    $("#sendcode").on('click touchstart',onClickSendPassword);
    $("#loginother").on('click touchstart',onLoginOtherClick);
    //$("#log-in").click(onClickLogIn);
}

function onClickSendPassword(){
    //alert("before check");
//    freeForm.message = formStates.getMessage(formStates.regular, "");
    if(freeForm.isPhoneNumberOk()){
       //send password
       sendPassword(freeForm.getPhoneNumber(),function(msg){
        //alert(JSON.parse(msg).message);
       })
    }else{
        var data2replace={};
        data2replace[phoneNumberPlaceHolder]=freeForm.getPhoneNumber();
        freeForm.message=formStates.getMessage(formStates.wrong_phone_number_format,data2replace);
    }

}

function sendPassword(phoneNumber,callback){
//    alert(" "+phoneNumber+"Nomer");
    $("#sendcode").prop( "disabled", true );
    setTimeout(
    function(){
	$("#sendcode").prop( "disabled", false );
    }
    , 3000);
    var request = $.ajax({
	cache : false,
	url: "/v1e12wcngdas1v8efp7nxq3evce2jkrzyshg8s16/free_wifi_new/supp/sendPass4Number.php",
	method: "GET",
	data: { phone_number : phoneNumber },
	dataType: "html"
    });
    request.done(callback);
}

function accountLogOn(authForm,callback){
    var request = $.ajax({
	url: "/v1e12wcngdas1v8efp7nxq3evce2jkrzyshg8s16/free_wifi_new/supp/logon.php",
	method: "GET",
	data: { phone : authForm.login,passkey:authForm.password},
	dataType: "html"
    });
    request.done(callback);
}

function onLoginOtherClick(){
//    freeForm.message = formStates.getMessage(formStates.regular, "");
        if(freeForm.isPhoneNumberOk()){
            if(freeForm.isPasswordFormatOk()) {
                //auth
                accountLogOn(freeForm,function(mess){});
                setTimeout('location.reload(true)',7000);
            }else{
                var data2replace={};
    //            freeForm.message = formStates.getMessage(formStates.wrong_password_format, data2replace);
            }

        }else{
            var data2replace={};
            data2replace[phoneNumberPlaceHolder]=freeForm.getPhoneNumber();
        //    freeForm.message=formStates.getMessage(formStates.wrong_phone_number_format,data2replace);
        }
}

function onLoginClientClick(){
//    TTKForm.message = formStates.getMessage(formStates.regular, "");
    if(TTKForm.isLoginFormatOk()){
        if(TTKForm.isPasswordFormatOk()) {
            //auth
                accountLogOn(TTKForm,function(mess){});
            setTimeout('location.reload(true)',7000);

        }else{
            var data2replace={};
//            TTKForm.message = formStates.getMessage(formStates.wrong_password_format, data2replace);
        }

    }else{
        var data2replace={};
//        TTKForm.message=formStates.getMessage(formStates.wrong_login_format,data2replace);
    }
}

function TabSelector(){
    this.tabs=[];
    self=this;
    this.addTab = function(tab,selector){
        self.tabs.push(tab);
        self.bindTab(tab,selector);
    }
    this.colapsAll=function(){
        self.tabs.forEach(function(tab){
            tab.hide();
        })
    }
    this.selectTab = function(tab){
        //alert(tab.formRootContainerId);
        self.colapsAll();
        tab.show();
    }
    this.bindTab = function(tab,selector){
        $(selector).each(function(index){
            $(this).click(function(){self.selectTab(tab);});
        });
    }
}

function Tab(settings){
    var self=this;
    this.formMessageContainerId='';
    this.show=function(){
        $("#"+self.formRootContainerId).show()
    }
    this.hide=function(){
        $("#"+self.formRootContainerId).hide()
    }
    this.isEmpty = function(obj){
       return (obj==null || obj==undefined || obj.length==0);
    }
    //Применение переданных конструктору параметров
    this.applySettings=function(settings) {
        if (!self.isEmpty(settings)) {
            Object.keys(settings).forEach(function (key) {
                self[key] = settings[key];
            })
        }
    }
    self.applySettings(settings);
}

 function AuthForm(settings){
     Tab.call(this);
     var self=this;
     this.formMessageContainerId='';
     //this.formRootContainerId='';
     this.loginContainenrId='';
     this.passwordContainenrId='';
     this.minLoginLength=3;
     this.minPasswordLength=3;
     this.__defineGetter__("login", function(){
         return self.getValueByContainerId(self.loginContainenrId);
     });
     this.__defineGetter__("password", function(){
         return self.getValueByContainerId(self.passwordContainenrId);
     });
     this.__defineGetter__("message", function(){
         return self.getValueByContainerId(self.formMessageContainerId);
     });
     this.__defineSetter__("message", function(message){
         return $("#"+self.formMessageContainerId).html(message);
     });
     this.isLoginFormatOk=function(){
         return self.isEmpty(self.login)?false:(self.login.length>self.minLoginLength);
     }
     this.isPasswordFormatOk=function(){
         return self.isEmpty(self.password)?false:(self.password.length>self.minPasswordLength);
     }
     this.isFormDataOk = function(){
         return self.isLoginFormatOk() && self.isPasswordFormatOk();
     }
     this.getValueByContainerId=function(containerId){
        var value=$("#"+containerId).val();
        return self.isEmpty(value)?"":value;
     }
     self.applySettings(settings);
}

function StrangerForm(settings){
    AuthForm.call(this);
    this.minLoginLength=null;
    this.phoneNumberLength=10;
    this.phoneNumberContainerId='';
    var self=this;
    this.getPhoneNumber=function(){
        var phone=self.getValueByContainerId(self.phoneNumberContainerId);
        phone=self.removeNonDigit(phone);
        phone=phone.slice((-1)*self.phoneNumberLength);
        return phone;
    }
    this.__defineGetter__("login", function(){
	var ph='7'+self.getPhoneNumber();
        return ph;
    });
    this.removeNonDigit=function(str){
        return self.isEmpty(str)?"":str.replace(/[^0-9]+/g, '');
    }
    this.isPhoneNumberOk = function(){
        return (this.getPhoneNumber().length==self.phoneNumberLength);
    };
    self.applySettings(settings);
    //Именно здсь, после определения всех переменных, иначе упадет значение по умолчанию
    if(self.isEmpty(this.minLoginLength))this.minLoginLength=this.phoneNumberLength;
}

function FormStates(){
    var self=this;
                                                       //Состояния главной формы
    this.regular=                        "regular";                           //1. Новая
    this.wrong_phone_number_format=      "wrong_phone_number_format";        //2. Не верный формат телефона
    this.wrong_login_format=             "wrong_login_format";               //2. Не верный формат телефона
    this.wrong_password_format=          "wrong_password_format";            //3. Не верный формат пароля (число симсолов менее лимита minPasswordLength)

    this.password_request_in_progress=   "password_request_in_progress";     //4. Пароль запрашивается
    this.password_request_succeeded=     "password_request_succeeded";       //5. Пароль был  запрошен
    this.password_request_failed=        "password_request_failed";          //6. Ошибка запроса пароля

    this.login_request_in_progress=      "login_request_in_progress";        //7  Идет авторизация
    this.login_request_failed=           "login_request_failed";             //8. Ошибка авторизации
    this.login_request_succeeded=        "login_request_succeeded";          //9. Успешная авторизация идет переход на запрашиваемую страницу
    this.getMessage=function(state,data2putInMessage){
        var message = $("#"+state).html();
        Object.keys(data2putInMessage).forEach(function(key){
            message=message.replace(new RegExp(key,'g'),data2putInMessage[key]);
        })
       return (message==null || message==undefined)?"":message;
    }
};

