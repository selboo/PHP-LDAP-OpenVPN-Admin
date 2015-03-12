#!/bin/bash

cd /home/wwwroot/dokuwiki/pass/openvpn

mkdir -p $1
alias cp=cp

cp keys/ca.crt $1
cp keys/${1}.* $1

echo "client"                        >  ${1}/VPN1.ovpn
echo "pull"                          >> ${1}/VPN1.ovpn
echo "dev tap0"                      >> ${1}/VPN1.ovpn
echo "proto udp"                     >> ${1}/VPN1.ovpn
echo "remote 127.0.0.1 1194"   >> ${1}/VPN1.ovpn
echo "link-mtu 1400"                 >> ${1}/VPN1.ovpn
echo " "                             >> ${1}/VPN1.ovpn
echo "ca ca.crt"                     >> ${1}/VPN1.ovpn
echo "cert ${1}.crt"                 >> ${1}/VPN1.ovpn
echo "key ${1}.key"                  >> ${1}/VPN1.ovpn
echo " "                             >> ${1}/VPN1.ovpn
echo "comp-lzo"                      >> ${1}/VPN1.ovpn
echo "verb 4"                        >> ${1}/VPN1.ovpn
echo "mute 20"                       >> ${1}/VPN1.ovpn
echo "nobind"                        >> ${1}/VPN1.ovpn
echo "ns-cert-type server"           >> ${1}/VPN1.ovpn
echo "persist-key"                   >> ${1}/VPN1.ovpn
echo "persist-tun"                   >> ${1}/VPN1.ovpn

LANG=en_US.UTF-8
/usr/local/bin/rar a -p$2 $1.rar $1
rm -rf ${1}
