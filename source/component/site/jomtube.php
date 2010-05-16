<?php
// no direct access

defined( '_JEXEC' ) or die( 'Restricted access' );

// Require the base controller

require_once( JPATH_COMPONENT.DS.'controller.php' );

// Require specific controller if requested
if($controller = JRequest::getWord('controller')) {
    $path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
    if (file_exists($path)) {
        require_once $path;
    } else {
        $controller = '';
    }
}

// Include the jomtube Library
require_once(JPATH_SITE.DS.'components'.DS.'com_jomtube'.DS.'assets'.DS.'lib'.DS.'classes'.DS.'comments.class.php');
require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jomtube'.DS.'includes'.DS.'jomtube_remotevideo.php');
require_once (JPATH_COMPONENT.DS.'assets'.DS.'lib'.DS.'mix.php');

// Include the jomtube configs file
require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jomtube'.DS.'configs'.DS.'configs.jomtube.php');

//Require helperfile
require_once (JPATH_COMPONENT_SITE.DS.'helpers'.DS.'helpers.php');
require_once (JPATH_COMPONENT_SITE.DS.'helpers'.DS.'toolsHelpers.php');
require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jomtube'.DS.'helpers'.DS.'JTubePluginHelper.php');

$document	= & JFactory::getDocument();
//$document->addScript("components/com_jomtube/assets/js/mootools-release-1.11.js");
$document->addScript("components/com_jomtube/assets/js/jomtube.js");
$document->addScript("http://ajax.googleapis.com/ajax/libs/swfobject/2.1/swfobject.js");

//Include JomTube Style
$c = jomtube_configs::get_instance();
$document->addStyleSheet("components/com_jomtube/assets/styles/$c->jtube_skin/css/template.css");

//upload progress
//$document->addScript("components/com_jomtube/assets/js/swfobject.js");
require_once (JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jomtube'. DS . 'assets' . DS . 'lib' . DS . 'FlashUploader' . DS . 'SolmetraUploader.php');

//check permission
jomtube::checkPermission("acl_component");

// include language file
$lang_file = JPATH_COMPONENT . DS . 'languages' . DS . $c->jtube_language . '.php';
$lang_default_file = JPATH_COMPONENT . DS . 'languages' . DS . 'english.php';
if (file_exists($lang_file)) {
    require_once($lang_file);
} else {
    require_once($lang_default_file);
}

// Create the controller
$classname    = 'jomtubeController'.$controller;
$controller   = new $classname( );

// Perform the Request task
$controller->execute( JRequest::getVar( 'task' ));

// Redirect if set by the controller
$controller->redirect();
?>
