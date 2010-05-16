<?php
defined('_JEXEC') or die('Restricted access');

class jomtube_plugins extends JTable
{
	/**
	 * Primary Key
	 * @var int
	 */
	var $id 				= null;
	/** @var int */
	var $name			= 0;
	/** @var string */
	var $element 			= '';
	/** @var string */
	var $type 	= null;
	/** @var string */
	var $folder 	= null;
	var $published = null;
	var $author = null;
	var $checked_out = null;
	var $url = null;
	var $params = null;
	var $description = null;
	var $iscore = null;
	/**
	* @param database A database connector object
	*/
	function jomtube_plugins(& $db) {
		parent::__construct('#__jomtube_plugins', 'id', $db);
	}
	
	/**
	* Overloaded bind function
	*
	* @access public
	* @param array $hash named array
	* @return null|string	null is operation was satisfactory, otherwise returns an error
	* @see JTable:bind
	* @since 1.5
	*/
	function bind($array, $ignore = '')
	{
		if (isset( $array['params'] ) && is_array($array['params']))
		{
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = $registry->toString();
		}

		return parent::bind($array, $ignore);
	}
}
?>