<?php defined('_JEXEC') or die('Restricted access'); // no direct access ?>
<div id="video_grid-jtmodule" class="grid-view-jtmodule">
	<div id="browse-video-data-jtmodule" style="padding-left:<?php echo $margin_left_aio?>px">
	<?php
	$itemcount = 0;
	foreach($items as $item)
	{

    $itemcount++;
		?>
<!--## AMOUNT OF COLUMNS FIGURED OUT BY WIDTH PERCENT ##-->
		<div class="video-cell-jtmodule" style="width:<?php echo $columns_per_row?>%">
			<div class="video-entry-jtmodule" style="<?php if ($thumbnail_size=='small') echo "width:92px;"; elseif ($thumbnail_size=='medium') echo "width:124px;"; elseif ($thumbnail_size=='large') echo "width:154px;";?>">
				<div class="v120WideEntry-jtmodule">
					<div class="<?php if ($thumbnail_size=='small') echo "v90WrapperOuter-jtmodule"; elseif ($thumbnail_size=='medium') echo "v120WrapperOuter-jtmodule"; elseif ($thumbnail_size=='large') echo "v150WrapperOuter-jtmodule";?>" style="position: relative;">
						<div class="<?php if ($thumbnail_size=='small') echo "v90WrapperInner-jtmodule"; elseif ($thumbnail_size=='medium') echo "v120WrapperInner-jtmodule"; elseif ($thumbnail_size=='large') echo "v150WrapperInner-jtmodule";?>" style="position: static;">
						   <?php if ($item->video_type == 'local' || $item->video_type == ''):?>
  					   			<a href="<?php echo JRoute::_("index.php?option=com_jomtube&view=video&id=$item->id&Itemid=$Itemid")?>">
							        <img class="<?php if ($thumbnail_size=='small') echo "vimg90-jtmodule"; elseif ($thumbnail_size=='medium') echo "vimg120-jtmodule"; elseif ($thumbnail_size=='large') echo "vimg150-jtmodule";?>" src="<?php echo JomtubeAIOHelper::showSrcLocalThumbnail($item->directory."/_thumbs/".$item->video_thumb); ?>" border="0" />
								 </a>
							 <?php else:?>
							   <a href="<?php echo JRoute::_("index.php?option=com_jomtube&view=video&id=$item->id&Itemid=$Itemid")?>">
							        <img class="<?php if ($thumbnail_size=='small') echo "vimg90-jtmodule"; elseif ($thumbnail_size=='medium') echo "vimg120-jtmodule"; elseif ($thumbnail_size=='large') echo "vimg150-jtmodule";?>" src="<?php echo $item->video_thumb; ?>" border="0" />
								 </a>
							 <?php endif;?>
							<!--## VIDEO TIME##-->
							<?php if ($show_videoduration):?>
							     <?php if (trim($item->duration) != ""):?>
								<div class="video-time-videolist-jtmodule" style="<?php if ($thumbnail_size=='small') echo "margin-top:-61px;"; elseif ($thumbnail_size=='medium') echo "margin-top:-81px;"; elseif ($thumbnail_size=='large') echo "margin-top:-101px;";?>">
								<span><?php echo $item->duration?></span>
								</div>
								<?php endif;?>
							<?php endif;?>
						</div>
					</div>
					<!--## VIDEO TITLE ##-->
					<?php if ($show_videotitle):?>
					<div id="video-title-jtmodule">
						<a <?php if ($thumbnail_size=='small') echo "style=\"font-size:90%\"";?> href="<?php echo JRoute::_("index.php?option=com_jomtube&view=video&id=$item->id")?>" title="<?php echo $item->video_title; ?>">
					        <?php echo stripslashes($item->video_title);?>
						</a>
					</div>
					<?php endif;?>
					<!--## VIDEO DATE ADDED ##-->
					<?php if ($show_dateadded):?>
					<div id="video-added-jtmodule" <?php if ($thumbnail_size=='small') echo "style=\"font-size:80%\"";?>>
						<?php echo _VIDEOINFO_DATE_ADDED ?>: <?php echo $item->date_added?>
					</div>
					<?php endif;?>
					<!--## AUTHOR OF VIDEO ##-->
					<?php if ($show_author):?>
					<div id="video-from-jtmodule" <?php if ($thumbnail_size=='small') echo "style=\"font-size:80%\"";?>>
						<?php echo _VIDEOINFO_AUTHOR ?>:<span class="video-username-jtmodule"> <a href="<?php echo JRoute::_("index.php?option=com_jomtube&view=videos&type=member&user_id=" . $item->user_id) ?>"><?php echo jomtube::showShortAuthor($item->username)?></a></span>
					</div>
					<?php endif;?>
					<!--## VIDEO HITS COUNTER ##-->
					<?php if ($show_numberviews):?>
					<div id="video-view-count-jtmodule" <?php if ($thumbnail_size=='small') echo "style=\"font-size:80%\"";?>>
						<?php echo _VIDEOINFO_NUMBER_VIEWS ?>: <?php echo $item->hits?>
					</div>
					<?php endif;?>
					<!--## STARS RATING ##-->
					<?php if ($show_ratingstar):?>
					<div id="video-rating-jtmodule">
						 <div class="rating-jtmodule">
	  						<?php
		  						$emptyStar = JURI::base() . "components/com_jomtube/assets/images/emptyStar12x12.gif";
		  						$halfStar = JURI::base() . "components/com_jomtube/assets/images/halfStar12x12.gif";
		  						$fullStar = JURI::base() . "components/com_jomtube/assets/images/fullStar12x12.gif";
			  						if ($item->ratingAvg > 0) {
			  						    for ($i_rating = 1; $i_rating <= 5; $i_rating++) {
			  						        $star = floor($item->ratingAvg) >= $i_rating ? $fullStar : (round($item->ratingAvg) == $i_rating ? $halfStar : $emptyStar);
			  						        echo "<img class=\"starsize-jtmodule120\" src=\"".$star."\" "
			  						        ."alt=\"Rated ".round($item->ratingAvg, 1)." of 5\" "
			  						        ."title=\"Rated ".round($item->ratingAvg, 1)." of 5\" />";
			  						    }
			  						}
			  						else
			  						{
			  						    for ($i_rating = 1; $i_rating <= 5; $i_rating++) {
			  						        echo "<img class=\"starsize-jtmodule120\" src=\"".$emptyStar."\" "
			  						        ."alt=\"Rated 0 of 5\" "
			  						        ."title=\"Rated 0 of 5\" />";
			  						    }
			  						}
	  						?>
	  			   	  	</div>
					</div>
					<?php endif;?>
				</div>
			</div>
		</div>
	<?php
}
if ($itemcount == 0){
    echo "<p>We don't have any videos in this section yet. ";
}
	?>
	</div>
</div>
<div style="clear:both"></div>