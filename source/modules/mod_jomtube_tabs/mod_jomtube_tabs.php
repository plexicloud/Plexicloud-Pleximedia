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
    $document->addStyleSheet("modules/mod_jomtube_tabs/assets/css/jomtube_aio_tabs.css");
    $document->addScript("media/system/js/mootools.js");

    $numVideos = $params->get('numvideos');
    $thumbnail_size = $params->get('thumbnail_size');
	$margin_left_aio = $params->get('marginleftvid');
    $columns_per_row = $params->get('columns_per_row');
    $show_videotitle = $params->get('show_videotitle');
    $show_dateadded = $params->get('show_dateadded');
    $show_author = $params->get('show_author');
    $show_numberviews = $params->get('show_numberviews');
    $show_ratingstar = $params->get('show_ratingstar');
    $show_videoduration = $params->get('show_videoduration');

    //all in one tabs parameters
    $displaymode = 'F';
	$show_featured_tab = $params->get('show_featured_tab', 0);
    $show_most_viewed_tab = $params->get('show_most_viewed_tab', 0);
    $show_highest_rated_tab = $params->get('show_highest_rated_tab', 0);
    $show_latest_tab = $params->get('show_latest_tab', 0);
    if ($show_featured_tab)
        $displaymode = 'F';
    else if ($show_most_viewed_tab)
        $displaymode = 'V';
    else if ($show_highest_rated_tab)
        $displaymode = 'R';
    else if ($show_latest_tab)
        $displaymode = 'L';

    $where=$params->get('getCat');
	//echo $displaymode;
    $items = JomtubeTabsHelper::getItems($displaymode,$numVideos,$where);
	//print_r($items);
    $Itemid = JomtubeTabsHelper::generateJomTubeItemid();
}

require(JModuleHelper::getLayoutPath('mod_jomtube_tabs'));

?>