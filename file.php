<?php

class File {
        /**
	*$key缓存文件的文件名
	*$value缓存数据
	*$path缓存路径
	*
	*/
	//默认路径
	private $_dir;

	const EXT = '.txt';

	public function __construct() {
		$this->_dir = dirname(__FILE__) . '/files/';
	}
        
        //$cacheTime是缓存失效时间
	public function cacheData($key, $value = '', $cacheTime = 0) {
                //将其写入文件，fwrite和file_put_contents
		$filename = $this->_dir  . $key . self::EXT;

		if($value !== '') { // 将value值写入缓存
                        //删除缓存走这里
			if(is_null($value)) {
				return @unlink($filename);
			}
			$dir = dirname($filename);
                        //如果文件目录不存在则创建目录
			if(!is_dir($dir)) {
				mkdir($dir, 0777);
			}
 
                        //%011d是把时间设置为11位，不够前面补0
			$cacheTime = sprintf('%011d', $cacheTime);
                        //将$value写入文件,$value只能是字符串形式，不能是数组或是其它类型，写入成功则返回字节数，错误返回false
			return file_put_contents($filename,$cacheTime . json_encode($value));
		}

                //获取缓存
		if(!is_file($filename)) {
			return FALSE;
		} 
		$contents = file_get_contents($filename);
		$cacheTime = (int)substr($contents, 0 ,11);
		$value = substr($contents, 11);
                //缓存失效则删除，如果缓存失效时间不等于0
		if($cacheTime !=0 && ($cacheTime + filemtime($filename) < time())) {
			unlink($filename);
			return FALSE;
		}
                //没有失效的话返回$value值
		return json_decode($value, true);
		
	}
}

$file = new File();
//三个参数，第一个是文件名，第二个是值，第三个是缓存失效时间，获取文件只需要第一个值就行
echo $file->cacheData('test1');