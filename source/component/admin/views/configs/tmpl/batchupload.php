<?php
    defined('_JEXEC') or die('Restricted access');
?>
<!--Comments Integration-->
<fieldset class="adminform">
    <legend><?php echo JText::_('Batch Upload Settings');?></legend>
    <table class="admintable" cellspacing="1">
	<tbody>
	   <tr>
	       <td class="key" width="200"><?php echo JText::_('Batch Upload Directory');?></td>
	       <td>
	           <input type="text" value="/jomtubefiles/_batchupload" disabled="true" />
	       </td>
	   </tr>
	</tbody>
    </table>
</fieldset>