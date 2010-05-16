<?php
    defined('_JEXEC') or die('Restricted access');
?>
<?php $mosConfig_live_site = substr(JURI::base(), 0, strlen(JURI::base()) -15);?>
<fieldset class="adminform">
<table class="noshow" border="0">
    <col width="25%"></col>
    <tr>
        <td align="left">
            <fieldset class="adminform">
                <legend><?php echo JText::_('Bekle Skin');?> <input type="radio" name="jw_player_skin" value="bekle" <?php echo $this->c->jw_player_skin == "bekle" ? "checked" : "" ?> /></legend>
                <table class="admintable" cellspacing="1"width="100%">
            	<tbody>
            	   <tr>
            	       <td align="center">
            	           <embed src="<?php echo $mosConfig_live_site?>/components/com_jomtube/assets/swf/player.swf"bgcolor="#FFFFFF" allowscriptaccess="always" allowfullscreen="true" flashvars="stretching=fill&autostart=false&skin=<?php echo $mosConfig_live_site?>/components/com_jomtube/assets/swf/skins/bekle.swf&lightcolor=#ff6600&controlbar=over"/>
            	       </td>
            	   </tr>
            	</tbody>
                </table>
            </fieldset>
        </td>

        <td>
            <fieldset class="adminform">
                <legend><?php echo JText::_('Blue Metal Skin');?> <input type="radio" name="jw_player_skin" value="bluemetal" <?php echo $this->c->jw_player_skin == "bluemetal" ? "checked" : "" ?> /></legend>
               <table class="admintable" cellspacing="1"width="100%">
            	<tbody>
            	   <tr>
            	       <td align="center">
            	           <embed src="<?php echo $mosConfig_live_site?>/components/com_jomtube/assets/swf/player.swf" bgcolor="#FFFFFF" allowscriptaccess="always" allowfullscreen="true" flashvars="stretching=fill&autostart=false&skin=<?php echo $mosConfig_live_site?>/components/com_jomtube/assets/swf/skins/bluemetal.swf&lightcolor=#ff6600&controlbar=over"/>
            	       </td>
            	   </tr>
            	</tbody>
                </table>

            </fieldset>
        </td>

        <td>
            <fieldset class="adminform">
                <legend><?php echo JText::_('DangDang Skin');?> <input type="radio" name="jw_player_skin" value="dandang" <?php echo $this->c->jw_player_skin == "dangdang" ? "checked" : "" ?> /></legend>
                <table class="admintable" cellspacing="1"width="100%">
            	<tbody>
            	   <tr>
            	       <td align="center">
            	           <embed src="<?php echo $mosConfig_live_site?>/components/com_jomtube/assets/swf/player.swf" bgcolor="#FFFFFF" allowscriptaccess="always" allowfullscreen="true" flashvars="stretching=fill&autostart=false&skin=<?php echo $mosConfig_live_site?>/components/com_jomtube/assets/swf/skins/dandang.swf&lightcolor=#ff6600&controlbar=over"/>
            	       </td>
            	   </tr>
            	</tbody>
                </table>
            </fieldset>
        </td>

        <td>
            <fieldset class="adminform">
                <legend><?php echo JText::_('Metarby 10 Skin');?> <input type="radio" name="jw_player_skin" value="metarby10" <?php echo $this->c->jw_player_skin == "metarby10" ? "checked" : "" ?> /></legend>
                <table class="admintable" cellspacing="1"width="100%">
            	<tbody>
            	   <tr>
            	       <td align="center">
            	           <embed src="<?php echo $mosConfig_live_site?>/components/com_jomtube/assets/swf/player.swf" bgcolor="#FFFFFF" allowscriptaccess="always" allowfullscreen="true" flashvars="stretching=fill&autostart=false&skin=<?php echo $mosConfig_live_site?>/components/com_jomtube/assets/swf/skins/metarby10.swf&lightcolor=#ff6600&controlbar=over"/>
            	       </td>
            	   </tr>
            	</tbody>
                </table>

            </fieldset>
        </td>
    </tr>



    <tr>
        <td>
            <fieldset class="adminform">
                <legend><?php echo JText::_('Modieus Skin');?> <input type="radio" name="jw_player_skin" value="modieus" <?php echo $this->c->jw_player_skin == "modieus" ? "checked" : "" ?> /></legend>
                <table class="admintable" cellspacing="1"width="100%">
            	<tbody>
            	   <tr>
            	       <td align="center">
            	           <embed src="<?php echo $mosConfig_live_site?>/components/com_jomtube/assets/swf/player.swf"  bgcolor="#FFFFFF" allowscriptaccess="always" allowfullscreen="true" flashvars="stretching=fill&autostart=false&skin=<?php echo $mosConfig_live_site?>/components/com_jomtube/assets/swf/skins/modieus.swf&lightcolor=#ff6600&controlbar=over"/>
            	       </td>
            	   </tr>
            	</tbody>
                </table>
            </fieldset>
        </td>

        <td>
            <fieldset class="adminform">
                <legend><?php echo JText::_('Nacht  v1.0 Skin');?> <input type="radio" name="jw_player_skin" value="nacht" <?php echo $this->c->jw_player_skin == "nacht" ? "checked" : "" ?> /></legend>
                <table class="admintable" cellspacing="1"width="100%">
            	<tbody>
            	   <tr>
            	       <td align="center">
            	           <embed src="<?php echo $mosConfig_live_site?>/components/com_jomtube/assets/swf/player.swf"  bgcolor="#FFFFFF" allowscriptaccess="always" allowfullscreen="true" flashvars="stretching=fill&autostart=false&skin=<?php echo $mosConfig_live_site?>/components/com_jomtube/assets/swf/skins/nacht.swf&lightcolor=#ff6600&controlbar=over"/>
            	       </td>
            	   </tr>
            	</tbody>
                </table>

            </fieldset>
        </td>

        <td>
            <fieldset class="adminform">
                <legend><?php echo JText::_('Snel  v1.0 Skin');?> <input type="radio" name="jw_player_skin" value="snel" <?php echo $this->c->jw_player_skin == "snel" ? "checked" : "" ?> /></legend>
                <table class="admintable" cellspacing="1"width="100%">
            	<tbody>
            	   <tr>
            	       <td align="center">
            	           <embed src="<?php echo $mosConfig_live_site?>/components/com_jomtube/assets/swf/player.swf" bgcolor="#FFFFFF" allowscriptaccess="always" allowfullscreen="true" flashvars="stretching=fill&autostart=false&skin=<?php echo $mosConfig_live_site?>/components/com_jomtube/assets/swf/skins/snel.swf&lightcolor=#ff6600&controlbar=over"/>
            	       </td>
            	   </tr>
            	</tbody>
                </table>
            </fieldset>
        </td>

        <td>
            <fieldset class="adminform">
                <legend><?php echo JText::_('Stijl  v1.0 Skin');?> <input type="radio" name="jw_player_skin" value="stylish_slim" <?php echo $this->c->jw_player_skin == "stylish_slim" ? "checked" : "" ?> /></legend>
                <table class="admintable" cellspacing="1"width="100%">
            	<tbody>
            	   <tr>
            	       <td align="center">
            	           <embed src="<?php echo $mosConfig_live_site?>/components/com_jomtube/assets/swf/player.swf" bgcolor="#FFFFFF" allowscriptaccess="always" allowfullscreen="true" flashvars="stretching=fill&autostart=false&skin=<?php echo $mosConfig_live_site?>/components/com_jomtube/assets/swf/skins/stylish_slim.swf&lightcolor=#ff6600&controlbar=over"/>
            	       </td>
            	   </tr>
            	</tbody>
                </table>

            </fieldset>
        </td>
    </tr>
</table>
</fieldset>