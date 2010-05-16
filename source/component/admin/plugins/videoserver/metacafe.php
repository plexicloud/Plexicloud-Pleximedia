<?php
//#########################################################################
// METACAFE PLUGIN FOR JOMTUBE
// @ Copyright (C) 2008 Jomtube.com
// @ All rights reserved
// @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
//#########################################################################

defined( '_JEXEC' ) or die( 'Restricted access' );// no direct access

ini_set('error_reporting', 'E_ALL & ~E_NOTICE');

function metacafegetvideodetails($vidlink, $existingcode,$categorylist, $reqtype){
	global $database ,$my;

        $mosConfig_absolute_path = JPATH_SITE;
        $mosConfig_live_site =  substr(JURI::base(), 0, strlen(JURI::base()) -1);

if ($reqtype=="new") {

		$vidlink=jalemurldecode($vidlink);

		$smallvideocode=str_replace("http://www.metacafe.com/watch/","",$vidlink);

		//improved security not to call another site...
		$vidlink="http://www.metacafe.com/watch/".$smallvideocode;

} else if ($reqtype=="refresh") {
	if ($vidlink==""){
		$vidlink="http://www.metacafe.com/watch/".$existingcode;//trytoguess
	}

}

		$checkvideocode=substr($smallvideocode,-1);
		if ($checkvideocode=="/") {
		$smallvideocode=substr($smallvideocode,0,-1).".swf";
		}

		$videoservertype="metacafe";
// ##################################################
// ########### REMOTE ID PARSE FROM HTML SOURCE
// ##################################################
		$end_position = strpos($smallvideocode, "/");
		$remote_id = substr($smallvideocode, 0, $end_position); // remote_id -> FIELD name in Database
//      echo $remote_id; //TO TEST AND GET ONLY THE REMOTE ID NOTHING ELSE


// ###########################################################
// ###### SCRAPE PAGE VARIABLES USING SNOOPY CLASS
// ###########################################################
		include("$mosConfig_absolute_path/administrator/components/com_jomtube/includes/Snoopy.class.php");
		$snoopy = new Snoopy;
  		$video_ID = $video_detail->remote_id;
		$snoopy->fetch("http://www.metacafe.com/api/item/".$remote_id."");
		$strAPI = $snoopy->results;
//		echo $strAPI; //TO TEST AND RETURN API PAGE TO SCRAPE

// ##################################################
// ########### video_url -> PARSE FROM HTML SOURCE
// ##################################################
		$start_position = strpos($strAPI, "<media:player url=\"")+19;
		$end_position = strpos($strAPI, "\"", $start_position)-$start_position;
		$video_url = substr($strAPI, $start_position, $end_position);

// ##################################################
// ########### VIDEO TITLE PARSE FROM HTML SOURCE
// ##################################################
		$start_position = strpos($strAPI,"<media:title>")+13;
		$end_position = strpos($strAPI, "</media:title>")-$start_position;
		$videotitle = substr($strAPI,$start_position,$end_position);
		$videotitle = htmlentities($videotitle, ENT_NOQUOTES);
		$videotitle = ucwords (strtolower($videotitle));

// ##################################################
// ########### VIDEO DESCRIPTION PARSE FROM HTML SOURCE
// ##################################################
		$start_position = strpos($strAPI,"<media:description>")+19;
		$end_position = strpos($strAPI, "</media:description>", $start_position)-$start_position;
		$videodescription = substr($strAPI, $start_position, $end_position);
		$strlength = strlen($videodescription);
  		if ($strlength == 0) {
			$videodescription = $videotitle;
		}
		$videodescription = html_entity_decode($videodescription); // video_desc -> FIELD name in Database

// ##################################################
// ########### VIDEO UPDATED TODAYS DATE
// ##################################################
		$date_updated = date('Y-m-d'); // date_added -> FIELD name in Database

// ##################################################
// ########### VIDEO PUBLISHED DATE FROM HTML SOURCE
// ##################################################
		$start_position = strpos($strAPI,"<pubDate>")+9;
		$end_position = strpos($strAPI, "</pubDate>", $start_position)-$start_position;
		$video_published = substr($strAPI,$start_position,$end_position);
		$video_published = date("Y-m-d", strtotime($video_published)); // date_added -> FIELD name in Database
		$video_published = date('Y-m-d'); // THIS BYPASS ORGINAL PUBLISH DATE TO CURRENT DATE ADDED

// ##################################################
// ########### VIDEO KEYWORDS PARSE FROM HTML SOURCE
// ##################################################
		$start_position = strpos($strAPI,"<media:keywords>")+16;
		$end_position = strpos($strAPI, "</media:keywords>", $start_position)-$start_position;
		$videotags = substr($strAPI,$start_position,$end_position);
		$videotags = html_entity_decode($videotags);
		$videotags = str_replace("," , ", " , $videotags);

// ##################################################
// ########### video_thumb -> THUMBNAIL PARSE FROM HTML SOURCE
// ##################################################
		$start_position = strpos($strAPI, "<img src=\"")+10;
		$end_position = strpos($strAPI, "\"", $start_position)-$start_position;
		$thumbnail_link = substr($strAPI, $start_position, $end_position);

// ##################################################
// ########### thumb_display -> DISPLAY PARSE FROM HTML SOURCE
// ##################################################
		$start_position = strpos($strAPI, "<img src=\"")+10;
		$end_position = strpos($strAPI, "\"", $start_position)-$start_position;
		$display_thumb = substr($strAPI, $start_position, $end_position);

// ##################################################
// ########### VIDEO DURATION PARSE FROM HTML SOURCE
// ##################################################
		$start_position = strpos($strAPI,"duration=\"")+10;
		$end_position = strpos($strAPI, "\"", $start_position)-$start_position;
		$sec = substr($strAPI, $start_position, $end_position);
    	$videoduration = sec2hms($sec); // duration		-> FIELD name in Database

  if ($reqtype=="new") {
    $renderinputform=renderinputform($video_url,$thumbnail_link,$display_thumb,$videotitle,$videodescription,$videotags,$video_published,$date_updated,$videoduration,$remote_id,$videoservertype,$smallvideocode,$categorylist);
    return $renderinputform;
  } else if ($reqtype=="refresh") {
    return array ($picturelink, $videotitle, $itemcomment);
  }

}

function metacafeembed($video_detail, $jomtube_configs){
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
        if ($jomtube_configs->auto_play_on_load=="1") {
            $repeat="repeat=true";
        } else {
            $repeat="repeat=false";
        }


	$jwplayer = "".$mosConfig_live_site."/components/com_jomtube/assets/swf/player.swf";
	$skin = "".$mosConfig_live_site."/components/com_jomtube/assets/swf/skins/$jomtube_configs->jw_player_skin.swf";
	$autostart = $repeat; // 'true' or 'false' -> starts video when page loads
	$vidstretching = "fill"; // (none) no stretching, (exactfit), (uniform) stretch with black borders, (fill) uniform, but completely fill the display
	$display_thumb = $video_detail->display_thumb;
	$logo = "".$mosConfig_live_site."/administrator/components/com_jomtube/plugins/videoserver/metacafe.gif";

	$embedvideo = "
	<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" name=\"jomtube_player\"  width=\"".$videowidth."\" height=\"".$videoheight."\" >
	<param name=\"allowscriptaccess\" value=\"always\" />
	<param name=\"wmode\" value=\"transparent\" />
	<param name=\"allowfullscreen\" value=\"true\" />
	<param name=\"movie\" value=\"".$mosConfig_live_site."/components/com_jomtube/assets/swf/player.swf\" />
	<param name=\"flashvars\" value=\"width=".$videowidth."&height=".$videoheight."&enablejs=true".$longtail."&file=$mosConfig_live_site$video_detail->directory/cache/$video_detail->video_type/$video_detail->id.xml&image=".$display_thumb."&autostart=".$autostart."&stretching=".$vidstretching."&skin=".$skin."&$repeat&fullscreen=true\" />

 	<embed id=\"jomtubeplayer\" name=\"jomtubeplayer\" src=\"".$jwplayer."\" flashvars=\"width=".$videowidth."&height=".$videoheight."&file=$mosConfig_live_site$video_detail->directory/cache/$video_detail->video_type/$video_detail->id.xml&image=".$display_thumb."&autostart=".$autostart."&stretching=".$vidstretching."&skin=".$skin."&logo=".$logo."&$repeat&fullscreen=true\" width=".$videowidth." height=".$videoheight." allowfullscreen=\"true\" allowscriptaccess=\"always\" wmode=\"transparent\" type=\"application/x-shockwave-flash\" />
	</object>";

	return $embedvideo;

    }

    if ($local<>"1" OR $unexpectederror=="1"){
		$embedvideo="
		<embed flashVars=\"playerVars=showStats=no|autoPlay=yes|\" src=\"http://www.metacafe.com/fplayer/".$vcode."\" width=\"".$videowidth."\" height=\"".$videoheight."\" wmode=\"transparent\" allowFullScreen=\"true\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\"></embed>
		";
	}

return $embedvideo;

}

function metacafegeneratevideodownloadlink($video_url){
    $mosConfig_absolute_path = $GLOBALS['mosConfig_absolute_path'];
    $mosConfig_live_site =  $GLOBALS['mosConfig_live_site'];
    require_once("$mosConfig_absolute_path/administrator/components/com_jomtube/includes/jomtube_getflv.php");
    $flvlink = api_getflv($video_url);

    return $flvlink;
}


// ##########################################
// ###### CONVERT SECONDS TO HH:MM:SS
// ##########################################
function sec2hms ($sec, $padHours = false){
    // holds formatted string
    $hms = "";

    // there are 3600 seconds in an hour, so if we
    // divide total seconds by 3600 and throw away
    // the remainder, we've got the number of hours
    $hours = intval(intval($sec) / 3600);

    // add to $hms, with a leading 0 if asked for
    $hms .= ($padHours)
    ? str_pad($hours, 2, "0", STR_PAD_LEFT). ':'
    : $hours. ':';

    // dividing the total seconds by 60 will give us
    // the number of minutes, but we're interested in
    // minutes past the hour: to get that, we need to
    // divide by 60 again and keep the remainder
    $minutes = intval(($sec / 60) % 60);

    // then add to $hms (with a leading 0 if needed)
    $hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT). ':';

    // seconds are simple - just divide the total
    // seconds by 60 and keep the remainder
    $seconds = intval($sec % 60);

    // add to $hms, again with a leading 0 if needed
    $hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);

    // done!
    return $hms;
}

?>