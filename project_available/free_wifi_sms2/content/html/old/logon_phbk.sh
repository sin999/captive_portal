#!/bin/bash
login=$1
password=$2
sessionId=$3
accountInfo="Cisco-Account-Info=\"S"$sessionId"\"";
userName="User-Name=\""$login"\"";
command="Cisco-AVPair=\"subscriber:password="$password"\",cisco-avpair=\"subscriber:command=account-logon\"";
echo $accountInfo','$userName','$command | radclient -x -s 10.207.35.10:3799  coa cisco123 