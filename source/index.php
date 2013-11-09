<?php
/**
 * index.php 首页
 * ----------------------------------------------------------------
 * OldCMS,site:http://www.oldcms.com
 */
if(!defined('IN_OLDCMS')) die('Access Denied');

$smarty=InitSmarty();
$smarty->display('index.html');
?>