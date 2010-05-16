<?php
    defined('_JEXEC') or die('Restricted access');
?>
<!--Install New Plugins-->
<script language="javascript" type="text/javascript">
<!--
	function submitbutton1() {
		var form = document.adminForm;

		// do field validation
		if (form.install_package.value == ""){
			alert( "<?php echo JText::_( 'Please select a package', true ); ?>" );
		} else {
			form.boxchecked.value = '1';
			form.task.value = 'doInstall';
			form.submit();
		}
	}
//-->
</script>
<form enctype="multipart/form-data" action="index.php" method="post" name="adminForm">
<fieldset class="adminform">
    <legend><?php echo JText::_('Install New Plugins');?></legend>
    <table class="admintable" cellspacing="1" width="100%">
	<tbody>
        <tr>
            <td>
                <table width="100%">
                	<tr>
                		<th colspan="2"><?php echo JText::_( 'Upload Package File' ); ?></th>
                	</tr>
                	<tr>
                		<td width="120">
                			<label for="install_package"><?php echo JText::_( 'Package File' ); ?>:</label>
                		</td>
                		<td>
                			<input class="input_box" id="install_package" name="install_package" type="file" size="57" />
                			<input class="button" type="button" value="<?php echo JText::_( 'Upload File' ); ?> &amp; <?php echo JText::_( 'Install' ); ?>" onclick="submitbutton1()" />
                		</td>
                	</tr>
            	</table>
            </td>
        </tr>
    	<?php echo JHTML::_( 'form.token' ); ?>
	</tbody>
    </table>
</fieldset>

<?php
    echo $this->pane->startPane( 'stat-pane' );

    /*Videoserver tab*/
    $title = JText::_( 'Video Server' );
    echo $this->pane->startPanel( $title, 'videoserver' );
    require_once($this->tmplpath . DS . 'videoserver.php');
    echo $this->pane->endPanel();

    /*Templates tab*/
    $title = JText::_('Templates');
    echo $this->pane->startPanel( $title, 'templates' );
    require_once($this->tmplpath . DS . 'templates.php');
    echo $this->pane->endPanel();

    /*Language tab*/
    $title = JText::_('Languages');
    echo $this->pane->startPanel( $title, 'languages' );
    require_once($this->tmplpath . DS . 'languages.php');
    echo $this->pane->endPanel();

    echo $this->pane->endPane();
?>
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="type" value="plugins" />
<input type="hidden" name="controller" value="plugins" />
<input type="hidden" name="option" value="com_jomtube" />
<input type="hidden" name="view" value="plugins" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="installtype" value="upload" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>