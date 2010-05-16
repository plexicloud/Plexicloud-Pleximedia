<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

class jomtubeViewVideo extends JView {

    function display($tpl = null)
    {
        global $mainframe;
        $mosConfig_live_site =  substr(JURI::base(), 0, strlen(JURI::base()) -1);
        //Load pane behavior
        jimport('joomla.html.pane');

        //initialise variables
        $editor 	= & JFactory::getEditor();
        $document	= & JFactory::getDocument();
        $user 		= & JFactory::getUser();
        $pane 		= & JPane::getInstance('sliders');

        //get vars
        $cid 		= JRequest::getVar( 'id' );
        //Get data from the model
        $model		= & $this->getModel();
        $row     	= & $this->get( 'Data' );
        //$groups 	= & $this->get( 'Groups' );

        $yesno[] = JHTML::_('select.option', 0, 'No');
        $yesno[] = JHTML::_('select.option', 1, 'Yes');
        $ispublished = JHTML::_('select.genericlist', $yesno, 'published', '', 'value', 'text', $row->published);
        $isdownloadable = JHTML::_('select.genericlist', $yesno, 'downloadable', '', 'value', 'text', $row->downloadable);
        $isfeatured = JHTML::_('select.genericlist', $yesno, 'featured', '', 'value', 'text', $row->featured);

        // ###########################################################
        // ###### SHOW ALL SETTINGS
        // ###########################################################
        $c = jomtube_configs::get_instance();
        $this->assignRef( 'c', $c);

        //get video list in local directory
        if (JRequest::getVar('category_id', '') != '') {
            $row->directory = & $this->get('CatDirectory');
            $selectedCat = JRequest::getVar('category_id', '');
        } else {
            $selectedCat = $row->category_id;
        }
        $currentdir = JPATH_SITE . ($row->directory == '' ? '/jomtubefiles' : $row->directory);
        $dir = @opendir($currentdir);
        $videos = array();
        while($file=@readdir($dir))
        {
            if ($file!="." and $file!=".."){
                $vidfile=$file;
                $thumbdir="_thumbs";
                if ($vidfile!=$thumbdir){
                    if (!is_dir($vidfile)){
                        $videos[] = JHTML::_('select.option', $vidfile);
                    }
                }
            }//if
        }
        @closedir($dir);
        $videolist = JHTML::_('select.genericlist', $videos, 'video_url', 'id="localvideolist" class="inputbox" style="width:326px;font-size:10px;" size="7" onchange="selectthisvideo()"', 'value', 'text', $row->video_url);
        $this->assignRef('category_dir', $currentdir);

        $thumbdir="_thumbs";
        $current_dir = $currentdir."/".$thumbdir;
        $dir=@opendir($current_dir);
        $thumbs = array();
        while($file=@readdir($dir))
        {
            if ($file!="." and $file!=".."){
                $thumbfile=$file;
                $thumbs[] = JHTML::_('select.option', $thumbfile,$thumbfile );
            }
        }
        @closedir($dir);
        //******************************************
        $thumblist=JHTML::_( 'select.genericlist', $thumbs, 'video_thumb', 'id="localthumblist" class="inputbox" style="width:326px; font-size:10px;" size="7" onchange="selectthisthumb()"', 'value', 'text', $row->video_thumb);

        //get parent option
        $categories = & $this->get('ParentOption');
        $parentOptions[] = JHTML::_('select.option', '', '-Select Category-');
        foreach ($categories as $category) {
             $category_name = str_repeat( '&nbsp;', 4*substr_count($category->family_id, "/")) . "+" . $category->category_name;
             $parentOptions[] = JHTML::_('select.option', $category->id, $category_name);
        }
        $parentSelect = JHTML::_('select.genericlist', $parentOptions, 'category_id', 'id="catid" class="inputbox" size="1"  onchange="selectCategory(this)"', 'value', 'text', $selectedCat);
        //clean data
        JFilterOutput::objectHTMLSafe( $row, ENT_QUOTES, 'catdescription' );

        //load remote video
        if ($row->video_type != 'local' && $row->video_type != 'remote' && $row->video_type != NULL) {
            $current_dir = JPATH_ADMINISTRATOR.'/components/com_jomtube/plugins/videoserver/';
            require_once($current_dir . $row->video_type . '.php');
            $fvtype=str_replace(".","",$row->video_type);
            $popupvtype=jalemurlencode($row->video_type);
            $functionname=$fvtype."embed";

            $embed=$functionname($row, $c);
            $this->assignRef('embed', $embed);

            //download link
            //$functiondownload = $fvtype . "generatevideodownloadlink";
            //$row->download_link = $functiondownload($row->video_url);
        } else {
            //download link
            $row->download_link = $mosConfig_live_site .  $row->directory . '/' . $row->video_url;
        }

        //link tags
        $videotags_array=explode(' ',$row->tags);
        $videotagslinked = '';
        foreach ($videotags_array as $vt){
            $link= JRoute::_("index.php?view=videos&searchkey=$vt");
            $videotagslinked.="<a href=\"$link\">$vt</a> ";
        }


        $uri =& JURI::getInstance();
        $baseUrl = $uri->toString( array('scheme', 'host', 'port'));

        //assign data to template
        $this->assignRef('row'      	, $row);
        $this->assignRef('user_id', $user->id);
        $this->assignRef('user', $user);
        $this->assignRef('editor'		, $editor);
        $this->assignRef('parentSelect', $parentSelect);
        $this->assignRef('pane'			, $pane);
        $this->assignRef('ispublished', $ispublished);
        $this->assignRef('isdownloadable', $isdownloadable);
        $this->assignRef('isfeatured', $isfeatured);
        $this->assignRef('localvideolist', $videolist);
        $this->assignRef('localthumblist', $thumblist);
        $this->assignRef('videosincategory', $model->getVideosincategory());
        $this->assignRef('countvideosincategory', intval($model->getCountVideoincategory()));
        $this->assignRef('breadcrum', $model->getBreadcrumb());
        $this->assignRef('videotaglinked', $videotagslinked);
        $this->assignRef('basUrl', $baseUrl);

        //jom comments integration
        //$this->assignRef( 'jomtubeComments', toolsHelpers::integrateVideoComments($row));
        if ($c->commenting_system == "JomComment") {
            if (file_exists(JPATH_ROOT.DS.'plugins'.DS.'content'.DS.'jom_comment_bot.php')) {
                include_once(JPATH_COMPONENT.DS.'plugins'.DS.'JomComment/comments.php');
                $this->assignRef( 'jomtubeComments', new JVComments());
            } else {
                $warningMsg = 'notinstall';
                $this->assignRef( 'jomtubeComments', $warningMsg);
            }
        }


        // ##################################################
        // ##### SETS THE PAGE TITLE AND KEYWORDS DESCRIPTION
        // ##################################################
        $document->setTitle(stripslashes(htmlspecialchars_decode($row->video_title, ENT_QUOTES)));
        $document->setMetadata( 'keywords' , $row->tags);
        $document->setMetadata( 'description' , $row->video_desc);

        $this->assignRef('vidwidth', $c->video_player_width);
        $this->assignRef('vidheight', $c->video_player_height);
         $this->assignRef('autostart', $c->auto_play_on_load);
        parent::display($tpl);
    }
}
?>