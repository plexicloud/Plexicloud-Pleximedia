<?php
/**
 * @version 0.9 $Id: jomtube_videos.php 507 2008-01-03 15:48:34Z schlu $
 * @package Joomla
 * @subpackage jomtube
 * @copyright (C) 2008 - 2010 Christoph Lukes
 * @license GNU/GPL, see LICENSE.php
 * jomtube is free software; you can redistribute it and/or
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
 * @subpackage jomTube
 * @since 0.9
 */
class jomtube_videos extends JTable
{
	/**
	 * Primary Key
	 * @var int
	 */
	var $id 				= null;
	/** @var int */
	var $user_id			= 0;
	/** @var string */
	var $video_title 			= '';
	/** @var string */
	var $video_desc 	= null;
	/** @var string */
	var $category_id 	= null;
	var $video_type = null;
	var $video_url = null;
	var $video_thumb = null;
	var $downloadable = null;
	var $featured = null;
	var $published = null;
	var $duration = null;
	var $tags = null;
	var $date_added = null;
	var $hits = null;
	var $votetotal = null;
	var $remote_id = null;
	var $date_updated = null;
	var $display_thumb = null;

	/**
	* @param database A database connector object
	*/
	function jomtube_videos(& $db) {
		parent::__construct('#__jomtube_videos', 'id', $db);
	}

	// overloaded check function
	function check()
	{
		// Not typed in a category name?
		if (trim( $this->video_title) == '') {
			$this->_error = JText::_( 'ADD TITLE VIDEO' );
			JError::raiseWarning('SOME_ERROR_CODE', $this->_error );
			return false;
		}
		return true;
	}
}
?>