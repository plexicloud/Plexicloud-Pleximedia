<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

class jomtubeViewVideos extends JView {

    function display($tpl = null)
    {
        global $mainframe, $option;

        //initialise variables
        $user 		= & JFactory::getUser();
        $db  		= & JFactory::getDBO();
        $document	= & JFactory::getDocument();

        $params = clone($mainframe->getParams('com_jomtube'));

        $this->assignRef('header_title', $mainframe->getPageTitle());

        // Set defaults
        $params->def( 'video_category', -1);
        $params->def( 'video_order', 'newestvideos');
        $params->def( 'video_layout', 'grid' );
        $params->def( 'video_layout_columns', 4);
        $params->def ('show_video_title', 1);
        $params->def( 'show_video_description', 0);
        $params->def( 'show_video_rating', 1);
        $params->def( 'show_video_views', 1);
        $params->def( 'show_video_dateadded', 1);
        $params->def( 'show_video_duration', 1);
        $params->def( 'show_video_author', 1);
        $this->assignRef('params', $params);

        $model		= & $this->getModel();
        if (JRequest::getVar("category_id", 0, '', 'int') != 0)
            $this->assignRef('breadcrum', $model->getBreadcrumb());

        //Get data from the model
        $items      = & $this->get( 'Data');
        $total      = & $this->get( 'Total');
        $pageNav 	= & $this->get( 'Pagination' );

        //assign data to template
        $this->assignRef('lists'      	, $lists);
        $this->assignRef('items'      	, $items);
        $this->assignRef('pageNav' 		, $pageNav);
        $this->assignRef('user'			, $user);
        $this->assignRef('total'		, $total);

        // ###########################################################
        // ###### GET ALL SETTINGS
        // ###########################################################
        $c = jomtube_configs::get_instance();
        //prepare %column

        /*Show player in main page*/
        $playerpath = JPATH_COMPONENT . DS . 'views' . DS . 'video' .DS. 'tmpl' . DS . 'video_player.php';
        $this->assignRef('playerpath', $playerpath);
        $this->assignRef('vidwidth', $c->width_player_in_mainpage);
        $this->assignRef('vidheight', $c->height_player_in_mainpage);
        //get video for to play in main page
        $model->_data = null;
        $row = $model->getData(1);
        $this->assignRef('row', $row[0]);
        $autostart = false;
        $this->assignRef('autostart', $autostart);

        $percent_column = 100/intval($c->columns_per_page);
        $this->assignRef('percent_column', $percent_column);
        $this->assignRef('c'      	, $c);

        parent::display($tpl);
    }
}
?>