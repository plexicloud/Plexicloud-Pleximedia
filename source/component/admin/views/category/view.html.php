<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * View class for the EventList category screen
 *
 * @package Joomla
 * @subpackage EventList
 * @since 0.9
 */

class jomtubeViewCategory extends JView {

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

		//get vars
		$cid 		= JRequest::getVar( 'cid' );

		//create the toolbar
		if ( $cid ) {
			JToolBarHelper::title( JText::_( 'EDIT CATEGORY' ), 'jomtube-categories' );

		} else {
			JToolBarHelper::title( JText::_( 'ADD CATEGORY' ), 'jomtube-categories' );

		}
		JToolBarHelper::apply();
		JToolBarHelper::spacer();
		JToolBarHelper::save();
		JToolBarHelper::spacer();
		JToolBarHelper::cancel();

		//Get data from the model
		$model		= & $this->getModel();
		$row     	= & $this->get( 'Data' );
		//$groups 	= & $this->get( 'Groups' );

		//get parent option
		$categories = & $this->get('ParentOption');
		$parentOptions[] = JHTML::_('select.option', 0, '-Select Parent-');
		foreach ($categories as $category) {
			$category_name = str_repeat( '&nbsp;', 4*substr_count($category->family_id, "/")) . "+" . $category->category_name;
		  	$parentOptions[] = JHTML::_('select.option', $category->id, $category_name);
		}
    	$parentSelect = JHTML::_('select.genericlist', $parentOptions, 'parent_id', '', 'value', 'text', $row->parent_id);
		//clean data
		JFilterOutput::objectHTMLSafe( $row, ENT_QUOTES, 'catdescription' );

		//assign data to template
		$this->assignRef('row'      	, $row);
		$this->assignRef('editor'		, $editor);
		$this->assignRef('parentSelect', $parentSelect);
		$this->assignRef('pane'			, $pane);

		parent::display($tpl);
	}
}
?>