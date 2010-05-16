<?php
defined('_JEXEC') or die('Restricted access');
?>
<!--List Plugins-->
<fieldset class="adminform">
    <legend><?php echo JText::_('List Plugins');?></legend>
    <table class="adminlist" cellspacing="1">
	<thead>
        <tr>
			<th width="1%"><?php echo JText::_( 'Num' ); ?></th>
			<th class="title" width="20%">Plugin name</th>
			<th width="10%">Selected</th>
			<th width="20%">Description</th>
			<th width="5%">Published</th>
			<th width="5%">Type</th>
			<th width="25%">Directory</th>
			<th width="1%" nowrap="nowrap">ID</th>
		</tr>
	</thead>

	<tbody>
	   <tr>
	       <td colspan="7" align="center"><b>Developing</b></td>
	   </tr>
		<?php
		$k = 0;
		for ($i=0, $n=count($this->languages); $i < $n; $i++) {
		    $row = $this->languages[$i];
		    $link 		= 'index.php?option=com_jomtube&amp;controller=categories&amp;task=edit&amp;cid[]='. $row->id;
		    $checked 	= JHTML::_('grid.checkedout', $row, $i );

		    if ($row->iscore) {
    			$row->cbd		= 'disabled';
    			$row->style	= 'style="color:#999999;"';
    		} else {
    			$row->cbd		= null;
    			$row->style	= null;
    		}
   		?>
		<tr class="<?php echo "row$k"; ?>" <?php echo $row->style; ?>>
			<td><?php echo $this->pageNav->getRowOffset( $i ); ?></td>
			<td align="left">
                <input type="radio" id="cb<?php echo $i;?>" name="eid" value="<?php echo $row->id; ?>" onclick="isChecked(this.checked);" <?php echo $row->cbd; ?> />
				<span class="bold"><a href="index.php?option=com_jomtube&view=plugin&id=<?php echo $row->id; ?>"><?php echo $row->name; ?></a></span>
			</td>
			<td>

			</td>
			<td>
                <?php echo $row->description; ?>
			</td>
			<td>
				<?php echo $row->published; ?>
			</td>
			<td>
                <?php echo $row->type; ?>
			</td>
			<td>
                <?php echo $row->folder; ?>
			</td>
			<td align="center"><?php echo $row->id; ?></td>
		</tr>
		<?php $k = 1 - $k; } ?>
    </table>
</fieldset>