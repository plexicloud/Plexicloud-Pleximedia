<?php
    class jomtube_configs {

        // Stores the only allowable instance of this class.
        var $instanceConfig = null;

        function get_instance(){
            $instanceConfig = new jomtube_configs;
            return $instanceConfig;
        }

        // ###########################################################
        // ###### SYSTEM SETTINGS
        // ###########################################################
        var $jtube_template = 'default';
        var $jtube_skin = 'solar_sentinel_blue';
        var $jtube_language = '﻿english';

        // ###########################################################
        // ###### DISPLAY SETTINGS
        // ###########################################################
        /*TABS DISPLAY SETTINGS*/
        var $tabs_margin_left = '250';
        var $disable_allvideos_tab = '0';
        var $disable_myvideos_tab = '0';
        var $disable_categories_tab = '0';
        var $disable_addvideo_tab = '0';
        var $disable_uploadvideo_tab = '0';
        var $disable_search_form = '0';

        /*VIDEO LIST DISPLAY SETTING*/
        var $videos_per_page = '20';
        var $columns_per_page = '24.9';

        /*VIDEO DISPLAY SETTING*/
        var $video_player_width = '570';
        var $video_player_height = '321';
        var $aspect_constraint = '16x9';
        var $show_embed_code = '1';
        var $show_donwload_link = '0';
        var $allow_ratings = '1';
        var $allow_comment = '1';
        
        /*VIDEO LIST MODULE DISPLAY SETTINGS*/
        var $width_jomtube_left_module = '160';
        var $width_jomtube_center_module = '550'; 
        var $width_jomtube_right_module = '160'; 
        
        /*CATEGORY DISPLAY AND CUSTOM MODULE POSITION SETTINGS (CATEGORIES PAGE)*/
        var $categories_per_page = '';
        var $columns_categories_per_page = '';
        var $category_column_left_width = '160';
        var $category_column_center_width = '550';
        var $category_column_right_width = '160';
        
        /*ADD REMOTE VIDEO DISPLAY AND CUSTOM MODULE POSITION SETTINGS (ADD VIDEO PAGE)*/
        var $addvideo_column_left_width = '160';
        var $addvideo_column_center_width = '550';
        var $addvideo_column_right_width = '160';
        
        /*UPLOAD DISPLAY AND CUSTOMS MODULE POSITION SETTINGS*/
        var $upload_column_left_width = '160';
        var $upload_column_center_width = '550';
        var $upload_column_right_width = '160';
        
        /*Video Player  Setting In Main Page*/ 
        var $show_player_in_mainpage = '0'; 
        var $width_player_in_mainpage = '570'; 
        var $height_player_in_mainpage = '338'; 
        var $video_play_in_mainpage = 'Lastest Video';
        var $show_videodetail_play_in_mainpage = '0'; 
        
        // ###########################################################
        // ###### ACCESS LEVEL SETTINGS
        // ###########################################################
        var $acl_component = '29';
        var $acl_addvideo =  '29';
        var $acl_uploadvideo = '29';
        
     	// ########################################################### 
    	// ###### INTEGRATION SETTINGS                                  
     	// ########################################################### 
    	var $commenting_system =  'No'; 
    	var $community = 'No'; 

     	// ########################################################### 
    	// ###### CONVERSION SETTINGS                                  
     	// ########################################################### 
    	/*CONVERSION SETTINGS*/ 
    	var $use_ffmpeg = '1'; 
     	var $use_php_ffmpeg = '1'; 

     	/*FFMPEG SETTINGS*/ 
    	var $ffmpeg_path = '/usr/local/bin/ffmpeg'; 
     	var $re_convert_mp4_normal =  '0'; 
    	var $re_convert_flv = '0';

        /*VIDEO CONVERSION SETTINGS*/
        var $convert_frame_size = '600x338';
        var $convert_video_bitrate = '600';
        var $convert_audio_bitrate = '64';
        var $delete_orignial_file = '1';

        /*THUMBNAIL EXTRACTION SETTINGS*/
        var $thumb_width = '120';
        var $thumb_height = '90';
        var $display_thumb_width = '600';
        var $display_thumb_height = '338';

		/*HIGH DEFINITION ENCODING - H264*/ 
		var $h264_convert2mp4 = '0'; 
		var $h264_re_convert2mp4 = '0'; 
		var $h264_quality = 'default'; 

        // ###########################################################
        // ###### UPLOAD SETTINGS
        // ###########################################################
        var $uploader_type = 'flashupload';
        var $filesize = '10';        
        
        var $ft_mpg = 'checked';
        var $ft_mpeg = 'checked';
        var $ft_avi = 'checked';
        var $ft_divx = 'checked';
        var $ft_mp4 = 'checked';
        var $ft_flv = 'checked';
        var $ft_wmv = 'checked';
        var $ft_rm = 'checked';
        var $ft_mov = 'checked';
        var $ft_moov = 'checked';
        var $ft_asf = 'checked';
        var $ft_swf = 'checked';
        var $ft_vob = 'checked';
        
        // ###########################################################
        // ###### PLAYER SKILL SETTINGS
        // ###########################################################
        var $jw_player_skin = 'bekle';
    }
?>