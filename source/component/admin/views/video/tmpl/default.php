<?php
defined('_JEXEC') or die('Restricted access');
?>

<script language="javascript" type="text/javascript">
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

function selectCategory() {
    document.getElementById('task').value = 'selelctCat';
    document.adminForm.submit();
}

function selectthisvideo() {
    var file=document.getElementById("video_url").value;
    file = file.replace(/_/g, ' ');
    file = file.substr(0, file.length - 4);
    file = capitalizeMe(file);
    document.getElementById('video_title').value = file;
    document.getElementById('video_desc').value = file;
}

function capitalizeMe(str) {
    newStr = '';
    str = str.split(' ');
    for(var c=0; c < str.length; c++) {
        newStr += str[c].substring(0,1).toUpperCase() +
        str[c].substring(1,str[c].length) + ' ';
    }
    return newStr;
}
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm"  enctype="multipart/form-data">
	<table  cellspacing="0" cellpadding="4" border="0">
		<tr>
			<td><h2>Adding Local Video Settings</h2></td>
			<td></td>
		</tr>
		<tr>
			<td><label for="catname"><?php echo JText::_( 'Category Name' ).':'; ?></label></td>
			<td><?php echo $this->parentSelect;?></td>
		</tr>
		<tr>
		 	<td>Video Title</td>
		 	<td ><input onChange="capitalizeMe(this)" type="text" id="video_title" name="video_title" size="97" value="<?php echo $this->row->id ? $this->row->video_title : '';?>"/></td>
		</tr>
		<tr>
		 	<td>Video Description</td>
		 	<td ><textarea name="video_desc" cols="55" rows="5" id="video_desc"><?php echo $this->row->id ? $this->row->video_desc : ''?></textarea></td>
		</tr>
		<tr>
			<td>Published</td>
			<td><?php echo $this->ispublished; ?></td>
		</tr>
		<tr>
			<td>Downloadable</td>
			<td><?php echo $this->isdownloadable; ?></td>
		</tr>
		<tr>
			<td>Featured</td>
			<td><?php echo $this->isfeatured; ?></td>
		</tr>
		<tr>
		 	<td>Date Added</td>
		 	<td><?php echo JHTML::_('calendar', $this->row->id == 0 ? date('Y-m-d') : $this->row->date_added, 'date_added', 'date_added', '%Y-%m-%d', array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19', 'onkeypress'=>'return false;')); ?></div>
		</tr>
		<tr>
		 	<td>Author</td>
		 	<td><input size="25" type="text" name="user_id" value="<?php echo $this->row->id == 0 ? $this->my->id : $this->row->user_id?>" /></td>
		</tr>
		<tr>
		 	<td>Tags</td>
		 	<td><input type="text" name="tags" value="<?php echo $this->row->id ? $this->row->tags : ''?>" size="97"></td>
		</tr>
		<tr>
			<td><h2>Video Clip Selection</h2></td>
			<td></td>
		</tr>
		<tr>
		     <td>Category Directory Path:</td>
		     <td><input name="catdir" type="text" value="<?php echo $this->category_dir;?>" size="97"></td>
		</tr>
		<tr>
		     <td>Select a Video File</td>
		     <td><?php echo $this->localvideolist;?></td>
		</tr>
		<tr>
		     <td>Thumbnail</td>
		     <td>
                <?php if ($this->c->use_ffmpeg):?>
		          <i>The Video Thumbnail Is Generated Automatically When You Save Video</i>
	            <?php else : ?>
	               <input type="file" name="thumbnail" size="97" />
	            <?php endif; ?>
             </td>
		<tr>
		     <td>Video duration</td>
		     <td>
                <?php if ($this->c->use_ffmpeg):?>
		          <i>The Video Duration Is Generated Automatically When You Save Video</i>
	            <?php else : ?>
	               <input type="text" name="duration" value="<?php echo $this->row->duration; ?>"/> &nbsp;<i>(format:mm:ss)</i>
	            <?php endif; ?>
             </td>
		</tr>
	</table>

<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="option" value="com_jomtube" />
<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
<input type="hidden" name="controller" value="videos" />
<input type="hidden" name="view" value="video" />
<input type="hidden" id="task" name="task" value="" />
</form>


<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>
