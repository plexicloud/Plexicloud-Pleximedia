<?php defined('_JEXEC') or die('Restricted access'); // no direct access ?>
<?php if (!$jomtube_existed):?>
    <b>Please install JomTube component first!</b>
<?php else:?>
<!-- ###################### AJAX TABS START ################-->

	<script type="text/javascript">
	var live_site = '<?php echo JURI::base() ?>';
	var params = '&margin_left_aio=<?php echo $margin_left_aio?>&columns_per_row=<?php echo $columns_per_row?>&show_videotitle=<?php echo $show_videotitle?>&show_dateadded=<?php echo $show_dateadded?>&show_author=<?php echo $show_author?>&show_numberviews=<?php echo $show_numberviews?>&show_ratingstar=<?php echo $show_ratingstar?>&thumbnail_size=<?php echo $thumbnail_size?>&margin_left_aio=<?php echo $margin_left_aio?>&show_videoduration=<?php echo $show_videoduration?>';
	    <?php if ($show_featured_tab):?>
	window.addEvent('domready', function(){
	    $('featured').addEvent('click', function(e) {
	        e = new Event(e).stop();
	        $('featured').addClass('selected');
	        if ($('rated'))
                $('rated').removeClass('selected');
	        if ($('viewed'))
	           $('viewed').removeClass('selected');
	        if ($($('lastest')))
                $('lastest').removeClass('selected');
	        var timenow = Date();
	        var url = live_site + 'modules/mod_jomtube_tabs/ajax.jomtube.php?task=aio&act=featured&numVideos=<?php echo $numVideos;?>&timenow=' + timenow + params;
	        new Ajax(url, {method: 'get', update: $('tab-container-jt-tabs-module'), evalScripts: true}).request();
	    });
	});
    <?php endif;?>
    <?php if ($show_most_viewed_tab):?>
	window.addEvent('domready', function(){
	    $('viewed').addEvent('click', function(e) {
	        e = new Event(e).stop();
	        $('viewed').addClass('selected');
	        if ($($('lastest')))
                $('lastest').removeClass('selected');
            if ($('rated'))
                $('rated').removeClass('selected');
	        if ($('featured'))
                $('featured').removeClass('selected');
	        var timenow = Date();
	        var url = live_site + 'modules/mod_jomtube_tabs/ajax.jomtube.php?task=aio&act=viewed&numVideos=<?php echo $numVideos;?>&timenow=' + timenow + params;
	        new Ajax(url, {method: 'get', update: $('tab-container-jt-tabs-module'), evalScripts: true}).request();
	    });
	});
    <?php endif;?>
    <?php if ($show_highest_rated_tab):?>
	window.addEvent('domready', function(){
	    $('rated').addEvent('click', function(e) {
	        e = new Event(e).stop();
	        $('rated').addClass('selected');
	        if ($('viewed'))
                $('viewed').removeClass('selected');
	        if ($($('lastest')))
                $('lastest').removeClass('selected');
	        if ($('featured'))
                $('featured').removeClass('selected');
	        var timenow = Date();
	        var url = live_site + 'modules/mod_jomtube_tabs/ajax.jomtube.php?task=aio&act=rated&numVideos=<?php echo $numVideos;?>&timenow=' + timenow + params;
	        new Ajax(url, {method: 'get', update: $('tab-container-jt-tabs-module'), evalScripts: true}).request();
	    });
	});
    <?php endif;?>
	<?php if ($show_latest_tab):?>
	window.addEvent('domready', function(){
	    $('lastest').addEvent('click', function(e) {
	        e = new Event(e).stop();
	        $('lastest').addClass('selected');
	        if ($('viewed'))
	           $('viewed').removeClass('selected');
	        if ($('rated'))
                $('rated').removeClass('selected');
	        if ($('featured'))
                $('featured').removeClass('selected');
	        var timenow = Date();
	        var url = live_site + 'modules/mod_jomtube_tabs/ajax.jomtube.php?task=aio&act=lastest&numVideos=<?php echo $numVideos;?>&timenow=' + timenow + params;
	        new Ajax(url, {method: 'post', update: $('tab-container-jt-tabs-module'), evalScripts: true}).request();
	    });
	});
    <?php endif;?>
	</script>
	<div class="category-tabs-jt-tabs-module">
	   <?php $default_selected = 0;?>
	    <?php if ($show_featured_tab):?>
	    <?php $default_selected ++;?>
		<a id="featured" href="#" class="<?php echo $default_selected == 1 ? 'selected' : ''?>">Featured</a>
        <?php endif;?>
        <?php if ($show_most_viewed_tab):?>
        <?php $default_selected ++;?>
        <a id="viewed" href="#" class="<?php echo $default_selected == 1 ? 'selected' : ''?>">Most Viewed</a>
        <?php endif;?>
        <?php if ($show_highest_rated_tab):?>
        <?php $default_selected ++;?>
        <a id="rated" href="#" class="<?php echo $default_selected == 1 ? 'selected' : ''?>">Popular</a>
        <?php endif;?>
        <?php if ($show_latest_tab):?>
        <?php $default_selected ++;?>
        <a id="lastest" href="#" class="<?php echo $default_selected == 1 ? 'selected' : ''?>">Lastest</a>
        <?php endif;?>
    </div>

<div id="tab-container-jt-tabs-module">
<?php endif;?>
<!-- ###################### AJAX TABS END ################-->
<div id="video_grid-jt-tabs-module" class="grid-view-jt-tabs-module">
	<div id="browse-video-data-jt-tabs-module" style="padding-left:<?php echo $margin_left_aio?>px">
	<?php
	$itemcount = 0;

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
  					   			<a href="<?php echo JRoute::_("index.php?option=com_jomtube&view=video&id=$item->id") ?>">
							        <img class="<?php if ($thumbnail_size=='small') echo "vimg90-jt-tabs-module"; elseif ($thumbnail_size=='medium') echo "vimg120-jt-tabs-module"; elseif ($thumbnail_size=='large') echo "vimg150-jt-tabs-module";?>" src="<?php echo JomtubeTabsHelper::showSrcLocalThumbnail($item->directory."/_thumbs/".$item->video_thumb); ?>" border="0" />
								 </a>
							 <?php else:?>
							   <a href="<?php echo JRoute::_("index.php?option=com_jomtube&view=video&id=$item->id") ?>">
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
						<a href="<?php echo JRoute::_("index.php?option=com_jomtube&view=video&id=$item->id")?>" title="<?php echo $item->video_title; ?>">
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
					<?php $newLink3 = JRoute::_("index.php?option=com_community&view=profile&userid=".$item->user_id);
					//echo $newLink3;
					$patterns[0] = '/\/modules\//';
					$patterns[1] = '/mod_jomtube_tabs\//';
					$replacements[0] = '';
					$replacements[1] = '';
					$parsedLink3 = preg_replace($patterns, $replacements, $newLink3); ?>
					<div id="video-from-jt-tabs-module">
						<?php echo _VIDEOINFO_AUTHOR ?>:<span class="video-username-jt-tabs-module"> <a href="<?php echo JURI::BASE().$newLink3; ?>"><?php echo jomtube::showShortAuthor($item->username)?></a></span>
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
	  						$emptyStar = JURI::base() . "components/com_jomtube/assets/images/emptyStar12x12.gif";
	  						$halfStar = JURI::base() . "components/com_jomtube/assets/images/halfStar12x12.gif";
	  						$fullStar = JURI::base() . "components/com_jomtube/assets/images/fullStar12x12.gif";
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
if ($itemcount == 0){
    echo "<p>We don't have any videos in this section yet. ";
}
	?>
	</div>
</div>
</div>

<div style="clear:both"></div>