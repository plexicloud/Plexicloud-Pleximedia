<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

class jomtubeViewVideo extends JView {

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

        $c = jomtube_configs::get_instance();
        $this->assignRef('c'      	, $c);

        //get vars
        $cid 		= JRequest::getVar( 'cid' );

        //create the toolbar
        if ( $cid ) {
            JToolBarHelper::title( JText::_( 'EDIT VIDEO' ), 'jomtube-videos' );

        } else {
            JToolBarHelper::title( JText::_( 'ADD VIDEO' ), 'jomtube-videos' );

        }
        JToolBarHelper::apply();
        JToolBarHelper::spacer();
        JToolBarHelper::save();
        JToolBarHelper::spacer();
        JToolBarHelper::cancel();

        //Get data from the model
        $model		= & $this->getModel();
        $row     	= & $this->get( 'Data' );

        if ($row->id != 0 && $row->video_type != 'local' && $row->video_type != null) {
            $r = new JApplication();
            $r->redirect('index.php?option=com_jomtube&controller=videos&task=applylink&cid=' . $row->id);

            $ispublished = JHTML::_('select.genericlist', $yesno, 'published', '', 'value', 'text', $row->published == '' ? 1 : $row->published);
            $isdownloadable = JHTML::_('select.genericlist', $yesno, 'downloadable', '', 'value', 'text', $row->downloadable == '' ? 1 : $row->downloadable );
            $isfeatured = JHTML::_('select.genericlist', $yesno, 'featured', '', 'value', 'text', $row->featured);
        }

        $yesno[] = JHTML::_('select.option', 0, 'No');
        $yesno[] = JHTML::_('select.option', 1, 'Yes');

        //get video list in local directory
        if (JRequest::getVar('category_id', '') != '') {
            $row->directory = & $this->get('CatDirectory');
            $selectedCat = JRequest::getVar('category_id', '');
        } else {
            $selectedCat = $row->id ? $row->category_id : '';
        }

        $currentdir = JPATH_SITE . (@$row->directory == '' ? '/jomtubefiles' : $row->directory);
        $dir = @opendir($currentdir);
        $videos = array();
        while($file=@readdir($dir))
        {
            if ($file!="." and $file!=".."){
                $vidfile=$file;
                $thumbdir="_thumbs";
                $displaydir = "_display";
                if ($vidfile!=$thumbdir || $vidfile != $displaydir){
                    if (!is_dir($vidfile)){
                        $ext = JTHelper::getFileExt($vidfile);
                        if (!$c->use_ffmpeg && $ext != 'flv') {
                            continue;
                        }
                        $list_file[] = $vidfile;//JHTML::_('select.option', $vidfile);
                    }
                }
            }//if
        }
        @closedir($dir);
        @sort($list_file);
        if (is_array($list_file))
        foreach ($list_file as $file) {
            $videos[] = JHTML::_('select.option', $file);
        }

        $videolist = JHTML::_('select.genericlist', $videos, 'video_url', 'id="localvideolist" class="inputbox" style="width:326px;font-size:10px;" size="7" onchange="selectthisvideo()"', 'value', 'text', $row->id ? $row->video_url : '');
        $this->assignRef('category_dir', $currentdir);

        $thumbdir="_thumbs";
        $current_dir = $currentdir."/".$thumbdir;
        $dir=@opendir($current_dir);
        $thumbs = array();
        $thumb_file = array();
        while($file=@readdir($dir))
        {
            if ($file!="." and $file!=".."){
                $thumbfile=$file;
                $thumb_file[] = $thumbfile;
            }
        }
        @closedir($dir);
        if (is_array($thumb_file)) {
            sort($thumb_file);
            foreach ($thumb_file as $file) {
                $thumbs[] = JHTML::_('select.option', $thumbfile,$file );
            }
        }
        //******************************************
        $thumblist=JHTML::_( 'select.genericlist', $thumbs, 'video_thumb', 'id="localthumblist" class="inputbox" style="width:326px; font-size:10px;" size="7" onchange="selectthisthumb()"', 'value', 'text', $row->id ? $row->video_thumb : '');

        //get parent option
        $categories = & $this->get('ParentOption');
        $parentOptions[] = JHTML::_('select.option', '', '-Select Parent-');
        foreach ($categories as $category) {
            $category_name = str_repeat( '&nbsp;', 4*substr_count($category->family_id, "/")) . "+" . $category->category_name;
            $parentOptions[] = JHTML::_('select.option', $category->id, $category_name);
        }
        $parentSelect = JHTML::_('select.genericlist', $parentOptions, 'category_id', 'id="catid" class="inputbox" size="1"  onchange="selectCategory()"', 'value', 'text', $selectedCat);
        //clean data
        JFilterOutput::objectHTMLSafe( $row, ENT_QUOTES, 'catdescription' );

        //assign data to template
        $this->assignRef('row'      	, $row);
        $this->assignRef('editor'		, $editor);
        $this->assignRef('parentSelect', $parentSelect);
        $this->assignRef('pane'			, $pane);
        $this->assignRef('ispublished', $ispublished);
        $this->assignRef('isdownloadable', $isdownloadable);
        $this->assignRef('isfeatured', $isfeatured);
        $this->assignRef('localvideolist', $videolist);
        $this->assignRef('localthumblist', $thumblist);

        //assign data to template
        $this->assignRef('lists'      	, $lists);

        $this->assignRef('my', $my =& JFactory::getUser());
        parent::display($tpl);
    }
}
?>