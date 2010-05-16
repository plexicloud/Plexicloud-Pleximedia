<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

/**
 * EventList Component Categories Controller
 *
 * @package Joomla
 * @subpackage jomtube
 * @since 0.9
 */
class jomtubeControllerConfigs extends jomtubeController
{
    /**
	 * Constructor
	 *
	 * @since 0.9
	 */
    function __construct()
    {
        parent::__construct();

        // Register Extra task
        $this->registerTask( 'add'  ,		 	'edit' );
        $this->registerTask( 'apply', 			'save' );
        $this->registerTask( 'accesspublic', 	'access' );
        $this->registerTask( 'accessregistered','access' );
        $this->registerTask( 'accessspecial', 	'access' );
    }

    /**
	 * Logic to save a category
	 *
	 * @access public
	 * @return void
	 * @since 0.9
	 */
    function save()
    {
        // Check for request forgeries
        JRequest::checkToken() or die( 'Invalid Token' );

        $task		= JRequest::getVar('task');

        /*Check permission*/
        $configfile = 'components/com_jomtube/configs/configs.jomtube.php';
        @chmod($configfile, 0777);
        $permission = is_writeable($configfile);
        if (!$permission) {
            $msg = JText::_('The configuration file is unwriteable');
            $link = JRoute::_('index.php?option=com_jomtube&view=configs');
            $this->setRedirect($link, $msg);
        }

        $c = JRequest::get('post');

        $config = "<?php\n";
        $config .= "class jomtube_configs { \n\n";
        $config .= "    // Stores the only allowable instance of this class.\n";
        $config .= "    var \$instanceConfig = null;\n\n";
        $config .= "    function get_instance(){ \n";
        $config .= "        \$instanceConfig = new jomtube_configs; \n";
        $config .= "        return \$instanceConfig; \n";
        $config .= "    }\n\n";
        $config .= "    // ########################################################### \n";
        $config .= "    // ###### SYSTEM SETTINGS                                    \n ";
        $config .= "    // ########################################################### \n";
        $config .= "    var \$jtube_template = 'default'; \n";
        $config .= "    var \$jtube_skin = '" . $c["jtube_skin"] . "'; \n ";
        $config .= "    var \$jtube_language = '" . $c["jtube_language"] . "'; \n\n ";
        $config .= "    // ########################################################### \n";
        $config .= "    // ###### DISPLAY SETTINGS                                    \n ";
        $config .= "    // ########################################################### \n";
        $config .= "    /*Tabs Display Settings*/ \n";
        $config .= "    var \$tabs_margin_left = '" . $c["tabs_margin_left"] . "'; \n ";
        $config .= "    var \$disable_allvideos_tab = '" . $c["disable_allvideos_tab"] . "'; \n ";
        $config .= "    var \$disable_myvideos_tab =  '" . $c["disable_myvideos_tab"] . "'; \n";
        $config .= "    var \$disable_categories_tab = '" . $c["disable_categories_tab"] . "'; \n ";
        $config .= "    var \$disable_addvideo_tab = '" . $c["disable_addvideo_tab"] . "'; \n ";
        $config .= "    var \$disable_uploadvideo_tab = '" . $c["disable_uploadvideo_tab"] . "'; \n";
        $config .= "    var \$disable_search_form = '" . $c["disable_search_form"] . "'; \n\n";
        $config .= "    /*Video List Display Setting*/ \n";
        $config .= "    var \$videos_per_page = '" . $c["videos_per_page"] . "'; \n ";
        $config .= "    var \$columns_per_page = '" . $c["columns_per_page"] . "'; \n\n";
        $config .= "    /*Video Display Setting*/ \n";
        $config .= "    var \$video_player_width = '" . $c["video_player_width"] . "'; \n ";
        $config .= "    var \$video_player_height = '" . $c["video_player_height"] . "'; \n ";
        $config .= "    var \$aspect_constraint = '" . $c["aspect_constraint"] . "'; \n ";
        $config .= "    var \$show_embed_code = '" . $c["show_embed_code"] . "'; \n ";
        $config .= "    var \$show_donwload_link = '" . $c["show_donwload_link"] . "'; \n ";
        $config .= "    var \$allow_ratings = '" . $c["allow_ratings"] . "'; \n ";
        $config .= "    var \$allow_comment = '" . $c["allow_comment"] . "'; \n\n ";

        $config .= "    /*Custom Module Display Setting*/ \n";
        $config .= "    var \$width_jomtube_left_module = '" . $c["width_jomtube_left_module"] . "'; \n ";
        $config .= "    var \$width_jomtube_right_module = '" . $c["width_jomtube_right_module"] . "'; \n ";
        $config .= "    var \$width_jomtube_center_module = '" . $c["width_jomtube_center_module"] . "'; \n\n ";

        $config .= "    /*Category display and custom module position settings (categories page)*/ \n";
        $config .= "    var \$categories_per_page = '" . $c["categories_per_page"] . "'; \n ";
        $config .= "    var \$columns_categories_per_page = '" . $c["columns_categories_per_page"] . "'; \n ";
        $config .= "    var \$category_column_left_width = '" . $c["category_column_left_width"] . "'; \n ";
        $config .= "    var \$category_column_center_width = '" . $c["category_column_center_width"] . "'; \n ";
        $config .= "    var \$category_column_right_width = '" . $c["category_column_right_width"] . "'; \n\n ";

        $config .= "    /*Upload display and customs module position settings*/ \n";
        $config .= "    var \$upload_column_left_width = '" . $c["upload_column_left_width"] . "'; \n ";
        $config .= "    var \$upload_column_center_width = '" . $c["upload_column_center_width"] . "'; \n ";
        $config .= "    var \$upload_column_right_width = '" . $c["upload_column_right_width"] . "'; \n\n ";

        $config .= "    /*Add remote video display and custom module position settings (add video page)*/ \n";
        $config .= "    var \$addvideo_column_left_width = '" . $c["addvideo_column_left_width"] . "'; \n ";
        $config .= "    var \$addvideo_column_center_width = '" . $c["addvideo_column_center_width"] . "'; \n ";
        $config .= "    var \$addvideo_column_right_width = '" . $c["addvideo_column_right_width"] . "'; \n\n ";

        $config .= "    /*Video Player  Setting In Main Page*/ \n";
        $config .= "    var \$auto_play_on_load = '" . $c["auto_play_on_load"] . "';\n ";
        $config .= "    var \$show_player_in_mainpage = '" . $c["show_player_in_mainpage"] . "'; \n ";
        $config .= "    var \$width_player_in_mainpage = '" . $c["width_player_in_mainpage"] . "'; \n ";
        $config .= "    var \$height_player_in_mainpage = '" . $c["height_player_in_mainpage"] . "'; \n ";
        $config .= "    var \$video_play_in_mainpage = '" . $c["video_play_in_mainpage"] . "'; \n ";
        $config .= "    var \$show_videodetail_play_in_mainpage = '" . $c["show_videodetail_play_in_mainpage"] . "'; \n\n ";

        $config .= "    // ########################################################### \n";
        $config .= "    // ######ACCESS LEVEL SETTINGS                                   \n ";
        $config .= "    // ########################################################### \n\n";
        $config .= "    var \$acl_component = '" . $c["acl_component"] . "'; \n ";
        $config .= "    var \$acl_addvideo =  '" . $c["acl_addvideo"] . "'; \n";
        $config .= "    var \$acl_uploadvideo = '" . $c["acl_uploadvideo"] . "'; \n ";

        $config .= "    // ########################################################### \n";
        $config .= "    // ###### INTEGRATION SETTINGS                                  \n ";
        $config .= "    // ########################################################### \n";
        $config .= "    var \$commenting_system =  '" . $c["commenting_system"] . "'; \n";
        $config .= "    var \$community = '" . $c["community"] . "'; \n\n ";

        $config .= "    // ########################################################### \n";
        $config .= "    // ###### CONVERSION SETTINGS                                  \n ";
        $config .= "    // ########################################################### \n";
        $config .= "    /*Conversion Settings*/ \n";
        $config .= "    var \$use_ffmpeg = '" . $c["use_ffmpeg"] . "'; \n ";
        $config .= "    var \$use_php_ffmpeg = '" . $c["use_php_ffmpeg"] . "'; \n\n ";
        $config .= "    /*FFMPEG Settings*/ \n";
        $config .= "    var \$ffmpeg_path = '" . $c["ffmpeg_path"] . "'; \n ";
        $config .= "    var \$re_convert_mp4_normal =  '" . $c["re_convert_mp4_normal"] . "'; \n";
        $config .= "    var \$re_convert_flv = '" . $c["re_convert_flv"] . "'; \n\n ";

        $config .= "    /*Video Conversion Settings*/ \n";
        $config .= "    var \$convert_frame_size = '" . $c["convert_frame_size"] . "'; \n ";
        $config .= "    var \$convert_video_bitrate = '" . $c["convert_video_bitrate"] . "'; \n";
        $config .= "    var \$convert_audio_bitrate = '" . $c["convert_audio_bitrate"] . "'; \n ";
        $config .= "    var \$delete_orignial_file = '" . $c["delete_orignial_file"] . "'; \n\n";

        $config .= "    /*Thumbnail Extraction Settings*/ \n";
        $config .= "    var \$thumb_width = '" . $c["thumb_width"] . "'; \n ";
        $config .= "    var \$thumb_height = '" . $c["thumb_height"] . "'; \n ";
        $config .= "    var \$display_thumb_width = '" . $c["display_thumb_width"] . "'; \n ";
        $config .= "    var \$display_thumb_height = '" . $c["display_thumb_height"] . "'; \n\n ";

        $config .= "    /*High Definition Encoding - H264*/ \n";
        $config .= "    var \$h264_convert2mp4 = '" . $c["h264_convert2mp4"] . "'; \n ";
        $config .= "    var \$h264_re_convert2mp4 = '" . $c["h264_re_convert2mp4"] . "'; \n ";
        $config .= "    var \$h264_quality = '" . $c["h264_quality"] . "'; \n\n ";

        $config .= "    // ########################################################### \n";
        $config .= "    // ###### UPLOAD SETTINGS                                 \n ";
        $config .= "    // ########################################################### \n";
        $config .= "    var \$uploader_type = '" . $c["uploader_type"] . "'; \n ";
        $config .= "    var \$filesize = '" . $c["filesize"] . "'; \n ";
        $config .= "    var \$auto_approve = '" . $c["auto_approve"] . "'; \n ";

        $config .= "    var \$ft_mpg = '" . $c["ft_mpg"] . "'; \n ";
        $config .= "    var \$ft_mpeg = '" . $c["ft_mpeg"] . "'; \n ";
        $config .= "    var \$ft_avi = '" . $c["ft_avi"] . "'; \n ";
        $config .= "    var \$ft_divx = '" . $c["ft_divx"] . "'; \n ";
        $config .= "    var \$ft_mp4 = '" . $c["ft_mp4"] . "'; \n ";
        $config .= "    var \$ft_flv = '" . $c["ft_flv"] . "'; \n ";
        $config .= "    var \$ft_wmv = '" . $c["ft_wmv"] . "'; \n ";
        $config .= "    var \$ft_rm = '" . $c["ft_rm"] . "'; \n ";
        $config .= "    var \$ft_mov = '" . $c["ft_mov"] . "'; \n ";
        $config .= "    var \$ft_moov = '" . $c["ft_moov"] . "'; \n ";
        $config .= "    var \$ft_asf = '" . $c["ft_asf"] . "'; \n ";
        $config .= "    var \$ft_swf = '" . $c["ft_swf"] . "'; \n ";
        $config .= "    var \$ft_vob = '" . $c["ft_vob"] . "'; \n\n ";

        $config .= "    // ########################################################### \n";
        $config .= "    // ###### PLAYER SKILL SETTINGS                                              \n ";
        $config .= "    // ########################################################### \n";
        $config .= "    var \$jw_player_skin = '" . $c["jw_player_skin"] . "'; \n\n ";

        $config .= "}\n";
        $config .= "?>";

        //var_dump($config); exit();

        if ($fp = fopen("$configfile", "w")) {
            fputs($fp, $config, strlen($config));
            fclose ($fp);
        }

        $msg = JText::_('SAVED');
        $link = 'index.php?option=com_jomtube&view=configs';
        $this->setRedirect($link, $msg);
    }
}