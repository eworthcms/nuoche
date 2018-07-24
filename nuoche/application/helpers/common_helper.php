<?php

/**
 * 随机数的生成
 * @param number $len
 * @param number $type
 * @return string
 */
function gen_secret($len = 6, $type = 1) {
	$secret = '';
	for($i = 0; $i < $len; $i ++) {
		if (1 == $type) {
			if (0 == $i) {
				$secret .= chr ( rand ( 49, 57 ) );
			} else {
				$secret .= chr ( rand ( 48, 57 ) );
			}
		} else if (2 == $type) {
			$secret .= chr ( rand ( 65, 90 ) );
		} else {
			if (0 == $i) {
				$secret .= chr ( rand ( 65, 90 ) );
			} else {
				$secret .= (0 == rand ( 0, 1 )) ? chr ( rand ( 65, 90 ) ) : chr ( rand ( 48, 57 ) );
			}
		}
	}
	return $secret;
}
function is_ajax(){
	$res=false;
	if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
		$res=true;
	} 
	return $res;
}
/**
 *
 * @param string $mobile        	
 */
function is_mobile($mobile = '') {
	return preg_match ( '/^1[34578][\d]{9}$/', $mobile );
}
/**
 * 短信发送
 */
function send_sms($mobile = '', $content = '') {
	$username = 'clyznqy';
	$password = 'g5dqpchb';
	$apiUrl = 'http://sms-cly.cn/smsSend.do';
	$data = array (
			'username' => $username,
			'password' => md5 ( $username . md5 ( $password ) ),
			'mobile' => $mobile,
			'content' => $content . '【猫客宠物】' 
	);
	$res = http_request ( $apiUrl, $data );
	return $res;
}
function do_get($url) {
	$url2 = parse_url ( $url );
	$url2 ["path"] = ($url2 ["path"] == "" ? "/" : $url2 ["path"]);
	$url2 ["port"] = ($url2 ["port"] == "" ? 80 : $url2 ["port"]);
	$host_ip = @gethostbyname ( $url2 ["host"] );
	$fsock_timeout = 1;
	if (($fsock = fsockopen ( $host_ip, 80, $errno, $errstr, $fsock_timeout )) < 0) {
		return false;
	}
	$request = $url2 ["path"] . ($url2 ["query"] ? "?" . $url2 ["query"] : "");
	$in = "GET " . $request . " HTTP/1.0\r\n";
	$in .= "Accept: */*\r\n";
	$in .= "User-Agent: Payb-Agent\r\n";
	$in .= "Host: " . $url2 ["host"] . "\r\n";
	$in .= "Connection: Close\r\n\r\n";
	if (! @fwrite ( $fsock, $in, strlen ( $in ) )) {
		fclose ( $fsock );
		return false;
	}
	return get_http_content ( $fsock );
}
function do_post($url, $post_data = array()) {
	$url2 = parse_url ( $url );
	$url2 ["path"] = ($url2 ["path"] == "" ? "/" : $url2 ["path"]);
	$url2 ["port"] = ($url2 ["port"] == "" ? 80 : $url2 ["port"]);
	$host_ip = @gethostbyname ( $url2 ["host"] );
	$fsock_timeout = 1; // 秒
	if (($fsock = fsockopen ( $host_ip, 80, $errno, $errstr, $fsock_timeout )) < 0) {
		return false;
	}
	$request = $url2 ["path"] . ($url2 ["query"] ? "?" . $url2 ["query"] : "");
	$post_data2 = http_build_query ( $post_data );
	$in = "POST " . $request . " HTTP/1.0\r\n";
	$in .= "Accept: */*\r\n";
	$in .= "Host: " . $url2 ["host"] . "\r\n";
	$in .= "User-Agent: Lowell-Agent\r\n";
	$in .= "Content-type: application/x-www-form-urlencoded\r\n";
	$in .= "Content-Length: " . strlen ( $post_data2 ) . "\r\n";
	$in .= "Connection: Close\r\n\r\n";
	$in .= $post_data2 . "\r\n\r\n";
	unset ( $post_data2 );
	if (! @fwrite ( $fsock, $in, strlen ( $in ) )) {
		fclose ( $fsock );
		return false;
	}
	return get_http_content ( $fsock );
}
function get_http_content($fsock = null) {
	$out = null;
	while ( $buff = @fgets ( $fsock, 2048 ) ) {
		$out .= $buff;
	}
	fclose ( $fsock );
	$pos = strpos ( $out, "\r\n\r\n" );
	$head = substr ( $out, 0, $pos ); // http head
	$status = substr ( $head, 0, strpos ( $head, "\r\n" ) ); // http status line
	$body = substr ( $out, $pos + 4, strlen ( $out ) - ($pos + 4) ); // page body
	if (preg_match ( "/^HTTP\/\d\.\d\s([\d]+)\s.*$/", $status, $matches )) {
		if (intval ( $matches [1] ) / 100 == 2) {
			return $body;
		} else {
			return false;
		}
	} else {
		return false;
	}
}
function http_request($url, $data = array()) {
	if (! function_exists ( 'curl_init' )) {
		return empty ( $data ) ? DoGet ( $url ) : DoPost ( $url, $data );
	}
	$ch = curl_init ();
	if (is_array ( $data ) && $data) {
		$formdata = http_build_query ( $data );
		curl_setopt ( $ch, CURLOPT_POST, true );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $formdata );
	}
	curl_setopt ( $ch, CURLOPT_URL, $url );
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 1 );
	curl_setopt ( $ch, CURLOPT_TIMEOUT, 1 );
	return curl_exec ( $ch );
}

// 将base64编码保存为图片
/*function base64_to_pic($base64) {
	$type='jpg';
	$file_name=md5(time().gen_secret(6));
	$path_name="uploads/".date('Y-m-d').'';
	$full_name=APPPATH.$path_name;
	if (preg_match ( '/^(data:\s*image\/(\w+);base64,)/', $base64, $result )) {
		$new_file = $path_name.$file_name.$type;
		if (file_put_contents ( $full_name, base64_decode ( str_replace ( $result [1], '', $base64 ) ) )) {
			return $new_file;
		}else{
			return '';
		}
	}
}*/
function base64_to_pic($base64_image_content){
	//匹配出图片的格式
	if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
		$type = $result[2];
		$full_name = ROOTPATH."uploads/".date('Ymd',time())."/";
		$new_file = "uploads/".date('Ymd',time())."/";
		if(!is_dir($full_name))
		{  
			//检查是否有该文件夹，如果没有就创建，并给予最高权限
			mkdir($full_name);
			chmod($full_name,0777);
		}
		$file_name=md5(time().gen_secret(6)).".{$type}";
		$full_name = $full_name.$file_name;
		$new_file=$new_file.$file_name;
		if (file_put_contents($full_name, base64_decode(str_replace($result[1], '', $base64_image_content)))){
			return $new_file;
		}else{
			return  '';
		}
	}
}

function get_city_info($ip){
	$data=array();
	if (!empty($ip) && $ip!='127.0.0.1'){
		$httpUrl = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=' . $ip;
		$res = http_request ( $httpUrl );
		$resArr = json_decode ( $res, true );
		$data=$resArr;
	}else{
		$data=array('province'=>'北京','city'=>'北京');
	}
	return $data;
}
function merge_qrcode($file_name) {
	$imgs [0] =ROOT_PATH . 'uploads/qrcode/'.$file_name.'_qrcode.jpg';// 二维码图片
	$imgs [1] =ROOT_PATH . 'uploads/qrcode/'.$file_name.'_qrcode_no.jpg';// 
	$target = ROOT_PATH . 'uploads/qrcode/qrcode_back.jpg'; // 背景图片
	$target_img = Imagecreatefromjpeg ( $target );
	$source = array ();
	foreach ( $imgs as $k => $v ) {
		$source [$k] ['source'] = Imagecreatefromjpeg ( $v );
		
		$source [$k] ['size'] = getimagesize ( $v );
	}
	foreach ( $imgs as $k => $v ) {
		$source [$k] ['source'] = Imagecreatefromjpeg ( $v );
		$source [$k] ['size'] = getimagesize ( $v );
	}
	// imagecopy ($target_img,$source[0]['source'],2,2,0,0,$source[0]['size'][0],$source[0]['size'][1]);
	// imagecopy ($target_img,$source[1]['source'],250,2,0,0,$source[1]['size'][0],$source[1]['size'][1]);
	$num1 = 0;
	$num = 1;
	$tmp = 2;
	$tmpy = 2; // 图片之间的间距
	for($i = 0; $i < 2; $i ++) {
		if ($i == 0) {
			imagecopy ( $target_img, $source [$i] ['source'], $tmp, 20, 0, 0, $source [$i] ['size'] [0], $source [$i] ['size'] [1] );
		} else {
			imagecopy ( $target_img, $source [$i] ['source'], 0, 440, 0, 0, $source [$i] ['size'] [0], $source [$i] ['size'] [1] );
		}
	}
	Imagejpeg ( $target_img, ROOT_PATH . "uploads/qrcode/$file_name.jpg" );
}
function gen_text_img($file_name){
	// Set the content-type
	// Create the image
	$im = imagecreatetruecolor(430, 80);
	
	// Create some colors
	$white = imagecolorallocate($im, 255, 255, 255);
	$grey = imagecolorallocate($im, 128, 128, 128);
	$black = imagecolorallocate($im,197,43,39);
	imagefilledrectangle($im, 0, 0, 430, 80, $white);
	
	// The text to draw
	$textstr = $file_name;
	$text='';
	if (strlen($textstr)>1){
		for ($i=0;$i<=strlen($textstr);$i++){
			$text.=substr($textstr,$i,1)."";
		}
	}
	$text='NO '.$text;
	// Replace path by your own font path
	$font = ROOT_PATH.'font/arial.ttf';
	// Add some shadow to the text
	//imagettftext($im, 60, 0, 11, 21, $grey, $font, $text);
	// Add the text
	imagettftext($im, 30, 0, 110, 55, $black, $font, $text);
	// Using imagepng() results in clearer text compared with imagejpeg()
	//imagepng($im);
	ob_start();
	imagejpeg($im);
	$img = ob_get_contents();
	ob_end_clean();
	$size = strlen($img);
	$fp2=@fopen(ROOT_PATH.'uploads/qrcode/'.$textstr.'_qrcode_no.jpg', "a");
	fwrite($fp2,$img);
	fclose($fp2);
	imagedestroy($im);
}
function local_qrcode($qrcode_img,$file_name){
	$img_source=file_get_contents($qrcode_img);
	$orgin_qrcode_path=ROOT_PATH.'uploads/qrcode/';
	$orgin_qrcode_file=$file_name.'_qrcode.jpg';
	//var_dump($orgin_qrcode_path);exit;
	$res=file_put_contents($orgin_qrcode_path.$orgin_qrcode_file, $img_source);
}
function gen_text_focus_img($text){

	// Set the content-type
	// Create the image
	$im = imagecreatetruecolor(500, 80);
	
	// Create some colors
	$white = imagecolorallocate($im, 255, 255, 255);
	$grey = imagecolorallocate($im, 128, 128, 128);
	$black = imagecolorallocate($im, 0, 0, 0);
	imagefilledrectangle($im, 0, 0, 500, 80, $white);
	// Replace path by your own font path
	$font = ROOT_PATH.'font/msyh.ttf';
	// Add some shadow to the text
	//imagettftext($im, 60, 0, 11, 21, $grey, $font, $text);
	// Add the text
	$text=mb_convert_encoding($text,"UTF8");
	imagettftext($im,25, 0, 15, 55, $black, $font, $text);
	// Using imagepng() results in clearer text compared with imagejpeg()
	//imagepng($im);
	ob_start();
	imagejpeg($im);
	$img = ob_get_contents();
	ob_end_clean();
	$size = strlen($img);
	$fp2=@fopen(ROOT_PATH.'uploads/qrcode/qrcode_focus.jpg', "a");
	fwrite($fp2,$img);
	fclose($fp2);
	imagedestroy($im);
}

function merge_focus_qrcode($file_name){
	$imgs [0] =ROOT_PATH . 'uploads/qrcode/qrcode_focus.jpg';// 二维码图片
	$imgs [1] =ROOT_PATH . 'uploads/qrcode/'.$file_name.'_qrcode.jpg';// 二维码图片
	$target = ROOT_PATH . 'uploads/qrcode/qrcode_focus_back.jpg'; // 背景图片
	$target_img = Imagecreatefromjpeg ( $target );
	$source = array ();
	foreach ( $imgs as $k => $v ) {
		$source [$k] ['source'] = Imagecreatefromjpeg ( $v );
	
		$source [$k] ['size'] = getimagesize ( $v );
	}
	foreach ( $imgs as $k => $v ) {
		$source [$k] ['source'] = Imagecreatefromjpeg ( $v );
		$source [$k] ['size'] = getimagesize ( $v );
	}
	// imagecopy ($target_img,$source[0]['source'],2,2,0,0,$source[0]['size'][0],$source[0]['size'][1]);
	// imagecopy ($target_img,$source[1]['source'],250,2,0,0,$source[1]['size'][0],$source[1]['size'][1]);
	$num1 = 0;
	$num = 1;
	$tmp = 2;
	$tmpy = 2; // 图片之间的间距
	for($i = 0; $i < 2; $i ++) {
		if ($i == 0) {
			imagecopy ( $target_img, $source [$i] ['source'], $tmp, 20, 0, 0, $source [$i] ['size'] [0], $source [$i] ['size'] [1] );
		} else {
			imagecopy ( $target_img, $source [$i] ['source'], 38,100, 0, 0, $source [$i] ['size'] [0], $source [$i] ['size'] [1] );
		}
	}
	$file_name=$file_name."_qrcode_focus";
	Imagejpeg ( $target_img, ROOT_PATH . "uploads/qrcode/$file_name.jpg" );
	
}

function gen_qrcode($qrcode_img,$file_name){
	local_qrcode($qrcode_img, $file_name);
	gen_text_img($file_name);
	merge_qrcode($file_name);
	//gen_text_focus_img('临时停车 请多关注微信扫码挪车');
	merge_focus_qrcode($file_name);
}
//根据经纬度反查地理位置
function local_place($lat,$lng,$ip){
    $place='';
    if (!empty($lat) && !empty($lng)){
        $place=$lat.','.$lng;
    }
    $url="http://api.map.baidu.com/geocoder/v2/?location={$place}&output=json&pois=0&ak=no3uOkPZhRxSxpEP0syZQVQr";
    $resJson=http_request($url);
    $res=json_decode($resJson,true);
    $status=$res['status'];
    $result=$res['result'];
    $addressComponent=$result['addressComponent'];
    $formatted_address='';
    if ($status==0){
        $province=$addressComponent['province'];
        $city=$addressComponent['city'];
        $formatted_address=$result['formatted_address'];
        if ($province==$city){
            $city=$addressComponent['district'];
        }
    }else{
        //根据ip查询
        $ipUrl="http://api.map.baidu.com/location/ip?ip={$ip}&ak=no3uOkPZhRxSxpEP0syZQVQr&coor=bd09ll";
        $ipJson=http_request($ipUrl);
        $res=json_decode($ipJson,true);
        $content=$res['content'];
        $address_detail=$content['address_detail'];
        $province=$address_detail['province'];
        $city=$address_detail['city'];
    }
    return array('province'=>$province,'city'=>$city,'position'=>$formatted_address);
}