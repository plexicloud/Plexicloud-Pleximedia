<?php
ini_set('error_reporting', 'E_ALL & ~E_NOTICE');
/**
=== Explaining YouTube formats ===
Widescreen videos:
6 = 320x180 @ FLV;
18 = 480x270 @ MP4;
22 = 1280x720 @ MP4;
35 = 640x360 @ FLV;
*/
$videoid=$_GET["v"];
$format = $_GET["fmt"];
if(empty($format)) $format = 18;
$content= file_get_contents("http://youtube.com/get_video_info?video_id=$videoid");
parse_str($content);
$url = "http://www.youtube.com/get_video.php?video_id=" . $videoid . "&t=" . $token. "&fmt=".$format;
//$url = "http://www.youtube.com/get_video.php?video_id=" . $videoid . "&t=" . $token. ";
$headers = get_headers($url,1);

if(!is_array($headers['Location'])) {
$url = $headers['Location'];
}else {
foreach($headers['Location'] as $h){
if(strpos($h,"googlevideo.com")!=false){
$url = $h;
break;
}
}
}
if(isset($_GET["debug"])){
print "URI: $url<br/>" ;
echo "<pre>";print_r($headers);
die("it's all folks!");
}
header("Location: $url");
?>