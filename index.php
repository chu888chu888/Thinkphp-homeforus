<?php
/*define("APP_NAME","Web");
define("APP_PATH", "./Web/");
*/
define('APP_DEBUG', true);

define('BUILD_DIR_SECURE',true);
define('DIR_SECURE_FILENAME', 'default.html');
define('DIR_SECURE_CONTENT', 'deney Access!');


/*if( file_exists('./Web/Runtime/~runtime.php') ){
	require './Web/Runtime/~runtime.php';
}else{*/
	require './ThinkPHP/ThinkPHP.php';
/*}*/