<?php
header("Content-type: image/JPEG");
use UAParser\Parser;
require_once 'vendor/autoload.php';
$im = imagecreatefromjpeg("ip-image.jpg"); 
$ip = $_SERVER["REMOTE_ADDR"];
$ua = $_SERVER['HTTP_USER_AGENT'];
$get = $_GET["s"];
$get = base64_decode(str_replace(" ","+",$get));
$weekarray = array("日","一","二","三","四","五","六"); 
//ua
$parser = Parser::create();
$result = $parser->parse($ua);
$os = $result->os->toString(); // Mac OS X
$browser = $result->device->family.'-'.$result->ua->family;// Safari 6.0.2 
//地址、温度
$data = json_decode(curl_get('https://api.xhboke.com/ip/v1.php?ip='.$ip), true);
$country = $data['site']['country']; 
$region = $data['site']['region']; 
$adcode = $data['site']['adcode']; 
$weather = $data['city']['weather'];
$temperature = $data['city']['temperature'];
//历史上今天
//$data = json_decode(get_curl('https://xhboke.com/mz/today.php'), true);
//$today = $data['cover']['title']; 
//定义颜色
$black = ImageColorAllocate($im, 0,0,0);//定义黑色的值
$color = ImageColorAllocate($im, 69,174,255);//颜色
$font = 'ip-auto.ttf';//加载字体
//输出
imagettftext($im, 18, 0, 10, 40, $color, $font,'你好吖，来自互联网的朋友！');
imagettftext($im, 18, 0, 10, 72, $color, $font, '今天是'.date('Y年n月j日').' 星期'.$weekarray[date("w")]);//当前时间
imagettftext($im, 18, 0, 10, 104, $color, $font,'IP:'.$ip);//IP
imagettftext($im, 18, 0, 10, 140, $color, $font,'操作系统:'.$os);//操作系统
imagettftext($im, 18, 0, 10, 175, $color, $font,'浏览器:'.$browser);//浏览器
imagettftext($im, 18, 0, 10, 200, $color, $font,$get); 
ImageGif($im);
ImageDestroy($im);


function curl_get($url, array $params = array(), $timeout = 6){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $file_contents = curl_exec($ch);
    curl_close($ch);
    return $file_contents;
}
?>


