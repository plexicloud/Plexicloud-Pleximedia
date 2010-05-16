<?php
class jomtubeControllerPlugins extends jomtubeController
{
    function __construct()
    {
        parent::__construct();

        // Register Extra task
        $this->registerTask( 'apply', 		'save');
        $this->registerTask( 'unpublish', 	'publish');
        $this->registerTask( 'edit' , 		'display' );
        $this->registerTask( 'add' , 		'display' );
    }

    /**
	 * Install an plugin for JomTube Component
	 *
	 * @access	public
	 * @return	void
	 * @since	1.5
	 */
    function doInstall()
    {
        // Check for request forgeries
        JRequest::checkToken() or jexit( 'Invalid Token' );

        $model	= &$this->getModel( 'Plugins' );
        //$view	= &$this->getView( 'plugins' );

        if ($model->install()) {
            //$cache = &JFactory::getCache('mod_menu');
            //$cache->clean();
        }

        $this->setRedirect('index.php?option=com_jomtube&view=plugins');
    }

    /**
	 * Remove an plugin (Uninstall)
	 *
	 * @access	public
	 * @return	void
	 * @since	1.5
	 */
	function remove()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		//$type	= JRequest::getWord('type', 'components');
		$model	= &$this->getModel( 'plugins' );
		//$view	= &$this->getView( $type );

		$eid = JRequest::getVar('eid', array(), '', 'array');

		// Update to handle components radio box
		// Checks there is only one extensions, we're uninstalling components
		// and then checks that the zero numbered item is set (shouldn't be a zero
		// if the eid is set to the proper format)
		//if((count($eid) == 1) && ($type == 'components') && (isset($eid[0]))) $eid = array($eid[0] => 0);

		JArrayHelper::toInteger($eid, array());
		$result = $model->remove($eid);

		//$view->setModel( $model, true );
		//$view->display();
		$this->setRedirect('index.php?option=com_jomtube&view=plugins');
	}


    function save()
    {
        // Check for request forgeries
        JRequest::checkToken() or jexit( 'Invalid Token' );
        $task		= JRequest::getVar('task');
        $db   =& JFactory::getDBO();
        $model = $this->getModel('plugins');
        $post = JRequest::get( 'post' );
        if ($returnid = $model->store($post)) {

            switch ($task)
            {
                case 'apply':
                    $msg = JText::sprintf( 'Successfully Saved changes to Plugin');
                    $this->setRedirect( 'index.php?option=com_jomtube&view=plugin&id='. $returnid, $msg );
                    break;

                case 'save':
                default:
                    $msg = JText::sprintf( 'Successfully Saved Plugin');
                    $this->setRedirect( 'index.php?option=com_jomtube&view=plugins', $msg );
                    break;
            }
            $cache = &JFactory::getCache('com_jomtube');
            $cache->clean();
        } else {

            $msg 	= '';
            $link 	= 'index.php?option=com_jomtube&view=video';
        }

    }

    function publish( )
    {
        // Check for request forgeries
        JRequest::checkToken() or jexit( 'Invalid Token' );

        $db		=& JFactory::getDBO();
        $user	=& JFactory::getUser();
        $cid     = JRequest::getVar( 'cid', array(0), 'post', 'array' );
        JArrayHelper::toInteger($cid, array(0));
        $publish = ( $this->getTask() == 'publish' ? 1 : 0 );
        $client  = JRequest::getWord( 'filter_client', 'site' );

        if (count( $cid ) < 1) {
            $action = $publish ? JText::_( 'publish' ) : JText::_( 'unpublish' );
            JError::raiseError(500, JText::_( 'Select a plugin to '.$action ) );
        }

        $cids = implode( ',', $cid );

        $query = 'UPDATE #__plugins SET published = '.(int) $publish
        . ' WHERE id IN ( '.$cids.' )'
        . ' AND ( checked_out = 0 OR ( checked_out = '.(int) $user->get('id').' ))'
        ;
        $db->setQuery( $query );
        if (!$db->query()) {
            JError::raiseError(500, $db->getErrorMsg() );
        }

        if (count( $cid ) == 1) {
            $row =& JTable::getInstance('plugin');
            $row->checkin( $cid[0] );
        }

        $this->setRedirect( 'index.php?option=com_plugins&client='. $client );
    }

    function cancel(  )
    {
        // Check for request forgeries
        JRequest::checkToken() or jexit( 'Invalid Token' );

        $client  = JRequest::getWord( 'filter_client', 'site' );

        $db =& JFactory::getDBO();
        $row =& JTable::getInstance('plugin');
        $row->bind(JRequest::get('post'));
        $row->checkin();

        $this->setRedirect( JRoute::_( 'index.php?option=com_plugins&client='. $client, false ) );
    }
}