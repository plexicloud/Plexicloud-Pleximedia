<?php
	defined('_JEXEC') or die('Restricted access');
?>

<script language="javascript" type="text/javascript">
function submitbutton(pressbutton)
{
	var form = document.adminForm;

	if (pressbutton == 'cancel') {
		submitform( pressbutton );
		return;
	}

	// do field validation
	if (form.category_name.value == ""){
		alert( "<?php echo JText::_( 'ADD NAME CATEGORY' ); ?>" );
	} else {
		submitform( pressbutton );
	}
}
</script>

<table cellspacing="0" cellpadding="4" border="0">
	<tr>
		<td><h2>Videos Category Settings</h2></td>
		<td></td>
	</tr>
	<tr>
		<td>Input Category Name</td>
		<td><form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data"><input name="category_name" value="<?php echo $this->row->category_name; ?>" size="97" maxlength="100" /></td>
	</tr>
	<tr>
		<td>Select A Parent Category</td>
		<td><?php echo $this->parentSelect;?><br><i>Don't forget to select a Parent Category if this is a Subcategory.</i></td>
	</tr>
	<tr>
		<td>Category Description</td>
		<td><textarea name="category_info" cols="55" rows="5"><?php echo $this->row->category_info?></textarea></td>
	</tr>
	<tr>
		 <td>Category Directory</td>
		 <td><i>A Directory for this Category will be generated automatically on your Server.<br>All Videos for this Category will be placed under this category.</i></td>
	</tr>
	<tr>
		 <td>Category Thubmnail</td>
		 <td><input type="file" name="thumbnail" size="97" /></td>
	</tr>
	<tr>
		 <td>Info</td>
		 <td>Click The Save Button Icon Above To Save Settings</td>
	</tr>
</table>

<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="option" value="com_jomtube" />
<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
<input type="hidden" name="controller" value="categories" />
<input type="hidden" name="view" value="category" />
<input type="hidden" name="task" value="" />
</form>


<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>