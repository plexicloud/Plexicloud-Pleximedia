<?php // no direct access
defined('_JEXEC') or die('Restricted access');

// #######################################################
// #################### BEHAVIOUR PARAMETERS FOR JW PLAYER
// #######################################################
?>

<?php if ($this->row->video_type != 'local' && $this->row->video_type != 'remote'  && $this->row->video_type != NULL):?>
  	<?php echo $this->embed;?>
<?php else :?>
        <?php $mosConfig_live_site = substr(JURI::base(), 0, strlen(JURI::base()) -1);?>
        <?php
        if ($this->row->video_type == 'remote') {
            $file = $this->row->video_url;
        } else {
            $file = $mosConfig_live_site . $this->row->directory . '/' . $this->row->video_url;
        }
        ?>
<embed src="<?php echo $mosConfig_live_site?>/components/com_jomtube/assets/swf/player.swf" height="<?php echo $this->vidheight;?>" width="<?php echo $this->vidwidth;?>" bgcolor="#FFFFFF" allowscriptaccess="always" allowfullscreen="true" flashvars="file=<?php echo $file?>&stretching=fill&autostart=<?php echo $this->autostart?>&skin=<?php echo $mosConfig_live_site?>/components/com_jomtube/assets/swf/skins/<?php echo $this->c->jw_player_skin ?>.swf&lightcolor=#ff6600&controlbar=over"
/>

<?php endif;?>