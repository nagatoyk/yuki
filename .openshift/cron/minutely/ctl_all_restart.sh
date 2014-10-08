#!/bin/bash
#配置你的Openshift ssh用户名
sshid=$USER
#脚本运行部分,替换为你自己的应用
curl -I $OPENSHIFT_APP_DNS 2> /dev/null | head -1 |  grep -q '200\|301\|302'

s=$?

if [ $s != 0 ];
then
echo "`date +"%Y-%m-%d %I:%M:%S"` down" >> $OPENSHIFT_DATA_DIR/web_error.log
#分钟%10 取余
let t=`date +"%M"`%10
#每隔10分执行一次，防止连续多次重启，服务器压力太大
if [ $t -eq 0 ];
then
#重启日志叠加记录>>，发现太大了可以删除，或者改成覆盖记录>
echo "`date +"%Y-%m-%d %I:%M:%S"` restarting..." >> $OPENSHIFT_DATA_DIR/web_error.log
/usr/bin/gear stop 2>&1 /dev/null
/usr/bin/gear start 2>&1 /dev/null
echo "`date +"%Y-%m-%d %I:%M:%S"` restarted!!!" >> $OPENSHIFT_DATA_DIR/web_error.log
fi
else
echo "`date +"%Y-%m-%d %I:%M:%S"` is ok" > $OPENSHIFT_DATA_DIR/web_run.log
fi