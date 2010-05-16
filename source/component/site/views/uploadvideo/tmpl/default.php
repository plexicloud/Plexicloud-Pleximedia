<?php
defined('_JEXEC') or die('Restricted access');
?>

<div id="videomainbody">
<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">

<!--############ LEFT COLUMN ###########-->
    <?php JTHelper::loadCustomVerticalModule('jomtube_upload_left', $this->c->upload_column_left_width, 'left')?>

<!--############ MIDDLE COLUMN ###########-->
    <div id="videomiddle-column" style="width:<?php echo $this->c->upload_column_center_width?>px">
        <?php if ($this->c->uploader_type == 'flashupload'):?>
            <!--####################################################-->
            <!--############ UPLOAD PROGRESS ###########-->
            <!--####################################################-->
            <script type="text/javascript" src="administrator/components/com_jomtube/assets/lib/FlashUploader/SolmetraUploader.js"></script>
            <script type="text/javascript">
            SolmetraUploader.setErrorHandler('test');
            function test (id, str) { alert('ERROR: ' + str); }
            SolmetraUploader.setEventHandler('testEvent');
            function testEvent (id, str, data) {
                //alert('EVENT: ' + str);
                //selected, uploading, error, complete
                if (str == 'uploading') {
                    document.getElementById("jomtube-upload-container").style.display="block";
                }
                if (str == 'complete') {
                    document.getElementById("btn_submit").disabled= false;//btn_submit
                }
                if (str == 'error') {
                    document.getElementById("btn_submit").disabled= true;//btn_submit
                }
            }
            </script>
            <?php
            // === Instantiate the class
            $jconfig = new JConfig();
            //var_dump($jconfig->sef);
            if ($jconfig->sef == '1')
                $flashuploader_path ='../administrator/components/com_jomtube/assets/lib/FlashUploader/';
            else
                $flashuploader_path ='administrator/components/com_jomtube/assets/lib/FlashUploader/';

            $solmetraUploader = new SolmetraUploader(
            $flashuploader_path,           // a base path to Flash Uploader's directory (relative to the page)
            'upload.php',       // path to a file that handles file uploads (relative to uploader.swf) [optional]
            'administrator/components/com_jomtube/assets/lib/FlashUploader/config.php'  // path to a server-side config file (relative to the page) [optional]
            );


                echo $solmetraUploader->getInstance('file',     // name of the field
                540,              // width
                40,              // height
                false,            // not required - allow form to be submitted
                true             // hijack form (recommended)
                //'uploader.xml',     // let's use different front-end config file than specified in the config.php
                // (please note that this URL is relative to this demo file)
                //true              // embed config (this will load front-end configuration XML file and embed it in the HTML)
                );
            ?>
        <?php endif;?>
        <?php if ($this->c->uploader_type == 'flashupload'):?>
            <div id="jomtube-upload-container" style="display: none;">
					<div class="uploadvideo-title">Upload Video To Server For Conversion</div>
        <?php endif;?>
        <?php if ($this->c->uploader_type == 'normal'):?>
            <div id="jomtube-upload-container">
			<div class="uploadvideo-title">Upload Video To Server For Conversion</div>
        <?php endif;?>
				<table cellspacing="0" cellpadding=4" border="0">
				    <?php if ($this->c->uploader_type == 'normal'):?>
				        <tr>
				        	<td><?php echo _VIDEOINFO_FILE ?></td>
				    		<td>
				    		  <?php if ($this->c->use_ffmpeg):?>
				    		      <input type="file" id="file" name="file" size="50" onchange="movie_LimitAttachVideo(this);" />
						      <?php else: ?>
						          <input type="file" id="file" name="file" size="50" onchange="movie_LimitAttachVideoNonFFmpeg(this);" />
					          <?php endif;?>
				            </td>
				    	</tr>
				    <?php endif;?>
				    <?php if (!$this->c->use_ffmpeg):?>
				        <tr>
				            <td><?php echo _VIDEOINFO_THUMBNAIL; ?></td>
				            <td><input type="file" id="thumbnail" name="thumbnail" size="50" onchange="thumbnail_LimitAttachVideo(this);" /></td>
				        </tr>
				    <?php endif;?>
					<tr>
				    	<td><?php echo _VIDEOINFO_CATEGORY ?></td>
						<td><?php echo $this->parentSelect;?></td>
					</tr>

					<tr>
				      	<td><?php echo _VIDEOINFO_TITLE ?></td>
						<td><input type="text" id="video_title" name="video_title" style="width: 400px" value="" /></td>
					</tr>
					<tr>
				    	<td><?php echo _VIDEOINFO_DESCRIPTION ?></td>
						<td><textarea name="video_desc" id="video_desc" style="width: 400px" rows="3"></textarea></td>
				    </tr>
					<tr>
				        <td><?php echo _VIDEOINFO_TAGS ?></td>
						<td><input type="text" name="tags" id="tags" style="width: 400px" value=""/></td>
				    </tr>

				    <tr>
				    	<td></td>
					<td align="right">
					   <?php if ($this->c->uploader_type == 'flashupload'):?>
				             <input type="submit" id="btn_submit" value="<?php echo _JTUBE_UPLOAD_VIDEO_UPLOAD_BUTTON; ?>" onclick="return checkVideoUpload();" disabled="true" >
				        <?php endif;?>
				        <?php if ($this->c->uploader_type == 'normal'):?>
				             <input type="submit" id="btn_submit" value="<?php echo _JTUBE_UPLOAD_VIDEO_UPLOAD_BUTTON; ?>" onclick="return checkVideoUpload();">
				        <?php endif;?>
					</td>
					</tr>
				</table>
			</div>

	    <!--############## CUSTOM DISCLAIMER MODULE POSITION ###########-->
	    <div>
	          <?php
	          $jomtube_upload_disclaimer_modules = &JModuleHelper::getModules('upload_disclaimer');
	          foreach ($jomtube_upload_disclaimer_modules as $upload_disclaimer_module) {
	              echo "<div id=\"disclaimer-module\">";
	              echo JModuleHelper::renderModule($upload_disclaimer_module);
	              echo "</div>";
	          }
	            ?>
	    </div>
	    <!--############## CUSTOM DISCLAIMER MODULE POSITION END ###########-->

	</div>
<!--############ RIGHT COLUMN ###########-->
    <?php JTHelper::loadCustomVerticalModule('jomtube_upload_right', $this->c->upload_column_right_width, 'right')?>

    <?php echo JHTML::_( 'form.token' ); ?>
    <input type="hidden" name="date_updated" value="<?php echo date("Y-m-d") ?>"/>
    <input type="hidden" name="date_added" value="<?php echo date("Y-m-d") ?>"/>
    <input type="hidden" name="video_type" value="local"/>
    <input type="hidden" name="option" value="com_jomtube" />
    <input type="hidden" name="id" value="" />
    <input type="hidden" id="task" name="task" value="uploadvideo" />
    </form>
</div>

<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>
