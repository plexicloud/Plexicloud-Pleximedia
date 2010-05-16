<?php defined('_JEXEC') or die('Restricted access'); // no direct access ?>
<?php foreach($items as $item):?>
<div id="v145_container">
    <div class="v145WideEntry">
        <div class="v145WrapperOuter">
            <div class="v145WrapperInner">
	       <?php if ($item->video_type == 'local' || $item->video_type == ''):?>
	           <a href="<?php echo JRoute::_("index.php?option=com_jomtube&view=video&id=$item->id")?>">
	               <img class="image_145" src="<?php echo substr(JURI::base(), 0, strlen(JURI::base()) -1).$item->directory?>/_thumbs/<?php echo $item->video_thumb; ?>" border="0" />
	           </a>
	       <?php else:?>
	           <a href="<?php echo JRoute::_("index.php?option=com_jomtube&view=video&id=$item->id;")?>">
	               <img class="image_145" src="<?php echo $item->video_thumb; ?>" border="0" />
                    </a>
	       <?php endif;?>
                <div class="video-time-145">
                <span><?php echo $item->duration?></span>
                </div>
        	   </div>
        </div>
    </div>
    <div id="v145titlecontainer">
        <div class="v145shortTitle">
            <a href="<?php echo JRoute::_("index.php?option=com_jomtube&view=video&id=$item->id")?>" title="<?php echo $item->video_title; ?>">
                <?php echo stripslashes($item->video_title);?>
	   		</a>
        </div>
		<div id="v145facets">
		    <div class="v145added">
		        Added: <?php echo $item->date_added?>
		    </div>
		</div>
	</div>
</div>
<?php endforeach;?>
<br/>
<center><a href="<?php echo $morLink?>"><?php echo $strLink?></a></center>