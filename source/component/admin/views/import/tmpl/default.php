<?php
    defined('_JEXEC') or die('Restricted access');
?>
<?php
    echo $this->pane->startPane( 'stat-pane' );

    /*Seyret tab*/
    $title = JText::_( 'Seyret' );
    echo $this->pane->startPanel( $title, 'seyret' );
    require_once($this->tmplpath . DS . 'seyret.php');
    echo $this->pane->endPanel();

    /*HWDVideoShare tab*/
    $title = JText::_('HWDVideoShare ');
    echo $this->pane->startPanel( $title, 'hwd' );
    require_once($this->tmplpath . DS . 'hwd.php');
    echo $this->pane->endPanel();

?>