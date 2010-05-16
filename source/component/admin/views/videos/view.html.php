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

		JHTML::_('behavior.tooltip');

		//get vars

		//create the toolbar
		JToolBarHelper::title( JText::_( 'Videos' ), 'jomtube-videos' );
		JToolBarHelper::spacer();
		//JToolBarHelper::addNew();
		JToolBarHelper::custom('add', 'local-add', 'new', 'Local', false );
		JToolBarHelper::spacer();
		JToolBarHelper::custom('remote', 'remote-add', 'new', 'Remote', false );
		JToolBarHelper::spacer();
		JToolBarHelper::custom('batch', 'batch-upload', 'new', 'Batch', false );
		JToolBarHelper::spacer();
		JToolBarHelper::custom('edit', 'edit-video', 'edit', 'Edit', false );
		JToolBarHelper::spacer();
		JToolBarHelper::custom('remove', 'delete-video', 'new', 'Delete', false );

		//Get data from the model
		$rows      	= & $this->get( 'Data');
		$total      = & $this->get( 'Total');
		$pageNav 	= & $this->get( 'Pagination' );

		//get parent option
		$categories = & $this->get('ParentOption');
		$parentOptions[] = JHTML::_('select.option', '', '-Select Category-');
		foreach ($categories as $category) {
			$category_name = str_repeat( '&nbsp;', 4*substr_count($category->family_id, "/")) . "+" . $category->category_name;
		  $parentOptions[] = JHTML::_('select.option', $category->id, $category_name);
		}
    $parentSelect = JHTML::_('select.genericlist', $parentOptions, 'category_id', 'id="catid" class="inputbox" size="1"  onchange="submitform()"', 'value', 'text', JRequest::getVar('category_id', 0));

    //filter video server
    $video_type = $this->get('VideoType');
    $video_server[] = JHTML::_('select.option', '', 'Select Video Server');
    if ($video_type) {
      foreach ($video_type as $type) {
        $video_server[] = JHTML::_('select.option', $type->video_type, ucfirst($type->video_type));
      }
    }

    $videoServerSelect = JHTML::_('select.genericlist', $video_server, 'video_server', 'onchange="submitform()"', 'value', 'text', JRequest::getVar('video_server', ''));

    //featured filter
	$yesno[] = JHTML::_('select.option', -1, 'Select Featured');
    $yesno[] = JHTML::_('select.option', 0, 'No');
    $yesno[] = JHTML::_('select.option', 1, 'Yes');
    $isfeatureded = JHTML::_('select.genericlist', $yesno, 'featured', 'onchange="submitform()"', 'value', 'text', JRequest::getVar('featured', -1, '', 'int'));

	//published filter
    $yesno = array();
	$yesno[] = JHTML::_('select.option', -1, 'Select Published');
    $yesno[] = JHTML::_('select.option', 0, 'No');
    $yesno[] = JHTML::_('select.option', 1, 'Yes');
    $ispublish = JHTML::_('select.genericlist', $yesno, 'publish', 'onchange="submitform()"', 'value', 'text', JRequest::getVar('publish', -1, '', 'int'));

    //search filter
	$filters = array();
	$lists['category'] = $parentSelect;
	$lists['video_server'] = $videoServerSelect;
    $lists['featured'] = $isfeatureded;
    $lists['publish'] = $ispublish;

	//assign data to template
	$this->assignRef('lists'      	, $lists);
	$this->assignRef('rows'      	, $rows);
	$this->assignRef('pageNav' 		, $pageNav);
	$this->assignRef('user'			, $user);

		parent::display($tpl);
	}
}
?>