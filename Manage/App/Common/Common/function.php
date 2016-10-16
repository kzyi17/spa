<?php
/**
 +----------------------------------------------------------
 * 加密密码  (哈希算法)
 +----------------------------------------------------------
 * @param string    $data   待加密字符串
 +----------------------------------------------------------
 * @return string 返回加密后的字符串
 */
function encrypt($data) {
	return sha1(C("AUTH_CODE") . sha1($data));
}

/**
 * 将JSON输出到log文件
 * @param unknown $data
 */
function debugPrint($data){
	$dir = APP_PATH."Runtime/Logs/";
	if (!is_dir($dir)) {
		mkdir($dir);
	}

	$fileName = date("Y-m-d", time());
	$content = "IP:".$_SERVER['REMOTE_ADDR']."[".$_SERVER['REQUEST_METHOD']."] ".date("Y-m-d H:i:s", time())."\nhttp://". $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "\n";
	$content .= print_r($data, true) . "\n-------------结束-------------\n\n";
	file_put_contents($dir . $fileName . ".txt", $content, FILE_APPEND);
}

/**
 * 截取UTF-8编码下字符串的函数
 *
 * @param   string      $str        被截取的字符串
 * @param   int         $length     截取的长度
 * @param   bool        $append     是否附加省略号
 *
 * @return  string
 */
function sub_str($str, $length = 0, $append = true)
{
	$str = trim($str);
	$strlength = strlen($str);

	if ($length == 0 || $length >= $strlength)
	{
		return $str;
	}
	elseif ($length < 0)
	{
		$length = $strlength + $length;
		if ($length < 0)
		{
			$length = $strlength;
		}
	}

	if (function_exists('mb_substr'))
	{
		$newstr = mb_substr($str, 0, $length, EC_CHARSET);
	}
	elseif (function_exists('iconv_substr'))
	{
		$newstr = iconv_substr($str, 0, $length, EC_CHARSET);
	}
	else
	{
		//$newstr = trim_right(substr($str, 0, $length));
		$newstr = substr($str, 0, $length);
	}

	if ($append && $str != $newstr)
	{
		$newstr .= '...';
	}

	return $newstr;
}

/**
 * 数组转换
 * @param $array
 * @return $array
 */
function array_format($array){

	if(!is_array($array) or empty($array))
	{
		return array();
	}

	$format = array();
	$count = count(current($array));

	foreach ($array as $k=>$v){
		if(count($v)!=$count){
			return array();
		}

		for ($i=0;$i<$count;$i++){
			$format[$i][$k] = $v[$i];
		}
	}
	return $format;
}

/**
 * 生成商品货号
 * @param String
 * @return string 货号
 */
function GoodsNo($pre)
{
	return $pre.time().rand(10,99);
}

/**
 +-----------------------------------------------------------------------------------------
 * 删除目录及目录下所有文件或删除指定文件
 +-----------------------------------------------------------------------------------------
 * @param str $path   待删除目录路径
 * @param int $delDir 是否删除目录，1或true删除目录，0或false则只删除文件保留目录（包含子目录）
 +-----------------------------------------------------------------------------------------
 * @return bool 返回删除状态
 +-----------------------------------------------------------------------------------------
 */
function delDirAndFile($path, $delDir = FALSE) {
	$handle = opendir($path);
	if ($handle) {
		while (false !== ( $item = readdir($handle) )) {
			if ($item != "." && $item != ".." && $iem != ".svn")
				is_dir("$path/$item") ? delDirAndFile("$path/$item", $delDir) : unlink("$path/$item");
		}
		closedir($handle);
		if ($delDir)
			return rmdir($path);
	}else {
		if (file_exists($path)) {
			return unlink($path);
		} else {
			return FALSE;
		}
	}
}
