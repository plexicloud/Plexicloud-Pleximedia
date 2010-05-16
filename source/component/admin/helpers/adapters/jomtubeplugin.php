<?php
/**
 * @version		$Id:plugin.php 6961 2007-03-15 16:06:53Z tcp $
 * @package		Joomla.Framework
 * @subpackage	Installer
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

/**
 * Plugin installer
 *
 * @package		Joomla.Framework
 * @subpackage	Installer
 * @since		1.5
 */
class InstallerJomTubePlugin extends JObject
{
	/**
	 * Constructor
	 *
	 * @access	protected
	 * @param	object	$parent	Parent object [JInstaller instance]
	 * @return	void
	 * @since	1.5
	 */
	function __construct(&$parent)
	{
		$this->parent =& $parent;
	}

	/**
	 * Custom install method
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function install()
	{
		// Get a database connector object
		$db =& $this->parent->getDBO();

		// Get the extension manifest object
		$manifest =& $this->parent->getManifest();
		$this->manifest =& $manifest->document;

		/**
		 * ---------------------------------------------------------------------------------------------
		 * Manifest Document Setup Section
		 * ---------------------------------------------------------------------------------------------
		 */

		// Set the plugin name
		$name =& $this->manifest->getElementByPath('name');
		$name = JFilterInput::clean($name->data(), 'string');
		$this->set('name', $name);

		// Get the component description
		$description = & $this->manifest->getElementByPath('description');
		//var_dump($description); exit();
		if (is_a($description, 'JSimpleXMLElement')) {
			$this->parent->set('message', $description->data());
			$this->set('description', $description->data());
		} else {
			$this->parent->set('message', '' );
			$this->set('description', '');
		}

		// Set the plugin author
		$author =& $this->manifest->getElementByPath('author');
		$author = JFilterInput::clean($author->data(), 'string');
		$this->set('author', $author);

		// Set the plugin url
		$url =& $this->manifest->getElementByPath('url');
		$url = JFilterInput::clean($url->data(), 'string');
		$this->set('url', $url);

		/*
		 * Backward Compatability
		 * @todo Deprecate in future version
		 */
		$type = $this->manifest->attributes('type');

		// Set the installation path
		$element =& $this->manifest->getElementByPath('files');
		if (is_a($element, 'JSimpleXMLElement') && count($element->children())) {
			$files =& $element->children();
			//var_dump($files); exit();
			foreach ($files as $file) {
				if ($file->attributes($type)) {
					$pname = $file->attributes($type);
					break;
				}
			}
		}
		$group = $this->manifest->attributes('group');
		//var_dump($pname); exit();
		if (!empty ($pname) && !empty($group)) {
		    switch ($group) {
		        case 'thirdparty':
		            $this->parent->setPath('extension_root', JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jomtube'.DS.'plugins'.DS.'videoserver');
		            $folder = "administrator/components/com_jomtube/plugins/videoserver";
		            break;
		        case 'template':
		            $this->parent->setPath('extension_root', JPATH_COMPONENT_SITE.DS.'assets'.DS.'styles'.DS.$pname);
		            $folder = "components/com_jomtube/assets/styles/".$pname;
		            break;
		    }

		} else {
			$this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.JText::_('No plugin file specified'));
			return false;
		}

		/**
		 * ---------------------------------------------------------------------------------------------
		 * Filesystem Processing Section
		 * ---------------------------------------------------------------------------------------------
		 */

		// If the plugin directory does not exist, lets create it
		$created = false;
		if (!file_exists($this->parent->getPath('extension_root'))) {
			if (!$created = JFolder::create($this->parent->getPath('extension_root'))) {
				$this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.JText::_('Failed to create directory').': "'.$this->parent->getPath('extension_root').'"');
				return false;
			}
		}

		/*
		 * If we created the plugin directory and will want to remove it if we
		 * have to roll back the installation, lets add it to the installation
		 * step stack
		 */
		if ($created) {
			$this->parent->pushStep(array ('type' => 'folder', 'path' => $this->parent->getPath('extension_root')));
		}

		// Copy all necessary files
		if ($this->parent->parseFiles($element, -1) === false) {
			// Install failed, roll back changes
			$this->parent->abort();
			return false;
		}

		// Parse optional tags -- media and language files for plugins go in admin app
		$this->parent->parseMedia($this->manifest->getElementByPath('media'), 1);
		$this->parent->parseLanguages($this->manifest->getElementByPath('languages'), 1);

		/**
		 * ---------------------------------------------------------------------------------------------
		 * Database Processing Section
		 * ---------------------------------------------------------------------------------------------
		 */

		// Check to see if a plugin by the same name is already installed
		$query = 'SELECT `id`' .
				' FROM `#__jomtube_plugins`' .
				' WHERE type = '.$db->Quote($group) .
				' AND element = '.$db->Quote($pname);
		$db->setQuery($query);
		if (!$db->Query()) {
			// Install failed, roll back changes
			$this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.$db->stderr(true));
			return false;
		}
		$id = $db->loadResult();

		// Was there a module already installed with the same name?
		if ($id) {

			if (!$this->parent->getOverwrite())
			{
				// Install failed, roll back changes
				$this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.JText::_('Plugin').' "'.$pname.'" '.JText::_('already exists!'));
				return false;
			}

		} else {
		    $query = "INSERT INTO #__jomtube_plugins (`name`, `element`, `type`, `folder`, `params`, `author`, `url`, `description`)
		              VALUES (\"".$this->get('name')."\", \"$pname\", \"$group\", \"$folder\"
		              , \"".$this->parent->getParams()."\", \"".$this->get('author')."\", \"".$this->get('url')."\", \"".$this->get('description')."\")";
			//echo $query; exit();
		    $db->setQuery($query);

			// Editor plugins are published by default
			if ($group == 'editors') {
				$row->published = 1;
			}

			if (!$db->Query()) {
				// Install failed, roll back changes
				$this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.$db->stderr(true));
				return false;
			}

			// Since we have created a plugin item, we add it to the installation step stack
			// so that if we have to rollback the changes we can undo it.
			$this->parent->pushStep(array ('type' => 'plugin', 'id' => $row->id));
		}

		/**
		 * ---------------------------------------------------------------------------------------------
		 * Finalization and Cleanup Section
		 * ---------------------------------------------------------------------------------------------
		 */

		// Lastly, we will copy the manifest file to its appropriate place.
		if (!$this->parent->copyManifest(-1)) {
			// Install failed, rollback changes
			$this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.JText::_('Could not copy setup file'));
			return false;
		}
		return true;
	}

	/**
	 * Custom uninstall method
	 *
	 * @access	public
	 * @param	int		$cid	The id of the plugin to uninstall
	 * @param	int		$clientId	The id of the client (unused)
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function uninstall($id, $clientId )
	{
		// Initialize variables
		$row	= null;
		$retval = true;
		$db		=& $this->parent->getDBO();

		// First order of business will be to load the module object table from the database.
		// This should give us the necessary information to proceed.
		$query = 'SELECT *' .
				' FROM `#__jomtube_plugins`' .
				' WHERE id = '.$id
				;
		$db->setQuery($query);
		if (!$db->Query()) {
			// Install failed, roll back changes
			$this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.$db->stderr(true));
			return false;
		}
		$row = $db->loadObject();

		if ( !$row ) {
			JError::raiseWarning(100, JText::_('ERRORUNKOWNEXTENSION'));
			return false;
		}

		// Is the plugin we are trying to uninstall a core one?
		// Because that is not a good idea...
		if ($row->iscore) {
			JError::raiseWarning(100, JText::_('Plugin').' '.JText::_('Uninstall').': '.JText::sprintf('WARNCOREPLUGIN', $row->name)."<br />".JText::_('WARNCOREPLUGIN2'));
			return false;
		}

		// Get the plugin folder so we can properly build the plugin path
		if (trim($row->folder) == '') {
			JError::raiseWarning(100, JText::_('Plugin').' '.JText::_('Uninstall').': '.JText::_('Folder field empty, cannot remove files'));
			return false;
		}

		// Set the plugin root path
		$this->parent->setPath('extension_root', JPATH_ROOT.DS.$row->folder);

		// Because plugins don't have their own folders we cannot use the standard method of finding an installation manifest
		$manifestFile = JPATH_ROOT.DS.$row->folder.DS.$row->element.'.xml';

		if (file_exists($manifestFile))
		{
			$xml =& JFactory::getXMLParser('Simple');

			// If we cannot load the xml file return null
			if (!$xml->loadFile($manifestFile)) {
				JError::raiseWarning(100, JText::_('Plugin').' '.JText::_('Uninstall').': '.JText::_('Could not load manifest file'));
				return false;
			}

			/*
			 * Check for a valid XML root tag.
			 * @todo: Remove backwards compatability in a future version
			 * Should be 'install', but for backward compatability we will accept 'mosinstall'.
			 */
			$root =& $xml->document;
			if ($root->name() != 'install' && $root->name() != 'mosinstall') {
				JError::raiseWarning(100, JText::_('Plugin').' '.JText::_('Uninstall').': '.JText::_('Invalid manifest file'));
				return false;
			}

			// Remove the plugin files
			$this->parent->removeFiles($root->getElementByPath('images'), -1);
			$this->parent->removeFiles($root->getElementByPath('files'), -1);
			JFile::delete($manifestFile);

			// Make sure we delete the plugin template folders
            if ($row->type == 'template')
                JFolder::delete($this->parent->getPath('extension_root'));

			// Remove all media and languages as well
			$this->parent->removeFiles($root->getElementByPath('media'));
			$this->parent->removeFiles($root->getElementByPath('languages'), 1);
		} else {
			JError::raiseWarning(100, 'Plugin Uninstall: Manifest File invalid or not found');
			return false;
		}

		// Now we will no longer need the plugin object, so lets delete it
		$query = 'DELETE FROM #__jomtube_plugins WHERE id = ' . $id;
		$db->setQuery($query);
		if (!$db->Query()) {
			// Install failed, roll back changes
			$this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.$db->stderr(true));
			return false;
		}

		// If the folder is empty, let's delete it
		/*$files = JFolder::files($this->parent->getPath('extension_root'));
		if (!count($files)) {
			JFolder::delete($this->parent->getPath('extension_root'));
		}*/

		return $retval;
	}

	/**
	 * Custom rollback method
	 * 	- Roll back the plugin item
	 *
	 * @access	public
	 * @param	array	$arg	Installation step to rollback
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function _rollback_plugin($arg)
	{
		// Get database connector object
		$db =& $this->parent->getDBO();

		// Remove the entry from the #__plugins table
		$query = 'DELETE' .
				' FROM `#__jomtube_plugins`' .
				' WHERE id='.(int)$arg['id'];
		$db->setQuery($query);
		return ($db->query() !== false);
	}
}