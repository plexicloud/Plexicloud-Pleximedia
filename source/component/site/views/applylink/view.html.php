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
class jomtubeViewApplylink extends JView {

    function display($tpl = null)
    {
        global $mainframe;

        $link = JRequest::getVar('inputlink', '');
        $vidlink=jomtube_urldecode($link);

        $yesno[] = JHTML::_('select.option', 0, 'No');
        $yesno[] = JHTML::_('select.option', 1, 'Yes');
        //$ispublished = JHTML::_('select.genericlist', $yesno, 'published', '', 'value', 'text', $row->published == '' ? 1 : $row->published);
        //$isdownloadable = JHTML::_('select.genericlist', $yesno, 'downloadable', '', 'value', 'text', $row->downloadable == '' ? 1 : $row->downloadable );
        //$isfeatured = JHTML::_('select.genericlist', $yesno, 'featured', '', 'value', 'text', $row->featured);

        //get parent option

        $categories = JRequest::getVar('category');
        $parentOptions[] = JHTML::_('select.option', '', '-Select Parent-');
        foreach ($categories as $category) {
            $category_name = str_repeat( '&nbsp;', 4*substr_count($category->family_id, "/")) . "+" . $category->category_name;
            $parentOptions[] = JHTML::_('select.option', $category->id, $category_name);
        }
        $selectedCat = '';
        $parentSelect = JHTML::_('select.genericlist', $parentOptions, 'category_id', 'class="inputbox" size="1" ', 'value', 'text', $selectedCat);

        //assign data to template
        $this->assignRef('ispublished', $ispublished);
        $this->assignRef('isdownloadable', $isdownloadable);
        $this->assignRef('isfeatured', $isfeatured);
        $this->assignRef('parentSelect', $parentSelect);

        $this->assignRef( 'inputlink',	$link);
        $this->assignRef( 'videodetails',	JRequest::getVar('videodetails'));
        $this->assignRef( 'existed_video',	JRequest::getVar('existed_video'));

        // ###########################################################
        // ###### GET ALL SETTINGS
        // ###########################################################
        $c = jomtube_configs::get_instance();
        $this->assignRef('c'      	, $c);

        parent::display($tpl);
    }
}
?>