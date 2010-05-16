<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * View class for the jomtube categories screen
 *
 * @package Joomla
 * @subpackage jomtube
 * @since 0.9
 */

class jomtubeViewCategories extends JView {

	function display($tpl = null)
	{
		global $mainframe, $option;

		//initialise variables
		$user 		= & JFactory::getUser();
		$db  		= & JFactory::getDBO();
		$document	= & JFactory::getDocument();

		JHTML::_('behavior.tooltip');

		//get vars

		//create the toolbar
		JToolBarHelper::title( JText::_( 'Categories' ), 'jomtube-categories' );
		JToolBarHelper::spacer();
		JToolBarHelper::custom('add', 'add-category', 'new', 'Add', false );
		JToolBarHelper::spacer();
		JToolBarHelper::custom('edit', 'edit-category', 'new', 'Edit', false );
		JToolBarHelper::spacer();
		JToolBarHelper::custom('remove', 'delete-category', 'new', 'Delete', false );

		//Get data from the model
		$rows      	= & $this->get( 'Data' );
		$total      = & $this->get( 'Total');
		$pageNav 	= & $this->get( 'Pagination' );

		//assign data to template
		$this->assignRef('lists'      	, $lists);
		$this->assignRef('rows'      	, $rows);
		$this->assignRef('pageNav' 		, $pageNav);
		$this->assignRef('user'			, $user);

		parent::display($tpl);
	}
}
?>