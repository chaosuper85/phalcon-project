#!/usr/bin/env python
# -*- coding: UTF-8 -*-
#post-receive
#使用githook 实现自动化部署和通知 ref http://182.92.111.190:8090/pages/viewpage.action?pageId=1770439
import sys
import subprocess
# Import smtplib for the actual sending function
import smtplib
# Import the email modules we'll need
from email.MIMEMultipart import MIMEMultipart
from email.MIMEText import MIMEText

def send_email(subject , newcommit):
	gitUrl = "http://123.59.59.233:7990/projects/XDDWEB/repos/fe_www/commits/"
	fromaddr = "noreply@56xdd.com"
	toaddr = "rd@56xdd.com"
	#toaddr = "99689131@qq.com"
	msg = MIMEMultipart()
	msg['From'] = fromaddr
	msg['To'] = toaddr
	msg['Subject'] = subject
	body = gitUrl + newcommit
	msg.attach(MIMEText(body, 'plain'))
	server = smtplib.SMTP('smtp.exmail.qq.com', 25)
	server.starttls()
	server.login(fromaddr, "xdd123@bj")
	text = msg.as_string()
	server.sendmail(fromaddr, toaddr, text)
	server.quit()


# 1. Read STDIN (Format: "from_commit to_commit branch_name")
(old, new, branch) = sys.stdin.read().split()

# 2. Only deploy if staging branch was pushed
if branch == 'refs/heads/staging':
	pt=subprocess.Popen(" unset GIT_DIR && sh /home/work/fe_test/yk/phpweb/script/fewww_deploy.sh  ",shell=True,stdout=subprocess.PIPE)
    ptout = pt.stdout.readlines()
	print "------- deploy success --------"
	p=subprocess.Popen("git log staging  --no-merges --pretty=format:'%an,%s,%ar' -1",shell=True,stdout=subprocess.PIPE)
	out = p.stdout.readline()
	send_email(out,new)
	print "--------send notify email success------"

# # 3. Only deploy if xiaoqizhi branch was pushed
if branch == 'refs/heads/xiaoqizhi':
	pt=subprocess.Popen(" unset GIT_DIR && sh /home/work/zhuchao/deploy/deploy.sh  ",shell=True,stdout=subprocess.PIPE)
        #pt=subprocess.Popen("sh /home/work/zhuchao/deploy/deploy.sh ",shell=True,stdout=subprocess.PIPE)
	ptout = pt.stdout.readlines()
	#print ptout
	p=subprocess.Popen("git log xiaoqizhi --pretty=format:'GIT_PUSH_NOTIFY %an,%s,%ar' -1",shell=True,stdout=subprocess.PIPE)
	out = p.stdout.readline()
	send_email(out,new)
	print "send mail finish."