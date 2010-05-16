<?php
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

require_once(dirname(__FILE__).DS.'helper.php');

$jomtube_configs_file = JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_jomtube'.DS.'configs'.DS.'configs.jomtube.php';
if (!file_exists($jomtube_configs_file)) {
    $jomtube_existed = false;
} else {
    $jomtube_existed = true;
    require_once($jomtube_configs_file);
    require_once(JPATH_SITE.DS.'components'.DS.'com_jomtube'.DS.'helpers'.DS.'helpers.php');
    require_once (JPATH_SITE.DS.'components'.DS.'com_jomtube'.DS.'assets'.DS.'lib'.DS.'mix.php');
    $c = jomtube_configs::get_instance();

    // include language file
    $lang_file = JPATH_SITE.DS.DS.'components'.DS.'com_jomtube' . DS . 'languages' . DS . $c->jtube_language . '.php';
    $lang_default_file = JPATH_SITE.DS.'components'.DS.'com_jomtube' . DS . 'languages' . DS . 'english.php';
    if (file_exists($lang_file)) {
        require_once($lang_file);
    } else {
        require_once($lang_default_file);
    }

    $document = & JFactory::getDocument();


//	if ($thumbnail_size=='small') {$document->addStyleSheet("modules/mod_jomtube_aio/assets/css/jomtube_aio90.css");}
//  if ($thumbnail_size=='medium') {$document->addStyleSheet("modules/mod_jomtube_aio/assets/css/jomtube_aio120.css");}

$document->addStyleSheet("modules/mod_jomtube_aio/assets/css/jomtube_aio120.css");

	

   	$thumbnail_size = $params->get('thumbnail_size');
	$margin_left_aio = $params->get('marginleftvid');
    $displaymode = $params->get('displaymode');
    $numVideos = $params->get('numvideos');
    $columns_per_row = $params->get('columns_per_row');
    $show_videotitle = $params->get('show_videotitle');
    $show_dateadded = $params->get('show_dateadded');
    $show_author = $params->get('show_author');
    $show_numberviews = $params->get('show_numberviews');
    $show_ratingstar = $params->get('show_ratingstar');
	$show_videoduration = $params->get('show_videoduration');



    $where=$params->get('getCat');
    $items = JomtubeAIOHelper::getItems($displaymode,$numVideos,$where);
    $Itemid = JomtubeAIOHelper::generateJomTubeItemid();
}

require(JModuleHelper::getLayoutPath('mod_jomtube_aio'));

?>