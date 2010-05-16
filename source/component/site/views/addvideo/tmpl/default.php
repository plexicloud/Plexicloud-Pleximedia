<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<script>
  function validate() {
    var inputlink = document.getElementById("inputlink").value;
    if (inputlink=='') {
      alert("Please copy your video links and paste into the inputbox");
      return false;
    }
  }
</script>


<div id="videomainbody">

<!--############ LEFT COLUMN ###########-->
    <?php JTHelper::loadCustomVerticalModule('jomtube_addvideo_left', $this->c->addvideo_column_left_width, 'left')?>

<!--############ MIDDLE COLUMN ###########-->
  	<div id="videomiddle-column" style="width:<?php echo $this->c->addvideo_column_center_width?>px">
		<div id="jomtube-addvideo-container">
		<div class="addremote-title">Add Remote Video</div>
    		<form onsubmit="return validate()" action="<?php echo JRoute::_("index.php")?>" method="post" name="adminForm" id="adminForm">
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td colspan="2"><p><b><?php echo _JTUBE_ADD_VIDEO_MESSAGE ?></b></p></td>
				</tr>
				<tr>
		   			<td height="45" width="15%"><?php echo _JTUBE_ADD_VIDEO_INPUTLINK ?></td>
		   			<td><input type="text" name="inputlink" style="width: 98%" id="inputlink"/></td>
				</tr>
				<tr>
    				<td colspan="2"><div style="float:right;margin-right:4px"><input id="submit" type="submit" value="<?php echo _JTUBE_ADD_VIDEO_APPLY_BUTTON ?>"/><div></td>
				</tr>
			</table>

			<?php echo JHTML::_( 'form.token' ); ?>
    		<input type="hidden" name="option" value="com_jomtube" />
    		<input type="hidden" id="task" name="task" value="applylink" />
			</form>
		</div>

	    <!--############## CUSTOM DISCLAIMER MODULE POSITION ###########-->
	    <div>
	          <?php
	          $jomtube_addvideo_disclaimer_modules = &JModuleHelper::getModules('addvideo_disclaimer');
	          foreach ($jomtube_addvideo_disclaimer_modules as $addvideo_disclaimer_module) {
	              echo "<div id=\"disclaimer-module\">";
	              echo JModuleHelper::renderModule($addvideo_disclaimer_module);
	              echo "</div>";
	          }
	            ?>
	    </div>
	    <!--############## CUSTOM DISCLAIMER MODULE POSITION END ###########-->

	</div>

<!--############ RIGHT COLUMN ###########-->
    <?php JTHelper::loadCustomVerticalModule('jomtube_addvideo_right', $this->c->addvideo_column_right_width, 'right')?>

</div>
