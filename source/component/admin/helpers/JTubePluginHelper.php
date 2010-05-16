<?php
/**
* @version		$Id: helper.php 10707 2008-08-21 09:52:47Z eddieajau $
* @package		Joomla.Framework
* @subpackage	Plugin
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
* Plugin helper class
*
* @static
* @package		Joomla.Framework
* @subpackage	Plugin
* @since		1.5
*/
class JTubePluginHelper
{
	/**
	 * Get the plugin data of a specific type if no specific plugin is specified
	 * otherwise only the specific plugin data is returned
	 *
	 * @access public
	 * @param string 	$type 	The plugin type, relates to the sub-directory in the plugins directory
	 * @param string 	$plugin	The plugin name
	 * @return mixed 	An array of plugin data objects, or a plugin data object
	 */
	function &getPlugin($type, $plugin = null)
	{
		$result = array();

		$plugins = JTubePluginHelper::_load();

		$total = count($plugins);		
		for($i = 0; $i < $total; $i++)
		{
			if(is_null($plugin))
			{
				if($plugins[$i]->type == $type) {
					$result[] = $plugins[$i];
				}
			}
			else
			{
				if($plugins[$i]->type == $type && $plugins[$i]->element == $plugin) {
					$result = $plugins[$i];
					break;
				}
			}

		}

		return $result;
	}

	/**
	 * Checks if a plugin is enabled
	 *
	 * @access	public
	 * @param string 	$type 	The plugin type, relates to the sub-directory in the plugins directory
	 * @param string 	$plugin	The plugin name
	 * @return	boolean
	 */
	function isEnabled( $type, $plugin = null )
	{
		$result = &JPluginHelper::getPlugin( $type, $plugin);
		return (!empty($result));
	}

	/**
	* Loads all the plugin files for a particular type if no specific plugin is specified
	* otherwise only the specific pugin is loaded.
	*
	* @access public
	* @param string 	$type 	The plugin type, relates to the sub-directory in the plugins directory
	* @param string 	$plugin	The plugin name
	* @return boolean True if success
	*/
	function importPlugin($type, $plugin = null)
	{
		$result = false;

		$plugins = JPluginHelper::_load();

		$total = count($plugins);
		for($i = 0; $i < $total; $i++) {
			if($plugins[$i]->type == $type && ($plugins[$i]->element == $plugin ||  $plugin === null)) {
				JPluginHelper::_import( $plugins[$i] );
				$result = true;
			}
		}

		return $result;
	}

	/**
	 * Loads the plugin file
	 *
	 * @access private
	 * @return boolean True if success
	 */
	function _import( &$plugin )
	{
		static $paths;

		if (!$paths) {
			$paths = array();
		}

		$result	= false;
		$plugin->folder = preg_replace('/[^A-Z0-9_\.-]/i', '', $plugin->folder);
		$plugin->element  = preg_replace('/[^A-Z0-9_\.-]/i', '', $plugin->element);

		$path	= JPATH_BASE.DS.$plugin->folder.DS.$plugin->element.'.php';

		if (!isset( $paths[$path] ))
		{
			if (file_exists( $path ))
			{
				require_once( $path );				
			}
			else
			{
				$paths[$path] = false;
			}
		}
	}

	/**
	 * Loads the published plugins
	 *
	 * @access private
	 */
	function _load()
	{
		$plugins = null;

		$db		=& JFactory::getDBO();		

		$query = 'SELECT type, element , params, name'
			. ' FROM #__jomtube_plugins'
			. ' WHERE published >= 1'				
			;

		$db->setQuery( $query );

		if (!($plugins = $db->loadObjectList())) {
			JError::raiseWarning( 'SOME_ERROR_CODE', "Error loading Plugins: " . $db->getErrorMsg());
			return false;
		}

		return $plugins;
	}

}
