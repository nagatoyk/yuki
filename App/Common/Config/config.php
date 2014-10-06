<?php
if (!defined("HDPHP_PATH"))exit('No direct script access allowed');
//更多配置请查看hdphp/Config/config.php
return array(
    /********************************数据库********************************/
    'DB_DRIVER'                     => 'mysqli',    //数据库驱动
    'DB_CHARSET'                    => 'utf8',      //数据库字符集
    'DB_HOST'                       => '127.0.0.1', //数据库连接主机  如127.0.0.1
    'DB_PORT'                       => 3306,        //数据库连接端口
    'DB_USER'                       => 'root',      //数据库用户名
    'DB_PASSWORD'                   => '',          //数据库密码
    'DB_DATABASE'                   => '',          //数据库名称
    'DB_PREFIX'                     => '',          //表前缀
    'DB_BACKUP'                     => 'backup/',   //数据库备份目录
    /********************************URL设置********************************/
    'HTTPS'                         => true,       //基于https协议
    'URL_REWRITE'                   => true,       //url重写模式
    'URL_TYPE'                      => 1,           //类型 1:PATHINFO模式 2:普通模式 3:兼容模式
    'PATHINFO_DLI'                  => '/',         //URL分隔符 URL_TYPE为1、3时起效
    'PATHINFO_VAR'                  => 'q',         //兼容模式get变量
    'HTML_SUFFIX'                   => '',          //伪静态扩展名
);