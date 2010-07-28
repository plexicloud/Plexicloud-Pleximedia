<?php
defined('_JEXEC') or die('Restricted access'); 
?>

<?php if ($mainframe->get('show_page_title', 1)){  ?>
<div class="componentheading<?php echo $mainframe->get( 'pageclass_sfx' ); ?>">
	<?php //echo $mainframe->getPageTitle(); ?>
</div>
<?php } ?>

<table><tr><td>

<?php 
echo base64_decode(JRequest::getVar('toolbar'));
?>

<div id="category-col-right-container" style>
		<!--############## CUSTOM MODULE POSITION ###########-->
		<div id="category-column-right">
		      <?php
              $jomtube_category_modules = &JModuleHelper::getModules('jomtube_category');
	              foreach ($jomtube_category_modules as $jomtube_category) {
	                  echo "<div id=\"module-padding\">";
	                  echo JModuleHelper::renderModule($jomtube_category);
	                  echo "</div>";
	              }
              ?>
		</div>
		<!--############## CUSTOM MODULE POSITION END ###########-->
</div>

<script>
 function selectCategory(catid) {
    var link = "index.php?option=com_jomtube&task=categories&catid=" + catid.value;
    location.href = link;
  }
</script>


<!------------- CATEGORY MAIN CONTAINER AND TITLE ---------------->
<div id="category-column">
	<div id="categorybox">
		<div id="categorytitle">
			Video Categories
		</div>
		<!-- <div id="categorylistbox">
			<?php //echo $this->categoryList ?>
		</div> -->
	</div>

<!------------------- BREADCRUMB CONTAINER ----------------------->
	<div id="breadcrumbcontainer">
	    <a href="<?php echo JRoute::_("index.php?view=categories")?>">Video Channels</a>
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

				   					<?php if ($row->thumb):?>
			        				<a href="<?php echo JRoute::_($link . $row->id)?>"><img src="<?php echo substr(JURI::base(), 0, strlen(JURI::base()) -1).$row->directory?>/_thumbs/<?php echo $row->thumb; ?>" border="0" class="categoryvimg120"/></a>
					 				<?php endif;?>
										<?php
										if ($row->video_count > 0) {
											echo "<div class=\"video-time\"><span>".$row->video_count." videos</span></div>";
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
	Videos Found In This Category:
</div>

<div id="vl_container">
	<?php
	$itemcount = 0;
	if ($this->items)
	foreach($this->items as $item)
	{
	    $itemcount++;
		?>
		<div class="vlcell">
			<div class="vlentry">
				<div class="vlcontainer">
					<div class="v120WideEntry">
						<div class="v120WrapperOuter">
							<div class="v120WrapperInner">
							   <?php if ($item->video_type == 'local' || $item->video_type == ''):?>
      					   			<a href="<?php echo JRoute::_("index.php?view=video&id=$item->id")?>">
								        <img class="image_125" src="<?php echo substr(JURI::base(), 0, strlen(JURI::base()) -1).$item->directory?>/_thumbs/<?php echo $item->video_thumb; ?>" border="0" />
    								 </a>
								 <?php else:?>
								   <a href="<?php echo JRoute::_("index.php?view=video&id=$item->id;")?>">
								        <img class="image_125" src="<?php echo $item->video_thumb; ?>" border="0" />
    								 </a>
								 <?php endif;?>
								<div class="video-time-125">
								<span><?php echo $item->duration?></span>
								</div>
							</div>
						</div>
					</div>
						<div class="vltitle">
   							 <div class="vlshortTitle">
        							<a href="<?php echo JRoute::_("index.php?view=video&id=$item->id")?>" title="<?php echo $item->video_title; ?>">
							        <?php echo stripslashes($item->video_title);?>
   							</a>
    						</div>
						</div>
				</div>
				<div class="vlfacets">
					<div class="vladded">
    						Added: <?php echo $item->date_added?>
					 </div>
					<div>
    						From:<span class="vladdby"> <a href="<?php echo JRoute::_("index.php?option=com_community&view=profile&userid=" . $item->user_id) ?>"><?php echo strlen($item->username) > 12 ? substr($item->username, 0, 12) . '...' : $item->username?></a></span>
					</div>
					<div class="totalvidhits"/>
    						Views:</span> <?php echo $item->hits?><br/>
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
	    echo "No Videos";
	}
	?>
</div>
<!--#####################################################-->
<!--############ YOUTUBE STYLE VIDEOLIST END ############-->
<!--#####################################################-->

<!--########################################################-->
<!--############ YOUTUBE STYLE PAGINATION START ############-->
<!--########################################################-->
<?php
    echo "<div id=\"pagination_container\">";
    echo "	<div id=\"video_pagination\">";

    if ($itemcount > 0) {
        echo "<div align=\"center\" style=\"padding-top: 10px;\">";
        echo $this->pageNav->getPagesLinks();
        echo "</div>";
    }

    echo "	</div>";
    echo "</div>";
?>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="com_jomtube" />
	<input type="hidden" name="controller" value="categories" />
	<input type="hidden" name="view" value="categories" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="" />
</form>
</td></tr></table>