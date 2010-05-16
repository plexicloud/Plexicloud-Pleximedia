<?php
//#########################################################################
// YOUTUBE PLUGIN FOR JOMTUBE
// @ Copyright (C) 2008 Jomtube.com
// @ All rights reserved
// @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
//#########################################################################

defined( '_JEXEC' ) or die( 'Restricted access' ); // no direct access

ini_set('error_reporting', 'E_ALL & ~E_NOTICE');

function youtubegetvideodetails($vidlink, $existingcode,$categorylist, $reqtype){
    global $my, $downloadcachingtimeout;

    $mosConfig_absolute_path = JPATH_SITE;
    $mosConfig_live_site =  substr(JURI::base(), 0, strlen(JURI::base()) -1);

    if ($reqtype=="new") {
        $vidlink=jalemurldecode($vidlink);
        $videoservertype="youtube";

        $youtubeurlmatch = "(.*)v="; //youtube urlmatch (v= must be in it)
        $smallvideocode  = eregi_replace($youtubeurlmatch, '', $vidlink ); //eregi replace vidlink
        $smallvideocode=substr($smallvideocode,0,11);
    } else if ($reqtype=="refresh") {
        $smallvideocode=$existingcode;
    }

    // set video data feed URL
    $feedURL = "http://gdata.youtube.com/feeds/api/videos/".$smallvideocode."";

    // read feed into SimpleXML object
    $entry = simplexml_load_file($feedURL);

    // Call Function To Parse Video
    $video = parseVideoEntry($entry);

    $videotitle = $video->title; 				// video_title 	-> FIELD name in Database
	$videotitle = htmlentities($videotitle, ENT_NOQUOTES);
	$videotitle = ucwords (strtolower($videotitle));

    $videodescription =	$video->description;	// video_desc 	-> FIELD name in Database
	$strlength = strlen($videodescription);
	if ($strlength == 0) {
		$videodescription = $videotitle;
	}
	$videodescription = strip_tags($videodescription);

    $videotags = $video->keywords;				// tags			-> FIELD name in Database
    $sec = $video->length;						// Convert to 00:00:00 i.e. hrs:min:sec
    $videoduration = sec2hms($sec); 			// duration		-> FIELD name in Database
    $viddate = array();
    $viddate = date_parse($video->published); 										// Convert Date 0000-00-00
    $video_published = $viddate[year]."-".$viddate[month]."-".$viddate[day]; 		// date_added 	-> FIELD name in Database
    $vidupdate = date_parse($video->updated); 										// Convert Date 0000-00-00
    $date_updated = $vidupdate[year]."-".$vidupdate[month]."-".$vidupdate[day];		// date_updated 	-> FIELD name in Database
    $video_url = $video->videourl;				// video_url	-> FIELD name in Database
    $remote_id = $smallvideocode;				// remote_id	-> FIELD name in Database
    $thumbnail_link = $video->thumbnailURL;		// video_thumb 	-> FIELD name in Database
    $display_thumb = $video->displayURL;		// display_thumb-> FIELD name in Database
    $video_published = date('Y-m-d');

    if ($reqtype=="new") {
        $renderinputform=renderinputform($video_url,$thumbnail_link,$display_thumb,$videotitle,$videodescription,$videotags,$video_published,$date_updated,$videoduration,$remote_id,$videoservertype,$smallvideocode,$categorylist);
        return $renderinputform;
    } else if ($reqtype=="refresh") {
        return array ($picturelink, $videotitle, $itemcomment,$videotags,$videodescription,$videoduration,$video_published,$thumbnail_link);
    }

}

function youtubeembed($video_detail, $jomtube_configs){
    $mosConfig_absolute_path = JPATH_SITE;
    $mosConfig_live_site =  substr(JURI::base(), 0, strlen(JURI::base()) -1);
    $vcode = jalemurldecode($video_detail->remote_id);

// ##################################################
// ########### USE VIDEO WIDTH, HEIGHT CONFIG FROM BACKEND
// ##################################################
    if ($jomtube_configs->video_player_height>0 AND $jomtube_configs->video_player_width>0){
        $videowidth = $jomtube_configs->video_player_width;
        $videoheight = $jomtube_configs->video_player_height;
    }

// ##################################################
// ########### USE LOCAL PLAYER (WILL BE CONFIG IN BACKEND)
// ##################################################
    $local_player = 1; //$local_player = $jomtube_configs->local_player
    if ($local_player=="1"){


//	$FLVlink = "".$mosConfig_live_site."/administrator/components/com_jomtube/plugins/videoserver/jwscript/Google_URI.php?docid=".$video_ID."&type=flv";
//	$FLVlink = "".$mosConfig_live_site."/administrator/components/com_jomtube/plugins/videoserver/jwscript/YouTube_Multi-Format.php?v=".$vcode."&fmt=6";
// echo $FLVlink;


?>

    <script type="text/javascript">
      var flashvars =
      {
        'file':                                  encodeURIComponent('<?php echo $mosConfig_live_site;?>/administrator/components/com_jomtube/plugins/videoserver/jwscript/YouTube_Multi-Format.php?v=<?php echo $vcode;?>'),
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


    if ($pos404>0) $unexpectederror="1";

    if ($local<>"1" OR $unexpectederror=="1"){
       $embedvideo="<object width=\"".$videowidth."px\" height=\"".$videoheight."\"><param name=\"movie\" value=\"http://www.youtube.com/v/".$vcode."&autoplay=1&rel=0\"></param><param name=\"wmode\" value=\"transparent\"></param><embed src=\"http://www.youtube.com/v/".$vcode."&rel=0&autoplay=1\" type=\"application/x-shockwave-flash\" wmode=\"transparent\" width=\"".$videowidth."px\" height=\"".$videoheight."px\"></embed></object>";
    }
    return $embedvideo;
}


// ##########################################
// ###### FUNCTION TO PARSE A VIDEO <ENTRY>
// ##########################################
function parseVideoEntry($entry) {
    $obj= new stdClass;

    // get nodes in media: namespace for media information
    $media = $entry->children('http://search.yahoo.com/mrss/');
    $obj->title = $media->group->title;
    $obj->description = $media->group->description;
    $obj->keywords = $media->group->keywords;

    // get video player URL
    $attrs = $media->group->player->attributes();
    $obj->videourl = $attrs['url'];

    // get video thumbnail
    $attrs = $media->group->thumbnail[1]->attributes();
    $obj->thumbnailURL = $attrs['url'];

    // get display thumbnail
    $attrs = $media->group->thumbnail[3]->attributes();
    $obj->displayURL = $attrs['url'];

    // get <yt:duration> node for video length
    $yt = $media->children('http://gdata.youtube.com/schemas/2007');
    $attrs = $yt->duration->attributes();
    $obj->length = $attrs['seconds'];

    // get <published> node for publish date
    $published = $entry->published;
    $attrs = $published->attributes();
    $obj->published = $entry->published;

    // get <updated> node for update date
    $updated = $entry->updated;
    $attrs = $updated->attributes();
    $obj->updated = $entry->updated;

    // return object to caller
    return $obj;
}

function youtubegeneratevideodownloadlink($video_url){
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