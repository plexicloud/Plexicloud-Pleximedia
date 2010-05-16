<?php
defined('_JEXEC') or die('Restricted access');
?>
<!--FFMPEG Test-->
<fieldset class="adminform">
    <legend><?php echo JText::_('Conversion Settings');?></legend>
    <table class="admintable" cellspacing="1">
	<tbody>
        <tr>
            <td class="key">Use ffmpeg</td>
            <td>
                <?php echo JHTML::_('select.genericlist', $this->yesno, 'use_ffmpeg', '', 'value', 'text', $this->c->use_ffmpeg); ?>
            </td>
        </tr>
        <tr>
            <td class="key">Use php-ffmpeg</td>
            <td>
                <?php echo JHTML::_('select.genericlist', $this->yesno, 'use_php_ffmpeg', '', 'value', 'text', $this->c->use_php_ffmpeg); ?>
            </td>
        </tr>
	</tbody>
    </table>
</fieldset>

<!--FFMPEG Settings-->
<fieldset class="adminform">
    <legend><?php echo JText::_('FFMPEG Settings');?></legend>
    <table class="admintable" cellspacing="1">
	<tbody>
	   <tr>
	       <td class="key" width="200"><?php echo JText::_('FFMPEG Path');?></td>
	       <td>
	           <input type="text" value="<?php echo $this->c->ffmpeg_path; ?>" name="ffmpeg_path" size="50" />
	       </td>
	   </tr>
	</tbody>
    </table>
</fieldset>

<!--Conversion of flv, mp4 videos Settings-->
<fieldset class="adminform">
    <legend><?php echo JText::_('Conversion of flv, mp4 Videos');?></legend>
    <table class="admintable" cellspacing="1">
	<tbody>
            <tr>
	       <td class="key"><?php echo JText::_('Re-convert flv videos');?></td>
	       <td>
                <?php echo JHTML::_('select.genericlist', $this->yesno, 're_convert_mp4_normal', '', 'value', 'text', $this->c->re_convert_mp4_normal); ?>
	       </td>
	   </tr>
	   <tr>
	       <td class="key"><?php echo JText::_('Re-convert mp4 videos');?></td>
	       <td>
                    <?php echo JHTML::_('select.genericlist', $this->yesno, 're_convert_flv', '', 'value', 'text', $this->c->re_convert_flv); ?>
	       </td>
	   </tr>
	</tbody>
    </table>
</fieldset>

<!--Video Conversion Settings-->
<fieldset class="adminform">
    <legend><?php echo JText::_('Video Conversion Settings');?></legend>
    <table class="admintable" cellspacing="1">
	<tbody>
	   <!--Video Frame Size-->
            <tr>
	       <td class="key"><?php echo JText::_('Video Frame Size');?></td>
	       <td>
                    <?php echo JHTML::_('select.genericlist', $this->convert_frame_size, 'convert_frame_size', '', 'value', 'text', $this->c->convert_frame_size); ?>
	       </td>
	   </tr>
	   <!--Video Bitrate-->
	   <tr>
	       <td class="key"><?php echo JText::_('Video Bitrate');?></td>
	       <td>
                    <?php echo JHTML::_('select.genericlist', $this->convert_video_bitrate, 'convert_video_bitrate', '', 'value', 'text', $this->c->convert_video_bitrate); ?>
	       </td>
	   </tr>
	   <!--Audio Bitrate-->
	   <tr>
	       <td class="key"><?php echo JText::_('Audio Bitrate');?></td>
	       <td>
                    <?php echo JHTML::_('select.genericlist', $this->convert_audio_bitrate, 'convert_audio_bitrate', '', 'value', 'text', $this->c->convert_audio_bitrate); ?>
	       </td>
	   </tr>
	   <!--Delete Original Videos-->
	   <tr>
	       <td class="key"><?php echo JText::_('Delete Original Videos');?></td>
	       <td>
                    <?php echo JHTML::_('select.genericlist', $this->yesno, 'delete_orignial_file', '', 'value', 'text', $this->c->delete_orignial_file); ?>
	       </td>
	   </tr>
	</tbody>
    </table>
</fieldset>

<!--Thumbnail Extraction  Settings-->
<!--
<fieldset class="adminform">
    <legend><?php echo JText::_('Thumbnail Extraction  Settings');?></legend>
    <table class="admintable" cellspacing="1">
        <col width""></col>
        <col width="400"></col>
        <col width=""></col>
	<tbody>
            <tr>
	       <td class="key"><?php echo JText::_('Thumbnail Dimensions');?></td>
	       <td>

	       </td>
	   </tr>
	   <tr>
	       <td class="key"><?php echo JText::_('Display Thumbnail Dimensions');?></td>
	       <td>

	       </td>
	       <td>This will be used for flash player preview image</td>
	   </tr>
	</tbody>
    </table>
</fieldset>
-->
<!--High Definition Encoding(H264) Settings-->
<fieldset class="adminform">
    <legend><span class="jomtube_warning"><?php echo JText::_('High Definition Encoding(H264) Settings');?></span></legend>
    <table class="admintable" cellspacing="1">
        <col width""></col>
        <col width="400"></col>
        <col width=""></col>
	<tbody>
	   <!--Convert To MP4-->
            <tr>
	       <td class="key"><?php echo JText::_('Convert To MP4');?></td>
	       <td>
                    <?php echo JHTML::_('select.genericlist', $this->yesno, 'h264_convert2mp4', '', 'value', 'text', $this->c->h264_convert2mp4); ?>
	       </td>
	       <td>
	           As defined by Adobe, H.264 is the next-generation video compression technology in the MPEG-4 standard, also known as MPEG-4 Part 10 (ISO/IEC 14496-10). x264 is a free software library for encoding H.264/MPEG-4 AVC video streams. This involves the addition of x264 encoding options to JomTube
	       </td>
	   </tr>
	   <!--Re-convert MP4 Videos-->
	   <tr>
	       <td class="key"><?php echo JText::_('Re-convert MP4 Videos');?></td>
	       <td>
                    <?php echo JHTML::_('select.genericlist', $this->yesno, 'h264_re_convert2mp4', '', 'value', 'text', $this->c->h264_re_convert2mp4); ?>
	       </td>
	       <td>
	           Do you want to re-convert MP4 video using your conversions settings or just copy them directly in to Jomtube Video Gallery? (If you do not have the required third party software the mp4 files will just be copied directly into the upload folder)
	       </td>
	   </tr>
	   <!--MP4 Quality-->
	   <tr>
	       <td class="key"><?php echo JText::_('MP4 Quality');?></td>
	       <td>
                    <?php echo JHTML::_('select.genericlist', $this->h264_quality, 'h264_quality', '', 'value', 'text', $this->c->h264_quality); ?>
	       </td>
	   </tr>
	</tbody>
    </table>
</fieldset>

<!--Conversion of MP3 Settings-->
<!--
<fieldset class="adminform">
    <legend><?php echo JText::_('Conversoin of MP3 Audios');?></legend>
    <table class="admintable" cellspacing="1">
        <col width""></col>
        <col width="400"></col>
        <col width=""></col>
	<tbody>
	   <tr>
	       <td class="key"><?php echo JText::_('Re-convert MP3 Audio');?></td>
	       <td>
                    <?php echo JHTML::_('select.genericlist', $this->yesno, 'h264_re_convert2mp4', '', 'value', 'text', $this->c->h264_re_convert2mp4); ?>
	       </td>
	       <td>
	           Do you want to re-convert MP4 video using your conversions settings or just copy them directly in to Jomtube Video Gallery? (If you do not have the required third party software the mp4 files will just be copied directly into the upload folder)
	       </td>
	   </tr>
	   <tr>
	       <td class="key"><?php echo JText::_('MP3 Quality');?></td>
	       <td>
                    <?php echo JHTML::_('select.genericlist', $this->h264_quality, 'h264_quality', '', 'value', 'text', $this->c->h264_quality); ?>
	       </td>
	   </tr>
	</tbody>
    </table>
</fieldset>
-->