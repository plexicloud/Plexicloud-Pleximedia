<?php
	defined('_JEXEC') or die('Restricted access');
?>

<form action="index.php" method="post" name="adminForm" id="adminForm" onsubmit="return checkvideo();">

<table cellspacing="0" cellpadding=4" border="0">
	<tr>
		<td><h2>Edit Video Information</h2></td>
		<td></td>
	</tr>
	<tr>
		<td>Input Link</td>
		<td><input type="text" name="inputlink" style="width: 400px" value="<?php echo $this->inputlink?>"/>&nbsp;&nbsp;<input type="submit" value="Apply" onclick="this.disabled=true;"/>
			<input type="hidden" name="option" value="com_jomtube" />
			<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
			<input type="hidden" name="controller" value="videos" />
			<input type="hidden" id="task" name="task" value="applylink" />
			</form>
		</td>
	</tr>
	<tr>
        <td>Video Thumbnail</td>
      	<td><img src="<?php echo $this->videodetails['thumbnail_link']?>"/></td>
    </tr>
	<tr>
      	<td>Video Title</td>
		<td><form action="index.php" method="post" name="adminForm" id="adminForm"><input type="text" name="video_title" style="width: 400px" value="<?php echo $this->videodetails['title']?>" /></td>
	</tr>
	<tr>
    	<td>Video Description</td>
		<td><textarea name="video_desc" style="width: 400px" rows="3"><?php echo $this->videodetails['videodescription']?></textarea></td>
    </tr>
	<tr>
        <td>Tags</td>
		<td><input type="text" name="tags" style="width: 400px" value="<?php echo $this->videodetails['videotags']?>"/></td>
    </tr>
	<tr>
    	<td>Date Added:</td>
		<td><input type="text" name="date_added" style="width: 400px" value="<?php echo $this->videodetails['video_published']?>"/></td>
    </tr>
	<tr>
        <td>Date Updated:</td>
      	<td><input type="text" name="date_updated" style="width: 400px" value="<?php echo $this->videodetails['date_updated']?>"/></td>
    </tr>
	<tr>
    	<td>Video Duration:</td>
		<td><input type="text" name="duration" style="width: 400px" value="<?php echo $this->videodetails['videoduration']?>"/></td>
    </tr>
	<tr>
    	<td>Category</td>
		<td><?php echo $this->parentSelect;?></td>
	</tr>
	<tr>
    	<td></td>
	   	<td><input type="submit" value="Save Video Changes"></td>
	</tr>
</table>

<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="video_thumb" value="<?php echo $this->videodetails['thumbnail_link']?>"/>
<input type="hidden" name="video_url" value="<?php echo $this->inputlink?>"/>
<input type="hidden" name="video_type" value="<?php echo $this->videodetails['videoservertype']?>"/>
<input type="hidden" name="option" value="com_jomtube" />
<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
<input type="hidden" id="task" name="task" value="savelink" />
<input type="hidden" name="controller" value="videos" />
<input type="hidden" name="remote_id" value="<?php echo $this->videodetails['smallvideocode']?>" />
<input type="hidden" name="display_thumb" style="width: 400px" value="<?php echo $this->videodetails['display_thumb']?>"/>
<input type="hidden" name="user_id" value="<?php echo $this->videodetails['user_id']?>"/>
</form>


<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>
