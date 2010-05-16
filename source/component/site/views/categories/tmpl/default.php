<?php
defined('_JEXEC') or die('Restricted access'); ?>

<script>
function selectCategory(catid) {
    var link = "index.php?option=com_jomtube&task=categories&catid=" + catid.value;
    location.href = link;
}
</script>

<div id="videomainbody">

<!--############ LEFT COLUMN ###########-->
    <?php JTHelper::loadCustomVerticalModule('jomtube_category_left', $this->c->category_column_left_width, 'left')?>

<!--############ MIDDLE COLUMN ###########-->
    <div id="videomiddle-column" style="margin-right:15px; width:<?php echo $this->c->category_column_center_width?>px">
        <!------------- CATEGORY MAIN CONTAINER AND TITLE ---------------->
        <div id="category-video-container">
        	<div id="categorybox" style="width:<?php echo $this->c->category_column_center_width?>px">
        		<div id="categorytitle">
        			<?php echo _JTUBE_CATEGORIES_PAGE_TITLE ?>
        		</div>
        		<div id="categorylistbox">
        			<?php echo $this->categoryList ?>
        		</div>
        	</div>

        <!------------------- BREADCRUMB CONTAINER ----------------------->
        	<div id="breadcrumbcontainer">
        	    <a href="<?php echo JRoute::_("index.php?view=categories")?>"><?php echo _JTUBE_MAIN ?></a>
        	    <?php if ($this->breadcrum):?>
        	    	<?php foreach ($this->breadcrum as $cat):?>
        	    	<img src="components/com_jomtube/assets/images/arrow.png">
        			<a href="<?php echo JRoute::_("index.php?task=categories&catid=$cat->id")?>"><?php echo $cat->category_name?></a>
        	    	<?php endforeach;?>
        	    <?php endif;?>
        	</div>
        <div style="clear: both;"></div>
        <!--#######################################################-->
        <!--########## CATEGORIES LIST THUMBNAILS START ###########-->
        <!--#######################################################-->
        <div id="category-grid-container">
        <?php
        $k = 0;
        for ($i=0, $n=count($this->rows); $i < $n; $i++) {
            $row = $this->rows[$i];
            $link = 'index.php?task=categories&catid=';
        ?>
        			<div id="categorycontainer">
        		  	<!--############## CATEGORY LIST ENTRY START ###########-->
        		  	<div id="categorylists">
        		  		<div class="categorydiscoverbox">
        						<div class="categoryentry">
        							<div class="categoryv120WideEntry">
        								<div class="categoryv120WrapperOuter">
        									<div class="categoryv120WrapperInner">

        			        				<a href="<?php echo JRoute::_($link . $row->id)?>">
        			        				     <?php JTHelper::showLocalThumbnail("$row->directory/_thumbs/$row->thumb"); ?>
                                            </a>
        										<?php
        										if ($row->video_count > 0) {
        										    echo "<div class=\"video-count\"><span>".$row->video_count. " " . strtolower(_JTUBE_VIDEOS) . " </span></div>";
        										}
        										?>
        								</div>
        							</div>
        						</div>
        						<div class="category-main-content">
        							<div class="category-title">
        								<a href="<?php echo JRoute::_($link . $row->id)?>"><?php echo stripslashes($row->category_name); ?></a>
        							</div>
        							<div class="category-description">
        								<?php echo stripslashes($row->category_info);?>
        							</div>
        							<div class="category-subcategory">
        								<?php foreach ($row->childs as $child):?>
        				    				<a href="<?php echo JRoute::_($link . $child->id) ?>"><?php echo $child->category_name;?></a><span style="color:#666666;font-size:10px;"><?php echo " (".$child->video_count."),";?></span>&nbsp;
        				  				<?php endforeach;?>
        							</div>
        						</div>
        					</div>
        					<div class="category-divider"/>
        					</div>
        			</div>
          			<!--############## CATEGORY LIST ENTRY END ###########-->
        			</div>
        		</div>

        <?php $k = 1 - $k; } ?>
        </div>
        <!--#######################################################-->
        <!--########### CATEGORIES LIST THUMBNAILS END ############-->
        <!--#######################################################-->
        <div style="clear: both;"></div>
        <div id="VideosFoundContainer">
        	<form action="index.php" method="post" name="adminForm">
        	<?php echo _JTUBE_VIDEOS_FOUND_IN_THIS_CATEGORY ?>:
        </div>
        <div style="clear: both;"></div>

        <!--#######################################################-->
        <!--############ YOUTUBE STYLE VIDEOLIST START ############-->
        <!--#######################################################-->
			<div id="video_grid" class="grid-view">
    			<div id="browse-video-data">

        				<?php
        				$itemcount = 0;
        				if ($this->items) {
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
	    									        <img class="vimg120" src="<?php echo substr(JURI::base(), 0, strlen(JURI::base()) -1).$item->directory?>/_thumbs/<?php echo $item->video_thumb; ?>" border="0" />
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
		        	}
		        	if ($itemcount == 0){
		        	    echo _JTUBE_NO_VIDEOS;
		        	}
		        	?>
		        </div>
				<input type="hidden" name="boxchecked" value="0" />
				<input type="hidden" name="option" value="com_jomtube" />
				<input type="hidden" name="controller" value="categories" />
				<input type="hidden" name="view" value="categories" />
				<input type="hidden" name="task" value="" />
				<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
				<input type="hidden" name="filter_order_Dir" value="" />
			</form>
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
            echo "<div id=\"totalvideos\">Total Videos In Category (" . $this->pageNav->total . ")</div>";
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
<!--############ RIGHT COLUMN ###########-->
    <?php JTHelper::loadCustomVerticalModule('jomtube_category_right', $this->c->category_column_right_width, 'right')?>
