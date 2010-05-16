<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

class jomtubeViewCategories extends JView {

    function display($tpl = null)
    {
        global $mainframe, $option;

        //initialise variables
        $user 		= & JFactory::getUser();
        $db  		= & JFactory::getDBO();
        $document	= & JFactory::getDocument();
        $model		= & $this->getModel();

        //Get data from the model
        $rows      	= & $this->get( 'Data' );
        $total      = & $this->get( 'Total');
        $pageNav 	= & $this->get( 'Pagination' );

        //assign data to template
        $this->assignRef('lists'      	, $lists);
        $this->assignRef('rows'      	, $rows);
        $this->assignRef('pageNav' 		, $pageNav);
        $this->assignRef('user'			, $user);

        $this->assignRef('breadcrum', $model->getBreadcrumb());

        //Get data from the model
        $items      	= JRequest::getVar('items');
        $total      = JRequest::getVar('totals');
        $pageNav 	= JRequest::getVar('pagNav');

        //assign data to template
        $this->assignRef('lists'      	, $lists);
        $this->assignRef('items'      	, $items);
        $this->assignRef('pageNav' 		, $pageNav);
        $this->assignRef('user'			, $user);

        //get category list
        $categories = & $this->get('CategoryList');
        $parentOptions[] = JHTML::_('select.option', '', '-Select Category-');
        foreach ($categories as $category) {
            $category_name = str_repeat( '&nbsp;', 4*substr_count($category->family_id, "/")) . "+" . $category->category_name;
            $parentOptions[] = JHTML::_('select.option', $category->id, $category_name);
        }
        $categoryList = JHTML::_('select.genericlist', $parentOptions, 'category_id', 'id="catid" class="inputbox" size="1"  onchange="selectCategory(this)"', 'value', 'text', JRequest::getVar("id", "int"));
        $this->assignRef('categoryList'			, $categoryList);

        // ###########################################################
        // ###### GET ALL SETTINGS
        // ###########################################################
        $c = jomtube_configs::get_instance();
        $this->assignRef('c'			, $c);

        parent::display($tpl);
    }
}
?>