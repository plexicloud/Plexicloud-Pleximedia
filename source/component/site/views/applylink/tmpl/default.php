<?php
	defined('_JEXEC') or die('Restricted access');
?>
<div id="videomainbody">
<!--############ VIDEO LEFT COLUMN ###########-->
    <?php JTHelper::loadCustomVerticalModule('jomtube_addvideo_left', $this->c->addvideo_column_left_width, 'left')?>

<!--############ MIDDLE COLUMN ###########-->
	<div id="videomiddle-column" style="width:<?php echo $this->c->addvideo_column_center_width?>px">
		<div id="jomtube-addvideo-container">
		<div class="addremote-title">Apply Remote Video Link</div>
		    <table cellspacing="0" cellpadding="0" border="0" width="100%">
		        <?php if ($this->existed_video): //video existed?>
		            <tr>
		                <td valign="middle" align="center" height="100">
		                    <b>The VIDEO already exists in the Database and the duplicate will not be added</b>
		                    <br/>
		                    <b><a href="index.php?option=com_jomtube&view=video&id=<?php echo $this->existed_video->id ?>">View this video</a> | <a href="index.php?option=com_jomtube&view=addvideo">Add other video</a></b>
		                </td>
		            </tr>
		        <?php else:?>
		     		<tr>
		    			<td valign="top">
		          			<form action="<?php echo JRoute::_("index.php")?>" method="post" name="adminForm" id="adminForm">
		    					<table class="adminform">
				      		  		<tr>
				              			<td colspan="2"><p><b><?php echo _JTUBE_ADD_VIDEO_MESSAGE ?></b></p></td>
				      				</tr>
				      				<tr>
					      			   <td width="20%"><?php echo _JTUBE_ADD_VIDEO_INPUTLINK ?></td>
					      			   <td><input type="text" name="inputlink" style="width: 98%" value="<?php echo $this->inputlink?>"/></td>
				      				</tr>
				      		  		<tr>
				              			<td colspan="2" align="right"><input type="submit" value="<?php echo _JTUBE_ADD_VIDEO_APPLY_BUTTON ?>" onclick="this.disabled=true;"/>&nbsp;</td>
				      				</tr>
				    		  	</table>
		    					<input type="hidden" name="option" value="com_jomtube" />
		                      	<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
		                      	<input type="hidden" name="controller" value="videos" />
		                      	<input type="hidden" id="task" name="task" value="applylink" />
		          			</form>
		    			</td>
		    		</tr>
			    	<tr>
			        <td valign="top">
			          <b><?php echo _JTUBE_ADD_VIDEO_VIDEOINFO ?>:</b>
			          <br/>
			          <form action="<?php echo JRoute::_("index.php")?>" method="post" name="adminForm" id="adminForm" onsubmit="return checkvideo();">
			    			<table border="0" width="100%">
			    			 <tr>
			    			   <td >
			    			     <?php echo _VIDEOINFO_THUMBNAIL ?>
			    			   </td>
			      		    <td height="100">
			      		      <img src="<?php echo $this->videodetails['thumbnail_link']?>"/>
			      		    </td>
			      		  </tr>
			      		  <tr>
			              <td  width="20%">
			      				  <?php echo _VIDEOINFO_TITLE ?>
			      				</td>
			      				<td>
			      				  <input type="text" name="video_title" style="width: 400px" value="<?php echo $this->videodetails['title']?>" />
			      				</td>
			      			</tr>

			      			<tr>
			              <td >
			      				  <?php echo _VIDEOINFO_DESCRIPTION ?>
			      				</td>
			      				<td>
			      				  <textarea name="video_desc" style="width: 400px" rows="3"><?php echo $this->videodetails['videodescription']?></textarea>
			      				</td>
			      			</tr>

			      			<tr>
			              <td >
			      				  <?php echo _VIDEOINFO_TAGS ?>
			      				</td>
			      				<td>
			      				  <input type="text" name="tags" style="width: 400px" value="<?php echo $this->videodetails['videotags']?>"/>
			      				</td>
			      			</tr>

			      			<tr>
			              <td >
			      				  <?php echo _VIDEOINFO_DATE_ADDED ?>
			      				</td>
			      				<td>
			      				  <input type="text" name="date_added" style="width: 400px" value="<?php echo $this->videodetails['video_published']?>"/>
			      				</td>
			      			</tr>

			      			<tr>
			              <td >
			      				  <?php echo _VIDEOINFO_UPDATED ?>:
			      				</td>
			      				<td>
			      				  <input type="text" name="date_updated" style="width: 400px" value="<?php echo $this->videodetails['date_updated']?>"/>
			      				</td>
			      			</tr>

			      			<tr>
			              <td >
			      				  <?php echo _VIDEOINFO_DURATION ?>:
			      				</td>
			      				<td>
			      				  <input type="text" name="duration" style="width: 400px" value="<?php echo $this->videodetails['videoduration']?>"/>
			      				</td>
			      			</tr>

			      			<tr>
			              <td >
			      				  <?php echo _VIDEOINFO_CATEGORY ?>
			      				</td>
			      				<td>
			      				  <?php echo $this->parentSelect;?>
			      				</td>
			      			</tr>

			      			<tr>
			      			   <td colspan="2" align="center"><p><input type="submit" value="SUMBIT VIDEO" /></p></td>
			      			</tr>
			    		  </table>
			    		  <?php echo JHTML::_( 'form.token' ); ?>
			    		  <input type="hidden" name="video_thumb" value="<?php echo $this->videodetails['thumbnail_link']?>"/>
			          	  <input type="hidden" name="video_url" value="<?php echo $this->videodetails['video_url']?>"/>
			    		  <input type="hidden" name="video_type" value="<?php echo $this->videodetails['videoservertype']?>"/>
			    		  <input type="hidden" name="option" value="com_jomtube" />
			              <input type="hidden" name="id" value="" />
			              <input type="hidden" id="task" name="task" value="savelink" />
			              <input type="hidden" name="remote_id" value="<?php echo $this->videodetails['smallvideocode']?>" />
			      		  <input type="hidden" name="display_thumb" style="width: 400px" value="<?php echo $this->videodetails['display_thumb']?>"/>
			          </form>
			        </td>
			    	</tr>
			    	<?php endif;?>
			    </table>
		</div>
	</div>

<!--############ RIGHT COLUMN ###########-->
    <?php JTHelper::loadCustomVerticalModule('jomtube_addvideo_right', $this->c->addvideo_column_right_width, 'right')?>

</div>


<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>
