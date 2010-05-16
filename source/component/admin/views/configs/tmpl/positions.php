<?php
    defined('_JEXEC') or die('Restricted access');
?>

<!--Video List Display Setting-->
<fieldset class="adminform">
    <legend><?php echo JText::_('VIDEO LIST DISPLAY AND CUSTOMS MODULE POSITION SETTINGS');?></legend>
    <table class="admintable" cellspacing="1">
        <col width="220"></col>
        <col width="100"></col>
        <col width="20"></col>
	<tbody>
	   <tr>
	       <td class="key"><?php echo JText::_('Videos Per Page');?></td>
	       <td>
                    <input  type="text" value="<?php echo $this->c->videos_per_page ?>" name="videos_per_page" />
	       </td>
	       <td>
	           <a href="http://www.jomtube.com/dokuwiki/doku.php?id=columns.per.page#videos.per.page" title="Click Here For Help" target="_blank"><img src="<?php echo JURI::base()?>components/com_jomtube/assets/images/question.png" alt="Click Here For Help"></a>
	       </td>
	   </tr>
	   <tr>
	       <td class="key"><?php echo JText::_('Columns Per Page By (%) Value');?></td>
	       <td>
                    <input type="text" value="<?php echo $this->c->columns_per_page ?>" name="columns_per_page" />
	       <td>
	           <a href="http://www.jomtube.com/dokuwiki/doku.php?id=columns.per.page" title="Click Here For Help" target="_blank"><img src="<?php echo JURI::base()?>components/com_jomtube/assets/images/question.png" alt="Click Here For Help"></a>
	       </td>
	       <td>
	           <span class="jomtube_message">Example 25% = 4 Columns Click Help For Complete Info<span>
	       </td>
	   </tr>
	   <tr>
	       <td class="key"><?php echo JText::_('Video List Column Left width (px)');?></td>
	       <td>
	           <input type="text" value="<?php echo $this->c->width_jomtube_left_module ?>" name="width_jomtube_left_module" />
	       </td>
	       <td>
	           <a href="http://www.jomtube.com/dokuwiki/doku.php?id=video.list.left.column.width.px" title="Click Here For Help" target="_blank"><img src="<?php echo JURI::base()?>components/com_jomtube/assets/images/question.png" alt="Click Here For Help">
	       <td>
	           NOTE** <span class="jomtube_message">Set to 0 to Hide Right Column<span>
	       </td>
	   </tr>
	   <tr>
	       <td class="key"><?php echo JText::_('Video List Column Center Width (px)');?></td>
	       <td>
                     <input type="text" value="<?php echo $this->c->width_jomtube_center_module ?>" name="width_jomtube_center_module" />
	       </td>
	       <td>
	           <a href="http://www.jomtube.com/dokuwiki/doku.php?id=video.list.center.column.width.px" title="Click Here For Help" target="_blank"><img src="<?php echo JURI::base()?>components/com_jomtube/assets/images/question.png" alt="Click Here For Help"></a>
	       </td>
	   </tr>
	   <tr>
	       <td class="key"><?php echo JText::_('Video List Column Right width (px)');?></td>
	       <td>
                     <input type="text" value="<?php echo $this->c->width_jomtube_right_module ?>" name="width_jomtube_right_module" />
	       </td>
	       <td>
	           <a href="http://www.jomtube.com/dokuwiki/doku.php?id=video.list.right.column.width.px" title="Click Here For Help" target="_blank"><img src="<?php echo JURI::base()?>components/com_jomtube/assets/images/question.png" alt="Click Here For Help"></a>
	       </td>
	       <td>
	           NOTE** <span class="jomtube_message">Set to 0 to Hide Right Column<span>
	       </td>
	   </tr>
	</tbody>
    </table>
</fieldset>


<!--Category List Display Setting-->
<fieldset class="adminform">
    <legend><?php echo JText::_('CATEGORY DISPLAY AND CUSTOMS MODULE POSITION SETTINGS');?></legend>
    <table class="admintable" cellspacing="1">
        <col width="220"></col>
        <col width="100"></col>
        <col width="20"></col>
	<tbody>
	   <tr>
	       <td class="key"><?php echo JText::_('Categories Per Page');?></td>
	       <td>
                <input type="text" disabled="disabled" value="<?php echo $this->c->categories_per_page ?>" name="categories_per_page" />
	       </td>
	       <td>

	       </td>
			<td>
				<span class="jomtube_warning">NOT IMPLEMENTED YET - RESEARCHING</span>
	       </td>
	   </tr>
	   <tr>
	       <td class="key"><?php echo JText::_('Columns Per Page');?></td>
	       <td>
                <input type="text" disabled="disabled" value="<?php echo $this->c->columns_categories_per_page ?>" name="columns_categories_per_page" />
	       <td>

	       </td>
			<td>
				<span class="jomtube_warning">NOT IMPLEMENTED YET - RESEARCHING</span>
	       </td>
	   </tr>
	   <tr>
	       <td class="key"><?php echo JText::_('Category Column Left width (px)');?></td>
	       <td>
	           <input  type="text" value="<?php echo $this->c->category_column_left_width ?>" name="category_column_left_width" />
	       </td>
	       <td>
	           <a href="http://www.jomtube.com/dokuwiki/doku.php?id=category.left.column.width.px" title="Click Here For Help" target="_blank"><img src="<?php echo JURI::base()?>components/com_jomtube/assets/images/question.png" alt="Click Here For Help">
	       <td>
	           NOTE** <span class="jomtube_message">Set to 0 to Hide Right Column<span>
	       </td>
	   </tr>
	   <tr>
	       <td class="key"><?php echo JText::_('Category Column Center width (px)');?></td>
	       <td>
                <input  type="text" value="<?php echo $this->c->category_column_center_width ?>" name="category_column_center_width" />
	       </td>
	       <td>
	           <a href="http://www.jomtube.com/dokuwiki/doku.php?id=category.center.column.width.px" title="Click Here For Help" target="_blank"><img src="<?php echo JURI::base()?>components/com_jomtube/assets/images/question.png" alt="Click Here For Help">
	       </td>
	   </tr>
	   <tr>
	       <td class="key"><?php echo JText::_('Category Column Right width (px)');?></td>
	       <td>
                <input  type="text" value="<?php echo $this->c->category_column_right_width ?>" name="category_column_right_width" />
	       </td>
	       <td>
	           <a href="http://www.jomtube.com/dokuwiki/doku.php?id=category.right.column.width.px" title="Click Here For Help" target="_blank"><img src="<?php echo JURI::base()?>components/com_jomtube/assets/images/question.png" alt="Click Here For Help">
	       </td>
	       <td>
	           NOTE** <span class="jomtube_message">Set to 0 to Hide Right Column<span>
	       </td>
	   </tr>
	</tbody>
    </table>
</fieldset>


<!--Remote Video Display Setting-->
<fieldset class="adminform">
    <legend><?php echo JText::_('ADD REMOTE VIDEO DISPLAY AND CUSTOMS MODULE POSITION SETTINGS');?></legend>
    <table class="admintable" cellspacing="1">
        <col width="220"></col>
        <col width="100"></col>
        <col width="20"></col>
	<tbody>
	   <tr>
	       <td class="key"><?php echo JText::_('Remote Column Left width (px)');?></td>
	       <td>
	           <input  type="text" value="<?php echo $this->c->addvideo_column_left_width ?>" name="addvideo_column_left_width" />
	       </td>
	       <td>
	           <a href="http://www.jomtube.com/dokuwiki/doku.php?id=add.remote.video.left.column.width.px" title="Click Here For Help" target="_blank"><img src="<?php echo JURI::base()?>components/com_jomtube/assets/images/question.png" alt="Click Here For Help">
	       <td>
	           NOTE** <span class="jomtube_message">Set to 0 to Hide Right Column<span>
	       </td>
	   </tr>
	   <tr>
	       <td class="key"><?php echo JText::_('Remote Column Center width (px)');?></td>
	       <td>
                <input  type="text" value="<?php echo $this->c->addvideo_column_center_width ?>" name="addvideo_column_center_width" />
	       </td>
	       <td>
	           <a href="http://www.jomtube.com/dokuwiki/doku.php?id=add.remote.video.center.column.width.px" title="Click Here For Help" target="_blank"><img src="<?php echo JURI::base()?>components/com_jomtube/assets/images/question.png" alt="Click Here For Help"></a>
	       </td>
	   </tr>
	   <tr>
	       <td class="key"><?php echo JText::_('Remote Column Right width (px)');?></td>
	       <td>
               <input  type="text" value="<?php echo $this->c->addvideo_column_right_width ?>" name="addvideo_column_right_width" />
	       </td>
	       <td>
	           <a href="http://www.jomtube.com/dokuwiki/doku.php?id=add.remote.video.right.column.width.px" title="Click Here For Help" target="_blank"><img src="<?php echo JURI::base()?>components/com_jomtube/assets/images/question.png" alt="Click Here For Help"></a>
	       </td>
	       <td>
	           NOTE** <span class="jomtube_message">Set to 0 to Hide Right Column<span>
	       </td>
	   </tr>
	</tbody>
    </table>
</fieldset>

<!--Upload Display Setting-->
<fieldset class="adminform">
    <legend><?php echo JText::_('UPLOAD DISPLAY AND CUSTOMS MODULE POSITION SETTINGS');?></legend>
    <table class="admintable" cellspacing="1">
        <col width="220"></col>
        <col width="100"></col>
        <col width="20"></col>
	<tbody>
	   <tr>
	       <td class="key"><?php echo JText::_('Upload Column Left width (px)');?></td>
	       <td>
	           <input  type="text" value="<?php echo $this->c->upload_column_left_width ?>" name="upload_column_left_width" />
	       </td>
	       <td>
	           <a href="http://www.jomtube.com/dokuwiki/doku.php?id=upload.video.left.column.width.px" title="Click Here For Help" target="_blank"><img src="<?php echo JURI::base()?>components/com_jomtube/assets/images/question.png" alt="Click Here For Help"></a>
	       <td>
	           NOTE** <span class="jomtube_message">Set to 0 to Hide Right Column<span>
	       </td>
	   </tr>
	   <tr>
	       <td class="key"><?php echo JText::_('Upload Column Center width (px)');?></td>
	       <td>
                <input  type="text" value="<?php echo $this->c->upload_column_center_width ?>" name="upload_column_center_width" />
	       </td>
	       <td>
	           <a href="http://www.jomtube.com/dokuwiki/doku.php?id=upload.video.center.column.width.px" title="Click Here For Help" target="_blank"><img src="<?php echo JURI::base()?>components/com_jomtube/assets/images/question.png" alt="Click Here For Help"></a>
	       </td>
	   </tr>
	   <tr>
	       <td class="key"><?php echo JText::_('Upload Column Right width (px)');?></td>
	       <td>
                <input  type="text" value="<?php echo $this->c->upload_column_right_width ?>" name="upload_column_right_width" />
	       </td>
	       <td>
	           <a href="http://www.jomtube.com/dokuwiki/doku.php?id=upload.video.right.column.width.px" title="Click Here For Help" target="_blank"><img src="<?php echo JURI::base()?>components/com_jomtube/assets/images/question.png" alt="Click Here For Help"></a>
	       </td>
	       <td>
	           NOTE** <span class="jomtube_message">Set to 0 to Hide Right Column<span>
	       </td>
	   </tr>
	</tbody>
    </table>
</fieldset>