<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

/**
 * EventList Component Videos Controller
 *
 * @package Joomla
 * @subpackage jomtube
 * @since 0.9
 */
class jomtubeControllerVideos extends jomtubeController
{
	/**
	 * Constructor
	 *
	 * @since 0.9
	 */
	function __construct()
	{
		parent::__construct();

		// Register Extra task
		$this->registerTask('selectCat', 'edit');
		$this->registerTask( 'add'  ,		 	'edit' );
		$this->registerTask( 'apply', 			'save' );
		$this->registerTask( 'accesspublic', 	'access' );
		$this->registerTask( 'accessregistered','access' );
		$this->registerTask( 'accessspecial', 	'access' );
		$this->registerTask( 'uploadbatch', 'uploadbatch');
		$this->registerTask( 'featured', 'featured');
		$this->registerTask( 'unfeatured', 'unfeatured');
		$this->registerTask( 'applylink', 'applylink');
		$this->registerTask( 'savelink', 'savelink');
		$this->registerTask( 'remote', 'remote');
		$this->registerTask( 'batch', 'batch');
	}

	function applylink() {
	  $model = $this->getModel('video');
	  $id = JRequest::getVar('cid', 0);
	  if ($id != 0) {
	    JRequest::setVar('row', $model->getData());
	  }
    JRequest::setVar('view', 'applylink');
    JRequest::setVar('category', $model->getParentOption());
    parent::display();
	}

	function uploadbatch() {
	  $post = JRequest::get('post');
	  $model = $this->getModel('video');
	  $counter = $model->uploadbatch($post);
	  $this->setRedirect('index.php?option=com_jomtube&view=videos', 'Upload Batch Success: ' . $counter . ' videos !');
	}
	/**
	 * Logic to save a category
	 *
	 * @access public
	 * @return void
	 * @since 0.9
	 */
	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or die( 'Invalid Token' );

		$task		= JRequest::getVar('task');

		//Sanitize
		$post = JRequest::get( 'post' );

		$model = $this->getModel('video');

		if ($returnid = $model->store($post)) {

			switch ($task)
			{
				case 'apply' :
					$link = 'index.php?option=com_jomtube&view=video&cid[]='.$returnid;
					break;

				default :
					$link = 'index.php?option=com_jomtube&view=videos';
					break;
			}
			$msg = JText::_( 'VIDEO SAVED' );

			$cache = &JFactory::getCache('com_jomtube');
			$cache->clean();

		} else {

			$msg 	= '';
			$link 	= 'index.php?option=com_jomtube&view=video';
		}

		$model->checkin();

		$this->setRedirect($link, $msg);
	}

	function savelink()
	{
		// Check for request forgeries
		JRequest::checkToken() or die( 'Invalid Token' );

		$task		= JRequest::getVar('task');

		//Sanitize
		$post = JRequest::get( 'post' );

		$model = $this->getModel('video');

		if ($returnid = $model->store($post, 1)) {
			$msg = JText::_( 'VIDEO SAVED' );
      $link 	= 'index.php?option=com_jomtube&view=videos';
			$cache = &JFactory::getCache('com_jomtube');
			$cache->clean();
		} else {
			$msg 	= '';
			$link 	= 'index.php?option=com_jomtube&view=video';
		}

		$this->setRedirect($link, $msg);
	}
	/**
	 * Logic to publish videos
	 *
	 * @access public
	 * @return void
	 * @since 0.9
	 */
	function publish()
	{
		$cid 	= JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to publish' ) );
		}

		$model = $this->getModel('videos');

		if(!$model->publish($cid, 1)) {
			echo "<script> alert('".$model->getError()."'); window.history.go(-1); </script>\n";
		}

		$total = count( $cid );
		$msg 	= $total.' '.JText::_( 'VIDEO PUBLISHED');

		$this->setRedirect( 'index.php?option=com_jomtube&view=videos', $msg );
	}

	/**
	 * Logic to unpublish videos
	 *
	 * @access public
	 * @return void
	 * @since 0.9
	 */
	function unpublish()
	{
		$cid 	= JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to unpublish' ) );
		}

		$model = $this->getModel('videos');

		if(!$model->publish($cid, 0)) {
			echo "<script> alert('".$model->getError()."'); window.history.go(-1); </script>\n";
		}

		$total = count( $cid );
		$msg 	= $total.' '.JText::_( 'CATEGORY UNPUBLISHED');

		$this->setRedirect( 'index.php?option=com_jomtube&view=videos', $msg );
	}

	function featured() {
	  $id = JRequest::getVar('id', 0, '', 'int');
	  $model = $this->getModel('videos');
	  if (!$model->featured($id, 1)) {
	    echo "<script> alert('".$model->getError()."'); window.history.go(-1);</script>\n";
	  }
	  $this->setRedirect('index.php?option=com_jomtube&view=videos');
	}

	function unfeatured() {
	  $id = JRequest::getVar('id', 0, '', 'int');
	  $model = $this->getModel('videos');
	  if (!$model->featured($id, 0)) {
	    echo "<script> alert('".$model->getError()."'); window.history.go(-1);</script>\n";
	  }
	  $this->setRedirect('index.php?option=com_jomtube&view=videos');
	}
	/**
	 * Logic to orderup a category
	 *
	 * @access public
	 * @return void
	 * @since 0.9
	 */
	function orderup()
	{
		$model = $this->getModel('categories');
		$model->move(-1);

		$this->setRedirect( 'index.php?option=com_eventlist&view=categories');
	}

	/**
	 * Logic to orderdown a category
	 *
	 * @access public
	 * @return void
	 * @since 0.9
	 */
	function orderdown()
	{
		$model = $this->getModel('categories');
		$model->move(1);

		$this->setRedirect( 'index.php?option=com_eventlist&view=categories');
	}

	/**
	 * Logic to mass ordering categories
	 *
	 * @access public
	 * @return void
	 * @since 0.9
	 */
	function saveorder()
	{
		$cid 	= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$order 	= JRequest::getVar( 'order', array(0), 'post', 'array' );

		$model = $this->getModel('categories');
		$model->saveorder($cid, $order);

		$msg = 'New ordering saved';
		$this->setRedirect( 'index.php?option=com_com_eventlist&view=categories', $msg );
	}

	/**
	 * Logic to delete categories
	 *
	 * @access public
	 * @return void
	 * @since 0.9
	 */
	function remove()
	{
		global $option;

		$cid		= JRequest::getVar( 'cid', array(0), 'request', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to delete' ) );
		}

		$model = $this->getModel('videos');
		$msg = $model->delete($cid);

		$cache = &JFactory::getCache('com_jomtube');
		$cache->clean();

		$this->setRedirect( 'index.php?option='. $option .'&view=videos', $msg );
	}

	/**
	 * logic for cancel an action
	 *
	 * @access public
	 * @return void
	 * @since 0.9
	 */
	function cancel()
	{
		// Check for request forgeries
//		JRequest::checkToken() or die( 'Invalid Token' );
//
//		$video = & JTable::getInstance('jomtube_videos', '');
//		var_dump($video);
//		$video->bind(JRequest::get('post'));
//		$video->checkin();

		$this->setRedirect( 'index.php?option=com_jomtube&view=videos' );
	}

	/**
	 * Logic to set the category access level
	 *
	 * @access public
	 * @return void
	 * @since 0.9
	 */
	function access( )
	{
		global $option;

		$cid		= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$id			= $cid[0];
		$task		= JRequest::getVar( 'task' );

		if ($task == 'accesspublic') {
			$access = 0;
		} elseif ($task == 'accessregistered') {
			$access = 1;
		} else {
			$access = 2;
		}

		$model = $this->getModel('category');
		$model->access( $id, $access );

		$this->setRedirect('index.php?option='. $option .'&view=categories' );
	}

	/**
	 * Logic to create the view for the edit categoryscreen
	 *
	 * @access public
	 * @return void
	 * @since 0.9
	 */
	function edit( )
	{
		JRequest::setVar( 'view', 'video' );
		JRequest::setVar( 'hidemainmenu', 1 );

		$model 	= $this->getModel('video');
		$user	=& JFactory::getUser();

		parent::display();
	}

	function remote( )
	{
		JRequest::setVar( 'view', 'addvideo' );
		JRequest::setVar( 'hidemainmenu', 1 );

		//$model 	= $this->getModel('video');
		$user	=& JFactory::getUser();

		parent::display();
	}

	function batch( )
	{
		JRequest::setVar( 'view', 'uploadbatch' );
		JRequest::setVar( 'hidemainmenu', 1 );

		//$model 	= $this->getModel('video');
		$user	=& JFactory::getUser();

		parent::display();
	}

}