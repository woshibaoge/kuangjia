<?php
/**
 * 打印函数
 * @param $var
 */
function p($var){
	echo '<pre style="background: #ccc;padding: 10px;border-radius: 5px;">';
	print_r($var);
	echo '</pre>';
}

//c('database.db_name');
//c('captcha.length');
function c($path){
	//  吧字符创转化成数组
	$arr = explode('.',$path);
	//$arr = ['database','db_name'];
	//  引入到配置项的config文件
	$config = include '../system/config/' . $arr[0] . '.php';
	//   如果存在用户名  那么就返回 不存在就表示为空
	return isset($config[$arr[1]]) ? $config[$arr[1]] : NULL;
}






