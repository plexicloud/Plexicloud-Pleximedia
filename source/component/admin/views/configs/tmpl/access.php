<?php
    defined('_JEXEC') or die('Restricted access');
?>
<?php
    defined('_JEXEC') or die('Restricted access');
?>
<!--Comments Integration-->
<fieldset class="adminform">
    <legend><?php echo JText::_('Functions Access');?></legend>
    <table class="admintable" cellspacing="1">
        <col width="200"></col>
	<tbody>
	   <tr>
	       <td class="key" width="200"><?php echo JText::_('Main Component');?></td>
	       <td>
	           <?php echo JHTML::_('select.genericlist', $this->group_tree, 'acl_component', 'size="10"', 'value', 'text', $this->c->acl_component );?>
	       </td>
	   </tr>

	   <tr>
	       <td class="key" width="200"><?php echo JText::_('Add Remote Video Functions');?></td>
	       <td>
	           <?php echo JHTML::_('select.genericlist', $this->group_tree, 'acl_addvideo', 'size="10"', 'value', 'text', $this->c->acl_addvideo );?>
	       </td>
	   </tr>

	   <tr>
	       <td class="key" width="200"><?php echo JText::_('Upload Video Functions');?></td>
	       <td>
	           <?php echo JHTML::_('select.genericlist', $this->group_tree, 'acl_uploadvideo', 'size="10"', 'value', 'text', $this->c->acl_uploadvideo );?>
	       </td>
	   </tr>
	</tbody>
    </table>
</fieldset>