<?php
//#########################################################################
// CLIPSER.COM PLUGIN FOR JOMTUBE
// @ Copyright (C) 2008 Jomtube.com
// @ All rights reserved
// @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
//#########################################################################

defined( '_JEXEC' ) or die( 'Restricted access' ); // no direct access

function clipsergetvideodetails($vidlink, $existingcode,$categorylist, $reqtype){
	global $database, $my;

$mosConfig_absolute_path = JPATH_SITE;
        $mosConfig_live_site =  substr(JURI::base(), 0, strlen(JURI::base()) -1);

	$vidlink = jalemurldecode($vidlink);

    //http://clipser.com/watch_video/1048696
    $smallvideocode = str_replace("http://www.clipser.com/watch_video/", "", $vidlink);
    $smallvideocode = str_replace("http://clipser.com/watch_video/", "", $smallvideocode);

    //improved security not to call another site...
	$vidlink = "http://clipser.com/watch_video/".$smallvideocode;

    $videoservertype = "clipser";


// ###########################################################
// ###### SCRAPE CLIPSER.COM FROM SNOOPY CLASS
// ###########################################################
		include("$mosConfig_absolute_path/administrator/components/com_jomtube/includes/Snoopy.class.php");
		$snoopy = new Snoopy;
    	$video_ID = $video_detail->remote_id;
		$snoopy->fetch("http://clipser.com/watch_video/".$smallvideocode."");
		$strhtml = $snoopy->results;

// ##################################################
// ########### VIDEO TITLE PARSE FROM HTML SOURCE
// ##################################################
		$start_position = strpos($strhtml,"<title>")+17;
		$end_position = strpos($strhtml, "</title>", $start_position)-$start_position;
		$videotitle = substr($strhtml,$start_position,$end_position);
		$videotitle = htmlentities($videotitle, ENT_NOQUOTES);
		$videotitle = ucwords (strtolower($videotitle));

// ##################################################
// ########### VIDEO DESCRIPTION PARSE FROM HTML SOURCE
// ##################################################
		$start_position = strpos($strhtml,"<meta name=\"description\" content=\"")+34;
		$end_position = strpos($strhtml, "\" />", $start_position)-$start_position;
		$videodescription = substr($strhtml,$start_position,$end_position);
		$strlength = strlen($videodescription);
  		if ($strlength == 0) {
			$videodescription = $videotitle;
		}
		$videodescription = strip_tags($videodescription); // video_desc -> FIELD name in Database

// ##################################################
// ########### VIDEO UPDATED TODAYS DATE
// ##################################################
		$date_updated = date('Y-m-d'); // date_added -> FIELD name in Database

// ##################################################
// ########### VIDEO PUBLISHED DATE FROM HTML SOURCE
// ##################################################
		$video_published = date('Y-m-d'); // date_added -> FIELD name in Database

// ##################################################
// ########### VIDEO KEYWORDS PARSE FROM HTML SOURCE
// ##################################################
		$start_position = strpos($strhtml,"<meta name=\"keywords\" content=\"")+31;
		$end_position = strpos($strhtml, "\" />", $start_position)-$start_position;
		$videotags = substr($strhtml, $start_position, $end_position);

// ##################################################
// ########### VIDEO THUMBNAIL PARSE FROM HTML SOURCE
// ##################################################
		$start_position = strpos($strhtml, "[images][0]=")+12;
		$end_position = strpos($strhtml, "\" target", $start_position)-$start_position;
		$thumbnail_link = substr($strhtml, $start_position, $end_position);

// ##################################################
// ########### VIDEO ID PARSE FROM HTML SOURCE
// ##################################################
		$start_position = strpos($strhtml, "<input name=\"url_txt\" type=\"text\" value='")+42;
		$end_position = strpos($strhtml, "'", $start_position)-$start_position;
		$video_url = substr($strhtml, $start_position, $end_position);

// ##################################################
// ########### VIDEO DURATION PARSE FROM HTML SOURCE
// ##################################################
		$start_position = strpos($strhtml,"Duration</span>: <strong>")+25;
		$end_position = strpos($strhtml, "</strong>", $start_position)-$start_position;
		$videoduration = substr($strhtml, $start_position, $end_position);

  if ($reqtype=="new") {
    $renderinputform=renderinputform($video_url,$thumbnail_link,$display_thumb,$videotitle,$videodescription,$videotags,$video_published,$date_updated,$videoduration,$remote_id,$videoservertype,$smallvideocode,$categorylist);
    return $renderinputform;
  } else if ($reqtype=="refresh") {
    return array ($picturelink, $videotitle, $itemcomment);
  }

}

function clipserembed($video_detail, $jomtube_configs){
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
        if ($jomtube_configs->video_repeat=="1") {
            $repeat="repeat=true";
        } else {
            $repeat="repeat=false";
        }

// ###########################################################
// ###### LOAD CLIPSER PAGE FROM SNOOPY CLASS
// ###########################################################
		include("$mosConfig_absolute_path/administrator/components/com_jomtube/includes/Snoopy.class.php");
		$snoopy = new Snoopy;
    	$video_ID = $video_detail->remote_id;
		$snoopy->fetch("http://www.clipser.com/pathget_fileembed.php?vid=".$video_ID."");
		$strAPI = $snoopy->results;
//		return $strAPI;
// ###########################################################
// ###### GET FLV DIRECT LINK
// ###########################################################
		$start_position = strpos($strAPI,"&file=")+6;
		$end_position = strpos($strAPI,".flv", $start_position);
		$FLVlink = substr($strAPI, $start_position, $end_position+4);
//		echo "url test= ".$FLVlink;

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

    if ($local<>"1" OR $unexpectederror=="1"){
		$embedvideo="<object width=\"".$videowidth."px\" height=\"".$videoheight."\"><param name=\"movie\" value=\"http://www.clipser.com/Play?vid=".$vcode."&autostart=true\"></param><param name=\"wmode\" value=\"transparent\"></param><embed src=\"http://www.clipser.com/Play?vid=".$vcode."&autostart=true\" type=\"application/x-shockwave-flash\" wmode=\"transparent\" width=\"".$videowidth."px\" height=\"".$videoheight."px\"></embed></object>";
    }

    return $embedvideo;

}

function clipsergeneratevideodownloadlink($video_url){
    $mosConfig_absolute_path = $GLOBALS['mosConfig_absolute_path'];
    $mosConfig_live_site =  $GLOBALS['mosConfig_live_site'];
    require_once("$mosConfig_absolute_path/administrator/components/com_jomtube/includes/jomtube_getflv.php");
    $flvlink = api_getflv($video_url);

    return $flvlink;

}

?>