<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

class jomtubeViewUploadbatch extends JView {

	function display($tpl = null)
	{
		global $mainframe;

        JToolBarHelper::title( 'Batch Adding Videos Locally To Video List' , 'jomtube-videos' );

		JToolBarHelper::save('uploadbatch');
		JToolBarHelper::spacer();
		JToolBarHelper::cancel();


		$yesno[] = JHTML::_('select.option', 0, 'No');
    	$yesno[] = JHTML::_('select.option', 1, 'Yes');
    	$ispublished = JHTML::_('select.genericlist', $yesno, 'published', '', 'value', 'text', '');
    	$isdownloadable = JHTML::_('select.genericlist', $yesno, 'downloadable', '', 'value', 'text', '');
    	$isfeatured = JHTML::_('select.genericlist', $yesno, 'featured', '', 'value', 'text', '');

		//get parent option
		$categories = & $this->get('ParentOption');
		$parentOptions[] = JHTML::_('select.option', '', '-Select Parent-');
		foreach ($categories as $category) {
			$category_name = str_repeat( '&nbsp;', 4*substr_count($category->family_id, "/")) . "+" . $category->category_name;
		  $parentOptions[] = JHTML::_('select.option', $category->id, $category_name);
		}
    	$parentSelect = JHTML::_('select.genericlist', $parentOptions, 'category_id', 'id="catid" class="inputbox" size="1" ', 'value', 'text', '');
		//clean data
		JFilterOutput::objectHTMLSafe( $row, ENT_QUOTES, 'catdescription' );

		$this->assignRef('parentSelect', $parentSelect);
		$this->assignRef('pane'			, $pane);
    	$this->assignRef('ispublished', $ispublished);
    	$this->assignRef('isdownloadable', $isdownloadable);
    	$this->assignRef('isfeatured', $isfeatured);
    	$this->assignRef('my', $my =& JFactory::getUser());

		parent::display($tpl);
	}
}
?>