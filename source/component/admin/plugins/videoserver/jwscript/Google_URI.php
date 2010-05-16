<?php

// Google URI:      http://video.google.com/videoplay?docid=7206494026138253535
// MP4:
// call with:      http://www.mydomain.com/path/Google_URI.php?docid=7206494026138253535
// JWMP code:
// 'file':          encodeURIComponent('Google_URI.php?docid=7206494026138253535'),
// 'type':         'video',
// FLV:
// call with:      http://www.mydomain.com/path/Google_URI.php?docid=7206494026138253535&type=flv
// JWMP code:
// 'file':          encodeURIComponent('Google_URI.php?docid=7206494026138253535&type=flv'),
// 'type':         'video',

ini_set('error_reporting', 'E_ALL & ~E_NOTICE');

$docid = (isset($_GET['docid'])) ? strval($_GET['docid']) : '7206494026138253535';
$type  = (isset($_GET['type']))  ? strval($_GET['type'])  : 'flv';

$file = @file_get_contents("http://video.google.com/videoplay?docid=$docid");

if($type == 'flv')
{
  preg_match("/googleplayer.swf\?videoUrl\\\\x3d(.*?)\\\\x26/", $file, $match);
}
else
{
  preg_match('/right-click <a href="(.*?)">this link/', $file, $match);
}

$uri = urldecode($match[1]);

header("Location: $uri");

?>