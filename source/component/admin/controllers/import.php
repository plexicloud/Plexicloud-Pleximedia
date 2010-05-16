<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

/**
 * JomTube Component Import Controller
 *
 * @package Joomla
 * @subpackage JomTube
 * @since 0.9
 */
class jomtubeControllerImport extends jomtubeController
{
    /**
	 * Constructor
	 *
	 * @since 0.9
	 */
    function __construct()
    {
        parent::__construct();
    }

    // ###########################################################
    // ###### IMPORT VIDEO FROM SEYRET COMPONENT
    // ###########################################################
    function seyretImport()
    {
        global $mainframe;
        $db = & JFactory::getDBO();

        $seyretcid = JRequest::getInt( 'seyretcid', -1 );
        $category_id = JRequest::getInt( 'category_id', 0 );

        if (!empty($seyretcid) && ($seyretcid !== -1)) {
            $where = " WHERE `catid` LIKE '%*".$seyretcid."*#%'";
        } else {
            $where = "";
        }
        // import categories
        $db->setQuery( "SELECT * FROM #__seyret_items".$where );
        if ( !$db->query() ) {
            echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
            exit();
        }
        $rows_seyret = $db->loadObjectList();

        for ($i=0, $n=count($rows_seyret); $i < $n; $i++) {
            $row_seyret = $rows_seyret[$i];

            if ($row_seyret->videoservertype == "localfile") {
                //$_POST['video_id'] 	    = "local,".$row_seyret->videoservercode.",".$row_seyret->picturelink;
                continue;
            }

            $_POST['video_url']  = $row_seyret->videourl    ;
            $_POST['video_thumb'] = $_POST['display_thumb']  = $row_seyret->picturelink ;
            $_POST['remote_id'] = $row_seyret->videoservercode ;

            $_POST['video_type']        =  $row_seyret->videoservertype;
            $_POST['video_title'] 			= $row_seyret->title;
            $_POST['video_desc'] 		= $row_seyret->itemcomment;
            $_POST['category_id'] 		= $category_id;
            $_POST['tags'] 				= $row_seyret->videotags;
            $_POST['published'] 	= 1;

            $_POST['duration'] 		= $row_seyret->playtime;
            $_POST['date_added'] 	= date('Y-m-d H:i:s');
            $_POST['status'] 			= "complete";
            $_POST['user_id'] 			= $row_seyret->addedby;


            // bind it to the table
            $model = $this->getModel('video');
            if ($returnid = $model->store($_POST, 1)) {

            }
        }
        $link = 'index.php?option=com_jomtube&view=videos';
        $msg = "Successully imported ".$n." videos";
        $this->setRedirect($link, $msg);
    }


    // ###########################################################
    // ###### IMPORT VIDEO FROM HWDVIDEOSHARED
    // ###########################################################
    function hwdImport() {
        global $mainframe;
        $db = & JFactory::getDBO();

        $hwdcid = JRequest::getInt( 'hwdcid', -1 );
        $category_id = JRequest::getInt( 'category_id', 0 );

        if (!empty($hwdcid) && ($hwdcid !== -1)) {
            $where = " WHERE `category_id` = " . $hwdcid;
        } else {
            $where = "";
        }
        // import categories
        $db->setQuery( "SELECT * FROM #__hwdvidsvideos" . $where );
        if ( !$db->query() ) {
            echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
            exit();
        }
        $rows_hwd = $db->loadObjectList();

        $imported = 0;
        for ($i=0, $n=count($rows_hwd); $i < $n; $i++) {
            $row_hwd = $rows_hwd[$i];

            if ($row_hwd->video_type == "local") {
                //$_POST['video_id'] 	    = "local,".$row_seyret->videoservercode.",".$row_seyret->picturelink;
                continue;
            }

            $hwd_video_id = explode(",",$row_hwd->video_id);

            //var_dump($hwd_video_id); exit();

            $_POST['video_url']  = $hwd_video_id[2];
            $_POST['video_thumb'] = $_POST['display_thumb']  = $hwd_video_id[1];
            $_POST['remote_id'] = $hwd_video_id[0];
            //must be change
            $_POST['video_type']        =  substr($row_hwd->video_type, 0, strpos($row_hwd->video_type, "."));
            $_POST['video_title'] 			= $row_hwd->title;
            $_POST['video_desc'] 		= $row_hwd->description;
            $_POST['category_id'] 		= $category_id;
            $_POST['tags'] 				= $row_hwd->tags;
            $_POST['published'] 	= 1;
            $_POST['hits'] = $row_hwd->number_of_views;

            $_POST['duration'] 		= $row_hwd->video_length;
            $_POST['date_added'] 	= date('Y-m-d H:i:s');
            $_POST['status'] 			= "complete";
            $_POST['user_id'] 			= $row_hwd->user_id;

            //var_dump($_POST); exit();

            // bind it to the table
            $model = $this->getModel('video');
            if ($returnid = $model->store($_POST, 1)) {
                $imported ++;
            }
        }
        $link = 'index.php?option=com_jomtube&view=videos';
        $msg = "Successully imported ".$imported." videos";
        $this->setRedirect($link, $msg);
    }
}