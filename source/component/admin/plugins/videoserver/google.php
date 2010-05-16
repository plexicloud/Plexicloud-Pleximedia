<?php
//#########################################################################
// GOOGLE.COM PLUGIN FOR JOMTUBE
// @ Copyright (C) 2008 Jomtube.com
// @ All rights reserved
// @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
//#########################################################################

defined( '_JEXEC' ) or die( 'Restricted access' ); // no direct access

ini_set('error_reporting', 'E_ALL & ~E_NOTICE');

function googlegetvideodetails($vidlink, $existingcode,$categorylist, $reqtype){
    global $my;

    $mosConfig_absolute_path = JPATH_SITE;
    $mosConfig_live_site =  substr(JURI::base(), 0, strlen(JURI::base()) -1);

    if ($reqtype=="new") {

        $vidlink=jalemurldecode($vidlink);

        $googledomain = array();
        $googledomain[0]=".google.com/";
        $googledomain[1]=".google.ae/";
        $googledomain[2]=".google.com.af/";
        $googledomain[3]=".google.com.ag/";
        $googledomain[4]=".google.com.ai/";
        $googledomain[5]=".google.am/";
        $googledomain[6]=".google.com.ar/";
        $googledomain[7]=".google.as/";
        $googledomain[8]=".google.at/";
        $googledomain[9]=".google.com.au/";
        $googledomain[10]=".google.az/";
        $googledomain[11]=".google.ba/";
        $googledomain[12]=".google.com.bd/";
        $googledomain[13]=".google.be/";
        $googledomain[14]=".google.bg/";
        $googledomain[15]=".google.com.bh/";
        $googledomain[16]=".google.bi/";
        $googledomain[17]=".google.com.bn/";
        $googledomain[18]=".google.com.bo/";
        $googledomain[19]=".google.com.br/";
        $googledomain[20]=".google.bs/";
        $googledomain[21]=".google.co.bw/";
        $googledomain[22]=".google.com.bz/";
        $googledomain[23]=".google.ca/";
        $googledomain[24]=".google.cd/";
        $googledomain[25]=".google.cg/";
        $googledomain[26]=".google.ch/";
        $googledomain[27]=".google.ci/";
        $googledomain[28]=".google.co.ck/";
        $googledomain[29]=".google.cl/";
        $googledomain[30]=".google.cn/";
        $googledomain[31]=".google.com.co/";
        $googledomain[32]=".google.co.cr/";
        $googledomain[33]=".google.com.cu/";
        $googledomain[34]=".google.cz/";
        $googledomain[35]=".google.de/";
        $googledomain[36]=".google.dj/";
        $googledomain[37]=".google.dk/";
        $googledomain[38]=".google.dm/";
        $googledomain[39]=".google.com.do/";
        $googledomain[40]=".google.com.ec/";
        $googledomain[41]=".google.ee/";
        $googledomain[42]=".google.com.eg/";
        $googledomain[43]=".google.es/";
        $googledomain[44]=".google.com.et/";
        $googledomain[45]=".google.fi/";
        $googledomain[46]=".google.com.fj/";
        $googledomain[47]=".google.fm/";
        $googledomain[48]=".google.fr/";
        $googledomain[49]=".google.ge/";
        $googledomain[50]=".google.gg/";
        $googledomain[51]=".google.com.gi/";
        $googledomain[52]=".google.gl/";
        $googledomain[53]=".google.gm/";
        $googledomain[54]=".google.gp/";
        $googledomain[55]=".google.gr/";
        $googledomain[56]=".google.com.gt/";
        $googledomain[57]=".google.gy/";
        $googledomain[58]=".google.com.hk/";
        $googledomain[59]=".google.hn/";
        $googledomain[60]=".google.hr/";
        $googledomain[61]=".google.ht/";
        $googledomain[62]=".google.hu/";
        $googledomain[63]=".google.co.id/";
        $googledomain[64]=".google.ie/";
        $googledomain[65]=".google.co.il/";
        $googledomain[66]=".google.im/";
        $googledomain[67]=".google.co.in/";
        $googledomain[68]=".google.is/";
        $googledomain[69]=".google.it/";
        $googledomain[70]=".google.je/";
        $googledomain[71]=".google.com.jm/";
        $googledomain[72]=".google.jo/";
        $googledomain[73]=".google.co.jp/";
        $googledomain[74]=".google.co.ke/";
        $googledomain[75]=".google.com.kh/";
        $googledomain[76]=".google.ki/";
        $googledomain[77]=".google.kg/";
        $googledomain[78]=".google.co.kr/";
        $googledomain[79]=".google.kz/";
        $googledomain[80]=".google.la/";
        $googledomain[81]=".google.li/";
        $googledomain[82]=".google.lk/";
        $googledomain[83]=".google.co.ls/";
        $googledomain[84]=".google.lt/";
        $googledomain[85]=".google.lu/";
        $googledomain[86]=".google.lv/";
        $googledomain[87]=".google.com.ly/";
        $googledomain[88]=".google.co.ma/";
        $googledomain[89]=".google.md/";
        $googledomain[90]=".google.mn/";
        $googledomain[91]=".google.ms/";
        $googledomain[92]=".google.com.mt/";
        $googledomain[93]=".google.mu/";
        $googledomain[94]=".google.mv/";
        $googledomain[95]=".google.mw/";
        $googledomain[96]=".google.com.mx/";
        $googledomain[97]=".google.com.my/";
        $googledomain[98]=".google.com.na/";
        $googledomain[99]=".google.com.nf/";
        $googledomain[100]=".google.com.ng/";
        $googledomain[101]=".google.com.ni/";
        $googledomain[102]=".google.nl/";
        $googledomain[103]=".google.no/";
        $googledomain[104]=".google.com.np/";
        $googledomain[105]=".google.nr/";
        $googledomain[106]=".google.nu/";
        $googledomain[107]=".google.co.nz/";
        $googledomain[108]=".google.com.om/";
        $googledomain[109]=".google.com.pa/";
        $googledomain[110]=".google.com.pe/";
        $googledomain[111]=".google.com.ph/";
        $googledomain[112]=".google.com.pk/";
        $googledomain[113]=".google.pl/";
        $googledomain[114]=".google.pn/";
        $googledomain[115]=".google.com.pr/";
        $googledomain[116]=".google.pt/";
        $googledomain[117]=".google.com.py/";
        $googledomain[118]=".google.com.qa/";
        $googledomain[119]=".google.ro/";
        $googledomain[120]=".google.ru/";
        $googledomain[121]=".google.rw/";
        $googledomain[122]=".google.com.sa/";
        $googledomain[123]=".google.com.sb/";
        $googledomain[124]=".google.sc/";
        $googledomain[125]=".google.se/";
        $googledomain[126]=".google.com.sg/";
        $googledomain[127]=".google.sh/";
        $googledomain[128]=".google.si/";
        $googledomain[129]=".google.sk/";
        $googledomain[130]=".google.sn/";
        $googledomain[131]=".google.sm/";
        $googledomain[132]=".google.st/";
        $googledomain[133]=".google.com.sv/";
        $googledomain[134]=".google.co.th/";
        $googledomain[135]=".google.com.tj/";
        $googledomain[136]=".google.tk/";
        $googledomain[137]=".google.tm/";
        $googledomain[138]=".google.to/";
        $googledomain[139]=".google.tp/";
        $googledomain[140]=".google.com.tr/";
        $googledomain[141]=".google.tt/";
        $googledomain[142]=".google.com.tw/";
        $googledomain[143]=".google.com.ua/";
        $googledomain[144]=".google.co.ug/";
        $googledomain[145]=".google.co.uk/";
        $googledomain[146]=".google.com.uy/";
        $googledomain[147]=".google.co.uz/";
        $googledomain[148]=".google.com.vc/";
        $googledomain[149]=".google.co.ve/";
        $googledomain[150]=".google.vg/";
        $googledomain[151]=".google.co.vi/";
        $googledomain[152]=".google.com.vn/";
        $googledomain[153]=".google.vu/";
        $googledomain[154]=".google.ws/";
        $googledomain[155]=".google.co.za/";
        $googledomain[156]=".google.co.zm/";
        $googledomain[157]=".google.co.zw/";

        for ($i=1; $i<=157;$i++){
            $vidlink=str_replace($googledomain[$i],".google.com/",$vidlink);
        }

        $smallvideocode=str_replace("http://video.google.com/videoplay?docid=","",$vidlink);
        $smallvideocode=str_replace("/","",$smallvideocode);
        $pos = strpos($smallvideocode, "&");
        if ($pos>0){
            $smallvideocode=substr($smallvideocode,0,$pos);
        }

        //improved security not to call another site...
        $vidhtmllink="http://video.google.com/videoplay?docid=".$smallvideocode;

    } else if ($reqtype=="refresh") {
        if ($vidlink==""){
            $vidhtmllink="http://video.google.com/videoplay?docid=".$existingcode;
        }

    }

        $videoservertype="google";
// ###########################################################
// ###### SCRAPE PAGE FROM SNOOPY CLASS
// ###########################################################
		include("$mosConfig_absolute_path/administrator/components/com_jomtube/includes/Snoopy.class.php");
		$snoopy = new Snoopy;
    	$geturl = "http://video.google.com/videoplay?docid=".$smallvideocode."";
		$snoopy->fetch($geturl);
		$strAPI = $snoopy->results;

// ##################################################
// ########### VIDEO ID PARSE FROM HTML SOURCE
// ##################################################
   	 	$remote_id = $smallvideocode; // remote_id -> FIELD name in Database
//		echo $remote_id; // TEST OUT THE remote_id

// ##################################################
// ########### VIDEO ID PARSE FROM HTML SOURCE
// ##################################################
		$video_url = "http://video.google.com/videoplay?docid=".$remote_id.""; // video_url -> FIELD name in Database
//		echo $video_url; // TEST OUT THE Video URL

// ##################################################
// ########### VIDEO TITLE PARSE FROM HTML SOURCE
// ##################################################
	    $start_position = strpos($strAPI,"<title>")+7;
	    $end_position = strpos($strAPI, "</title>", $start_position)-$start_position;
	    $videotitle = substr($strAPI,$start_position,$end_position);
		$videotitle = ucwords (strtolower($videotitle));

// ##################################################
// ########### VIDEO DESCRIPTION PARSE FROM HTML SOURCE
// ##################################################
	    $start_position = strpos($strAPI,"<span id=\"long-desc\">");
	    $end_position = strpos($strAPI, "</span>", $start_position)-$start_position;
	    $videodescription=substr($strAPI,$start_position,$end_position);
		$videodescription = trim(strip_tags($videodescription)); // video_desc -> FIELD name in Databaseabase
			$strlength = strlen($videodescription);
	  		if ($strlength == 0) {
				$videodescription = $videotitle;
			}
//		echo $videodescription; // TEST OUT DESCRIPTION DISPLAY

// ##################################################
// ########### VIDEO UPDATED TODAYS DATE
// ##################################################
    	$date_updated = date('Y-m-d'); // date_added -> FIELD name in Database

// ##################################################
// ########### VIDEO PUBLISHED DATE
// ##################################################
	    $start_position = strpos($strAPI,"<span id=\"duration-and-date\">");
	    $end_position = strpos($strAPI, "</span><br>", $start_position)-$start_position;
	    $video_published = substr($strAPI,$start_position,$end_position);
		$video_published = strip_tags($video_published);
	    $durationtime = explode ("-", $video_published);
		$video_published = date("Y-m-d", strtotime($durationtime[2])); // date_added -> FIELD name in Database
//		print_r (explode ("-", $video_published));	// TEST CREATES AND DISPLAYS AN ARRAY
        $video_published = date('Y-m-d');

// ##################################################
// ########### VIDEO DURATION PARSE FROM HTML SOURCE
// ##################################################
	    $videoduration = trim($durationtime[1]); // duration	-> FIELD name in Database Convert to 00:00:00 i.e. hrs:min:sec

// ##################################################
// ########### VIDEO THUMBNAIL PARSE FROM HTML SOURCE
// ##################################################
	    $start_position1 = strpos($strAPI, "thumbnailUrl")+12;
	    $start_position = strpos($strAPI , "http://", $start_position1);
	    $end_position = strpos($strAPI, "'", $start_position)-$start_position;
	    $thumbnail_link = substr($strAPI,$start_position,$end_position);
		$thumbnail_link = urldecode($thumbnail_link);

    // ##################################################
    // ########### VIDEO KEYWORDS PARSE FROM HTML SOURCE
    // ##################################################
    //		$start_position = strpos($str,"</div><a class=\"tag\"")+22;
    //		$end_position = strpos($str, "</div>", $start_position)-$start_position;
    //		$videotags = substr($str, $start_position, $end_position);
    //		$videotags = strip_tags($videotags);
    //		$videotags = eregi_replace("(.*)>", '', $videotags); // tags -> FIELD name in Database


    if ($reqtype=="new") {
        $renderinputform=renderinputform($video_url,$thumbnail_link,$display_thumb,$videotitle,$videodescription,$videotags,$video_published,$date_updated,$videoduration,$remote_id,$videoservertype,$smallvideocode,$categorylist);
        return $renderinputform;
    } else if ($reqtype=="refresh") {
        return array ($picturelink, $videotitle, $itemcomment);
    }

}

function googleembed($video_detail, $jomtube_configs){
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
// ###### LOAD CLIPSER PAGE FROM SNOOPY CLASS
// ###########################################################
//		include("$mosConfig_absolute_path/administrator/components/com_jomtube/includes/Snoopy.class.php");
//		$snoopy = new Snoopy;
	   	$video_ID = $video_detail->remote_id;
//		$snoopy->fetch("http://keepvid.com/?url=http://video.google.com/videoplay?docid=".$video_ID."");
//		$strAPI = $snoopy->results;
//		return $strAPI;
// ###########################################################
// ###### GET FLV DIRECT LINK
// ###########################################################
//		$start_position = strpos($strAPI, "Links found on");
//		$start_position = strpos($strAPI, "Report any problems to", $start_position);
//		$start_position = strpos($strAPI, "<a href=\"", $start_position)+9;
//		$end_position = strpos($strAPI, "\"", $start_position)-$start_position;
//		$getflvlink = substr($strAPI, $start_position, $end_position);
//		$end_position = strpos($getflvlink, "\");
//		$getflvlink = substr($getflvlink, 0, $end_position);
//		$FLVlink = trim($getflvlink);
//		echo "url from keepvid = ".$FLVlink."";
		$FLVlink = "".$mosConfig_live_site."/administrator/components/com_jomtube/plugins/videoserver/jwscript/Google_URI.php?docid=".$video_ID."&type=flv";
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

// ##################################################
// ########### USE EMBED VIDEO
// ##################################################
	$embedvideo = "	<embed id=\"VideoPlayback\" src=\"http://video.google.com/googleplayer.swf?docid=".$vcode."&autoplay=true&hl=en&fs=true\" style=\"width:".$videowidth."px;height:".$videoheight."px\" allowFullScreen=\"true\" allowScriptAccess=\"always\" type=\"application/x-shockwave-flash\"> </embed>";
	}

	return $embedvideo;
}


function googlegeneratevideodownloadlink($video_url){
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