<?php
// no direct access
define( '_JEXEC', 1 );
define( 'JPATH_BASE', realpath(dirname(__FILE__).'/../..' ));
define( 'DS', DIRECTORY_SEPARATOR );

require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

// Include the jomtube configs file
require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jomtube'.DS.'configs'.DS.'configs.jomtube.php');
$c = jomtube_configs::get_instance();

//Require helperfile
require_once (JPATH_BASE . DS . 'components'.DS.'com_jomtube' . DS . 'helpers' . DS . 'helpers.php');
require_once (JPATH_BASE . DS . 'components'.DS.'com_jomtube' . DS . 'assets' . DS . 'lib' . DS . 'mix.php');

// include language file
$lang_file = JPATH_BASE . DS . 'components'.DS.'com_jomtube' . DS . 'languages' . DS . $c->jtube_language . '.php';
$lang_default_file = JPATH_BASE . DS . 'components'.DS.'com_jomtube' . DS . 'languages' . DS . 'english.php';
if (file_exists($lang_file)) {
    require_once($lang_file);
} else {
    require_once($lang_default_file);
}

$mainframe 	=& JFactory::getApplication('site');
$mainframe->initialise();

$task = JRequest::getVar('task', '');
switch ($task) {
    case 'aio':
        return aio();
}

function aio() {
    $act = JRequest::getVar('act', '');
    $database	=& JFactory::getDBO();
    switch ($act) {
        case 'category':
            $catid = JRequest::getVar('catid', 0);
            $where =   ' WHERE v.category_id = '.$catid;
            break;
        case 'member':
            $user_id = JRequest::getVar('userid', 0);
            $where =   ' WHERE v.user_id = '.$user_id;
            break;
        case 'newest':
            $catid = JRequest::getVar('catid', 0);
            $where =   ' ';
            break;
        case 'featured':
            $catid = JRequest::getVar('catid', 0);
            $where =   ' WHERE v.featured = 1';
            break;
        default:
            echo  "other";
            break;
    }//switch

    $select = 'SELECT v.*, c.directory, u.username, c.category_name, u.id as userid '
    . ' FROM #__jomtube_videos AS v '
    . ' LEFT JOIN #__users AS u ON v.user_id = u.id'
    . ' JOIN #__jomtube_categories AS c ON c.id = v.category_id';
    $query = $select . $where . ' AND v.published = 1 ORDER BY v.date_added DESC LIMIT 20';

    $count_query = 'SELECT COUNT(*) FROM #__jomtube_videos AS v' . $where;
    $database->setQuery($query);
    $videos = $database->loadObjectList();

    $database->setQuery($count_query);
    $count = $database->loadResult();

    ?>
    <?php foreach ($videos as $video):?>
						<div class="video-entry">
							<div class="v90WideEntry">
								<div class="v90WrapperOuter">
									<div class="v90WrapperInner">
									<?php if ($video->video_type == 'local' || $video->video_type == ''):?>
					   				<a href="index.php?option=com_jomtube&view=video&id=<?php echo $video->id; ?>" id="video-url-e8FGIveLwyw">
						        	<img alt="<?php echo $video->video_title?>" qlicon="e8FGIveLwyw" class="vimg90" src="<?php echo substr(JURI::base(), 0, strlen(JURI::base()) -24).$video->directory?>/_thumbs/<?php echo $video->video_thumb; ?>" border="0" />
							 		</a>
						 			<?php else:?>
						   			<a href="index.php?option=com_jomtube&view=video&id=<?php echo $video->id; ?>" id="video-url-e8FGIveLwyw">
						        	<img alt="<?php echo $video->video_title?>" qlicon="e8FGIveLwyw" class="vimg90" src="<?php echo $video->video_thumb; ?>" border="0" />
							 		</a>
						 			<?php endif;?>
										<div class="video-time"><span><?php echo $video->duration?></span>
										</div>
								</div>
							</div>
						</div>
						<div class="video-main-content">
							<div class="video-mini-title"><a href="index.php?option=com_jomtube&view=video&id=<?php echo $video->id; ?>" title="<?php echo $video->video_title?>"><?php echo stripslashes($video->video_title);?></a>
							</div>
							<div class="video-view-count"><?php echo $video->hits?> <?php echo strtolower(_VIDEOINFO_NUMBER_VIEWS) ?>
							</div>
							<div class="video-username"><?php echo _VIDEOINFO_AUTHOR ?>: <a href="index.php?option=com_jomtube&view=videos&type=member&user_id=<?php echo $video->user_id?>"><?php echo jomtube::showShortAuthor($video->username)?></a>
	  						</div>
						</div>
					</div>
					<div class="watch-discoverbox-divider"/>
					</div>
					<?php endforeach;?>
				  					<?php if ($count > 20):?>
				  					<center><a href="index.php?option=com_jomtube&view=videos&category_id=<?php echo $catid?>">See all <?php echo $count?> videos</a></center>
				  					<?php endif;?>

<?php
}
?>