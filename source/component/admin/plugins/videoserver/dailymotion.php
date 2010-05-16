<?php
//#########################################################################
// DAILYMOTION PLUGIN FOR JOMTUBE
// @ Copyright (C) 2008 Jomtube.com
// @ All rights reserved
// @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
//#########################################################################
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

ini_set('error_reporting', 'E_ALL & ~E_NOTICE');

function dailymotiongetvideodetails($vidlink, $existingcode,$categorylist, $reqtype){
    global $my;

    $mosConfig_absolute_path = JPATH_SITE;
    $mosConfig_live_site =  substr(JURI::base(), 0, strlen(JURI::base()) -1);

    if ($reqtype=="new") {

        $vidlink=jalemurldecode($vidlink);
        $smallvideocode=str_replace("http://www.dailymotion.com/","",$vidlink);
        //improved security not to call another site...
        $vidlink="http://www.dailymotion.com/".$smallvideocode;

    } else if ($reqtype=="refresh") {
        if ($vidlink==""){
            $vidlink="http://www.dailymotion.com/".$existingcode;
        }

    }

        $videoservertype="dailymotion";
// ##################################################
// ########### VIDEO LINK ID PARSE FROM HTML SOURCE
// ##################################################
	    $start_position = strpos($smallvideocode,"/video/")+6;
//		$end_position = strpos($smallvideocode, "/video/");
		$vidlink = substr($smallvideocode, $start_position); // remote_id -> FIELD name in Database
//      echo $remote_id; //TO TEST AND GET ONLY THE REMOTE ID NOTHING ELSE

// ###########################################################
// ###### SCRAPE API VIDEO PAGE FROM SNOOPY CLASS
// ###########################################################
		include("$mosConfig_absolute_path/administrator/components/com_jomtube/includes/Snoopy.class.php");
		$snoopy = new Snoopy;
		$snoopy->fetch("http://www.dailymotion.com/rss/video/".$vidlink."");
		$strAPI = $snoopy->results;
//     	echo $strAPI;

// ##################################################
// ########### remote_id PARSE FROM HTML SOURCE
// ##################################################
	    $start_position = strpos($strAPI,"<dm:id>")+7;
		$end_position = strpos($strAPI, "</dm:id>")-$start_position;
		$remote_id = substr($strAPI,$start_position,$end_position); // remote_id -> FIELD name in Database
      echo $remote_id; //TO TEST AND GET ONLY THE REMOTE ID NOTHING ELSE


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
		$start_position1 = strpos($strAPI,"<description><![CDATA[")+22;
		$start_position = strpos($strAPI,"</a><p>",$start_position1)+7;
		$end_position = strpos($strAPI, "</p><p>Author:", $start_position)-$start_position;
		$videodescription = substr($strAPI, $start_position, $end_position);
		$strlength = strlen($videodescription);
 		echo $strlength; // TEST THE LENGTH OF DESCRIPTION
 		if ($strlength == 0) {
			$videodescription = $videotitle;
		}
		$videodescription = strip_tags($videodescription); // video_desc -> FIELD name in Database
//		echo $videodescription; // TO TEST OUTPUT ONLY

// ##################################################
// ########### VIDEO KEYWORDS PARSE FROM HTML SOURCE
// ##################################################
		$start_position = strpos($strAPI,"<itunes:keywords>")+17;
		$end_position = strpos($strAPI, "</itunes:keywords>", $start_position)-$start_position;
		$videotags = substr($strAPI,$start_position,$end_position);
//		$videotags = html_entity_decode($videotags);
//		$videotags = str_replace("," , ", " , $videotags);

// ##################################################
// ########### VIDEO UPDATED TODAYS DATE
// ##################################################
    	$date_updated = date('Y-m-d'); // date_updated -> FIELD name in Database

// ##################################################
// ########### VIDEO PUBLISHED DATE FROM DAILYMOTION
// ##################################################
	    $start_position = strpos($strAPI,"<pubDate>")+9;
	    $end_position = strpos($strAPI, "</pubDate>", $start_position)-$start_position;
	    $video_published = substr($strAPI ,$start_position,$end_position);

	    $video_published = date("Y-m-d", strtotime($video_published)); // date_added -> FIELD name in Database
	    $video_published = date('Y-m-d');

// ##################################################
// ########### VIDEO DURATION PARSE FROM HTML SOURCE
// ##################################################
		$start_position = strpos($strAPI,"duration=\"")+10;
		$end_position = strpos($strAPI, "\"", $start_position)-$start_position;
		$sec = substr($strAPI, $start_position, $end_position);
    	$videoduration = sec2hms($sec); // duration		-> FIELD name in Database

// ##################################################
// ########### video_thumb -> THUMBNAIL PARSE FROM HTML SOURCE
// ##################################################
		$start_position = strpos($strAPI, "src=\"")+5;
		$end_position = strpos($strAPI, ".jpg", $start_position)-$start_position;
		$thumbnail_link = substr($strAPI, $start_position, $end_position+4);
//		echo $thumbnail_link; // TEST PATH FOR IMAGE THUMBNAIL

// ##################################################
// ########### $display_thumb -> THUMBNAIL PARSE FROM HTML SOURCE
// ##################################################
		$start_position = strpos($strAPI, "src=\"")+5;
		$end_position = strpos($strAPI, ".jpg", $start_position)-$start_position;
		$display_thumb = substr($strAPI, $start_position, $end_position+4);
//		echo $display_thumb; // TEST PATH FOR IMAGE THUMBNAIL

// ##################################################
// ########### video_url -> PARSE FROM HTML SOURCE
// ##################################################
		$start_position = strpos($strAPI, "<media:player url=\"")+19;
		$end_position = strpos($strAPI, "\"", $start_position)-$start_position;
		$video_url = substr($strAPI, $start_position, $end_position);
//		echo $video_url;  // TEST SHOW DEFAULT VIDEO ABSOLUTE PLAYER LINK

    if ($reqtype=="new") {
        $renderinputform=renderinputform($video_url,$thumbnail_link,$display_thumb,$videotitle,$videodescription,$videotags,$video_published,$date_updated,$videoduration,$remote_id,$videoservertype,$smallvideocode,$categorylist);
        return $renderinputform;
    } else if ($reqtype=="refresh") {
        return array ($video_url,$thumbnail_link,$display_thumb,$videotitle,$videodescription,$videotags,$video_published,$date_updated,$videoduration,$remote_id,$videoservertype,$smallvideocode,$categorylist);
    }

}

function dailymotionembed($video_detail, $jomtube_configs){
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
// ########### PARSE OUT VIDEO ID
// ##################################################
		$start_position = strpos($vcode, "/video/")+7;
		$remote_id = substr($vcode, $start_position);
//      echo $remote_id; //TEST THE REMOTE ID PARSE

// ###########################################################
// ###### SCRAPE API VIDEO PAGE FROM SNOOPY CLASS
// ###########################################################
		include("$mosConfig_absolute_path/administrator/components/com_jomtube/includes/Snoopy.class.php");
		$snoopy = new Snoopy;
		$snoopy->fetch("http://www.dailymotion.com/rss/video/".$remote_id."");
		$strAPI = $snoopy->results;
//     	echo $strAPI;
//      echo "http://www.dailymotion.com/rss/video/".$remote_id."";

// ##################################################
// ########### TEST FOR video/x-flv FORMAT EXIST
// ##################################################
		$start_position = strpos($strAPI, "<media:content url=\"http://www.dailymotion.com/get/");
		$start_positiontype = strpos($strAPI, "type=\"", $start_position)+6;
		$end_position = strpos($strAPI, "\"", $start_positiontype)-$start_positiontype;
		$flvmediacontent = substr($strAPI, $start_positiontype, $end_position);

// ########### GET FLVLINK
		if ($flvmediacontent=="video/x-flv") {
		$geturl = $video_detail->video_url; // RETRIEVE video_url FROM THE DATABASE
		$keepvidurl = "http://keepvid.com/?url=".$geturl."";

		$snoopy = new Snoopy;
		$snoopy->fetch($keepvidurl);
		$strAPI = $snoopy->results;

		$start_position = strpos($strAPI, "Links found on");
		$start_position = strpos($strAPI, "Report any problems to", $start_position);
		$start_position = strpos($strAPI, "<a href=\"", $start_position)+9;
		$end_position = strpos($strAPI, "\"", $start_position)-$start_position;
		$getflvlink = substr($strAPI, $start_position, $end_position);
		$flv_link = trim($getflvlink);
		$local_player = 1;
		$flv_link = urlencode($flv_link);
//		echo "url from keepvid = ".$flv_link.""; // TEST TO SEE THE RETRIEVED FLV LINK
//		$flv_link = "http://www.dailymotion.com/get/18/320x240/flv/14563400.flv?key=bd1ad15be2789300a59344a527dfbac313ac4cf.flv";

    	} else {

// ##################################################
// ########### EXTRACT VIDEO ID FOR DEFAULT EMBED PLAYER
// ##################################################
		$snoopy = new Snoopy;
		$snoopy->fetch("http://www.dailymotion.com/rss/video/".$remote_id."");
		$strAPI = $snoopy->results;
		$start_position = strpos($strAPI, "<dm:id>")+7;
		$end_position = strpos($strAPI, "</dm:id>", $start_position)-$start_position;
		$vcode = substr($strAPI, $start_position, $end_position);
		$local_player = 0;
//		echo $vcode;
		}

// ##################################################
// ########### USE LOCAL PLAYER (WILL BE CONFIG IN BACKEND)
// ##################################################
//    $local_player = 1; //$local_player = $jomtube_configs->local_player
    if ($local_player=="1"){

// #######################################################
// #### BEHAVIOUR PARAMETERS FOR JW PLAYER
// #######################################################
        if ($jomtube_configs->auto_play_on_load=="1") {
            $repeat="repeat=true";
        } else {
            $repeat="repeat=false";
        }

// ##################################################
// ########### JW PLAYER START IN JAVASECRIPT
// ##################################################
?>

	    <script type="text/javascript">
	      var flashvars =
	      {
	        'file':                                  '<?php echo $flv_link;?>',
	        'type':                                  'video',
			'skin':									 '<?php echo $mosConfig_live_site;?>/components/com_jomtube/assets/swf/skins/<?php echo $jomtube_configs->jw_player_skin ?>.swf',
			'controlbar':							 'over',
	        'stretching':                            'uniform',
	        'frontcolor':                            'ffffff',
	        'lightcolor':                            'ff6600',
	        'autostart':                             <?php echo $jomtube_configs->auto_play_on_load?>
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
// ###### DEFAULT EMBEDED PLAYER SETTINGS
// ###########################################################

    if ($local<>"1" OR $unexpectederror=="1"){

    // ##################################################
    // ########### USE EMBED VIDEO
    // ##################################################
        $embedvideo="<object width=\"".$videowidth."\" height=\"".$videoheight."\"><param name=\"movie\" value=\"http://www.dailymotion.com/swf/".$vcode."&autoPlay=1\"></param><param name=\"allowfullscreen\" value=\"true\"></param><embed src=\"http://www.dailymotion.com/swf/".$vcode."&autoPlay=1&related=1\" type=\"application/x-shockwave-flash\" width=\"".$videowidth."\" height=\"".$videoheight."\" allowfullscreen=\"true\"></embed></object>";
	}

	return $embedvideo;
}

function dailymotiongeneratevideodownloadlink($video_url){
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