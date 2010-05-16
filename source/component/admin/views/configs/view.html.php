<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * View class for the jomtube Video screen
 *
 * @package Joomla
 * @subpackage EventList
 * @since 0.9
 */

class jomtubeViewConfigs extends JView {

    function display($tpl = null)
    {
        global $mainframe;

        //Load pane behavior
        jimport('joomla.html.pane');
        $pane   	= & JPane::getInstance('tabs');

        //initialise variables
        $editor 	= & JFactory::getEditor();
        $document	= & JFactory::getDocument();
        $user 		= & JFactory::getUser();

        //get vars
        $cid 		= JRequest::getVar( 'cid' );

        //create the toolbar
        if ( $cid ) {
            JToolBarHelper::title( JText::_( 'CONFIGURATON' ), 'jomtube-videos' );
        } else {
            JToolBarHelper::title( JText::_( 'CONFIGURATION' ), 'jomtube-videos' );
        }
        //JToolBarHelper::apply();
        //JToolBarHelper::spacer();
        JToolBarHelper::save();
        JToolBarHelper::spacer();
        JToolBarHelper::cancel();

        $tmplpath = dirname(__FILE__).DS.'tmpl';
        $this->assignRef('tmplpath'			, $tmplpath);

        $this->assignRef('my', $my =& JFactory::getUser());
        $this->assignRef('pane'			, $pane);

        // ###########################################################
        // ###### SHOW ALL SETTINGS
        // ###########################################################
        $c = jomtube_configs::get_instance();
        $yesno[] = JHTML::_('select.option', '0', 'No');
        $yesno[] = JHTML::_('select.option', '1', 'Yes');
        $this->assignRef('c', $c);
        $this->assignRef('yesno', $yesno);

        /*System Settings*/
        //read .ini file to get dropdown style
        $styles_file = JPATH_SITE . DS . DS . 'components' . DS . 'com_jomtube' . DS . 'assets' . DS . 'styles' . DS . 'styles.ini';
        /*if (file_exists($styles_file)) {
            $styles_contents = file_get_contents($styles_file);
            $styles = explode("\n", $styles_contents);
            foreach ($styles as $style) {
                $style = explode("-", $style);
                $jtube_skin[] = JHTML::_('select.option', trim($style[0]), trim($style[1]));
            }
        } else {
            $jtube_skin[] = JHTML::_('select.option', 'no', 'No Style');
        }*/
        //$jtube_skin = $this->getOptionFromFile($styles_file, 'no');
        $jtube_skin = $this->getTemplatesOptions('no');

        $languages_file = JPATH_SITE . DS . DS . 'components' . DS . 'com_jomtube' . DS . 'languages' . DS . 'languages.ini';
        $jtube_language = $this->getOptionFromFile($languages_file, 'english');

        /*Disply Settings*/
        $this->assignRef('jtube_skin', $jtube_skin);
        $this->assignRef('jtube_language', $jtube_language);
        /*Vdieo Player In Main Page Settings*/
        $video_play_in_mainpage[] = JHTML::_('select.option', 'Lastest Video', 'Lastest Video');
        $video_play_in_mainpage[] = JHTML::_('select.option', 'Most Viewed Video', 'Most Viewed Video');
        $video_play_in_mainpage[] = JHTML::_('select.option', 'Highest Rated Video', 'Highest Rated Video');
        $video_play_in_mainpage[] = JHTML::_('select.option', 'Featured Video', 'Featured Video');
        $this->assignRef('video_play_in_mainpage', $video_play_in_mainpage);

        /*Integratin Settings*/
        $commenting_integration[] = JHTML::_('select.option', 'No', 'No');
        $commenting_integration[] = JHTML::_('select.option', 'JComment', 'JComment');
        $commenting_integration[] = JHTML::_('select.option', 'JomComment', 'JomComment');
        $this->assignRef('commenting_integration', $commenting_integration);
        $community[] = JHTML::_('select.option', 'No', 'No');
        $community[] = JHTML::_('select.option', 'JomSocial', 'Jom Social');
        $this->assignRef('community', $community);

        /*Access Lever Settings*/
        $acl		=& JFactory::getACL();

        $group_tree=array();
        //$group_tree[] = JHTML::_('select.option', -2 , '- Everybody -');
        //$group_tree[] = JHTML::_('select.option', -1, '- All Registerred Users -');
        $group_tree = array_merge($group_tree, $acl->get_group_children_tree( null, 'USERS', false ));
        //var_dump($group_tree); exit();
        $this->assignRef('group_tree', $group_tree);

        /*Conversions Settings*/
        $convert_frame_size[] = JHTML::_('select.option', '160x128', 'DEFAULT 160x128');
        $convert_frame_size[] = JHTML::_('select.option', '320x240', 'QVGA 320x240 [4:3]');
        $convert_frame_size[] = JHTML::_('select.option', '320x200', 'CGA 320x200 [8:5]');
        $convert_frame_size[] = JHTML::_('select.option', '600x338', 'WVGA 640x480 [16:9]');
        $convert_frame_size[] = JHTML::_('select.option', '640x480', 'VGA 640x480 [4:3]');
        $convert_frame_size[] = JHTML::_('select.option', '720x480', 'NTSC 720x480 [3:2]');
        $convert_frame_size[] = JHTML::_('select.option', '768x576', 'PAL 768x576 [4:3]');
        $convert_frame_size[] = JHTML::_('select.option', '800x600', 'SVGA 800x600 [4:3]');
        $convert_frame_size[] = JHTML::_('select.option', '800x480', 'WVGA 800x480 [5:3]');
        $convert_frame_size[] = JHTML::_('select.option', '854x480', 'WVGA 854x480 [16:9]');
        $convert_frame_size[] = JHTML::_('select.option', '1024x600', 'WSVGA 1024x600');
        $convert_frame_size[] = JHTML::_('select.option', '1280x1024', 'SXGA 1280x1024 [5:4]');
        $convert_frame_size[] = JHTML::_('select.option', '1280x720', 'HD720 1280x720 [16:9]');
        $convert_frame_size[] = JHTML::_('select.option', '1280x768', 'WXGA 1280x768 [5:3]');
        $convert_frame_size[] = JHTML::_('select.option', '1280x800', 'WXGA 1280x800 [8:5]');
        $this->assignRef('convert_frame_size', $convert_frame_size);

        $convert_video_bitrate[] = JHTML::_('select.option','16', '16 kbit/s - Videophone Quality');
        $convert_video_bitrate[] = JHTML::_('select.option','200', '200 kbit/s - Video-conferencing system quality');
        $convert_video_bitrate[] = JHTML::_('select.option','300', '300 kbit/s - Video-conferencing system quality');
        $convert_video_bitrate[] = JHTML::_('select.option','400', '400 kbit/s - Video-conferencing system quality');
        $convert_video_bitrate[] = JHTML::_('select.option','500', '500 kbit/s - Video-conferencing system quality');
        $convert_video_bitrate[] = JHTML::_('select.option','600', '600 kbit/s - Video-conferencing system quality');
        $convert_video_bitrate[] = JHTML::_('select.option','700', '700 kbit/s - Video-conferencing system quality');
        $convert_video_bitrate[] = JHTML::_('select.option','800', '800 kbit/s - Video-conferencing system quality');
        $convert_video_bitrate[] = JHTML::_('select.option','900', '900 kbit/s - Video-conferencing system quality');
        $convert_video_bitrate[] = JHTML::_('select.option','1000', '1000 kbit/s - Video-conferencing system quality');
        $convert_video_bitrate[] = JHTML::_('select.option','1250', '1250 kbit/s - VCD quality');
        $convert_video_bitrate[] = JHTML::_('select.option','5000', '5000 kbit/s - DVD quality');
        $convert_video_bitrate[] = JHTML::_('select.option','15000', '15000 kbit/s - HDTV quality');
        $this->assignRef('convert_video_bitrate', $convert_video_bitrate);

        $convert_audio_bitrate[] = JHTML::_('select.option','32', '32 kbit/s - AM quality');
        $convert_audio_bitrate[] = JHTML::_('select.option','64', '64 kbit/s - Default quality');
        $convert_audio_bitrate[] = JHTML::_('select.option','96', '96 kbit/s - FM quality');
        $convert_audio_bitrate[] = JHTML::_('select.option','128', '128 kbit/s - Standard Bitrate quality');
        $convert_audio_bitrate[] = JHTML::_('select.option','192', '192 kbit/s - DAB (Digital Audio Broadcasting) quality');
        $convert_audio_bitrate[] = JHTML::_('select.option','224', '224 kbit/s - CD quality');
        $this->assignRef('convert_audio_bitrate', $convert_audio_bitrate);

        $this->h264_quality[] = JHTML::_('select.option','highest', 'Highest');
        $this->h264_quality[] = JHTML::_('select.option','default', 'Default');
        $this->h264_quality[] = JHTML::_('select.option','lowest', 'Lowest');

        /*Uploader Settings*/
        $uploader_type[] = JHTML::_('select.option','normal', 'Normal');
        $uploader_type[] = JHTML::_('select.option','flashupload', 'Flash Uploader Progress');
        $this->assignRef('uploader_type', $uploader_type);


        parent::display($tpl);
    }

    function getOptionFromFile($filename, $default_option='') {
        if (file_exists($filename)) {
            $contents = file_get_contents($filename);
            $lines = explode("\n", $contents);
            foreach ($lines as $line) {
                $option = explode("-", $line);
                $options[] = JHTML::_('select.option', trim($option[0]), trim($option[1]));
            }
        } else {
            $options[] = JHTML::_('select.option', $default_option, 'Default');
        }
        return $options;
    }

    function getTemplatesOptions($default_option='') {
        $db   =& JFactory::getDBO();
        $query = 'SELECT element, name FROM #__jomtube_plugins WHERE type = \'template\' ORDER BY name';
        $db->setQuery($query);
        $templates = $db->loadObjectList();
        if ($templates) {
            foreach ($templates as $template) {
                $options[] = JHTML::_('select.option', $template->element, $template->name);
            }
        } else {
            $options[] = JHTML::_('select.option', $default_option, 'Default');
        }
        return $options;
    }
}
?>