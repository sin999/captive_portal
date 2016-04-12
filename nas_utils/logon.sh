#using logon
#logon.sh -nas cisco123@10.1.1.3:3799 -phbk 1.1.1.1:1234 -login mylogin -password myspassword


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
            -sessid)
            id_type="sessid"
            id_value=$stdin
            curparam="id_value"
            ;;
            -phbk)
            id_type="phbk"
            curparam="phbk"
            ;;
            -login)
            id_type="login"
            curparam="username"
            ;;
            -password)
            id_type="password"
            curparam="password"
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
echo $nas_ip $port $secret
sessionId=S$phbk
userName="User-Name=\""$username"\""
accountInfo="Cisco-Account-Info=\""$sessionId"\"";
command="Cisco-AVPair=\"subscriber:password="$password"\",cisco-avpair=\"subscriber:command=account-logon\"";
echo $userName","$accountInfo','$command | radclient $nas_ip:$port  coa $secret
