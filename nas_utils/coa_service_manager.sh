# usage - 
# service_manager.sh -nas cisco123@10.200.255.15:3799  -add L4REDIRECT_PBHK -login@ip 15CH1111@10.1.251.197 
# service_manager.sh -nas cisco123@10.200.255.15:3799  -add L4REDIRECT_PBHK -sessid 15CH1111@10.1.251.197 
# service_manager.sh -nas cisco123@10.200.255.15:3799  -add L4REDIRECT_PBHK -phbk 109.233.173.254:263 
# service_manager.sh -nas cisco123@10.200.255.15:3799  -del L4REDIRECT_RECLAMA  -sessid 04D15EAD
#
# where - nas NasAccessKey@NAS_IP:NAS_COA_PORT  
# -add/-del coma separated services 
# -login@ip  username@userSessionIPaddr
# -sessid    ciscoSessionId
# -phbk      PHBK (IP:PORT)
#
#
#The session ids could be pass through a pipe from another programm. You should use one of the session ids (login@ip,sessid or phbk) to point out what kind of 
# value would be pass. But there no values after the key.
#
#echo "15CH1111@10.1.251.197"|./service_manager.sh -login@ip -add L4REDIRECT_PBHK,L4REDIRECT_RECLAMA
#
#
# mysql -e "select concat(username,'@',framedipaddress) from radacct where not username=''  limit 10" radius |./service_manager.sh -nas cisco123@10.200.255.15:3799  -add L4REDIRECT_PBHK -login@ip
#
#
#
#
#
#

PlaceHolder="###Placehoder###"
CiscoAccountInfo="Cisco-Account-Info=\""$PlaceHolder"\""
UserName="User-Name=\"$PlaceHolder\""
AcctSessionId="Acct-Session-Id=\""$PlaceHolder"\""
ServiceName="Cisco-AVPair=\"subscriber:service-name="$PlaceHolder"\""
ActivateServiceCommand="cisco-avpair=\"subscriber:command=activate-service\""
DeactivateServiceCommand="cisco-avpair=\"subscriber:command=deactivate-service\""
AccountStatusQueryCommand="cisco-avpair=\"subscriber:command=account-status-query\""

nas_ip=10.200.255.15
port=3799
secret=cisco123

NA=""
stdin="stdin@Stream"
nas_conf='nas'
vrf=":vrf-id=INTERNET"
id_type=$NA
id_value=$NA
sessid=$NA
services=$NA
action=$NA
username=$NA
password=$NA
phbk=$NA
ipaddress=$NA
coa_header=$NA
coa_command=$NA

# 1 stage  Parsing input parameters
curparam=""
for var in "$@"
do
    if [[ $var == -* ]] ;
    then
	case $var in
	    -nas)
	    curparam="nas"
	    ;;
	    -addservice)
	    action="add"
	    curparam="services"
	    ;;
	    -delservice)
	    action="del"
	    curparam="services"
	    ;;
	    -add)
	    action="add"
	    curparam="services"
	    ;;
	    -del)
	    action="del"
	    curparam="services"
	    ;;
	    -sessid)
	    id_type="sessid"
	    id_value=$stdin
	    curparam="id_value"	    
	    ;;
	    -phbk)
	    id_type="phbk"
	    id_value=$stdin
	    curparam="id_value"
	    ;;
	    -login@ip)
	    id_type="login@ip"
	    id_value=$stdin
	    curparam="id_value"
	    ;;
	esac    
    else
	if [[ $curparam != "" ]]
	then    
	case $curparam in
	    nas)
	    nas_ipport=${var#*@}
	    port=${nas_ipport#*:}
	    nas_ip=${nas_ipport%:*}
	    secret=${var%@*}
	    ;;
	esac
	    eval "$curparam"=$var
	    curparam=""
	fi
    fi
done


function sendPacket(){
    case $id_type in 
	sessid)  
    	    coa_header=${AcctSessionId//$PlaceHolder/$id_value}
	;;
	phbk)  
    	    coa_header=${CiscoAccountInfo//$PlaceHolder/S$id_value}
	;;
	login@ip)  
    	    coa_header=${UserName//$PlaceHolder/${id_value%@*}},${CiscoAccountInfo//$PlaceHolder/S${id_value#*@}$vrf}
	;;
    esac

    if [[ $action == "del" ]] ;
	then 
	    command=$DeactivateServiceCommand
	else
	    command=$ActivateServiceCommand
    fi
    
    for service in $(echo $services | tr "," "\n")
    do
	service_selector=${ServiceName//$PlaceHolder/$service}
	echo "echo $coa_header,$service_selector,$command | radclient $nas_ip:$port coa $secret"
	echo $coa_header,$service_selector,$command | radclient -x $nas_ip:$port coa $secret
    done
}

#The body of the script!

if [[ $id_value == $stdin ]] ;
then
    while read LINE; do
    	id_value=${LINE}  
    	sendPacket
    done  
else
    sendPacket        
fi

