<?php
//#########################################################################
// VIMEO.COM PLUGIN FOR JOMTUBE
// @ Copyright (C) 2008 Jomtube.com
// @ All rights reserved
// @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
//#########################################################################

defined( '_JEXEC' ) or die( 'Restricted access' ); // no direct access

ini_set('error_reporting', 'E_ALL & ~E_NOTICE');

function vimeogetvideodetails($vidlink, $existingcode,$categorylist, $reqtype){
	global $database ,$my;

        $mosConfig_absolute_path = JPATH_SITE;
        $mosConfig_live_site =  substr(JURI::base(), 0, strlen(JURI::base()) -1);

if ($reqtype=="new") {

		$vidlink=jalemurldecode($vidlink);
		$smallvideocode=str_replace("http://www.vimeo.com/","",$vidlink);
		$smallvideocode=str_replace("http://vimeo.com/","",$smallvideocode);
		//improved security not to call another site...
		$vidlink="http://www.vimeo.com/".$smallvideocode;

} else if ($reqtype=="refresh") {


}
		$videoservertype="vimeo";
// ###########################################################
// ###### SCRAPE VIMEO.COM FROM SNOOPY CLASS
// ###########################################################
		include("$mosConfig_absolute_path/administrator/components/com_jomtube/includes/Snoopy.class.php");
		$snoopy = new Snoopy;
    	$video_ID = $video_detail->remote_id;
		$snoopy->fetch("http://vimeo.com/api/clip/".$smallvideocode.".xml");
		$strXML = $snoopy->results;

// ##################################################
// ########### video_url -> PARSE FROM HTML SOURCE
// ##################################################
		$start_position = strpos($strXML, "<url>")+5;
		$end_position = strpos($strXML, "</url>", $start_position)-$start_position;
		$video_url = substr($strXML, $start_position, $end_position);
//		echo $video_url;  // TEST SHOW DEFAULT VIDEO ABSOLUTE PLAYER LINK

// ##################################################
// ########### remote_id -> PARSE FROM HTML SOURCE
// ##################################################
		$start_position = strpos($strXML, "<clip_id>")+9;
		$end_position = strpos($strXML, "</clip_id>", $start_position)-$start_position;
		$remote_id = substr($strXML, $start_position, $end_position);
//		echo $remote_id;  // TEST SHOW DEFAULT VIDEO ABSOLUTE PLAYER LINK

// ##################################################
// ########### VIDEO TITLE PARSE FROM HTML SOURCE
// ##################################################
		$start_position = strpos($strXML, "<title>")+7;
		$end_position = strpos($strXML, "</title>")-$start_position;
		$videotitle = substr($strXML,$start_position,$end_position);
		$videotitle = htmlentities($videotitle, ENT_NOQUOTES);
		$videotitle = ucwords (strtolower($videotitle));

// ##################################################
// ########### VIDEO DESCRIPTION PARSE FROM HTML SOURCE
// ##################################################
		$start_position = strpos($strXML,"<caption>")+9;
		$end_position = strpos($strXML, "</caption>", $start_position)-$start_position;
		$videodescription = substr($strXML, $start_position, $end_position);
		$videodescription = str_replace("<![CDATA[" , "" , $videodescription);
		$videodescription = str_replace("]]>" , "" , $videodescription);
		$strlength = strlen($videodescription);
  		if ($strlength == 0) {
			$videodescription = $videotitle;
		}
		$videodescription = strip_tags($videodescription); // video_desc -> FIELD name in Database

// ##################################################
// ########### VIDEO THUMBNAIL PARSE FROM HTML SOURCE
// ##################################################
		$start_position = strpos($strXML, "<thumbnail_large>")+17;
		$end_position = strpos($strXML, "</thumbnail_large>")-$start_position;
		$thumbnail_link = substr($strXML,$start_position,$end_position);

// ##################################################
// ########### VIDEO BIG THUMBNAIL PARSE FROM HTML SOURCE
// ##################################################
		$start_position = strpos($strXML, "<thumbnail_large>")+17;
		$end_position = strpos($strXML, "</thumbnail_large>")-$start_position;
		$display_thumb = substr($strXML,$start_position,$end_position);

// ##################################################
// ########### VIDEO UPDATED TODAYS DATE
// ##################################################
		$date_updated = date('Y-m-d'); // date_added -> FIELD name in Database

// ##################################################
// ########### VIDEO PUBLISHED DATE FROM HTML SOURCE
// ##################################################
		$start_position = strpos($strXML,"<upload_date>")+13;
		$end_position = strpos($strXML, "</upload_date>", $start_position)-$start_position;
		$video_published = substr($strXML,$start_position,$end_position);
		$video_published = date("Y-m-d", strtotime($video_published)); // date_added -> FIELD name in Database
		$video_published = date('Y-m-d');

// ##################################################
// ########### VIDEO DURATION PARSE FROM HTML SOURCE
// ##################################################
		$start_position = strpos($strXML, "<duration>")+10;
		$end_position = strpos($strXML, "</duration>")-$start_position;
		$sec = substr($strXML,$start_position,$end_position);
    	$videoduration = sec2hms($sec); // duration		-> FIELD name in Database

// ##################################################
// ########### VIDEO KEYWORDS PARSE FROM HTML SOURCE
// ##################################################
		$start_position = strpos($strXML, "<tags>")+6;
		$end_position = strpos($strXML, "</tags>")-$start_position;
		$videotags = substr($strXML,$start_position,$end_position);

    if ($reqtype=="new") {
        $renderinputform=renderinputform($video_url,$thumbnail_link,$display_thumb,$videotitle,$videodescription,$videotags,$video_published,$date_updated,$videoduration,$remote_id,$videoservertype,$smallvideocode,$categorylist);
        return $renderinputform;
    } else if ($reqtype=="refresh") {
        return array ($vidlink,$picturelink,$videotitle,$itemcomment,$categorylist,$videoservertype,$smallvideocode,$videotags,$videodescription,$videoduration,$video_published,$thumbnail_link,$remote_id,$date_updated,$display_thumb);
    }

}

function vimeoembed($video_detail, $jomtube_configs){
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

// #######################################################
// #### BEHAVIOUR PARAMETERS FOR JW PLAYER
// #######################################################
        if ($jomtube_configs->auto_play_on_load=="1") {
            $repeat="repeat=true";
        } else {
            $repeat="repeat=false";
        }
// ###########################################################
// ###### LOAD API FROM SNOOPY CLASS
// ###########################################################
		$geturl = $video_detail->video_url; // RETRIEVE video_url FROM THE DATABASE
		$keepvidurl = "http://keepvid.com/?url=".$geturl."";

		include("$mosConfig_absolute_path/administrator/components/com_jomtube/includes/Snoopy.class.php");
		$snoopy = new Snoopy;
		$snoopy->fetch($keepvidurl);
		$strAPI = $snoopy->results;

// ###########################################################
// ###### GET FLV DIRECT LINK
// ###########################################################
		$start_position = strpos($strAPI, "Links found on");
		$start_position = strpos($strAPI, "Report any problems to", $start_position);
		$start_position = strpos($strAPI, "<a href=\"", $start_position)+9;
		$end_position = strpos($strAPI, "\"", $start_position)-$start_position;
		$getflvlink = substr($strAPI, $start_position, $end_position);
		$flv_link = trim($getflvlink);
//		echo "url from keepvid = ".$flv_link.""; // TEST TO SEE THE RETRIEVED FLV LINK
//		$flv_link = "http://www.vimeo.com/moogaloop/play/clip:2168002//67e8457815693fae47a79cdeec0a05dd/1236787200/video.flv?q=";

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
        'stretching':                            'fill',
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

    if ($local<>"1" OR $unexpectederror=="1"){
		$embedvideo="
		<object width=\"".$videowidth."\" height=\"".$videoheight."\" ><param name=\"allowfullscreen\" value=\"true\" /><param name=\"allowscriptaccess\" value=\"always\" /><param name=\"movie\" value=\"http://vimeo.com/moogaloop.swf?clip_id=".$vcode."&amp;server=subfighter.tv&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=ff6600&amp;fullscreen=1\" /><embed src=\"http://vimeo.com/moogaloop.swf?clip_id=".$vcode."&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=ff6600&amp;fullscreen=1\" type=\"application/x-shockwave-flash\" allowfullscreen=\"true\" allowscriptaccess=\"always\" width=\"".$videowidth."\" height=\"".$videoheight."\" ></embed></object>
		";
	}

return $embedvideo;

}

function vimeogeneratevideodownloadlink($video_url){
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
