<?php

defined('_JEXEC') or die('Restricted access');
?>

<script>
function submitbutton(pressbutton)
{
    var form = document.adminForm;

    if (!checkvideo()) {
        return false;
    }

    if (pressbutton == 'cancel') {
        submitform( pressbutton );
        return;
    }

    submitform( pressbutton );
}
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">


<table cellspacing="0" cellpadding="4" border="0">
	<tr>
		<td><h2>Video Batch Adding Settings</h2></td>
		<td></td>
	</tr>
	<tr>
		<td><label for="catname"><?php echo JText::_( 'Category Name' ).':'; ?></label></td>
		<td><?php echo $this->parentSelect;?></td>
	</tr>
	<tr>
		<td>Published:</td>
		<td><?php echo $this->ispublished; ?></td>
	</tr>
	<tr>
		<td>Downloadable:</td>
		<td><?php echo $this->isdownloadable; ?></td>
	</tr>
	<tr>
		<td>Featured:</td>
		<td><?php echo $this->isfeatured; ?></td>
	</tr>
	<tr>
	 	<td>Tags:</td>
	 	<td><input type="text" name="tags" value="" size="50" /></td>
	</tr>
	<tr>
	 	<td>Date Added:</td>
	 	<td><?php echo JHTML::_('calendar', date('Y-m-d'), 'date_added', 'date_added', '%Y-%m-%d', array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19', 'onkeypress'=>'return false;')); ?></td>
	</tr>
  	<tr>
        <td>Author</td>
        <td><input type="text" name="user_id" value="<?php echo $this->my->id;?>" /></td>
  	</tr>
</table>

<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="option" value="com_jomtube" />
<input type="hidden" name="id" value="" />
<input type="hidden" name="controller" value="videos" />
<input type="hidden" name="view" value="video" />
<input type="hidden" id="task" name="task" value="" />
</form>


<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>
