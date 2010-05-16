<?php
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

require_once(dirname(__FILE__).DS.'helper.php');

$document	= & JFactory::getDocument();
$document->addStyleSheet("modules/mod_jomtube_vertical/css/vertical_145.css");

$displaymode = $params->get('displaymode');
$numVideos = $params->get('numvideos');
$where=$params->get('getCat');

$items = AIOJomtubeHelper::getItems($displaymode,$numVideos,$where);

$morLink = '';
if ($displaymode=='L') {
    $morLink= JRoute::_("index.php?option=com_jomtube&view=videos");
    $strLink = "View More Videos";
}
else if ($displaymode=='V')
{
    $morLink= JRoute::_("index.php?option=com_jomtube&view=videos&order=hits");
    $strLink = "View More Videos";
}
else if ($displaymode=='R')
{
    $morLink= JRoute::_("index.php?option=com_jomtube&view=videos&order=votetotal");
    $strLink = "View More Videos";
}
else if ($displaymode=='F')
{
    $morLink= JRoute::_("index.php?option=com_jomtube&view=videos&featured=1");
    $strLink = "View More Videos";
}

require(JModuleHelper::getLayoutPath('mod_jomtube_vertical'));

?>