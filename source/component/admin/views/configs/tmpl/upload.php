<?php
    defined('_JEXEC') or die('Restricted access');
?>
<!--Upload Setting-->
<fieldset class="adminform">
    <legend><?php echo JText::_('Upload Settings');?></legend>
    <table class="admintable" cellspacing="1">
        <col width="200"></col>
	<tbody>
	   <tr>
	       <td class="key" width="200"><?php echo JText::_('Uploader Method');?></td>
	       <td>
	           <?php echo JHTML::_('select.genericlist', $this->uploader_type, 'uploader_type', '', 'value', 'text', $this->c->uploader_type); ?>
	       </td>
	   </tr>

	   <tr>
	       <td class="key" width="200"><?php echo JText::_('Maximum Upload Filesize (MB)');?></td>
	       <td>
	           <?php $upload_max = ereg_replace("[^0-9]", "", ini_get("upload_max_filesize") ); ?>
	           <input type="text" name="filesize" value="<?php echo $upload_max?>" disabled="true" /> <span class="jomtube_warning">(Depend on your PHP config )</span>
	       </td>
	   </tr>

	   <tr>
	       <td class="key" width="200"><?php echo JText::_('Allowed Typies');?></td>
	       <td>
	           <input type="checkbox" name="ft_mpg" value="<?php echo $this->c->ft_mpg?>" checked  disabled="true"/>&nbsp;mpg
	           <input type="checkbox" name="ft_mpeg" value="<?php echo $this->c->ft_mpeg?>" checked disabled="true" />&nbsp;mpeg
	           <input type="checkbox" name="ft_avi" value="<?php echo $this->c->ft_avi?>" checked disabled="true"/>&nbsp;avi
	           <input type="checkbox" name="ft_divx" value="<?php echo $this->c->ft_divx?>" checked disabled="true"/>&nbsp;divx
	           <input type="checkbox" name="ft_mp4" value="<?php echo $this->c->ft_mp4?>" checked disabled="true"/>&nbsp;mp4
	           <input type="checkbox" name="ft_flv" value="<?php echo $this->c->ft_flv?>" checked disabled="true"/>&nbsp;flv
	           <input type="checkbox" name="ft_wmv" value="<?php echo $this->c->ft_wmv?>" checked disabled="true"/>&nbsp;wmv
	           <input type="checkbox" name="ft_rm" value="<?php echo $this->c->ft_rm?>" checked disabled="true"/>&nbsp;rm
	           <input type="checkbox" name="ft_mov" value="<?php echo $this->c->ft_mov?>"checked disabled="true"/>&nbsp;mov
	           <input type="checkbox" name="ft_moov" value="<?php echo $this->c->ft_moov?>" checked disabled="true"/>&nbsp;moov
	           <input type="checkbox" name="ft_asf" value="<?php echo $this->c->ft_asf?>"checked disabled="true"/>&nbsp;asf
	           <input type="checkbox" name="ft_swf" value="<?php echo $this->c->ft_swf?>" checked disabled="true"/>&nbsp;swf
	           <input type="checkbox" name="ft_vob" value="<?php echo $this->c->ft_vob?>"checked disabled="true"/>&nbsp;vob
	       </td>
	   </tr>
	</tbody>
    </table>
</fieldset>

<fieldset class="adminform">
    <legend><?php echo JText::_('Approvals');?></legend>
    <table class="admintable" cellspacing="1">
        <col width="300"></col>
	<tbody>
	   <tr>
	       <td class="key" width="200"><?php echo JText::_('Auto - approve Uploaded Videos from frontend');?></td>
	       <td>
	           <?php echo JHTML::_('select.genericlist', $this->yesno, 'auto_approve', '', 'value', 'text', $this->c->auto_approve); ?>
	       </td>
	   </tr>
	</tbody>
    </table>
</fieldset>
