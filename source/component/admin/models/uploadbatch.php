<?php
/**
 * @version 0.9 $Id: category.php 507 2008-01-03 15:48:34Z schlu $
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

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

/**
 * jomtube Component Video Model
 *
 * @package Joomla
 * @subpackage jomtube
 * @since		0.9
 */
class jomtubeModelUploadbatch extends JModel
{

	/**
	 * Constructor
	 *
	 * @since 0.9
	 */
	function __construct()
	{
		parent::__construct();
	}



	function &getParentOption() {
	  $db		=& JFactory::getDBO();
		$sql = 'SELECT id, category_name, family_id FROM #__jomtube_categories ORDER  BY  family_id ';
		$db->setQuery($sql);
		$rows = $db->loadObjectList();

		return $rows;
	}

}
?>