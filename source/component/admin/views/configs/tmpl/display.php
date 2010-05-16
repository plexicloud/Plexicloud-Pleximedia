<?php
    defined('_JEXEC') or die('Restricted access');
?>

<!--Tabs Display Setting-->
<fieldset class="adminform">
    <legend><?php echo JText::_('Tabs Display Setting');?></legend>
    <table class="admintable" cellspacing="1">
        <col width="220"></col>
        <col width="100"></col>
        <col width="20"></col>
	<tbody>
	   <tr>
	       <td class="key"><?php echo JText::_('Margin Left Side Of Page (px)');?></td>
	       <td>
               <input type="text" value="<?php echo $this->c->tabs_margin_left ?>" name="tabs_margin_left" />
	       </td>
	       <td>
	           <a href="http://www.jomtube.com/dokuwiki/doku.php?id=margin.left.side.of.page" title="Click Here For Help" target="_blank"><img src="<?php echo JURI::base()?>components/com_jomtube/assets/images/question.png" alt="Click Here For Help"></a>
	       </td>
	   </tr>
	   <tr>
	       <td class="key"><?php echo JText::_('Disable All Videos Tab');?></td>
	       <td>
	           <?php echo JHTML::_('select.genericlist', $this->yesno, 'disable_allvideos_tab', '', 'value', 'text', $this->c->disable_allvideos_tab); ?>
	       </td>
	   </tr>
	   <tr>
	       <td class="key"><?php echo JText::_('Disable My Videos Tab');?></td>
	       <td>
	           <?php echo JHTML::_('select.genericlist', $this->yesno, 'disable_myvideos_tab', '', 'value', 'text', $this->c->disable_myvideos_tab); ?>
	       </td>
	   </tr>
	   <tr>
	       <td class="key"><?php echo JText::_('Disable Categories Tab');?></td>
	       <td>
	           <?php echo JHTML::_('select.genericlist', $this->yesno, 'disable_categories_tab', '', 'value', 'text', $this->c->disable_categories_tab); ?>
	       </td>
	   </tr>
	   <tr>
	       <td class="key"><?php echo JText::_('Disable Add Video Tab');?></td>
	       <td>
	           <?php echo JHTML::_('select.genericlist', $this->yesno, 'disable_addvideo_tab', '', 'value', 'text', $this->c->disable_addvideo_tab); ?>
	       </td>
	   </tr>
	   <tr>
	       <td class="key"><?php echo JText::_('Disable Upload Videos Tab');?></td>
	       <td>
	           <?php echo JHTML::_('select.genericlist', $this->yesno, 'disable_uploadvideo_tab', '', 'value', 'text', $this->c->disable_uploadvideo_tab); ?>
	       </td>
	   </tr>
	   <tr>
	       <td class="key"><?php echo JText::_('Disable Search Form');?></td>
	       <td>
	           <?php echo JHTML::_('select.genericlist', $this->yesno, 'disable_search_form', '', 'value', 'text', $this->c->disable_search_form); ?>
	       </td>
	   </tr>
	</tbody>
    </table>
</fieldset>

<!--Video Player  Setting In Main Page-->
<fieldset class="adminform">
    <legend><?php echo JText::_('Video Player In Main Page Settings');?></legend>
    <table class="admintable" cellspacing="1">
        <col width="200"></col>
	<tbody>
	   <tr>
	       <td class="key"><?php echo JText::_('Show Player In Main Page');?></td>
	       <td>
	           <?php echo JHTML::_('select.genericlist', $this->yesno, 'show_player_in_mainpage', '', 'value', 'text', $this->c->show_player_in_mainpage); ?>
	       </td>
	   </tr>
	   <tr>
	       <td class="key"><?php echo JText::_('Width Player In Main Page');?></td>
	       <td>
	           <input type="text" value="<?php echo $this->c->width_player_in_mainpage ?>" name="width_player_in_mainpage" />
	       </td>
	   </tr>
	   <tr>
	       <td class="key"><?php echo JText::_('Hight Player In Main Page');?></td>
	       <td>
	           <input type="text" value="<?php echo $this->c->height_player_in_mainpage ?>" name="height_player_in_mainpage" />
	       </td>
	   </tr>
	   <tr>
	       <td class="key"><?php echo JText::_('Video Play On');?></td>
	       <td>
	           <?php echo JHTML::_('select.genericlist', $this->video_play_in_mainpage, 'video_play_in_mainpage', '', 'value', 'text', $this->c->video_play_in_mainpage); ?>
	       </td>
	   </tr>
	   <tr>
	       <td class="key"><?php echo JText::_('Show Detail Of Video In Main Page');?></td>
	       <td>
	           <?php echo JHTML::_('select.genericlist', $this->yesno, 'show_videodetail_play_in_mainpage', '', 'value', 'text', $this->c->show_videodetail_play_in_mainpage); ?>
	       </td>
	   </tr>
	</tbody>
    </table>
</fieldset>

<!--Video Display Setting-->
<fieldset class="adminform">
    <legend><?php echo JText::_('Video Display Setting');?></legend>
    <table class="admintable" cellspacing="1">
        <col width="200"></col>
	<tbody>
	   <tr>
	       <td class="key"><?php echo JText::_('Video Player Size');?></td>
	       <td>
	           <?php echo JText::_('Width') . ':'; ?> <input type="text" onkeyup="javascript:update_player_height('width');" name="video_player_width" value="<?php echo $this->c->video_player_width; ?>" />
	           <?php echo JText::_('Height') . ':'; ?> <input type="text" onkeyup="javascript:update_player_height('height');" name="video_player_height" value="<?php echo $this->c->video_player_height; ?>" />
	       </td>
	   </tr>

	   <tr>
	       <td class="key"><?php echo JText::_('Aspect Ratio Constraint');?></td>
	       <td>
	           <input type="radio" onchange="javascript:update_player_height('width');" name="aspect_constraint" value="16x9"<?php echo $this->c->aspect_constraint == '16x9' ? 'checked' : '' ?> />16x9
        		  <input type="radio" onchange="javascript:update_player_height('width');" name="aspect_constraint" value="4x3" <?php echo $this->c->aspect_constraint == '4x3' ? 'checked' : '' ?> />4x3
        		  <input type="radio" onchange="javascript:update_player_height('none');" name="aspect_constraint" value="no" <?php echo $this->c->aspect_constraint == 'no' ? 'checked' : '' ?> />No Constraint
	       </td>
	   </tr>

	   <tr>
	       <td class="key"><?php echo JText::_('Show Embed Code');?></td>
	       <td>
	           <?php echo JHTML::_('select.radiolist', $this->yesno, 'show_embed_code', '', 'value', 'text', $this->c->show_embed_code); ?>
	       </td>
	   </tr>

	   <tr>
	       <td class="key"><?php echo JText::_('Show Download Link');?></td>
	       <td>
	           <?php echo JHTML::_('select.radiolist', $this->yesno, 'show_donwload_link', '', 'value', 'text', $this->c->show_donwload_link); ?>
	       </td>
	   </tr>

	   <tr>
	       <td class="key"><?php echo JText::_('Allow Ratings');?></td>
	       <td>
	           <?php echo JHTML::_('select.radiolist', $this->yesno, 'allow_ratings', '', 'value', 'text', $this->c->allow_ratings); ?>
	       </td>
	   </tr>

	   <tr>
	       <td class="key"><?php echo JText::_('Allow Commenting');?></td>
	       <td>
	           <?php echo JHTML::_('select.radiolist', $this->yesno, 'allow_comment', '', 'value', 'text', $this->c->allow_comment); ?>
	       </td>
	   </tr>

	   <tr>
	       <td class="key"><?php echo JText::_('Auto Play On Load');?></td>
	       <td>
	           <?php echo JHTML::_('select.radiolist', $this->yesno, 'auto_play_on_load', '', 'value', 'text', $this->c->auto_play_on_load); ?>
	       </td>
	   </tr>
	</tbody>
    </table>
</fieldset>