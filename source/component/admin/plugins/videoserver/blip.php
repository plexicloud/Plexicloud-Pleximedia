<?php
//#########################################################################
// BLIP.TV PLUGIN FOR JOMTUBE
// @ Copyright (C) 2008 Jomtube.com
// @ All rights reserved
// @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
//#########################################################################

defined( '_JEXEC' ) or die( 'Restricted access' ); // no direct access

ini_set('error_reporting', 'E_ALL & ~E_NOTICE');

function blipgetvideodetails($vidlink,$existingcode,$categorylist,$reqtype){
	global $database, $my;
        $mosConfig_absolute_path = JPATH_SITE;
        $mosConfig_live_site =  substr(JURI::base(), 0, strlen(JURI::base()) -1);

if ($reqtype=="new") {
	$vidlink=jalemurldecode($vidlink);
	$smallvideocode=str_replace("http://www.blip.tv/file/","",$vidlink);
	$smallvideocode=str_replace("http://blip.tv/file/","",$smallvideocode);
	//improved security not to call another site...
	$vidlink="http://blip.tv/file/".$smallvideocode;

} else if ($reqtype=="refresh") {
	if ($vidlink==""){
		$vidlink="http://blip.tv/file/".$existingcode;//trytoguess
	}

}

// ##################################################
// STRING CREATED FROM HTML SOURCE CODE
		$videoservertype="blip";
		$strhtml=jalem_file_get_contents($vidlink); // CONVERT HTML SOURCE TO STRING

		$feedlink = "http://blip.tv/file/".$smallvideocode."?skin=rss";
		$feedlink = str_replace("/?","?",$feedlink);
	 	$strXML = jalem_file_get_contents($feedlink); // CONVERT API FEED SOURCE TO STRING

// ##################################################
// ########### VIDEO TITLE PARSE FROM HTML SOURCE
// ##################################################
		$start_position = strpos($strXML,"<media:title>")+13;
		$end_position = strpos($strXML, "</media:title>", $start_position)-$start_position;
		$videotitle = substr($strXML,$start_position,$end_position);
		$videotitle = htmlentities($videotitle, ENT_NOQUOTES);
		$videotitle = ucwords (strtolower($videotitle));

// ##################################################
// ########### VIDEO DESCRIPTION PARSE FROM HTML SOURCE
// ##################################################
		$start_position = strpos($strhtml,"player.setDescription(\"")+23;
		$end_position = strpos($strhtml, "\");", $start_position)-$start_position;
		$videodescription = substr($strhtml,$start_position,$end_position);
		$strlength = strlen($videodescription);
  		if ($strlength == 0) {
			$videodescription = $videotitle;
		}

//		$videodescription = str_replace("\"","",$videodescription);
//		$videodescription = htmlentities($videodescription, ENT_QUOTES);

// ##################################################
// ########### VIDEO UPDATED TODAYS DATE
// ##################################################
		$date_updated = date('Y-m-d'); // date_added -> FIELD name in Database

// ##################################################
// ########### VIDEO PUBLISHED DATE FROM HTML SOURCE
// ##################################################
		$start_position = strpos($strXML,"<pubDate>")+9;
		$end_position = strpos($strXML, "</pubDate>", $start_position)-$start_position;
		$video_published = substr($strXML,$start_position,$end_position);
		$video_published = date("Y-m-d", strtotime($video_published)); // date_added -> FIELD name in Database
		$video_published = date('Y-m-d');

// ##################################################
// ########### VIDEO KEYWORDS PARSE FROM HTML SOURCE
// ##################################################
		$start_position = strpos($strXML,"<media:keywords>")+16;
		$end_position = strpos($strXML, "</media:keywords>", $start_position)-$start_position;
		$videotags = substr($strXML, $start_position, $end_position);
		$videotag_exisits = strlen ($videotags);
		If ($videotag_exisits == 0) {$videotags = $videotitle;}
		$videotags = str_replace(",",", ",$videotags);

// ##################################################
// ########### VIDEO THUMBNAIL PARSE FROM HTML SOURCE
// ##################################################
		$start_position = strpos($strXML, "<blip:smallThumbnail>")+21;
		$end_position = strpos($strXML, "</blip:smallThumbnail>", $start_position)-$start_position;
		$thumbnail_link = substr($strXML, $start_position, $end_position);

// ##################################################
// ########### DISLAY THUMBNAIL LARGE PARSE FROM HTML SOURCE
// ##################################################
		$start_position = strpos($strXML, "<media:thumbnail url=\"")+22;
		$end_position = strpos($strXML, ".jpg", $start_position)-$start_position;
		$display_thumb = substr($strXML, $start_position, $end_position+4);

// ##################################################
// ########### PLAYER LINK PARSE FROM HTML SOURCE
// ##################################################
		$start_position = strpos($strXML, "<media:player url=\"")+19;
		$end_position = strpos($strXML, "\"", $start_position)-$start_position;
		$video_url = substr($strXML, $start_position, $end_position);

// ##################################################
// ########### VIDEO ID PARSE FROM HTML SOURCE
// ##################################################
		$start_position = strpos($strhtml, "<option value=\"/file/")+21;
		$end_position = strpos($strhtml, "?filename", $start_position)-$start_position;
		$smallvideocode = substr($strhtml, $start_position, $end_position); // remote_id -> FIELD name in Database


// ##################################################
// ########### VIDEO DURATION PARSE FROM HTML SOURCE
// ##################################################
		$start_position = strpos($strXML,"<blip:runtime>")+14;
		$end_position = strpos($strXML, "</blip:runtime>", $start_position)-$start_position;
		$sec = substr($strXML,$start_position,$end_position);
    	$videoduration = sec2hms($sec); // Convert to 00:00:00 i.e. hrs:min:sec

		if ($reqtype=="new") {
		$renderinputform=renderinputform($video_url,$thumbnail_link,$display_thumb,$videotitle,$videodescription,$videotags,$video_published,$date_updated,$videoduration,$remote_id,$videoservertype,$smallvideocode,$categorylist);
		return $renderinputform;
		} else if ($reqtype=="refresh") {
		return array ($picturelink, $videotitle, $itemcomment);
		}

}


function blipembed($video_detail, $jomtube_configs){
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
    $local_player = 1; //$local_player = $jomtube_configs->local_player
    if ($local_player=="1"){

// #######################################################
// #### BEHAVIOUR PARAMETERS FOR JW PLAYER
// #######################################################
        if ($jomtube_configs->video_repeat=="1") {
            $repeat="repeat=true";
        } else {
            $repeat="repeat=false";
        }

// ###########################################################
// ###### LOAD BLIP.TV API FROM SNOOPY CLASS
// ###########################################################
		include("$mosConfig_absolute_path/administrator/components/com_jomtube/includes/Snoopy.class.php");
		$snoopy = new Snoopy;
    	$video_ID = $video_detail->remote_id;
		$snoopy->fetch("http://blip.tv/file/".$video_ID."?skin=api");
		$strAPI = $snoopy->results;

// ###########################################################
// ###### GET FLV DIRECT LINK
// ###########################################################
		$start_position = strpos($strAPI,"<link type=\"video/x-flv\" href=\"")+31;
		$end_position = strpos($strAPI,"\" />", $start_position)-$start_position;
		$FLVlink = substr($strAPI, $start_position, $end_position);

?>

    <script type="text/javascript">
      var flashvars =
      {
        'file':                                  '<?php echo $FLVlink;?>',
        'type':                                  'video',
		'skin':									 '<?php echo $mosConfig_live_site;?>/components/com_jomtube/assets/swf/skins/<?php echo $jomtube_configs->jw_player_skin ?>.swf',
		'controlbar':							 'over',
        'stretching':                            'fill',
        'frontcolor':                            'ffffff',
        'lightcolor':                            'ff6600',
        'autostart':                            <?php echo $jomtube_configs->auto_play_on_load?>
      };

      var params =
      {
        'allowfullscreen':                       'true',
        'allowscriptaccess':                     'always'
      };

      var attributes =
      {
        'name':                                  'JomPlayerId1',
        'id':                                    'JomPlayerId1'
      };

      swfobject.embedSWF('<?php echo $mosConfig_live_site;?>/components/com_jomtube/assets/swf/player.swf', 'JomTubePlayerId1', '<?php echo $videowidth;?>', '<?php echo $videoheight;?>', '9.0.124', false, flashvars, params, attributes);
    </script>

<?php

	$jwplayer_embed = "<div id=\"JomPlayerContainer\" class=\"JomPlayerContainer\">
	      				<a id=\"JomTubePlayerId1\" class=\"player1\" href=\"http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash\">Get the Adobe Flash Player to see this video.</a>
	    			  </div>
					  ";

	return $jwplayer_embed;
    }

// ###########################################################
// ###### BLIP.TV DEFAULT EMBEDED PLAYER SETTINGS
// ###########################################################

    if ($local<>"1" OR $unexpectederror=="1"){

// ###########################################################
// ###### LOAD BLIP.TV API FROM SNOOPY CLASS
// ###########################################################
		include("$mosConfig_absolute_path/administrator/components/com_jomtube/includes/Snoopy.class.php");
		$snoopy = new Snoopy;
    	$video_ID = $video_detail->remote_id;
		$snoopy->fetch("http://blip.tv/file/".$video_ID."?skin=api");
		$strAPI = $snoopy->results;

// ###########################################################
// ###### GET FLV DIRECT LINK
// ###########################################################
		$start_position = strpos($strAPI,"<link type=\"video/x-flv\" href=\"")+31;
		$end_position = strpos($strAPI,"\" />", $start_position)-$start_position;
		$FLVlink = substr($strAPI, $start_position, $end_position);

// ###########################################################
// ###### EXTERNAL PLAYER EMBED SOURCE CODE FROM PROVIDER
// ###########################################################
		$embedvideo="
		<embed width=\"".$videowidth."\" height=\"".$videoheight."\" allowfullscreen=\"true\" allowscriptaccess=\"always\" type=\"application/x-shockwave-flash\" src=\"http://blip.tv/scripts/flash/showplayer.swf?showplayerpath=/scripts/flash/showplayer.swf&feedurl=http%3A//subfightercom.blip.tv/rss/flash&brandname=Subfighter.tv&brandlink=http%3A//www.subfighter.tv&enablejs=true&allowm4v=true&preferredRole=Web&tabType2=guide&tabType1=guide&tabType3=guide&file=http%3A//subfightercom.blip.tv/rss/flash&showsharebutton=false&tabTitle1=Episodes&tabUrl1=http%3A//subfightercom.blip.tv/rss/flash&tabTitle2=Popular&tabUrl2=http%3A//subfightercom.blip.tv/rss/flash/%3Fsort%3Dpopular&tabTitle3=Episodes&tabUrl3=http%3A//subfightercom.blip.tv/rss/flash/%3Fsort%3D%7Edate&showguidebutton=false&file=".$FLVlink."&autostart=true\"/>
		";
	}
	return $embedvideo;
}

function blipgeneratevideodownloadlink($video_url){
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