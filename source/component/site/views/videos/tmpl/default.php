<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>


<div id="videomainbody">


	<!--############ VIDEO LEFT COLUMN ###########-->
    <?php JTHelper::loadCustomVerticalModule('jomtube_vidlist_left', $this->c->width_jomtube_left_module, 'left')?>

    <!--############ VIDEO GRID MIDDLE COLUMN ###########-->

	<div id="videomiddle-column" style="width:<?php echo $this->c->width_jomtube_center_module?>px">
		<div id="browseMain">

			<form action="<?php echo JRoute::_( 'index.php' );?>" method="get" name="jomtubeForm" id="jomtubeForm" >
			<input type="hidden" name="option" value="com_jomtube" />
			<input type="hidden" name="view" value="videos" />
			<input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
			</form>

			<!--####################################################-->
			<!--############ YOUTUBE STYLE SUBMENU START ###########-->
			<!--####################################################-->
			<?php
			     $category_id = JRequest::getVar('category_id', '');
			     $order = JRequest::getVar('order', '');
			     $featured = JRequest::getVar('featured', '');
		     ?>
			<?php if ($category_id == ''):?>
				<div id="videoModifiers">
				  <div class="subcategory first">
				    <?php $link=JRoute::_("index.php?view=videos");
				    echo "<a href=\"".$link."\" title=\"" . _SUBTABS_LASTEST . "\"><span " . ($order == '' && $featured != 1 ? "class='selected'" : '') . ">" . _SUBTABS_LASTEST . "</span></a>";
				    ?>
				  </div>
				  <div class="subcategory">
				    <?php $link=JRoute::_("index.php?view=videos&order=hits");
				    echo "<a href=\"".$link."\" title=\"" . _SUBTABS_MOST_VIEWED . "\"><span " . ($order == 'hits' ? "class='selected'" : '') . " >" . _SUBTABS_MOST_VIEWED . "</span></a>";
				    ?>
				  </div>
				  <div class="subcategory">
				    <?php $link=JRoute::_("index.php?view=videos&order=votetotal");
				    echo "<a href=\"".$link."\" title=\"" . _SUBTABS_HIGHEST_RATED . "\"><span " . ($order == 'votetotal' ? "class='selected'" : '') . " >" . _SUBTABS_HIGHEST_RATED . "</span></a>";
				    ?>
				  </div>
				  <div class="subcategory">
				    <?php $link=JRoute::_("index.php?view=videos&featured=1");
				    echo "<a href=\"".$link."\" title=\"" . _SUBTABS_FEATURED_VIDEOS . "\"><span " . ($featured == 1 ? "class='selected'" : '') . ">" . _SUBTABS_FEATURED_VIDEOS . "</span></a>";
				    ?>
				  </div>
				</div>
				<?php endif;?>
			<!--###################################################-->
			<!--############ YOUTUBE STYLE SUBMENU END ############-->
			<!--###################################################-->

				  <!--Breakcrumb-->
				  <?php if ($category_id != ''):?>
				   <div style="padding-top: 10px;">
				    <a href="index.php?option=com_jomtube&view=videos">Main</a>
				    <?php foreach ($this->breadcrum as $cat):?>
				      <img src="components/com_jomtube/assets/images/arrow.png">
					   <a href="<?php echo JRoute::_("index.php?view=videos&category_id=$cat->id") ?>"><?php echo $cat->category_name?></a>
				    <?php endforeach;?>
				  </div>
				  <?php endif;?>

			<!--#######################################################-->
			<!--############ YOUTUBE PLAYER IN MAIN PAGE ############-->
			<!--#######################################################-->
			<?php if ($this->c->show_player_in_mainpage):?>
			<div class="vl_player">
			    <div class="vl_player_title">
			        <?php echo $this->c->video_play_in_mainpage?> - <?php echo $this->row->video_title?>
			    </div>
			    <?php require_once($this->playerpath)?>
			</div>
			<?php endif;?>


			<!--#######################################################-->
			<!--############ YOUTUBE STYLE VIDEOLIST START ############-->
			<!--#######################################################-->
			<div id="video_grid" class="grid-view">
			<div id="browse-video-data">

				<?php
				$itemcount = 0;
				foreach($this->items as $item)
				{

				    $itemcount++;
					?>
			<!--## AMOUNT OF COLUMNS FIGURED OUT BY WIDTH PERCENT ##-->
						<div class="video-cell" style="width:<?php echo $this->c->columns_per_page?>%">
							<div class="video-entry">
								<div class="v120WideEntry">
	    							<div class="v120WrapperOuter" style="position: relative;">
	        							<div class="v120WrapperInner" style="position: static;">
	        							   <?php if ($item->video_type == 'local' || $item->video_type == ''):?>
	              					   			<a href="<?php echo JRoute::_("index.php?view=video&id=$item->id")?>">
                                                    <?php JTHelper::showLocalThumbnail($item->directory."/_thumbs/".$item->video_thumb); ?>
	    	    								 </a>
	  	    								 <?php else:?>
	  	    								   <a href="<?php echo JRoute::_("index.php?view=video&id=$item->id")?>">
	    									        <img class="vimg120" src="<?php echo $item->video_thumb; ?>" border="0" />
	    	    								 </a>
	  	    								 <?php endif;?>
											<div class="video-time-videolist">
											<span><?php echo $item->duration?></span>
											</div>
	        							</div>
	    							</div>
								</div>
	    						<div class="video-main-content-cat">
									<div class="video-title">
		       							 <div class="video-short-title">
		            							<a href="<?php echo JRoute::_("index.php?view=video&id=$item->id")?>" title="<?php echo $item->video_title; ?>">
		  								        <?php echo stripslashes($item->video_title);?>
		  	   							</a>
		        						</div>
	    							</div>
			 					</div>
								<div class="video-facets">
			        					<div class="vladded">
			            						<?php echo _VIDEOINFO_DATE_ADDED ?>: <?php echo $item->date_added?>
			       						 </div>
			        					<div>
			            						<?php echo _VIDEOINFO_AUTHOR ?>:<span class="video-username"> <a href="<?php echo JRoute::_("index.php?view=videos&type=member&user_id=" . $item->user_id) ?>"><?php echo jomtube::showShortAuthor($item->username)?></a></span>
			        					</div>
			        					<div class="video-view-count"/>
			            						<?php echo _VIDEOINFO_NUMBER_VIEWS ?>:</span> <?php echo $item->hits?><br/>
			        					</div>
			        					<div class="video-thumb-duration-rating">
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
								</div>
							</div>
						</div>
				<?php
				}
				if ($itemcount == 0){
				   // echo "<p>We don't have any videos in this section yet. ";
				}
				?>
			</div>
			</div>
			<!--#####################################################-->
			<!--############ YOUTUBE STYLE VIDEOLIST END ############-->
			<!--#####################################################-->

			<!--########################################################-->
			<!--############ YOUTUBE STYLE PAGINATION START ############-->
			<!--########################################################-->
			<?php
			echo "<div id=\"pagination_container\">";
			echo "<div id=\"video_pagination\">";

			if ($itemcount > 0)
			{
			    echo "<div align=\"center\" style=\"padding-top: 10px;\">";
			    echo $this->pageNav->getPagesLinks();
			    echo "<div id=\"totalvideos\">Total Videos In Category (" . $this->total . ")</div>";
			    echo "</div>";
			}

			echo "</div>";
			echo "</div>";
			?>
			<!--######################################################-->
			<!--############ YOUTUBE STYLE PAGINATION END ############-->
			<!--######################################################-->
			</div>

	</div>

	<!--############ VIDEO RIGHT COLUMN ###########-->
	<?php JTHelper::loadCustomVerticalModule('jomtube_vidlist_right', $this->c->width_jomtube_right_module, 'right')?>
</div>