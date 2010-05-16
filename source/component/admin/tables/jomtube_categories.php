<?php
/**
 * @version 0.9 $Id: eventlist_categories.php 507 2008-01-03 15:48:34Z schlu $
 * @package Joomla
 * @subpackage jomtube
 * @copyright (C) 2008 - 2010 Christoph Lukes
 * @license GNU/GPL, see LICENSE.php
 * EventList is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.

 * jomtube is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with EventList; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

defined('_JEXEC') or die('Restricted access');

/**
 * EventList categories Model class
 *
 * @package Joomla
 * @subpackage JomTube
 * @since 0.9
 */
class jomtube_categories extends JTable
{
	/**
	 * Primary Key
	 * @var int
	 */
	var $id 				= null;
	/** @var int */
	var $parent_id			= 0;
	/** @var string */
	var $category_name 			= '';
	/** @var string */
	var $category_info 	= null;
	/** @var string */
	var $family_id 	= null;
	var $directory = null;
	var $thumbnail = null;
	/**
	* @param database A database connector object
	*/
	function jomtube_categories(& $db) {
		parent::__construct('#__jomtube_categories', 'id', $db);
	}

	// overloaded check function
	function check()
	{
		// Not typed in a category name?
		if (trim( $this->category_name ) == '') {
			$this->_error = JText::_( 'ADD NAME CATEGORY' );
			JError::raiseWarning('SOME_ERROR_CODE', $this->_error );
			return false;
		}

		return true;
	}
}
?>