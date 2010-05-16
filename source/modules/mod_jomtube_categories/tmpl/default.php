<?php defined('_JEXEC') or die('Restricted access'); // no direct access 
 
//########## GET THE CSS NAME FOR SELECTED BULLET ##########//   
if ($style == 3) $cssbullet = "bullet-trianglelight-jtmodule";
if ($style == 4) $cssbullet = "bullet-squarelight-jtmodule";
if ($style == 5) $cssbullet = "bullet-pluslight-jtmodule";
if ($style == 6) $cssbullet = "bullet-dotlight-jtmodule";
if ($style == 7) $cssbullet = "bullet-arrowlight-jtmodule";
if ($style == 8) $cssbullet = "bullet-triangledark-jtmodule";
if ($style == 9) $cssbullet = "bullet-squaredark-jtmodule";
if ($style == 10) $cssbullet = "bullet-plusdark-jtmodule";
if ($style == 11) $cssbullet = "bullet-dotdark-jtmodule";
if ($style == 12) $cssbullet = "bullet-arrowdark-jtmodule";
?>

<div id="main-cat-jtmodule">
    <!--DOT seprated list (Horizontal)-->
    <?php if ($style == 0):?>
		<div style="text-align:center">
        <?php foreach($items as $item):?>
    	   <a href="<?php echo JRoute::_('index.php?option=com_jomtube&task=categories&catid='.$item->id.'&Itemid='.$jomtube_itemid) ?>"><?php echo $item->category_name?></a>
    	   &nbsp;&middot;&nbsp;
		<?php endforeach;?>
		</div>
	<?php endif;?>

	<!--Pipe seprated list (Horizontal)-->
    <?php if ($style == 1):?>
		<div style="text-align:center">
        <?php foreach($items as $item):?>
    	<a href="<?php echo JRoute::_('index.php?option=com_jomtube&task=categories&catid='.$item->id.'&Itemid='.$jomtube_itemid) ?>"><?php echo $item->category_name?></a>
    	&nbsp;|&nbsp;
		</div>
		<?php endforeach;?>
	<?php endif;?>

	<!--Dash seprated list (Horizontal)-->
    <?php if ($style == 2):?>
		<div style="text-align:center">
        <?php foreach($items as $item):?>
    	<a href="<?php echo JRoute::_('index.php?option=com_jomtube&task=categories&catid='.$item->id.'&Itemid='.$jomtube_itemid) ?>"><?php echo $item->category_name?></a>
    	&nbsp;-&nbsp;
		</div>
		<?php endforeach;?>
	<?php endif;?>

	<!--Bulleted list for Light Background(Vertical)-->
    <?php if ($style == 3 || $style == 4 || $style == 5 || $style == 6 || $style == 7):?>
    	<ul class="<?php echo $cssbullet;?>">
    		<?php foreach($items as $item):?>
        	<li><a href="<?php echo JRoute::_('index.php?option=com_jomtube&task=categories&catid='.$item->id.'&Itemid='.$jomtube_itemid) ?>"><?php echo $item->category_name?></a></li>
    		<?php endforeach;?>
    	</ul>
	<?php endif;?>

	<!--Bulleted list for Dark Background(Vertical)-->
    <?php if ($style == 8 || $style == 9 || $style == 10 || $style == 11 || $style == 12):?>
    	<ul class="<?php echo $cssbullet;?>">
    		<?php foreach($items as $item):?>
        	<li><a href="<?php echo JRoute::_('index.php?option=com_jomtube&task=categories&catid='.$item->id.'&Itemid='.$jomtube_itemid) ?>"><?php echo $item->category_name?></a></li>
    		<?php endforeach;?>
    	</ul>
	<?php endif;?>
</div>

<!--DISPLAY OR HIDE VIEW MORE-->
<?php if ($show_morelink == 1):?>
	<div id="cat-viewmore-jtmodule" style="text-align:<?php echo $textalign;?>">
		<span><a href="<?php echo JRoute::_('index.php?option=com_jomtube&view=categories&Itemid='.$jomtube_itemid)?>">View More...</a></span>
	</div>
<?php endif;?>