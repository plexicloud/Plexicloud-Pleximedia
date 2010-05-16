<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

class jomtubeViewApplylink extends JView {

    function display($tpl = null)
    {
        global $mainframe;

        //Load pane behavior
        jimport('joomla.html.pane');

        //initialise variables
        $editor 	= & JFactory::getEditor();
        $document	= & JFactory::getDocument();
        $user 		= & JFactory::getUser();
        $pane 		= & JPane::getInstance('sliders');

        JToolBarHelper::cancel();

        $link = JRequest::getVar('inputlink', '');

        if ($link != '') {
            $vidlink=$this->jalemurldecode($link);
            $current_dir = JPATH_ADMINISTRATOR.'/components/com_jomtube/plugins/videoserver/';
            $dir=opendir($current_dir);
            while($file=readdir($dir))
            {
                if ($file!="." and $file!=".."){
                    $pluginname=str_replace(".php","",$file);
                    $temporaryvidlink="Joomlaalem".$vidlink;
                    $pos = strpos($temporaryvidlink, $pluginname);
                    $pluginaddress=$current_dir.$file;
                    if ($pos<>"0"){
                        require($pluginaddress);
                        $nodotservertype=str_replace(".","",$pluginname);
                        $funcname=$nodotservertype."getvideodetails";
                        $videodetails=$funcname($vidlink,  '', '', "new");
                        $aserverfound=1;
                    }
                }
            }
            closedir($dir);
        } else { //edit reote video
            $row = JRequest::getVar('row');
            $link = $row->video_url;
            $videodetails['thumbnail'] = $row->video_thumb;
            $videodetails['title'] = $row->video_title;
            $videodetails['link'] = $row->video_url;
            $videodetails['videodescription'] = $row->video_desc;
            $videodetails['thumbnail_link'] = $row->video_thumb;
            $videodetails['videotags'] = $row->tags;
            $videodetails['video_published'] = $row->date_added;
            $videodetails['videoduration'] = $row->duration;
            $videodetails['videoservertype'] = $row->video_type;
            $videodetails['date_updated'] = $row->date_updated;
            $videodetails['video_published'] = $row->date_added;
            $videodetails['smallvideocode'] = $row->remote_id;
            $videodetails['user_id'] = $row->user_id;
            $selectedCat = $row->category_id;
        }

        $yesno[] = JHTML::_('select.option', 0, 'No');
        $yesno[] = JHTML::_('select.option', 1, 'Yes');
        $ispublished = JHTML::_('select.genericlist', $yesno, 'published', '', 'value', 'text', $row->published == '' ? 1 : $row->published);
        $isdownloadable = JHTML::_('select.genericlist', $yesno, 'downloadable', '', 'value', 'text', $row->downloadable == '' ? 1 : $row->downloadable );
        $isfeatured = JHTML::_('select.genericlist', $yesno, 'featured', '', 'value', 'text', $row->featured);

        //get parent option

        $categories = JRequest::getVar('category');
        $parentOptions[] = JHTML::_('select.option', '', '-Select Parent-');
        foreach ($categories as $category) {
            $category_name = str_repeat( '&nbsp;', 4*substr_count($category->family_id, "/")) . "+" . $category->category_name;
            $parentOptions[] = JHTML::_('select.option', $category->id, $category_name);
        }
        $parentSelect = JHTML::_('select.genericlist', $parentOptions, 'category_id', 'id="catid" class="inputbox" size="1"  onchange="selectCategory()"', 'value', 'text', $selectedCat);

        //assign data to template
        $this->assignRef('ispublished', $ispublished);
        $this->assignRef('isdownloadable', $isdownloadable);
        $this->assignRef('isfeatured', $isfeatured);
        $this->assignRef('parentSelect', $parentSelect);

        $this->assignRef( 'inputlink',	$link);
        $this->assignRef( 'videodetails',	$videodetails);
        $this->assignRef( 'row',	$row);

        parent::display($tpl);
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
}
?>