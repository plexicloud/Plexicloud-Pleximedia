<?php
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

require_once(dirname(__FILE__).DS.'helper.php');

$document	= & JFactory::getDocument();
$document->addStyleSheet("modules/mod_jomtube_categories/css/categories.css");

//get mod_jomtube_categories parameters
$ordering = $params->get('order');
$jomtube_itemid = $params->get('jomtube_itemid', 0);
$style = $params->get('style');
$show_subs = $params->get('show_subs');
$show_morelink = $params->get('show_morelink');
$textalign = $params->get('textalign');

if (!$jomtube_itemid) {
    $jomtube_itemid = JomtubeModCategoriesHelper::generateJomTubeItemid();
}
$items = JomtubeModCategoriesHelper::getItems($ordering, $show_subs);

require(JModuleHelper::getLayoutPath('mod_jomtube_categories'));

?>
