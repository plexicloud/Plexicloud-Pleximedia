<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

function getuseravatar($addedby){
    global $mosConfig_absolute_path, $mosConfig_live_site, $Itemid, $database;

    $avat="";
    $avatar="";

    $link=sefRelToAbs("index.php?option=com_jomtube&task=uservideoslist&Itemid=$Itemid&from=0&userid=$addedby");

    //cb avatar
    if (file_exists($mosConfig_absolute_path."/components/com_comprofiler/comprofiler.php")){
        $database->setQuery("SELECT * FROM #__comprofiler WHERE user_id='$addedby'");
        $avt = $database->loadObjectList();
        foreach ($avt as $avt) {
            $avat=$avt->avatar;
        }
    }

    $avatarlink=$mosConfig_live_site."/images/comprofiler/".$avat;
    $avatar="<a href=\"".$link."\"><img src=\"".$avatarlink."\" border=\"0\" width=\"48\"></a>";
    if ($avat=="") $avatar="";


    if ($avatar==""){
        $avatar="<a href=\"".$link."\"><img src=\"".$mosConfig_live_site."/components/com_jomtube/themes/".$theme."/images/noavatar.jpg\" border=\"0\" width=\"48\"></a>";
    }

    return $avatar;

}

function jalem_file_get_contents($url) {

    if (function_exists('curl_init')){
        $ch=curl_init();
        $timeout=30; // set to zero for no timeout
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
        $file_contents=curl_exec($ch);
        curl_close($ch);
    } else {
        $file_contents=file_get_contents($url);
    }
    return $file_contents;
}


//Function:jagetusername ->start *****************
function jagetusersname($id) {
    global $database, $my, $mosConfig_absolute_path;
    require($mosConfig_absolute_path."/administrator/components/com_jomtube/jomtube_config.php");

    $username=_UNKNOWN;

    $database->setQuery("SELECT name, username FROM #__users WHERE id='$id'");
    $usname = $database->loadObjectList();
    foreach ($usname as $usname)
    {
        $uname=$usname->name;
        $username=$usname->username;
    }

    if ($screenname=="username"){
        return $username;
    } else {
        return $uname;
    }



}
//Function:jagetusername->end   *******************


function getjomtubecomments($id){
    global $mosConfig_absolute_path, $Itemid;
    require($mosConfig_absolute_path.'/components/com_jomtube/includes/jomtube_permissions.php');
    require($mosConfig_absolute_path."/administrator/components/com_jomtube/jomtube_config.php");


    $comments="";

    if ($showcomments=="yes"){

        if ($commentingsystem=="jcomments") {
            $comments = $mosConfig_absolute_path . '/components/com_jcomments/jcomments.php';
            if (file_exists( $comments )) {
                require_once( $comments );
                $comments = JComments::showComments( $id, 'com_jomtube', '' );
            }
        } else if ($commentingsystem=="jomcomment"){
            $jomcommentfile=$mosConfig_absolute_path. "/mambots/content/jom_comment_bot.php";
            if (file_exists($jomcommentfile)){
                include_once($jomcommentfile);
                $comments=jomcomment($id, "com_jomtube");
                $comments.="<div id=\"jomtubefbdiscussinput\"></div><div id=\"toggle\"></div>";
            }
        } else if ($commentingsystem=="fireboard") {

            if ($showcomments=="yes"){
                $comments=jomtubefbforum($id);
            }

            if ($comments=="") {
                $comments="<div id=\"jomtubefbdiscussinput\"></div><div id=\"toggle\"></div>";
            }
        } else if ($commentingsystem=="nocomment"){

            $comments="<div id=\"jomtubefbdiscussinput\"></div><div id=\"toggle\"></div>";


        }

    }

    return $comments;

}


function generaterandom ($length){
    $randomstart ="";
    $possible = "0123456789abcdefghijklmnopqrstuwxyz";
    $i = 0;
    while ($i < $length) {
        $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
        if (!strstr($randomstart, $char)) {
            $randomstart .= $char;
            $i++;
        }
    }
    return $randomstart;
}


//strange but some servers don't accept % sign, so don't use php urlencode
function jalemurlencode($url){
    $url=str_replace("&","*am*",$url);
    $url=str_replace("=","*eq*",$url);
    $url = str_replace( "/", "25ja25", $url );
    $url = str_replace( ":", "1twodots1", $url );
    $url = str_replace(	".", "p_p_p", $url );
    $url = str_replace(	" ", "h_h_h", $url );
    $url = str_replace(	"-", "m_m_m", $url );

    return $url;
}

function jalemurldecode($url){
    $url=str_replace("*am*","&",$url);
    $url=str_replace("*eq*","=",$url);
    $url = str_replace( "25ja25", "/",$url );
    $url = str_replace( "1twodots1", ":",$url );
    $url = str_replace( "p_p_p", ".",$url );
    $url = str_replace( "h_h_h", " ",$url );
    $url = str_replace( "m_m_m", "-",$url );
    return $url;
}



function generatereadmorelink($string, $cutcharcount ,$spanid){

    $stringcharcount=strlen($string);
    if ($cutcharcount=="") $cutcharcount=300;

    if ($stringcharcount>$cutcharcount){

        $itisok="no";
        while ($itisok=="no"){
            $chartest=substr($string, $cutcharcount, 1);
            if ($chartest==" "){
                $itisok="yes";
            } else {
                $cutcharcount=$cutcharcount-1;
            }
        }

        $newstring1=substr($string, 0, $cutcharcount);
        $morestring=" <span id=\"more".$spanid."\" class=\"readmore\" onclick=showmoredetails('".$spanid."')> ["._READMORE."] </span>";
        $lessstring=" <span id=\"less".$spanid."\" class=\"readmore hiddenspan\" onclick=showlessdetails('".$spanid."')> ["._LESS."] </span>";
        $newstring2="<span id=\"".$spanid."\" class=\"hiddenspan\">".substr($string, $cutcharcount, 10000)."</span>";

        return $newstring1. $newstring2. $morestring.  $lessstring;


    } else {
        return $string;
    }


}

function renderinputform($video_url,$thumbnail_link,$display_thumb,$videotitle,$videodescription,$videotags,$video_published,$date_updated,$videoduration,$remote_id,$videoservertype,$smallvideocode,$categorylist){
    $remotevideo = array();
    $remotevideo['video_url'] = $video_url;
    $remotevideo['thumbnail_link'] = $thumbnail_link;
    $remotevideo['display_thumb'] = $display_thumb;
    $remotevideo['title'] = $videotitle;
    $remotevideo['videodescription'] = $videodescription;
    $remotevideo['videotags'] = $videotags;
    $remotevideo['video_published'] = $video_published;
    $remotevideo['date_updated'] = $date_updated;
    $remotevideo['videoduration'] = $videoduration;
    $remotevideo['remoteid'] = $remote_id;
    $remotevideo['videoservertype'] = $videoservertype;
    $remotevideo['smallvideocode'] = $smallvideocode;
	return $remotevideo;
}

function genXmlFile($row, $video_link) {
    $mosConfig_absolute_path = JPATH_BASE;
    $mosConfig_live_site = substr(JURI::base(), 0, strlen($mosConfig_live_site) -1);
    $cache_dir = $mosConfig_absolute_path . $row->directory . '/cache/' . $row->video_type;
    $cache_file =  $cache_dir  . '/' . $row->id . '.xml';
    //echo $cache_dir; exit();
    //check existed cache file
    //if (!file_exists($cache_file)) {
        if (!is_dir($cache_dir))
        {
            $oldumask=umask(0);
            mkdir ($cache_dir,0755, true);
            umask($oldumask);
        }
        $fh=fopen($cache_file,'w');
        $cache_content = "
<rss version=\"2.0\" xmlns:media=\"http://search.yahoo.com/mrss/\">
	<channel>
		<title>Example media RSS playlist</title>

		<item>
			<title>FLV Video</title>
			<link>http://www.bigbuckbunny.org/</link>
			<description>Big Buck Bunny is a short animated film by the Blender Institute, part of the Blender Foundation.</description>
			<media:credit role=\"author\">the Peach Open Movie Project</media:credit>
			<media:content url=\"".$video_link."\" />
		</item>

	</channel>
</rss>
";

         fwrite($fh,$cache_content);
         fclose($fh);
    //} else {
    //    return ;
    //}
}

?>