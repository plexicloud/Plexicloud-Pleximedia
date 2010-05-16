<?php
	defined('_JEXEC') or die('Restricted access');
?>

<form action="index.php" method="post" name="adminForm">

	<table class="adminform">
		<tr>
			<td width="100%">

			</td>
			<td nowrap="nowrap">
        Filter:
				<?php echo $this->lists['category'];	?>
				<?php echo $this->lists['video_server'];?>
				<?php echo $this->lists['publish'];?>
				<?php echo $this->lists['featured'];?>
			</td>
		</tr>
	</table>

	<table class="adminlist" cellspacing="1">
	<thead>
		<tr>
			<th width="1%"><?php echo JText::_( 'Num' ); ?></th>
			<th width="1%"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $this->rows ); ?>);" /></th>
			<th class="title" width="10%">Title</th>
			<th width="10%">Thumbnail</th>
			<th width="20%">Detail</th>
			<th width="10%">Duration</th>
			<th width="10%">Category</th>
			<th width="10%">Url</th>
			<th width="5%">Server</th>
			<th width="5%">Publish</th>
			<th width="5%">Featured</th>
			<th width="1%" nowrap="nowrap">ID</th>
		</tr>
	</thead>

	<tbody>
		<?php
		$k = 0;
		for ($i=0, $n=count($this->rows); $i < $n; $i++) {
			$row = $this->rows[$i];

			$link 		= 'index.php?option=com_jomtube&amp;controller=videos&amp;task=edit&amp;cid[]='. $row->id;
			$checked 	= JHTML::_('grid.checkedout', $row, $i );
			$published 	= JHTML::_('grid.published', $row, $i );
   		?>
		<tr class="<?php echo "row$k"; ?>">
			<td><?php echo $this->pageNav->getRowOffset( $i ); ?></td>
			<td width="7"><?php echo $checked; ?></td>
			<td align="left">
				<a href="<?php echo $link?>"><?php echo $row->video_title; ?></a>
			</td>
			<td>
        <?php if ($row->video_thumb != ''):?>
          <?php if ($row->video_type == 'local' || $row->video_type == ''):?>
            <a href="<?php echo $link?>"><?php JTHelper::showLocalThumbnail("$row->directory/_thumbs/$row->video_thumb"); ?></a>
          <?php else:?>
            <img style="cursor: pointer;" onclick="location.href='<?php echo $link?>'" width="120" height="90" src="<?php echo $row->video_thumb?>"/>
          <?php endif;?>
        <?php endif;?>
			</td>
			<td>
        <?php echo $row->video_desc; ?>
			</td>
			<td>
			  <?php echo $row->duration;?>
			</td>
			<td>
        <?php echo $row->category_name;?>
			</td>
			<td>
          	  <a href="<?php echo $row->video_url;?>" target="_blank"><?php echo $row->video_url;?></a>
			</td>
			<td>
        <?php echo trim($row->video_type) == '' ? 'Local' : ucfirst($row->video_type)?>
			</td>
			<td align="center"><?php echo $published ?></td>
			<td align="center">
					<?php
					if ($row->featured == 1) {
					?>
						<img style="cursor: pointer;" onclick="location.href='index.php?option=com_jomtube&task=unfeatured&controller=videos&id=<?php echo $row->id?>'" src="images/tick.png" width="16" height="16" border="0"/>
					<?php
					}else {
					?>
						<img style="cursor: pointer;" onclick="location.href='index.php?option=com_jomtube&task=featured&controller=videos&id=<?php echo $row->id?>'" src="images/publish_x.png" width="16" height="16" border="0"/>
					<?php
					}
					?>
				</td>
				<td align="center"><?php echo $row->id; ?></td>
		</tr>
		<?php $k = 1 - $k; } ?>
		<tr>
		  <td colspan="11" align="center" style="text-align: center">
        <?php echo $this->pageNav->getListFooter(); ?>
		  </td>
		</tr>
	</tbody>

	</table>

	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="com_jomtube" />
	<input type="hidden" name="controller" value="videos" />
	<input type="hidden" name="view" value="videos" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="" />
</form>