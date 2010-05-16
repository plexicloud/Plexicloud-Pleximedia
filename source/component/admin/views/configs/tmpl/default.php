<?php
    defined('_JEXEC') or die('Restricted access');
?>
<script language="javascript" src="<?php echo JURI::base() ?>components/com_jomtube/assets/js/form_controls.js"></script>
<form action="index.php?option=com_jomtube" method="post" id="adminForm" name="adminForm">

<!--CHECK PERMISSION CONFIG FILE-->
<table class="admintable" cellspacing="0">
    <tbody>
       <tr>
           <td>
               <?php
                   /*Check permission*/
                        $configfile = 'components/com_jomtube/configs/configs.jomtube.php';
                        $permission = is_writeable($configfile);
                        if (!$permission) {
                            echo "<span style='color: red; font-weight: bold;'>The configuration file is unwriteable</span>";
                        } else {
                            echo "<span style='color: #6DB03C; font-weight: bold'>The configuration file is writeable</span>";
                        }
                ?>
           </td>
       </tr>
    </tbody>
</table>

<?php
    echo $this->pane->startPane( 'stat-pane' );

    /*Setup tab*/
    $title = JText::_( 'Setup' );
    echo $this->pane->startPanel( $title, 'setup' );
    require_once($this->tmplpath . DS . 'setup.php');
    echo $this->pane->endPanel();

    /*Display tab*/
    $title = JText::_('Display');
    echo $this->pane->startPanel( $title, 'display' );
    require_once($this->tmplpath . DS . 'display.php');
    echo $this->pane->endPanel();

    /*Display tab*/
    $title = JText::_('Custom Positions');
    echo $this->pane->startPanel( $title, 'Custom Positions' );
    require_once($this->tmplpath . DS . 'positions.php');
    echo $this->pane->endPanel();

    /*Access Settings*/
    $title = JText::_('Access');
    echo $this->pane->startPanel( $title, 'access' );
    require_once($this->tmplpath . DS . 'access.php');
    echo $this->pane->endPanel();

    /*Integration*/
    $title = JText::_('Integration');
    echo $this->pane->startPanel( $title, 'integration' );
    require_once($this->tmplpath . DS . 'integration.php');
    echo $this->pane->endPanel();

    /*Convertion*/
    $title = JText::_('Conversion');
    echo $this->pane->startPanel( $title, 'conversion' );
    require_once($this->tmplpath . DS . 'conversion.php');
    echo $this->pane->endPanel();

    /*Upload*/
    $title = JText::_('Upload');
    echo $this->pane->startPanel( $title, 'module' );
    require_once($this->tmplpath . DS . 'upload.php');
    echo $this->pane->endPanel();

    /*Player*/
    $title = JText::_('JW Player Skins');
    echo $this->pane->startPanel( $title, 'module' );
    require_once($this->tmplpath . DS . 'player.php');
    echo $this->pane->endPanel();

    /*Custom module*/
    /*$title = JText::_('Custom Module');
    echo $this->pane->startPanel( $title, 'module' );
    require_once($this->tmplpath . DS . 'custom_module.php');
    echo $this->pane->endPanel();*/

    echo $this->pane->endPane();
?>
<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="option" value="com_jomtube" />
<input type="hidden" name="controller" value="configs" />
<input type="hidden" name="view" value="configs" />
<input type="hidden" name="task" value="save" />
</form>