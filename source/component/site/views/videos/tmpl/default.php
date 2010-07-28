<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<?php if ( $this->params->get( 'show_page_title', 1 ) ) : ?>
<div class="componentheading<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
	<?php //echo $this->params->get( 'page_title' ); ?>
</div>
<?php endif; ?>

<table><tr><td>

<?php 
echo base64_decode(JRequest::getVar('toolbar'));
?>

<div id="videomainbody">
	<div id="videoleft-column">
	      <?php
	      $jomtube_left_modules = &JModuleHelper::getModules('jomtube_left');
	      foreach ($jomtube_left_modules as $left_module) {
	          echo "<div id=\"module-padding\">";
	          echo JModuleHelper::renderModule($left_module);
	          echo "</div>";
	      }
	        ?>
	</div>

<div id="videomiddle-column">
<form action="<?php echo JRoute::_( 'index.php' );?>" method="get" name="jomtubeForm" id="jomtubeForm" >
<input type="hidden" name="option" value="com_jomtube" />
<input type="hidden" name="view" value="videos" />
<input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
</form>

<!--####################################################-->
<!--############ YOUTUBE STYLE SUBMENU START ###########-->
<!--####################################################-->
<?php if ($_GET['category_id'] == ''):?>
	<div id="videoModifiers">
	<div class="subcategory first">
	    <?php $link=JRoute::_("index.php?view=videos&featured=1");
	    echo "<a href=\"".$link."\" title=\"Featured Videos\"><span " . ($_GET['featured'] == 1 ? "class='selected'" : '') . ">Featured Videos</span></a>";
	    ?>
	  </div>
	  <div class="subcategory">
	    <?php $link=JRoute::_("index.php?view=videos&order=hits");
	    echo "<a href=\"".$link."\" title=\"Most Viewed Videos\"><span " . ($_GET['order'] == 'hits' ? "class='selected'" : '') . " >Most Viewed</span></a>";
	    ?>
	  </div>
	  <div class="subcategory">
	    <?php $link=JRoute::_("index.php?view=videos&order=votetotal");
	    echo "<a href=\"".$link."\" title=\"Highest Rate Videos\"><span " . ($_GET['order'] == 'votetotal' ? "class='selected'" : '') . " >Highest Rated</span></a>";
	    ?>
	  </div>
	  <div class="subcategory">
	    <?php $link=JRoute::_("index.php?view=videos");
	    echo "<a href=\"".$link."\" title=\"Latest Videos\"><span " . ($_GET['order'] == '' && $_GET['featured'] != 1 ? "class='selected'" : '') . ">Latest Videos</span></a>";
	    ?>
	  </div>
	</div>
	<?php endif;?>
<!--###################################################-->
<!--############ YOUTUBE STYLE SUBMENU END ############-->
<!--###################################################-->

  <!--Breakcrumb-->
  <?php if ($_GET['category_id'] != ''):?>
   <div style="padding-top: 10px;">
    <a href="index.php?option=com_jomtube&view=videos">Main</a>
    <?php foreach ($this->breadcrum as $cat):?>
      <img src="components/com_jomtube/assets/images/arrow3.jpg">
	   <a href="<?php echo JRoute::_("index.php?view=videos&category_id=$cat->id") ?>"><?php echo $cat->category_name?></a>
    <?php endforeach;?>
  </div>
  <?php endif;?>
<!--#######################################################-->
<!--############ YOUTUBE STYLE VIDEOLIST START ############-->
<!--#######################################################-->
<div id="vl_container">

	<?php
	$itemcount = 0;
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
            						From:<span class="vladdby"> <a href="<?php echo JRoute::_("index.php?option=com_community&view=profile&userid=" . $item->user_id) ?>"><?php echo jomtube::showShortAuthor($item->username)?></a></span>
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
	    echo "<p>We don't have any videos in this section yet. ";
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
	<div id="videoright-column">
	      <?php
	      $jomtube_right_modules = &JModuleHelper::getModules('jomtube_right');
	      foreach ($jomtube_right_modules as $right_module) {
	          echo "<div id=\"module-padding\">";
	          echo JModuleHelper::renderModule($right_module);
	          echo "</div>";
	      }
	        ?>
	</div>
</div>
</td></tr></table>