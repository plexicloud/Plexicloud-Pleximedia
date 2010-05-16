<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );
//jimport( 'joomla.installer.installer' );
jimport('joomla.installer.helper');
require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jomtube'.DS.'helpers'.DS.'JomTubeInstaller.php');

class jomtubeModelPlugins  extends JModel
{
    /**
	 * Category data array
	 *
	 * @var array
	 */
    var $_data = null;

    /**
	 * Category total
	 *
	 * @var integer
	 */
    var $_total = null;

    /**
	 * Pagination object
	 *
	 * @var object
	 */
    var $_pagination = null;

    /**
	 * Categorie id
	 *
	 * @var int
	 */
    var $_id = null;

    /**
	 * Constructor
	 *
	 * @since 0.9
	 */
    function __construct()
    {
        parent::__construct();
        global $mainframe, $option;
    }

    /**
	 * Method to set the category identifier
	 *
	 * @access	public
	 * @param	int Category identifier
	 */
    function setId($id)
    {
        // Set id and wipe data
        $this->_id	 = $id;
        $this->_data = null;
    }

    function install()
	{
		global $mainframe;

		$this->setState('action', 'install');

		$package = $this->_getPackageFromUpload();

        //var_dump($package); exit();

		// Was the package unpacked?
		if (!$package) {
			$this->setState('message', 'Unable to find install package');
			return false;
		}

		// Get a database connector
		//$db = & JFactory::getDBO();

		// Get an installer instance
		$installer =& JomTubeInstaller::getInstance();

		// Install the package
		if (!$installer->install($package['dir'])) {
			// There was an error installing the package
			$msg = JText::sprintf('INSTALLEXT', JText::_($package['type']), JText::_('Error'));
			$result = false;
		} else {
			// Package installed sucessfully
			$msg = JText::sprintf('INSTALLEXT', JText::_($package['type']), JText::_('Success'));
			$result = true;
		}

		// Set some model state values
		$mainframe->enqueueMessage($msg);
		$this->setState('name', $installer->get('name'));
		$this->setState('result', $result);
		$this->setState('message', $installer->message);
		$this->setState('extension.message', $installer->get('extension.message'));

		// Cleanup the install files
		if (!is_file($package['packagefile'])) {
			$config =& JFactory::getConfig();
			$package['packagefile'] = $config->getValue('config.tmp_path').DS.$package['packagefile'];
		}

		JInstallerHelper::cleanupInstall($package['packagefile'], $package['extractdir']);

		return $result;
	}

    /**
	 * Remove (uninstall) an extension
	 *
	 * @static
	 * @param	array	An array of identifiers
	 * @return	boolean	True on success
	 * @since 1.0
	 */
	function remove($eid=array())
	{
		global $mainframe;

		// Initialize variables
		$failed = array ();

		/*
		 * Ensure eid is an array of extension ids in the form id => client_id
		 * TODO: If it isn't an array do we want to set an error and fail?
		 */
		if (!is_array($eid)) {
			$eid = array($eid => 0);
		}

		// Get a database connector
		$db =& JFactory::getDBO();

		// Get an installer object for the extension type
		jimport('joomla.installer.installer');
		$installer = & JomTubeInstaller::getInstance();

		// Uninstall the chosen extensions

		foreach ($eid as $id => $clientId)
		{
			$id		= trim( $id );
			$result	= $installer->uninstall('JomTubePlugin', $clientId, $clientId );

			// Build an array of extensions that failed to uninstall
			if ($result === false) {
				$failed[] = $clientId;
			}
		}

		if (count($failed)) {
			// There was an error in uninstalling the package
			$msg = JText::sprintf('UNINSTALLEXT', JText::_('JomTube Plugin'), JText::_('Error'));
			$result = false;
		} else {
			// Package uninstalled sucessfully
			$msg = JText::sprintf('UNINSTALLEXT', JText::_($this->_type), JText::_('Success'));
			$result = true;
		}

		$mainframe->enqueueMessage($msg);
		$this->setState('action', 'remove');
		$this->setState('name', $installer->get('name'));
		$this->setState('message', $installer->message);
		$this->setState('extension.message', $installer->get('extension.message'));

		return $result;
	}

	/**
	 * @param string The class name for the installer
	 */
	function _getPackageFromUpload()
	{
		// Get the uploaded file information
		$userfile = JRequest::getVar('install_package', null, 'files', 'array' );

		// Make sure that file uploads are enabled in php
		if (!(bool) ini_get('file_uploads')) {
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('WARNINSTALLFILE'));
			return false;
		}

		// Make sure that zlib is loaded so that the package can be unpacked
		if (!extension_loaded('zlib')) {
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('WARNINSTALLZLIB'));
			return false;
		}

		// If there is no uploaded file, we have a problem...
		if (!is_array($userfile) ) {
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('No file selected'));
			return false;
		}

		// Check if there was a problem uploading the file.
		if ( $userfile['error'] || $userfile['size'] < 1 )
		{
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('WARNINSTALLUPLOADERROR'));
			return false;
		}

		// Build the appropriate paths
		$config =& JFactory::getConfig();
		$tmp_dest 	= $config->getValue('config.tmp_path').DS.$userfile['name'];
		$tmp_src	= $userfile['tmp_name'];

		// Move uploaded file
		jimport('joomla.filesystem.file');
		$uploaded = JFile::upload($tmp_src, $tmp_dest);

		// Unpack the downloaded package file
		$package = JInstallerHelper::unpack($tmp_dest);

		return $package;
	}
    /**
	 * Method to get categories item data
	 *
	 * @access public
	 * @return array
	 */
    function getData($type)
    {
        // Lets load the content if it doesn't already exist
        //if (empty($this->_data))
        //{
            $query = $this->_buildQuery($type);
            $this->_data = $this->_getList($query);
        //}

        return $this->_data;
    }

    /**
	 * Method to get the total nr of the plugins
	 *
	 * @access public
	 * @return integer
	 */
    function getTotal($type='thirdparty')
    {
        // Lets load the content if it doesn't already exist
        if (empty($this->_total))
        {
            $query = $this->_buildQuery($type);
            $this->_total = $this->_getListCount($query);
        }

        return $this->_total;
    }

    /**
	 * Method to get a pagination object for the plugins
	 *
	 * @access public
	 * @return integer
	 */
    function getPagination()
    {
        // Lets load the content if it doesn't already exist
        if (empty($this->_pagination))
        {
            jimport('joomla.html.pagination');
            $this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
        }

        return $this->_pagination;
    }

    /**
	 * Method to build the query for the plugins
	 *
	 * @access private
	 * @return integer
	 * @since 0.9
	 */
    function _buildQuery($type)
    {
        $query = 'SELECT * '
        . ' FROM #__jomtube_plugins WHERE type = \'' . $type . '\' ORDER BY iscore, name '
        ;
        return $query;
    }

    function store($data)
    {
        $row  =& $this->getTable('jomtube_plugins', '');
        //var_dump($data); exit();
        // bind it to the table
        if (!$row->bind($data)) {
            JError::raiseError(500, $this->_db->getErrorMsg() );
            return false;
        }

        // Make sure the data is valid
        if (!$row->check()) {
            $this->setError($row->getError());
            return false;
        }

        // Store it in the db
        if (!$row->store()) {
            JError::raiseError(500, $this->_db->getErrorMsg() );
            return false;
        }

        return $row->id;
    }
}

?>
