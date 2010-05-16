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
class jomtubeViewAddvideo extends JView {

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

		JToolBarHelper::spacer();
		JToolBarHelper::save('applylink');
		JToolBarHelper::spacer();
		JToolBarHelper::cancel();
    	JToolBarHelper::title( JText::_( 'ADD REMOTE VIDEO' ), 'jomtube-videos' );
		parent::display($tpl);
	}
}
?>