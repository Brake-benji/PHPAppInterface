<?php

/*$arr = array(
	'id' => 1,
	'name' => 'username',
	'pwd' => 123456

 );

$data = "输出json数据";
$newData = iconv('UTF-8', 'GBK', $data);  //次函数用于字符串的编码转换
echo $newData;
echo json_encode($newData);*/

/**
* 按JSON方式输出通信数据（封装）
* @param integer $code 状态码
* @param string  $message 提示信息
* @param array $data 数据
* return string
*/

class Response {
	const JSON = "json";
	/**
	* 按综合方式输出通信数据
	* @param integer $code 状态码
	* @param string $message 提示信息
	* @param array $data 数据
	* @param string $type 数据类型
	* return string
	*/
	public static function show($code, $message = '', $data = array(), $type = self::JSON) {
		if(!is_numeric($code)) {
			return '';
		}

		$type = isset($_GET['format']) ? $_GET['format'] : self::JSON;

		$result = array(
			'code' => $code,
			'message' => $message,
			'data' => $data,
		);
                
                //$type = self::JSON走这里.地址栏jsonTest.php?format=json这样访问即可
		if($type == 'json') {
			self::json($code, $message, $data);
			exit;
		} elseif($type == 'array') {
			var_dump($result);
		} elseif($type == 'xml') {
			self::xmlEncode($code, $message, $data);
			exit;
		} else {
			// TODO
		}
	}
        
        /*public static function xml(){
		//以XML的形式展现
        header("Content-Type:text/xml");

		//组装字符串的方法
		$xml = "<?xml vesion='1.0' encoding='UTF-8'?>\n";
		$xml .= "<root>\n";
		$xml .= "<code>200</code>\n";
		$xml .= "<message>数据返回成功</message>\n";
		$xml .= "<data>\n";
		$xml .= "<id>1</id>\n";
		$xml .= "<name>benji</name>\n";
		$xml .= "</data>\n";
		$xml .= "</root>\n";

		echo $xml;
	}*/

	/**
	* 按json方式输出通信数据
	* @param integer $code 状态码
	* @param string $message 提示信息
	* @param array $data 数据
	* return string
	*/
	public static function json($code, $message = '', $data = array()) {
		
		if(!is_numeric($code)) {
			return '';
		}

		$result = array(
			'code' => $code,
			'message' => $message,
			'data' => $data
		);

		echo json_encode($result);
		exit;
	}

	/**
	* 按xml方式输出通信数据
	* @param integer $code 状态码
	* @param string $message 提示信息
	* @param array $data 数据
	* return string
	*/
	public static function xmlEncode($code, $message, $data = array()) {
		if(!is_numeric($code)) {
			return '';
		}

		$result = array(
			'code' => $code,
			'message' => $message,
			'data' => $data,
		);

		header("Content-Type:text/xml");
		$xml = "<?xml version='1.0' encoding='UTF-8'?>\n";
		$xml .= "<root>\n";
                
                //这边可以是$result也可以是$data,但是区别很大效果不一样
		$xml .= self::xmlToEncode($result);

		$xml .= "</root>";
		echo $xml;
	}

	public static function xmlToEncode($data) {
                //定义变量存储XML数据
		$xml = $attr = "";
		foreach($data as $key => $value) {
			if(is_numeric($key)) {
				$attr = " id='{$key}'";
				$key = "item";
			}
                        //加大括号是为了识别标签内容
			$xml .= "<{$key}{$attr}>";
                        //$result时是数组，要递归下
			$xml .= is_array($value) ? self::xmlToEncode($value) : $value;
			$xml .= "</{$key}>\n";
		}
		return $xml;
	}

}