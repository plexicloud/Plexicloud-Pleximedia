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
    var $jtube_skin = 'solar_sentinel_lightgray'; 
     var $jtube_language = '﻿english'; 

     // ########################################################### 
    // ###### DISPLAY SETTINGS                                    
     // ########################################################### 
    /*Tabs Display Settings*/ 
    var $tabs_margin_left = '250'; 
     var $disable_allvideos_tab = '0'; 
     var $disable_myvideos_tab =  '0'; 
    var $disable_categories_tab = '0'; 
     var $disable_addvideo_tab = '0'; 
     var $disable_uploadvideo_tab = '0'; 
    var $disable_search_form = '0'; 

    /*Video List Display Setting*/ 
    var $videos_per_page = '20'; 
     var $columns_per_page = '24.9'; 

    /*Video Display Setting*/ 
    var $video_player_width = '570'; 
     var $video_player_height = '321'; 
     var $aspect_constraint = '16x9'; 
     var $show_embed_code = '1'; 
     var $show_donwload_link = '0'; 
     var $allow_ratings = '1'; 
     var $allow_comment = '1'; 

     /*Custom Module Display Setting*/ 
    var $width_jomtube_left_module = '160'; 
     var $width_jomtube_right_module = '160'; 
     var $width_jomtube_center_module = '550'; 

     /*Category display and custom module position settings (categories page)*/ 
    var $categories_per_page = ''; 
     var $columns_categories_per_page = ''; 
     var $category_column_left_width = '160'; 
     var $category_column_center_width = '550'; 
     var $category_column_right_width = '160'; 

     /*Upload display and customs module position settings*/ 
    var $upload_column_left_width = '160'; 
     var $upload_column_center_width = '550'; 
     var $upload_column_right_width = '160'; 

     /*Add remote video display and custom module position settings (add video page)*/ 
    var $addvideo_column_left_width = '160'; 
     var $addvideo_column_center_width = '550'; 
     var $addvideo_column_right_width = '160'; 

     /*Video Player  Setting In Main Page*/ 
    var $auto_play_on_load = '1';
     var $show_player_in_mainpage = '0'; 
     var $width_player_in_mainpage = ''; 
     var $height_player_in_mainpage = ''; 
     var $video_play_in_mainpage = 'Lastest Video'; 
     var $show_videodetail_play_in_mainpage = '0'; 

     // ########################################################### 
    // ######ACCESS LEVEL SETTINGS                                   
     // ########################################################### 

    var $acl_component = '29'; 
     var $acl_addvideo =  '18'; 
    var $acl_uploadvideo = '18'; 
     // ########################################################### 
    // ###### INTEGRATION SETTINGS                                  
     // ########################################################### 
    var $commenting_system =  'JomComment'; 
    var $community = 'No'; 

     // ########################################################### 
    // ###### CONVERSION SETTINGS                                  
     // ########################################################### 
    /*Conversion Settings*/ 
    var $use_ffmpeg = '1'; 
     var $use_php_ffmpeg = '1'; 

     /*FFMPEG Settings*/ 
    var $ffmpeg_path = '/usr/local/bin/ffmpeg'; 
     var $re_convert_mp4_normal =  '0'; 
    var $re_convert_flv = '0'; 

     /*Video Conversion Settings*/ 
    var $convert_frame_size = '600x338'; 
     var $convert_video_bitrate = '600'; 
    var $convert_audio_bitrate = '64'; 
     var $delete_orignial_file = '1'; 

    /*Thumbnail Extraction Settings*/ 
    var $thumb_width = ''; 
     var $thumb_height = ''; 
     var $display_thumb_width = ''; 
     var $display_thumb_height = ''; 

     /*High Definition Encoding - H264*/ 
    var $h264_convert2mp4 = '0'; 
     var $h264_re_convert2mp4 = '0'; 
     var $h264_quality = 'default'; 

     // ########################################################### 
    // ###### UPLOAD SETTINGS                                 
     // ########################################################### 
    var $uploader_type = 'normal'; 
     var $filesize = ''; 
     var $auto_approve = '1'; 
     var $ft_mpg = ''; 
     var $ft_mpeg = ''; 
     var $ft_avi = ''; 
     var $ft_divx = ''; 
     var $ft_mp4 = ''; 
     var $ft_flv = ''; 
     var $ft_wmv = ''; 
     var $ft_rm = ''; 
     var $ft_mov = ''; 
     var $ft_moov = ''; 
     var $ft_asf = ''; 
     var $ft_swf = ''; 
     var $ft_vob = ''; 

     // ########################################################### 
    // ###### PLAYER SKILL SETTINGS                                              
     // ########################################################### 
    var $jw_player_skin = 'bekle'; 

 }
?>