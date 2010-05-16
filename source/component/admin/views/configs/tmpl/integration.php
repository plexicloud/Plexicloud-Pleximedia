<?php
    defined('_JEXEC') or die('Restricted access');
?>
<?php
    defined('_JEXEC') or die('Restricted access');
?>
<!--Comments Integration-->
<fieldset class="adminform">
    <legend><?php echo JText::_('Comments Integration');?></legend>
    <table class="admintable" cellspacing="1">
	<tbody>
	   <tr>
	       <td class="key" width="200"><?php echo JText::_('Commenting Component');?></td>
	       <td>
	           <?php echo JHTML::_('select.genericlist', $this->commenting_integration, 'commenting_system', '', 'value', 'text', $this->c->commenting_system); ?>
	       </td>
	   </tr>
	</tbody>
    </table>
</fieldset>

<!--JomSocial Integration-->
<fieldset class="adminform">
    <legend><?php echo JText::_('Community Integration');?></legend>
    <table class="admintable" cellspacing="1">
        <col width=""></col>
        <col width="400"></col>
	<tbody>
	   <tr>
	       <td class="key" width="200"><?php echo JText::_('JomSocial Component');?></td>
	       <td>
                    <?php echo JHTML::_('select.genericlist', $this->community, 'community', '', 'value', 'text', $this->c->community); ?>
	       </td>
	       <td>

	       </td>
	   </tr>
	</tbody>
    </table>
</fieldset>