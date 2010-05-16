<?php
defined('_JEXEC') or die('Restricted access'); ?>

<form action="index.php" method="post" name="adminForm">

	<table class="adminlist" cellspacing="1">
	<thead>
		<tr>
			<th width="1%"><?php echo JText::_( 'Num' ); ?></th>
			<th width="1%"><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $this->rows ); ?>);" /></th>
			<th class="title" width="20%">Category name</th>
			<th width="15%">Thumbnail</th>
			<th width="40%">Parent Category</th>
			<th width="30%">Category Directory</th>
			<th width="1%" nowrap="nowrap">ID</th>
		</tr>
	</thead>

	<tfoot>
		<tr>
			<td colspan="7">
				<?php echo $this->pageNav->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>

	<tbody>
		<?php
		$k = 0;
		for ($i=0, $n=count($this->rows); $i < $n; $i++) {
		    $row = $this->rows[$i];

		    $link 		= 'index.php?option=com_jomtube&amp;controller=categories&amp;task=edit&amp;cid[]='. $row->id;
		    $checked 	= JHTML::_('grid.checkedout', $row, $i);
   		?>
		<tr class="<?php echo "row$k"; ?>">
			<td><?php echo $this->pageNav->getRowOffset( $i ); ?></td>
			<td width="7"><?php echo $checked; ?></td>
			<td align="left">
				<a href="<?php echo $link?>"><?php echo $row->category_name; ?></a>
			</td>
			<td>
			     <?php JTHelper::showLocalThumbnail("$row->directory/_thumbs/$row->thumbnail"); ?>
			</td>
			<td>
				<?php echo $row->parent_category; ?>
			</td>
			<td>
        <?php echo $row->directory; ?>
			</td>
			<td align="center"><?php echo $row->id; ?></td>
		</tr>
		<?php $k = 1 - $k; } ?>
	</tbody>

	</table>

	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="com_jomtube" />
	<input type="hidden" name="controller" value="categories" />
	<input type="hidden" name="view" value="categories" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="" />
</form>