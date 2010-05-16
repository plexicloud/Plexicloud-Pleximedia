<?php
/**
* @version		$Id: users.php 9764 2007-12-30 07:48:11Z ircmaxell $
* @package		Joomla
* @subpackage	Users
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/*
 * Make sure the user is authorized to view this page
 */
$user = & JFactory::getUser();

if (!$user->authorize( 'com_users', 'manage' )) {
	$mainframe->redirect( 'index.php', JText::_('ALERTNOTAUTH') );
}

$document	= & JFactory::getDocument();
$document->addStyleSheet('components/com_jomtube/assets/css/styles.css');
$document->addScript("components/com_jomtube/assets/js/jomtube.js");

// Include the jomtube Library
require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jomtube'.DS.'includes'.DS.'jomtube_remotevideo.php');

// Include the jomtube configs file
require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jomtube'.DS.'configs'.DS.'configs.jomtube.php');

//Require helperfile
require_once (JPATH_COMPONENT_SITE.DS.'helpers'.DS.'helpers.php');

// Require the base controller
require_once (JPATH_COMPONENT.DS.'controller.php');

// Require specific controller if requested
if( $controller = JRequest::getWord('controller') ) {
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if (file_exists($path)) {
		require_once $path;
	} else {
		$controller = '';
	}
}

//Create the controller
$classname  = 'jomtubeController'.$controller;
$controller = new $classname( );

// Perform the Request task
$controller->execute( JRequest::getWord('task'));
$controller->redirect();

