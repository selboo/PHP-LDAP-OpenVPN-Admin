#!/usr/bin/python
#coding=utf8

import pexpect
import sys,os
import time

NAME = sys.argv[1]
MAIL = sys.argv[2]
PASS = sys.argv[3]

os.system('echo "#!/bin/bash" > openvpn/%s.txt' %(NAME))
os.system('echo "cd /home/wwwroot/dokuwiki/pass/openvpn" >> openvpn/%s.txt' %(NAME))

os.system('echo "rm -rf %s*" >> openvpn/%s.txt' %(NAME, NAME))
os.system('echo "rm -rf keys/%s.*" >> openvpn/%s.txt' %(NAME, NAME))
os.system('echo "echo 00 > keys/serial" >> openvpn/%s.txt' %(NAME))
os.system('echo "> keys/index.txt" >> openvpn/%s.txt' %(NAME))

os.system('echo "source ./vars && export KEY_EMAIL=%s" >> openvpn/%s.txt' %(MAIL, NAME))
os.system('echo "/bin/bash pkitool --pass %s" >> openvpn/%s.txt' %(NAME, NAME))

child = pexpect.spawn('/bin/bash openvpn/%s.txt' %(NAME))
child.logfile = sys.stdout
time.sleep(1)
child.expect(':')
time.sleep(1)
child.sendline(PASS)
child.expect(':')
time.sleep(1)
child.sendline(PASS)
child.sendline('\n')

os.system('/bin/bash openvpn/openvpn.sh %s %s' %(NAME, PASS))

