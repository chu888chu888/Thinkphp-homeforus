<?php
/**
 * 
 * 删除目录函数
 * @param 目录名	 $directory
 */
function delDir($directory){
	if (is_dir($directory) == false){
		exit("The Directory Is Not Exist!");
	}
	$handle = opendir($directory);
	while (($file = readdir($handle)) !== false){
		if ($file != "." && $file != ".."){
			is_dir("$directory/$file") ? delDir("$directory/$file") : unlink("$directory/$file");
		}
	}
	if (readdir($handle) == false){
		closedir($handle);
		rmdir($directory);
		return true;
	}else{
		return false;
	}
}

/**
 * 
 * 计算目录的大小
 * @param 目录名	 $dir
 */
function calcuate_dir($dir){
	$size = 0;
	if( is_dir($dir) ){
		if( !!$handle = opendir($dir) ){
			while ( ($files = readdir($handle)) !== false ){
				if( $files != '.' && $files != '..' ){
					if( is_dir($dir.'/'.$files) ){
						$size += calcuate_dir($dir.'/'.$files);
					}else{
						$size += filesize($dir.'/'.$files);
					}
				}
			}
	
		}				
	}
	closedir($handle);
	return $size;
}

/**
 * 
 * 发送邮件  
 * 将下载后的文件解压，将PHPMail目录移动至ThinkPHP目录中的Vendor内。
 *（请确保class.phpmailer.php文件就在ThinkPHP\Vendor\PHPMailer\class.phpmailer.php）
 * @param 收件人地址	 $address
 * @param 邮件标题		 $title
 * @param 邮件内容		 $message
 */
function SendMail($address,$title,$message){
	vendor('PHPMailer.class#PHPMailer');
	$mail = new PHPMailer();
	
	//设置PHPMail使用SMTP服务器发送
	$mail->IsSMTP();	
	//设置字符编码
	$mail->CharSet='UTF-8';
	// 添加收件人地址，可以多次使用来添加多个收件人
    $mail->AddAddress($address);
    // 设置邮件标题
    $mail->Subject=$title;
    // 设置邮件正文
    $mail->Body=$message;
    // 设置邮件头的From字段。
    $mail->From=C('MAIL_ADDRESS');
    // 设置发件人名字
    $mail->FromName=C('MAIL_NAME');
    // 设置SMTP服务器。
    $mail->Host=C('MAIL_SMTP');
    // 设置为"需要验证"
    $mail->SMTPAuth=true;
    // 设置用户名和密码。
    $mail->Username=C('MAIL_LOGIN');
    $mail->Password=C('MAIL_PASSWORD');
    
    // 发送邮件。
    return($mail->Send());
}

/**
 +----------------------------------------------------------
 * 字符串截取，支持中文和其他编码
 +----------------------------------------------------------
 * @access public
 +----------------------------------------------------------
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
 +----------------------------------------------------------
 * @return string
 +----------------------------------------------------------
 */
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true){
	if(function_exists("mb_substr"))
		return mb_substr($str, $start, $length, $charset);
	elseif(function_exists('iconv_substr')) {
		return iconv_substr($str,$start,$length,$charset);
	}
	$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
	$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
	$re['gbk']	  = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
	$re['big5']	  = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
	preg_match_all($re[$charset], $str, $match);
	$slice = join("",array_slice($match[0], $start, $length));
	if($suffix) return $slice."…";
	return $slice;
}

/**
 * 
 * 获取字符串长度，不管了中文还是字符
 * 字符串	 $str
 */
function get_length($str){   
	$len=strlen($str);   
 	$i=0; $j=0;  
 	while($i<$len){   
       if(preg_match("/^[".chr(0xa1)."-".chr(0xf9)."]+$/",$str[$i])){   
         $i+=2;   
        }else{   
         $i+=1;   
     	}   
    $j++;
	 }   
 return $j;   
}  

/**
 * 验证码生成
 * 验证码宽度		$width
 * 验证码高度 		$height
 * 验证码显示数 		$num
 * 干扰素数量		$interferon
 * 干扰线数量		$line
 * SESSION保存名	$verify_name
 */ 
function create_verify($width='80',$height='30',$num='5',$interferon='80',$line='4',$verify_name='verify'){
	$code = strtoupper( substr(md5(rand(0, 100)), 0, $num) );
	$_SESSION[$verify_name] = $code;
	
	//生成图片
	$im = imagecreate($width, $height);
	imagecolorallocate( $im, rand(230, 250), rand(230, 250), rand(230, 250) );
	
	//产生干扰素
	for ($i=0;$i<$interferon;$i++) {
		$pixel_color = imagecolorallocate( $im, rand(0, 255), rand(0, 255), rand(0, 255) );
		imagesetpixel($im, rand(0, $width), rand(0, $height), $pixel_color);
	}
	
	//产生干扰线条
	for ($j=0;$j<$line;$j++) {
		$line_color = imagecolorallocate($im, rand(0,255), rand(0,255), rand(0,255));
		imageline($im, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $line_color);
	}
	
	//写入字符
	for ($m=0;$m<$num;$m++){
		$text_color = imagecolorallocate($im, rand(0,100), rand(0,100), rand(0,100));
		$x = rand($width/$num*$m+5, $width/$num*($m+1)-20);
		$y = rand(5, $height-20);
		imagechar($im, 38, $x, $y, $code[$m], $text_color);
	}
	
	//生成验证码图片
	header('content-type:image/png');
	imagepng($im);
	imagedestroy($im);
}

