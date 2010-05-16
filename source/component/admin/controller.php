<?php
/**

 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

/**
 * Users Component Controller
 *
 * @package		Joomla
 * @subpackage	Users
 * @since 1.5
 */
class jomtubeController extends JController
{
    /**
	 * Constructor
	 *
	 * @params	array	Controller configuration array
	 */

    function __construct($config = array())
    {
        parent::__construct($config);

        /* Setup our toolbar buttons */
        JToolBarHelper::title( JText::_( 'jomtube! Settings' ), 'jomtube-configuration' );

        $taskName = JRequest::getVar('task', '');
        $viewName = JRequest::getVar('view', '');

        //JSubMenuHelper::addEntry(JText::_('Configuration'), 'index.php?option=com_jomtube&task=config', $taskName == "config");
        JSubMenuHelper::addEntry(JText::_('Configuration'), 'index.php?option=com_jomtube&view=configs', $viewName == "configs");
        JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_jomtube&view=categories', $viewName == "categories");
        JSubMenuHelper::addEntry(JText::_('Videos'), 'index.php?option=com_jomtube&view=videos', $viewName == "videos");

        /*
        * Added 2009-04-06
        * Version 1.0.5
        */
        JSubMenuHelper::addEntry(JText::_('Plugins'), 'index.php?option=com_jomtube&view=plugins', $viewName == "plugins");
        //JSubMenuHelper::addEntry(JText::_('Templates'), 'index.php?option=com_jomtube&view=templates', $viewName == "templates");
        //JSubMenuHelper::addEntry(JText::_('Languages'), 'index.php?option=com_jomtube&view=languages', $viewName == "languages");
        JSubMenuHelper::addEntry(JText::_('Import'), 'index.php?option=com_jomtube&view=import', $viewName == "import");
        /*End 2009-04-06*/

        parent::__construct();

    }

    /**
	 * Displays a view
	 */
    function display( )
    {
        global $mainframe;
        $document	= & JFactory::getDocument();
        //$params = clone($mainframe->getParams('com_jomtube'));
        $viewName = JRequest::getCmd( 'view', 'videos' );
        $viewType = $document->getType();
        $viewLayout = JRequest::getCmd( 'layout', 'default' );

        $view = &$this->getView($viewName, $viewType);
        $model_category = &$this->getModel('category');
        // Configure the view.
        $view->setModel($model_category, false);

        switch($this->getTask())
        {
            case "do_videoconfig":
                $model = &$this->getModel( 'video_display_settings' );
                $model->do_video_display_settings();
                $this->setup_video_display_variables();
                JToolBarHelper::save( 'do_videoconfig' );
                JRequest::setVar('view', 'video_display_settings');
                JRequest::setVar('saved', '1');
                break;
            case "":
                if (JRequest::getVar('view') == '')
                    JRequest::setVar('view', 'videos');
                break;
            case "config":
                $model = &$this->getModel( 'video_display_settings' );
                $this->setup_video_display_variables();
                JToolBarHelper::save( 'do_videoconfig' );
                JRequest::setVar('view', 'video_display_settings');
                break;
        }


        JToolBarHelper::back( 'back' );

        parent::display();
    }

}
