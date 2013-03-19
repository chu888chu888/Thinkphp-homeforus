<?php
$arr1 = require 'database.inc.php';
$arr2 = require 'webinfo.inc.php';

$arr3 = array(
    //'配置项'=>'配置值'
    'SHOW_PAGE_TRACE'=>true,
   // 'URL_HTML_SUFFIX'=>'.html',
	
	'APP_GROUP_LIST' 	=> 'Home,Admin',
	'DEFAULT_GROUP' 	=> 'Home',
	'APP_GROUP_MODE'	=>	1,
	
	// 模板替换
	'TMPL_PARSE_STRING' => array (
			'__HOME__' 	=> '/Public/Home',
			'__ADMIN__' => '/Public/Admin' ,
	),
	
	// 开启大小写敏感
	'URL_CASE_INSENSITIVE' => false,
);

return array_merge($arr1,$arr2,$arr3);
?>