<?php
//#########################################################################
// REVVER.COM PLUGIN FOR JOMTUBE
// @ Copyright (C) 2008 Jomtube.com
// @ All rights reserved
// @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
//#########################################################################

defined( '_JEXEC' ) or die( 'Restricted access' );// no direct access

ini_set('error_reporting', 'E_ALL & ~E_NOTICE');

function revvergetvideodetails($vidlink, $existingcode, $categorylist, $reqtype){
	global $database ,$my;

        $mosConfig_absolute_path = JPATH_SITE;
        $mosConfig_live_site =  substr(JURI::base(), 0, strlen(JURI::base()) -1);

		$vidlink=jalemurldecode($vidlink);

		$smallvideocode=str_replace("http://www.revver.com/video/","",$vidlink);
		$smallvideocode=str_replace("http://revver.com/video/","",$smallvideocode);
		//http://www.revver.com/video/531282/glacier-race
		//improved security not to call another site...
		$vidlink="http://www.revver.com/video/".$smallvideocode;

		$smallvideocode  = eregi_replace("/(.*)", "", $smallvideocode );

		$videoservertype="revver";



//	$vidlink = jalemurldecode($vidlink);

    //http://clipser.com/watch_video/1048696
 //   $smallvideocode = str_replace("http://www.clipser.com/watch_video/", "", $vidlink);
   // $smallvideocode = str_replace("http://clipser.com/watch_video/", "", $smallvideocode);

    //improved security not to call another site...
//	$vidlink = "http://clipser.com/watch_video/".$smallvideocode;

//    $videoservertype = "clipser";



// ###########################################################
// ###### SCRAPE PAGE FROM SNOOPY CLASS
// ###########################################################
		include("$mosConfig_absolute_path/administrator/components/com_jomtube/includes/Snoopy.class.php");
		$snoopy = new Snoopy;
		$snoopy->fetch("http://www.revver.com/video/".$smallvideocode."");
		$strAPI = $snoopy->results;

// ##################################################
// ########### VIDEO ID PARSE FROM HTML SOURCE
// ##################################################
   	 	$remote_id = $smallvideocode; // remote_id -> FIELD name in Database
//		echo $remote_id; // TEST OUT THE remote_id

// ##################################################
// ########### VIDEO ID PARSE FROM HTML SOURCE
// ##################################################
		$start_position = strpos($strAPI,"http://www.facebook.com/share.php?u=")+36;
		$end_position = strpos($strAPI, "\"", $start_position)-$start_position;
		$video_url = substr($strAPI,$start_position,$end_position);
		$video_url = urldecode($video_url);
//		echo $video_url; // TEST OUT THE Video URL

// ##################################################
// ########### VIDEO TITLE PARSE FROM HTML SOURCE
// ##################################################
		$start_position = strpos($strAPI,"<meta name=\"title\" content=\"")+28;
		$end_position = strpos($strAPI, "\" />", $start_position)-$start_position;
		$videotitle = substr($strAPI,$start_position,$end_position);
		$videotitle = ucwords (strtolower($videotitle));

// ##################################################
// ########### VIDEO DESCRIPTION PARSE FROM HTML SOURCE
// ##################################################
		$start_position = strpos($strAPI,"<span id=\"video_description_text\">")+34;
		$end_position = strpos($strAPI, "</span>", $start_position)-$start_position;
		$videodescription = substr($strAPI,$start_position,$end_position);
		$videodescription = strip_tags($videodescription); // video_desc -> FIELD name in Database
//		echo $videodescription; // TEST OUT THE Video Description

// ##################################################
// ########### VIDEO UPDATED TODAYS DATE
// ##################################################
		$date_updated = date('Y-m-d'); // date_added -> FIELD name in Database

// ##################################################
// ########### VIDEO PUBLISHED DATE FROM HTML SOURCE
// ##################################################
		$start_position1 = strpos($strAPI,"</strong> since")+16;
		$start_position = strpos($strAPI,"<strong>", $start_position1)+8;
		$end_position = strpos($strAPI, "</strong>", $start_position)-$start_position;
		$video_published = substr($strAPI,$start_position,$end_position);
		$video_published = date("Y-m-d", strtotime($video_published)); // date_added -> FIELD name in Database
		$video_published = date('Y-m-d');

// ##################################################
// ########### VIDEO KEYWORDS PARSE FROM HTML SOURCE
// ##################################################
		$start_position = strpos($strAPI,"<meta name=\"keywords\" content=\"")+31;
		$end_position = strpos($strAPI, "\" />", $start_position)-$start_position;
		$videotags = substr($strAPI, $start_position, $end_position);

// ##################################################
// ########### VIDEO THUMBNAIL PARSE FROM HTML SOURCE
// ##################################################
		$start_position = strpos($strAPI, "<link rel=\"image_src\" href=\"")+28;
		$end_position = strpos($strAPI, "\" />", $start_position)-$start_position;
		$thumbnail_link = substr($strAPI, $start_position, $end_position);

// ##################################################
// ########### VIDEO THUMBNAIL PARSE FROM HTML SOURCE
// ##################################################
		$start_position = strpos($strAPI, "<link rel=\"image_src\" href=\"")+28;
		$end_position = strpos($strAPI, "\" />", $start_position)-$start_position;
		$display_thumb = substr($strAPI, $start_position, $end_position);

// ##################################################
// ########### VIDEO DURATION PARSE FROM HTML SOURCE
// ##################################################

  if ($reqtype=="new") {
    $renderinputform=renderinputform($video_url,$thumbnail_link,$display_thumb,$videotitle,$videodescription,$videotags,$video_published,$date_updated,$videoduration,$remote_id,$videoservertype,$smallvideocode,$categorylist);
    return $renderinputform;
  } else if ($reqtype=="refresh") {
    return array ($picturelink, $videotitle, $itemcomment);
  }

}

function revverembed($video_detail, $jomtube_configs){
    $mosConfig_absolute_path = JPATH_SITE;
    $mosConfig_live_site =  substr(JURI::base(), 0, strlen(JURI::base()) -1);
    $vcode = jalemurldecode($video_detail->remote_id);

// ##################################################
// ########### USE VIDEO WIDTH, HEIGHT CONFIG FROM BACKEND
// ##################################################
    if ($jomtube_configs->video_player_height>0 AND $jomtube_configs->video_player_width>0){
        $videowidth=$jomtube_configs->video_player_width;
        $videoheight=$jomtube_configs->video_player_height;
    }

// ##################################################
// ########### USE LOCAL PLAYER (WILL BE CONFIG IN BACKEND)
// ##################################################
    $local_player = 0; //$local_player = $jomtube_configs->local_player
    if ($local_player=="1"){


// ##################################################
// ########### GET DIRECT LINK TO FLV CREATE XML
// ##################################################
       $cache_file = $mosConfig_absolute_path .  $video_detail->directory . '/cache/' . $video_detail->video_type . '/' . $video_detail->id . '.xml';
      if (file_exists($cache_file))
        unlink($cache_file);
        require("$mosConfig_absolute_path/administrator/components/com_jomtube/includes/jomtube_getflv.php");
		$flvlink = api_getflv($video_detail->video_url);
        genXmlFile($video_detail, $flvlink);

// #######################################################
// #### BEHAVIOUR PARAMETERS FOR JW PLAYER
// #######################################################
        if ($jomtube_configs->auto_play_on_load == "1") {
            $repeat="repeat=true";
        } else {
            $repeat="repeat=false";
        }


	$jwplayer = "".$mosConfig_live_site."/components/com_jomtube/assets/swf/player.swf";
	$skin = "".$mosConfig_live_site."/components/com_jomtube/assets/swf/skins/$jomtube_configs->jw_player_skin.swf";
	$autostart = $repeat; // 'true' or 'false' -> starts video when page loads
	$vidstretching = "fill"; // (none) no stretching, (exactfit), (uniform) stretch with black borders, (fill) uniform, but completely fill the display
	$display_thumb = $video_detail->display_thumb;

	$embedvideo = "
	<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" name=\"jomtube_player\"  width=\"".$videowidth."\" height=\"".$videoheight."\" >
	<param name=\"allowscriptaccess\" value=\"always\" />
	<param name=\"wmode\" value=\"transparent\" />
	<param name=\"allowfullscreen\" value=\"true\" />
	<param name=\"movie\" value=\"".$mosConfig_live_site."/components/com_jomtube/assets/swf/player.swf\" />
	<param name=\"flashvars\" value=\"width=".$videowidth."&height=".$videoheight."&enablejs=true".$longtail."&file=$mosConfig_live_site$video_detail->directory/cache/$video_detail->video_type/$video_detail->id.xml&image=".$display_thumb."&autostart=".$autostart."&stretching=".$vidstretching."&skin=".$skin."&$repeat&fullscreen=true\" />

 	<embed id=\"jomtubeplayer\" name=\"jomtubeplayer\" src=\"".$jwplayer."\" flashvars=\"width=".$videowidth."&height=".$videoheight."&file=$mosConfig_live_site$video_detail->directory/cache/$video_detail->video_type/$video_detail->id.xml&image=".$display_thumb."&autostart=".$autostart."&stretching=".$vidstretching."&skin=".$skin."&$repeat&fullscreen=true\" width=".$videowidth." height=".$videoheight." allowfullscreen=\"true\" allowscriptaccess=\"always\" wmode=\"transparent\" type=\"application/x-shockwave-flash\" />
	</object>";

	return $embedvideo;

    }

    if ($local<>"1" OR $unexpectederror=="1"){
		$embedvideo="<object width=\"".$videowidth."\" height=\"".$videoheight."\" data=\"http://flash.revver.com/player/1.0/player.swf?mediaId=".$vcode."\" type=\"application/x-shockwave-flash\" id=\"revvervideo".$vcode."\"><param name=\"Movie\" value=\"http://flash.revver.com/player/1.0/player.swf?mediaId=".$vcode."\"></param><param name=\"FlashVars\" value=\"allowFullScreen=true&autoStart=true&backColor=#000000&frontColor=#FFFFFF&gradColor=#000000&relatedVideos=false&creatorVideos=false&shareUrl=embedUrl&pngLogo=http://static2.revver.com/player/logo/subfightertv_logo_.png\"></param><param name=\"AllowFullScreen\" value=\"true\"></param><param name=\"AllowScriptAccess\" value=\"always\"></param><embed type=\"application/x-shockwave-flash\" src=\"http://flash.revver.com/player/1.0/player.swf?mediaId=".$vcode."\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" allowScriptAccess=\"always\" flashvars=\"allowFullScreen=true\" allowfullscreen=\"true\" width=\"".$videowidth."\" height=\"".$videoheight."\"></embed></object>";
	}
	return $embedvideo;

}

function revvergeneratevideodownloadlink($video_url){
    $mosConfig_absolute_path = $GLOBALS['mosConfig_absolute_path'];
    $mosConfig_live_site =  $GLOBALS['mosConfig_live_site'];
    require_once("$mosConfig_absolute_path/administrator/components/com_jomtube/includes/jomtube_getflv.php");
//    $flvlink = api_getflv($video_url);

    return $flvlink;
}


?>