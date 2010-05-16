<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.model');
/**
* @package		Joomla
* @subpackage	Polls
*/
class jomtubeModelvideo_display_settings  extends JModel
{
    function get_video_display_settings()
    {
        $db		=& JFactory::getDBO();
        $sql = "select * from #__jomtube_config";
        $db->setQuery( $sql );
        $rows = $db->loadObject();

        return $rows;
    }
    function do_video_display_settings()
    {
        $db		=& JFactory::getDBO();

        /* determine if this is the first save */
        $sql = "select id from #__jomtube_config";
        $db->setQuery($sql);
        $exists_vars = $db->loadObjectList();
        $exists = $exists_vars[0];
        /* Always use "registered" as the minimum */
        $min_gid = 18;

        if (JRequest::getVar('gid') > 0)
        {
            $min_gid = JRequest::getVar('gid');
        }

        /* Setup the aspect variables */
        $show_embed_code = JRequest::getVar('show_embed_code')*1;
        $auto_play_onload = JRequest::getVar('auto_play_onload')*1;
        $allow_ratings = JRequest::getVar('allow_ratings')*1;
        $video_player_height = JRequest::getVar('video_player_height')*1;
        $video_player_width = JRequest::getVar('video_player_width')*1;
        $aspect_constraint = JRequest::getVar('aspect_constraint')*1;

        $mysqlRealEscapeExists = function_exists("mysql_real_escape_string");
        $magicQuotesActive = get_magic_quotes_gpc();

        $ffmpeg_path = JRequest::getVar('ffmpeg_path');

        if ($mysqlRealEscapeExists) {
            $map_profile_url = mysql_real_escape_string(JRequest::getVar('map_profile_url'));
            $map_profile_table = mysql_real_escape_string(JRequest::getVar('map_profile_table'));
            $map_profile_id = mysql_real_escape_string(JRequest::getVar('map_profile_id'));
            $map_profile_user_id = mysql_real_escape_string(JRequest::getVar('map_profile_user_id'));
            $map_profile_avatar = mysql_real_escape_string(JRequest::getVar('map_profile_avatar'));
            $map_profile_avatar_prefix = mysql_real_escape_string(JRequest::getVar('map_profile_avatar_prefix'));
        } else {
            $map_profile_url = !$magicQuotesActive ? addslashes(JRequest::getVar('map_profile_url')) : "";
            $map_profile_table = !$magicQuotesActive ? addslashes(JRequest::getVar('map_profile_table')) : "";
            $map_profile_id = !$magicQuotesActive ? addslashes(JRequest::getVar('map_profile_id')) : "";
            $map_profile_user_id = !$magicQuotesActive ? addslashes(JRequest::getVar('map_profile_user_id')) : "";
            $map_profile_avatar = !$magicQuotesActive ? addslashes(JRequest::getVar('map_profile_avatar')) : "";
            $map_profile_avatar_prefix = !$magicQuotesActive ? addslashes(JRequest::getVar('map_profile_avatar_prefix')) : "";
        }


        switch ($aspect_constraint)
        {
            case "16x9":
                $aspect_constraint = 1;
                break;
            case "4x3":
                $aspect_constraint = 2;
                break;
            default:
                $aspect_constraint = 0;
                break;
        }

        $profileSystem = JRequest::getVar('profileSystem') == "" ? "default" : JRequest::getVar('profileSystem');
        $videoSystem = JRequest::getVar('videoSystem') == "" ? "default" : JRequest::getVar('videoSystem');
        $commentsSystem = JRequest::getVar('commentsSystem') == "" ? "default" : JRequest::getVar('commentsSystem');

        if ($exists->id == "") {
            $sql = "INSERT INTO #__jomtube_config ("
            ."min_gid, show_embeded, auto_play, has_ratings, video_player_height, "
            ."aspect_constraint, profile_system, video_system, comments_system, map_profile_url, "
            ."map_profile_table, map_profile_id, map_profile_user_id, "
            ."map_profile_avatar, map_profile_avatar_prefix, ffmpeg_path)"
            ."VALUES ("
            ."'".$min_gid."',"
            ."'".$show_embed_code."',"
            ."'".$auto_play_onload."',"
            ."'".$allow_ratings."',"
            ."'".$video_player_height."',"
            ."'".$video_player_width."',"
            ."'".$aspect_constraint."',"
            ."'".$profileSystem."',"
            ."'".$videoSystem."',"
            ."'".$commentsSystem."',"
            ."'".$map_profile_url."',"
            ."'".$map_profile_table."',"
            ."'".$map_profile_id."',"
            ."'".$map_profile_user_id."',"
            ."'".$map_profile_avatar."',"
            ."'".$map_profile_avatar_prefix."',"
            ."'".$ffmpeg_path."'"
            .")";
        } else {
            $sql = "UPDATE #__jomtube_config SET "
            ."min_gid = '".$min_gid."',"
            ."show_embeded = '".$show_embed_code."',"
            ."auto_play = '".$auto_play_onload."',"
            ."has_ratings = '".$allow_ratings."',"
            ."video_player_height = '".$video_player_height."',"
            ."video_player_width = '".$video_player_width."',"
            ."aspect_constraint = '".$aspect_constraint."',"
            ."profile_system = '".$profileSystem."',"
            ."video_system = '".$videoSystem."',"
            ."comments_system = '".$commentsSystem."',"
            ."map_profile_url = '".$map_profile_url."',"
            ."map_profile_table = '".$map_profile_table."',"
            ."map_profile_id = '".$map_profile_id."',"
            ."map_profile_user_id = '".$map_profile_user_id."',"
            ."map_profile_avatar = '".$map_profile_avatar."',"
            ."map_profile_avatar_prefix = '".$map_profile_avatar_prefix."',"
            ."ffmpeg_path = '".$ffmpeg_path."' "
            ."LIMIT 1";
        }
        $db->setQuery($sql);
        $db->query();
    }
}

?>
