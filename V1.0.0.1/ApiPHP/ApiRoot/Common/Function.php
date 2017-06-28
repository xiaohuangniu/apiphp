<?php
/*
 +----------------------------------------------------------------------
 + Title        : Api框架 公共函数文件
 + Author       : 极资源首席工程师 - 小黄牛
 + Version      : V1.0.0.1
 + Initial-Time : 2017-06-26 13:00
 + Last-time    : 2017-06-27 08:44 + 小黄牛
 + Desc         : 加入db、S、X、M函数
 +----------------------------------------------------------------------
*/

/**
 * 获取配置文件项的内容，并且可以对配置文件项做临时修改
 * @param string $name  配置变量名
 * @param mixed  $num   需要临时修改的配置参数，默认为空，为空时只做查询操作
 * @return mixed
 */
function config($name,$num=''){
	global $JunPhpConfigFunction;
	
	# 无配置引入则获取
	if (empty($JunPhpConfigFunction)) {
		$C = include COMMON_PATH.'/Config'.EXT;
		$JunPhpConfigFunction = $C;
	}
	
	# $num 不为空则做临时修改
	if (!empty($num)) {
		$JunPhpConfigFunction[$name] = $num;
	} else {
		return $JunPhpConfigFunction[$name];
	}
}

/**
 * 获取数据库配置项的内容
 * @param string $name  配置变量名
 * @return mixed
 */
function db($name){
    $C     = include COMMON_PATH.'/Database'.EXT;
    $str   = $C;
    return $str[$name];
}

/**
 * 输出JSON
 * @param int         $code   状态码
 * @param string|int  $msg    描述
 * @param mixed       $data   返回数据
 * @param bool        $type   是否输出或返回
 * @return string json后的字符串
 */
function json($code, $msg, $data='', $type=false){
	$json = json_encode([
				'code' => "{$code}",
				'msg'  => $msg,
				'data' => $data,
	]);
	
	# 直接返回
	if ($type) { return $json; }
	# 直接输出
	echo $json;exit;
}

/**
 * 数据加解密(来自Discuz经典代码)
 * @param string $string      需要加解密的数据
 * @param bool   $operation   是否解密，false|true
 * @param string $key         加解密秘钥     
 * @param int    $expiry      数据失效时间
 * @return string 加解密后的字符串
 */
function dec_ryption($string, $operation = true, $key, $expiry = 0) {   
    # 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙   
    $ckey_length = 4;   
    # 密匙   
    $key  = md5($key);   
    # 密匙a会参与加解密   
    $keya = md5(substr($key, 0, 16));   
    # 密匙b会用来做数据完整性验证   
    $keyb = md5(substr($key, 16, 16));   
    # 密匙c用于变化生成的密文   
    $keyc = $ckey_length ? ($operation == true ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';   
    # 参与运算的密匙   
    $cryptkey   = $keya.md5($keya.$keyc);   
    $key_length = strlen($cryptkey);   
   
    # 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)， 
    # 解密时会通过这个密匙验证数据完整性   
    # 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确   
    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) :  sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;   
    $string_length = strlen($string);   
    $result = '';   
    $box    = range(0, 255);   
    $rndkey = array();   

    # 产生密匙簿   
    for($i = 0; $i <= 255; $i++) {   
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);   
    }   

    # 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上对并不会增加密文的强度   
    for($j = $i = 0; $i < 256; $i++) {   
        $j   = ($j + $box[$i] + $rndkey[$i]) % 256;   
        $tmp = $box[$i];   
        $box[$i] = $box[$j];   
        $box[$j] = $tmp;   
    }   

    # 核心加解密部分   
    for($a = $j = $i = 0; $i < $string_length; $i++) {   
        $a = ($a + 1) % 256;   
        $j = ($j + $box[$a]) % 256;   
        $tmp     = $box[$a];   
        $box[$a] = $box[$j];   
        $box[$j] = $tmp;   
        # 从密匙簿得出密匙进行异或，再转成字符   
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));   
    }   

    if($operation == true) {  
        # 验证数据有效性，请看未加密明文的格式   
        if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) &&  substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {   
            return substr($result, 26);   
        } else {   
            return '';   
        }   
    } else {   
        # 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因   
        # 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码   
        return $keyc.str_replace('=', '', base64_encode($result));   
    }   
}


/*
* SQL注入过滤
* $string : 需要过滤的字符串
* 返回过滤之后的字符串
*/
function S($string) {
	$source = 'or|and|xor|not|select|insert|update|delete|=>|<=|!=|union|into|load_file|outfile';
	$badword = explode('|',$source);
	$badword1 = array_combine($badword,array_fill(0,count($badword),'/'));
    $string = strtr($string,$badword1);
	if (! get_magic_quotes_gpc ()) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = JunSql($val);
			}
		} else {
				$string = $string;
		}
	}
    return $string;
}

/*
* XSS攻击过滤
* $string : 需要过滤的字符串
* 返回过滤之后的字符串
*/
function X($string) {
    $string = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S', '', $string);

    $parm1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');

    $parm2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');

    $parm = array_merge($parm1, $parm2);

    for ($i = 0; $i < sizeof($parm); $i++) {
        $pattern = '/';
        for ($j = 0; $j < strlen($parm[$i]); $j++) {
            if ($j > 0) {
                $pattern .= '(';
                $pattern .= '(&#[x|X]0([9][a][b]);?)?';
                $pattern .= '|(&#0([9][10][13]);?)?';
                $pattern .= ')?';
            }
            $pattern .= $parm[$i][$j];
        }
        $pattern .= '/i';
        $string = preg_replace($pattern, '', $string);
    }
    return $string;
}


/*
* M方法，实例化Model基类
* $table : 数据表名， 不带表前缀，为空时只能执行源生sql语句
* 返回实例化对象
*/
# 引入模型类
require LIB_PATH.'Model/Model.php';
function M($table=''){
	return new Model($table);
}