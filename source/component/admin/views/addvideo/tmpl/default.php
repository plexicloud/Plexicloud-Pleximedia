<?php
	defined('_JEXEC') or die('Restricted access');
?>

<form action="index.php" method="post" name="adminForm" id="adminForm">

<table cellspacing="0" cellpadding="4" border="0">
	<tr>
		<td><h2>Add Remote Video Settings</h2></td>
		<td></td>
	</tr>
	<tr>
	   <td>Input Link</td>
	   <td><input type="text" name="inputlink" size="100"/><br>Please Insert A Supported Remote Link. Then click the <b>Save</b> Button to proceed.</td>
	</tr>
</table>

<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="option" value="com_jomtube" />
<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
<input type="hidden" name="controller" value="videos" />
<input type="hidden" id="task" name="task" value="applylink" />
</form>


<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>
