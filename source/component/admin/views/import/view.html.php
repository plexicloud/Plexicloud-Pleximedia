<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

class jomtubeViewImport extends JView {

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
        $db = & JFactory::getDBO();

        //get vars
        $cid 		= JRequest::getVar( 'cid' );

        //create the toolbar
        if ( $cid ) {
            JToolBarHelper::title( JText::_( 'EDIT VIDEO' ), 'jomtube-videos' );
        } else {
            JToolBarHelper::title( JText::_( 'ADD VIDEO' ), 'jomtube-videos' );
        }

        //###########################################
        //############ SEYRET IMPORT
        //###########################################
        $seyret_check = 0;
        if (file_exists(JPATH_ADMINISTRATOR . '/components/com_seyret/')) {
            $seyret_check = 1;

            //check number of seyret videos
            $db->SetQuery( 'SELECT count(*) FROM #__seyret_items');
            $seyretitems = $db->loadResult();
            $this->assignRef('seyretitems', $seyretitems);

            //get seyret categories
            $db->setQuery( "SELECT `id` AS `key`, `categoryname` AS `text` FROM #__seyret_categories ORDER BY categoryname" );
            $rows_seyret = $db->loadObjectList();

            $n = count($rows_seyret);
            $rows_seyret[$n]->key = "-1";
            $rows_seyret[$n]->text = "All Categories";

            $seyretcatsel = JHTML::_('select.genericlist', $rows_seyret, 'seyretcid', 'class="inputbox" size="1"', 'key', 'text', -1);

            $this->assignRef('seyretcatsel', $seyretcatsel);
        }
         $this->assignRef('seyret_check', $seyret_check);

        //###########################################
        //############ HWD IMPORT
        //###########################################
        $hwd_check = 0;
        if (file_exists(JPATH_ADMINISTRATOR . '/components/com_hwdvideoshare/')) {
            $hwd_check = 1;

            //check number of hwd videos
            $db->SetQuery( 'SELECT count(*) FROM #__hwdvidsvideos');
            $seyretitems = $db->loadResult();
            $this->assignRef('hwdtitems', $seyretitems);

            //get hwd categories
            $db->setQuery( "SELECT `id` AS `key`, `category_name` AS `text` FROM #__hwdvidscategories ORDER BY category_name" );
            $rows_hwd = $db->loadObjectList();

            $n = count($rows_hwd);
            $rows_hwd[$n]->key = "-1";
            $rows_hwd[$n]->text = "All Categories";

            $hwdcatsel = JHTML::_('select.genericlist', $rows_hwd, 'hwdcid', 'class="inputbox" size="1"', 'key', 'text', -1);

            $this->assignRef('hwdcatsel', $hwdcatsel);
        }
         $this->assignRef('hwd_check', $hwd_check);

        //###########################################
        //############ BUILD JOMTUBE CATEGORIES
        //###########################################
        $model = $this->getModel('category');
        $categories = $model->getParentOption();
        //$parentOptions[] = JHTML::_('select.option', '', '-Select Parent-');
        foreach ($categories as $category) {
            $category_name = str_repeat( '&nbsp;', 4*substr_count($category->family_id, "/")) . "+" . $category->category_name;
            $parentOptions[] = JHTML::_('select.option', $category->id, $category_name);
        }
        $parentSelect = JHTML::_('select.genericlist', $parentOptions, 'category_id', 'id="catid" class="inputbox" size="1"  onchange="selectCategory()"', 'value', 'text', '');
        $this->assignRef('parentSelect', $parentSelect);

        $tmplpath = dirname(__FILE__).DS.'tmpl';
        $this->assignRef('tmplpath'			, $tmplpath);

        $this->assignRef('my', $my =& JFactory::getUser());
        $this->assignRef('pane'			, $pane);
        parent::display($tpl);
    }
}
?>