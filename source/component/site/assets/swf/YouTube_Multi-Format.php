<?php

// call with: http://www.mydomain.com/path/YouTube_Multi-Format.php?v=K2_U1kbIcJQ&fmt=18
//        'file':                                   encodeURIComponent('YouTube_Multi-Format.php?v=K2_U1kbIcJQ&fmt=18'),
//        'type':                                  'video',
// fmt=6  HQ FLV   480x360
// fmt=18 HQ MP4   480x270
// fmt=22 HQ MP4  1280x720
// fmt=35 HQ MP4   640x360

$videoid = (isset($_GET['v']))   ? strval($_GET['v'])   : 'K2_U1kbIcJQ';
$fmt     = (isset($_GET['fmt'])) ? intval($_GET['fmt']) : '';
$uri     = "http://www.youtube.com/api2_rest?method=youtube.videos.get_video_token&video_id=$videoid";
$t       = trim(strip_tags(@file_get_contents($uri)));
$uri     = "http://www.youtube.com/get_video.php?video_id=$videoid&t=$t&fmt=$fmt";

//...debug
/*
$headers = get_headers($uri);
print "<pre>\n";
print "URI: $uri\n" ;
print_r($headers);
print "\n</pre>\n";
exit;
*/
//...debug

header("Location: $uri");

?>