<?php
/**
 * @version 0.9 $Id: categories.php 545 2008-02-08 13:19:06Z schlu $
 * @package Joomla
 * @subpackage jomtube
 * @copyright (C) 2008 - 2010 Christoph Lukes
 * @license GNU/GPL, see LICENSE.php
 * EventList is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.

 * jomtube is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with EventList; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

/**
 * EventList Component Categories Controller
 *
 * @package Joomla
 * @subpackage jomtube
 * @since 0.9
 */
class jomtubeControllerCategories extends jomtubeController
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
		$this->registerTask( 'add'  ,		 	'edit' );
		$this->registerTask( 'apply', 			'save' );
		$this->registerTask( 'accesspublic', 	'access' );
		$this->registerTask( 'accessregistered','access' );
		$this->registerTask( 'accessspecial', 	'access' );
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
		
		$model = $this->getModel('category');

		if ($returnid = $model->store($post)) {

			switch ($task)
			{
				case 'apply' :
					$link = 'index.php?option=com_jomtube&view=category&cid[]='.$returnid;
					break;

				default :
					$link = 'index.php?option=com_jomtube&view=categories';
					break;
			}
			$msg = JText::_( 'CATEGORY SAVED' );

			$cache = &JFactory::getCache('com_jomtube');
			$cache->clean();

		} else {

			$msg 	= '';
			$link 	= 'index.php?option=com_jomtube&view=category';
		}

		$model->checkin();

		$this->setRedirect($link, $msg);
	}

	/**
	 * Logic to publish categories
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

		$model = $this->getModel('categories');

		if(!$model->publish($cid, 1)) {
			echo "<script> alert('".$model->getError()."'); window.history.go(-1); </script>\n";
		}

		$total = count( $cid );
		$msg 	= $total.' '.JText::_( 'CATEGORY PUBLISHED');

		$this->setRedirect( 'index.php?option=com_eventlist&view=categories', $msg );
	}

	/**
	 * Logic to unpublish categories
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

		$model = $this->getModel('categories');

		if(!$model->publish($cid, 0)) {
			echo "<script> alert('".$model->getError()."'); window.history.go(-1); </script>\n";
		}

		$total = count( $cid );
		$msg 	= $total.' '.JText::_( 'CATEGORY UNPUBLISHED');

		$this->setRedirect( 'index.php?option=com_eventlist&view=categories', $msg );
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

		$cid		= JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to delete' ) );
		}

		$model = $this->getModel('categories');

		$msg = $model->delete($cid);

		$cache = &JFactory::getCache('com_eventlist');
		$cache->clean();

		$this->setRedirect( 'index.php?option='. $option .'&view=categories', $msg );
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
//		$category = & JTable::getInstance('eventlist_categories', '');
//		$category->bind(JRequest::get('post'));
//		$category->checkin();

		$this->setRedirect( 'index.php?option=com_jomtube&view=categories' );
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
		JRequest::setVar( 'view', 'category' );
		JRequest::setVar( 'hidemainmenu', 1 );

		$model 	= $this->getModel('category');
		$user	=& JFactory::getUser();
		
		parent::display();
	}
}