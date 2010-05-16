<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

class jomtubeViewUploadVideo extends JView {

    function display($tpl = null)
    {
        global $mainframe;
        $c = jomtube_configs::get_instance();

        //check user login
        $user 		= & JFactory::getUser();
        jomtube::checkPermission("acl_uploadvideo");

        //$this->setModel('video');
        $model = $this->getModel('video');
        $categories =  $model->getParentOption();

        $parentOptions[] = JHTML::_('select.option', '', '-Select Parent-');
        foreach ($categories as $category) {
            $category_name = str_repeat( '&nbsp;', 4*substr_count($category->family_id, "/")) . "+" . $category->category_name;
            $parentOptions[] = JHTML::_('select.option', $category->id, $category_name);
        }
        $parentSelect = JHTML::_('select.genericlist', $parentOptions, 'category_id', 'class="inputbox" size="1" ', 'value', 'text', '');

        $this->assignRef('c', $c);

        $this->assignRef('parentSelect', $parentSelect);
        parent::display($tpl);
    }
}
?>