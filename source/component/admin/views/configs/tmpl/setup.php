<?php
    defined('_JEXEC') or die('Restricted access');
?>

<!--LANGUAGE SETTING-->
<fieldset class="adminform">
    <legend><?php echo JText::_('Jomtube Language Manager');?></legend>
    <table class="admintable" cellspacing="1">
        <col width="220"></col>
        <col width="100"></col>
        <col width="15"></col>
	<tbody>
	   <tr>
	       	<td class="key"><?php echo JText::_('Currently Selected Language');?></td>
	       	<td><?php echo JHTML::_('select.genericlist', $this->jtube_language, 'jtube_language', '', 'value', 'text', $this->c->jtube_language); ?></td>
			<td></td>
			<td><span class="jomtube_message">(Displays Only In The Frontend)</span></td>
	   </tr>
	</tbody>
    </table>
</fieldset>

<!--THEME SETTING-->
<fieldset class="adminform">
    <legend><?php echo JText::_('Jomtube Theme Mananger');?></legend>
    <table class="admintable" cellspacing="1">
        <col width="220"></col>
        <col width="100"></col>
        <col width="20"></col>
	<tbody>
	   <tr>
	       <td class="key"><?php echo JText::_('Currently Selected Theme');?></td>
	       <td>
	           <?php echo JHTML::_('select.genericlist', $this->jtube_skin, 'jtube_skin', '', 'value', 'text', $this->c->jtube_skin); ?>
	       </td>
	   </tr>
	</tbody>
    </table>
</fieldset>

<!--CHECK PERMISSIONS-->
<fieldset class="adminform">
    <legend><?php echo JText::_('Directory Permission Check');?></legend>
    <table class="admintable" cellspacing="1">
        <col width="220"></col>
        <col width="100"></col>
        <col width="20"></col>
	<tbody>
	   <tr>
	       <td class="key"><?php echo JText::_('The configruation file');?></td>
	       <td>
	           <?php
	               /*Check permission*/
                        $configfile = 'components/com_jomtube/configs/configs.jomtube.php';
                        $permission = is_writeable($configfile);
                        if (!$permission) {
                            echo "<span style='color: red; font-weight: bold;'>is unwriteable</span>";
                        } else {
                            echo "<span style='color: #6DB03C; font-weight: bold'>is writeable</span>";
                        }
	            ?>
	       </td>
	   </tr>
	   <tr>
	       <td class="key"><?php echo JText::_('The jomtubefile directory');?></td>
	       <td>
	           <?php
	               /*Check permission*/
                        $jomtubefiles_directory = JPATH_SITE.DS.'jomtubefiles';
                        $permission = is_writeable($jomtubefiles_directory);
                        if (!$permission) {
                            echo "<span style='color: red; font-weight: bold;'>is unwriteable</span>";
                        } else {
                            echo "<span style='color: #6DB03C; font-weight: bold'>is writeable</span>";
                        }
	            ?>
	       </td>
	   </tr>
	   <tr>
	       <td class="key"><?php echo JText::_('FlashUploader tmp');?></td>
	       <td>
	           <?php
	               /*Check permission*/
                        $jomtubefiles_directory = JPATH_SITE.DS.'/administrator/components/com_jomtube/assets/lib/FlashUploader/tmp';
                        $permission = is_writeable($jomtubefiles_directory);
                        if (!$permission) {
                            echo "<span style='color: red; font-weight: bold;'>is unwriteable</span>";
                        } else {
                            echo "<span style='color: #6DB03C; font-weight: bold'>is writeable</span>";
                        }
	            ?>
	       </td>
	   </tr>

	   <tr>
	       <td class="key"><?php echo JText::_('FlashUploader uploads');?></td>
	       <td>
	           <?php
	               /*Check permission*/
                        $jomtubefiles_directory = JPATH_SITE.DS.'/administrator/components/com_jomtube/assets/lib/FlashUploader/uploads';
                        $permission = is_writeable($jomtubefiles_directory);
                        if (!$permission) {
                            echo "<span style='color: red; font-weight: bold;'>is unwriteable</span>";
                        } else {
                            echo "<span style='color: #6DB03C; font-weight: bold'>is writeable</span>";
                        }
	            ?>
	       </td>
	   </tr>
	</tbody>
    </table>
</fieldset>