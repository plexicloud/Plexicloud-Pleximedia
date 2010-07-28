<?php

// no direct access

define( '_JEXEC', 1 );

define( 'JPATH_BASE', realpath(dirname(__FILE__).'/../..' ));

define( 'DS', DIRECTORY_SEPARATOR );



require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );

require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );



require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_jomtube'.DS.'configs'.DS.'configs.jomtube.php');

require_once(JPATH_SITE.DS.'modules'.DS.'mod_jomtube_tabs'.DS.'helper.php');

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



$mainframe 	=& JFactory::getApplication('site');

$mainframe->initialise();



$task = JRequest::getVar('task', '');



switch ($task) {

    case 'aio':

        return aio();

}



function aio() {

    $act = JRequest::getVar('act', '');

    $numVideos = JRequest::getVar('numVideos', 0);

    $thumbnail_size = JRequest::getVar('thumbnail_size', '');

    $columns_per_row = JRequest::getVar('columns_per_row', '');

	$margin_left_aio = JRequest::getVar('marginleftvid', 0);

    //video description parameters

    $show_videotitle = JRequest::getVar('show_videotitle', 0);

    $show_dateadded = JRequest::getVar('show_dateadded', 0);

    $show_author = JRequest::getVar('show_author', 0);

    $show_numberviews = JRequest::getVar('show_numberviews', 0);

    $show_ratingstar = JRequest::getVar('show_ratingstar', 0);

    $show_videoduration = JRequest::getVar('show_videoduration', 0);



    $database	=& JFactory::getDBO();



    $order = '';

    $where = ' WHERE v.published = 1 ';



    switch ($act) {

        case 'lastest':

            $order = 'v.date_added DESC';

            break;

        case 'viewed':

            $order = 'v.hits DESC';

            break;

        case 'rated':

            $order = 'v.votetotal DESC';

            break;

        case 'featured':

            $where .=   ' AND v.featured = 1';

            $order = 'v.date_added DESC';

            break;

        default:

            var_dump($act);

            break;

    }//switch



    $select = 'SELECT v.*, c.directory, u.username, c.category_name, u.id as userid '

    . ' FROM #__jomtube_videos AS v '

    . ' LEFT JOIN #__users AS u ON v.user_id = u.id'

    . ' JOIN #__jomtube_categories AS c ON c.id = v.category_id';

    $query = $select . $where . ' ORDER BY ' . $order . ' LIMIT ' . $numVideos;



    $count_query = 'SELECT COUNT(*) FROM #__jomtube_videos AS v' . $where;

    $database->setQuery($query);

    $items = $database->loadObjectList();

	$Itemid = JomtubeTabsHelper::generateJomTubeItemid();

    $count = count($items);

    for($i = 0; $i < $count; $i++)

    {

        $query = 'SELECT AVG(rating) FROM #__jomtube_rating WHERE v_id = ' . $items[$i]->id;

        $database->setQuery($query);

        $items[$i]->ratingAvg = $database->loadResult();

    }



    ?>



  <div id="video_grid-jt-tabs-module" class="grid-view-jt-tabs-module">

	<div id="browse-video-data-jt-tabs-module">

	<?php

	$itemcount = 0;

	if ($items) {

	foreach($items as $item)

	{



	    $itemcount++;

		?>

<!--## AMOUNT OF COLUMNS FIGURED OUT BY WIDTH PERCENT ##-->

		<div class="video-cell-jt-tabs-module" style="width:<?php echo $columns_per_row?>%">

			<div class="video-entry-jt-tabs-module" style="<?php if ($thumbnail_size=='small') echo "width:92px;"; elseif ($thumbnail_size=='medium') echo "width:290px;"; elseif ($thumbnail_size=='large') echo "width:154px;";?>">

				<div class="v120WideEntry-jt-tabs-module">

					<div class="<?php if ($thumbnail_size=='small') echo "v90WrapperOuter-jt-tabs-module"; elseif ($thumbnail_size=='medium') echo "v120WrapperOuter-jt-tabs-module"; elseif ($thumbnail_size=='large') echo "v150WrapperOuter-jt-tabs-module";?>" style="position: relative;">

						<div class="<?php if ($thumbnail_size=='small') echo "v90WrapperInner-jt-tabs-module"; elseif ($thumbnail_size=='medium') echo "v120WrapperInner-jt-tabs-module"; elseif ($thumbnail_size=='large') echo "v150WrapperInner-jt-tabs-module";?>" style="position: static;">

						   <?php if ($item->video_type == 'local' || $item->video_type == ''):?>
						   <?php 

							$newLink = JRoute::_("index.php?option=com_jomtube&view=video&id=".$item->id."&Itemid=".$Itemid);
							$newLink10 =JRoute::_("index.php?option=com_jomtube&view=video&id=$item->id");
							$patterns[0] = '/\/modules\//';
							$patterns[1] = '/mod_jomtube_tabs\//';
							$replacements[0] = '';
							$replacements[1] = '';
							$parsedLink = preg_replace($patterns, $replacements, $newLink10);
							
							?>								
  					   			<a href="/<?php echo $parsedLink; ?>">

							        <img class="<?php if ($thumbnail_size=='small') echo "vimg90-jt-tabs-module"; elseif ($thumbnail_size=='medium') echo "vimg120-jt-tabs-module"; elseif ($thumbnail_size=='large') echo "vimg150-jt-tabs-module";?>" src="<?php echo JomtubeTabsHelper::showSrcLocalThumbnailAjax($item->directory."/_thumbs/".$item->video_thumb); ?>" border="0" />

								 </a>

							<?php else: ?>
						    <?php 

							$newLink = JRoute::_("index.php?option=com_jomtube&view=video&id=".$item->id."&Itemid=".$Itemid);
							$newLink10 =JRoute::_("index.php?option=com_jomtube&view=video&id=$item->id");
							$patterns[0] = '/\/modules\//';
							$patterns[1] = '/mod_jomtube_tabs\//';
							$replacements[0] = '';
							$replacements[1] = '';
							$parsedLink = preg_replace($patterns, $replacements, $newLink10);
							
							?>
							   <a href="/<?php echo $parsedLink; ?>">

							        <img class="<?php if ($thumbnail_size=='small') echo "vimg90-jt-tabs-module"; elseif ($thumbnail_size=='medium') echo "vimg120-jt-tabs-module"; elseif ($thumbnail_size=='large') echo "vimg150-jt-tabs-module";?>" src="<?php echo $item->video_thumb; ?>" border="0" />

								 </a>

							 <?php endif;?>

							<!--## VIDEO TIME##-->

							<?php if ($show_videoduration):?>

							     <?php if (trim($item->duration) != ""):?>

								<div class="video-time-videolist-jt-tabs-module" style="<?php if ($thumbnail_size=='small') echo "margin-top:-61px;"; elseif ($thumbnail_size=='medium') echo "margin-top:-81px;"; elseif ($thumbnail_size=='large') echo "margin-top:-101px;";?>">

								<span><?php echo $item->duration?></span>

								</div>

								<?php endif;?>

							<?php endif;?>

						</div>

					</div>


					<!-- ## Video metadata -->
					<div id="video-metadata">
					
					<!--## VIDEO TITLE ##-->

					<?php if ($show_videotitle):?>

					<div id="video-title-jt-tabs-module">
				    <?php 

					$newLink2 = JRoute::_("index.php?option=com_jomtube&view=video&id=".$item->id."&Itemid=".$Itemid);
					$newLink10 =JRoute::_("index.php?option=com_jomtube&view=video&id=$item->id");
					$patterns[0] = '/\/modules\//';
					$patterns[1] = '/mod_jomtube_tabs\//';
					$replacements[0] = '';
					$replacements[1] = '';
					$parsedLink2 = preg_replace($patterns, $replacements, $newLink10);
					
					?>
						<a href="/<?php echo $parsedLink2; ?>" title="<?php echo $item->video_title; ?>">

					        <?php echo stripslashes($item->video_title);?>

						</a>

					</div>

					<?php endif;?>

					<!--## VIDEO DATE ADDED ##-->

					<?php if ($show_dateadded):?>

					<div id="video-added-jt-tabs-module">

						<?php echo _VIDEOINFO_DATE_ADDED ?>: <?php echo $item->date_added?>

					</div>

					<?php endif;?>

					<!--## AUTHOR OF VIDEO ##-->

					<?php if ($show_author):?>

					<div id="video-from-jt-tabs-module">
				    <?php 

				    //this is currently broken?? need to look at filter by user later
					//$newLink3 = JRoute::_('index.php?option=com_jomtube&view=videos&type=member&user_id=' . $item->user_id."&Itemid=".$Itemid);
					$newLink3 = JRoute::_("index.php?option=com_community&view=profile&userid=".$item->user_id);
					//echo $newLink3;
					$patterns[0] = '/\/modules\//';
					$patterns[1] = '/mod_jomtube_tabs\//';
					$replacements[0] = '';
					$replacements[1] = '';
					$parsedLink3 = preg_replace($patterns, $replacements, $newLink3);
					//echo $parsedLink3;
					//$parsedLink3 = '/community/profile?userid='. $item->user_id;
					//$parsedLink3 = preg_replace($patterns, $replacements, $newLink10);
					
					?>
						<?php echo _VIDEOINFO_AUTHOR ?>:<span class="video-username-jt-tabs-module"> <a href="<?php echo DS.$parsedLink3; ?>"><?php echo jomtube::showShortAuthor($item->username)?></a></span>

					</div>

					<?php endif;?>

					<!--## VIDEO HITS COUNTER ##-->

					<?php if ($show_numberviews):?>

					<div id="video-view-count-jt-tabs-module">

						<?php echo _VIDEOINFO_NUMBER_VIEWS ?>: <?php echo $item->hits?>

					</div>

					<?php endif;?>

					<!--## STARS RATING ##-->

					<?php if ($show_ratingstar):?>

					<div class="video-thumb-duration-rating-jt-tabs-module">

						 <div style="float:left">

	  						<?php

	  						$root = substr(JURI::root( true ), 0, strlen(JURI::root( true ))-24);

	  						$emptyStar = $root . "components/com_jomtube/assets/images/emptyStar12x12.gif";

	  						$halfStar = $root . "components/com_jomtube/assets/images/halfStar12x12.gif";

	  						$fullStar = $root . "components/com_jomtube/assets/images/fullStar12x12.gif";

	  						if ($item->ratingAvg > 0) {

	  						    for ($i_rating = 1; $i_rating <= 5; $i_rating++) {

	  						        $star = floor($item->ratingAvg) >= $i_rating ? $fullStar : (round($item->ratingAvg) == $i_rating ? $halfStar : $emptyStar);

	  						        echo "<img src=\"".$star."\" height=\"12\" width=\"12\" "

	  						        ."alt=\"Rated ".round($item->ratingAvg, 1)." of 5\" "

	  						        ."title=\"Rated ".round($item->ratingAvg, 1)." of 5\" />";

	  						    }

	  						}

	  						else

	  						{

	  						    for ($i_rating = 1; $i_rating <= 5; $i_rating++) {

	  						        echo "<img src=\"".$emptyStar."\" height=\"12\" width=\"12\" "

	  						        ."alt=\"Rated 0 of 5\" "

	  						        ."title=\"Rated 0 of 5\" />";

	  						    }

	  						}

	  						?>

	  			   	  	</div>

					</div>

					<?php endif;?>
					
					</div>
					<!-- ## Video Metadata ## -->
					
				</div>

			</div>

		</div>

	<?php

	}

	}

	if ($itemcount == 0){

	    echo "<p>We don't have any videos in this section yet. ";

	}

	?>

	</div>

</div>



<?php

}

?>