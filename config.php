<?php
/**
 * config.php 系统配置：数据库连接、显示信息等
 * ----------------------------------------------------------------
 * OldCMS,site:http://www.oldcms.com
 */

/* 数据库连接 */
$config['dbHost']		='127.0.0.1';			//数据库地址
$config['dbUser']		='root';				//用户
$config['dbPwd']		='oldcms';				//密码
$config['database']		='oldcms';				//数据库名
$config['charset']		='utf8';				//数据库字符集
$config['tbPrefix']		='oc_';					//表名前缀
$config['dbType']		='mysql';				//数据库类型(目前只支持mysql)

/* 注册配置 */
$config['register']		='normal';				//normal,正常;invite,只允许邀请注册;close,关闭注册功能
$config['mailauth']		=true;					//注册时是否邮箱验证

/* url配置 */
$config['urlroot']		='http://oldcms.com';	//访问的url起始
$config['urlrewrite']	=true;					//是否启用Url Rewrite

/* 存储配置 */
$config['filepath']		=ROOT_PATH.'/upload';	//文件存储目录,结尾无'/'
$config['fileprefix']	=$config['urlroot'].'/upload'; //访问文件起始,结尾无'/'

/* 主题选择 */
$config['theme']		='a';				//主题选择
$config['template']		='a';				//模板选择

/* 显示设置 */
$config['show']=array(
	'sitename'			=>'OldCMS',						//网站名
	'sitedesc'			=>'开源、简单、安全的PHP开发框架',			//一句话简介
	'keywords'			=>'OldCMS,PHP,WEB,开源,简单,安全',			//keywords
	'description'		=>'开源、简单、安全的PHP开发框架',			//description
	'adminmail'			=>'admin@oldcms.com'		//管理员邮箱
);

/* 积分等级设置 */
$config['point']=array(
	'award'=>array(
		'publish'		=>2,
		'comment'		=>2,
		'invitereg'		=>10 					//邀请注册奖励
	)
);

/* 邮件服务器配置 */
$config['mail']=array(
	'mailer'			=>'smtp',				//smtp,mail,sendmail,qmail
	'host'				=>'smtp.qq.com',		//smtp server
	'port'       		=>25,					//server port
	'username'   		=>'admin@oldcms.com',	//server username
	'password'   		=>'123456',				//server password
	'name'   			=>'OldCMS',				//发件用户名称
	'charset'    		=>'utf-8',				//内容编码
	'contentType'		=>'html'				//html/txt(默认为txt)
);

/* 其它设置 */
$config['timezone']		='Asia/Shanghai';		//时区
$config['expires']		=3600;					//过期时长(秒)
$config['debug']		=true;					//调试模式(是否显示程序、数据库等错误)
?>