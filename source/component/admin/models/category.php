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
 * EventList Component Category Model
 *
 * @package Joomla
 * @subpackage jomtube
 * @since		0.9
 */
class jomtubeModelCategory extends JModel
{
    /**
	 * Category id
	 *
	 * @var int
	 */
    var $_id = null;

    /**
	 * Category data array
	 *
	 * @var array
	 */
    var $_data = null;

    /**
	 * Groups data array
	 *
	 * @var array
	 */
    var $_groups = null;

    /**
	 * Constructor
	 *
	 * @since 0.9
	 */
    function __construct()
    {
        parent::__construct();

        $array = JRequest::getVar('cid',  0, '', 'array');
        $this->setId((int)$array[0]);
    }

    /**
	 * Method to set the identifier
	 *
	 * @access	public
	 * @param	int category identifier
	 */
    function setId($id)
    {
        // Set category id and wipe data
        $this->_id	    = $id;
        $this->_data	= null;
    }

    /**
	 * Method to get content category data
	 *
	 * @access	public
	 * @return	array
	 * @since	0.9
	 */
    function &getData()
    {
        if ($this->_loadData())
        {

        }
        else  $this->_initData();

        return $this->_data;
    }

    /**
	 * Method to get the group data
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	0.9
	 */
    /*function &getGroups()
    {
    $query = 'SELECT id AS value, name AS text'
    . ' FROM #__eventlist_groups'
    . ' ORDER BY name'
    ;
    $this->_db->setQuery( $query );

    $this->_groups = $this->_db->loadObjectList();

    return $this->_groups;
    }*/

    /**
	 * Method to load content event data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 * @since	0.9
	 */
    function _loadData()
    {
        // Lets load the content if it doesn't already exist
        if (empty($this->_data))
        {
            $query = 'SELECT *'
            . ' FROM #__jomtube_categories'
            . ' WHERE id = '.$this->_id
            ;
            $this->_db->setQuery($query);
            $this->_data = $this->_db->loadObject();

            return (boolean) $this->_data;
        }
        return true;
    }

    /**
	 * Method to initialise the category data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 * @since	0.9
	 */
    function _initData()
    {
        // Lets load the content if it doesn't already exist
        if (empty($this->_data))
        {
            $category = new stdClass();
            $category->id					= 0;
            $category->parent_id			= 0;
            //$category->groupid				= 0;
            $category->category_name				= null;
            $category->category_info				= null;
            $category->family_id		= null;

            $this->_data					= $category;
            return (boolean) $this->_data;
        }
        return true;
    }

    /**
	 * Method to checkin/unlock the item
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	0.9
	 */
    function checkin()
    {
        if ($this->_id)
        {
            $category = & JTable::getInstance('jomtube_categories', '');
            return $category->checkin($this->_id);
        }
        return false;
    }


    /**
	 * Method to store the category
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
    function store($data)
    {
        $row  =& $this->getTable('jomtube_categories', '');

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

        //upadate family id
        if ($row->parent_id != 0) {
            $family_id = $this->_getFamilyid($row->parent_id) . '/' . $row->id;
        } else {
            $family_id = $row->id;
        }
        $row->family_id = $family_id;
        //create category directory and update into database
        $row->directory = $this->_createCategoryDirectory($row);

        //upload category thumbnail
        $thumb_dir = JPATH_SITE."/$row->directory/_thumbs";
        if (!is_dir($thumb_dir))
        mkdir($thumb_dir);
        $thumb_file = $_FILES['thumbnail']['name'];
        if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], "$thumb_dir/$thumb_file")) {
            $row->thumbnail = $thumb_file;
            //resize image
            @$this->SizeImage(125, 100, "$thumb_dir/$thumb_file", "$thumb_dir/$thumb_file");
            //var_dump($thumb_file); exit();
        }

        if (!$row->store()) {
            JError::raiseError(500, $this->_db->getErrorMsg() );
            return false;
        }

        return $row->id;
    }

    function _getFamilyid($id) {
        $db = & JFactory::getDBO();
        $query = 'SElECT family_id FROM #__jomtube_categories WHERE id = ' . $id;
        $db->setQuery($query);
        return $db->loadResult();
    }

    function _createCategoryDirectory($row) {
        $mosConfig_absolute_path = JPATH_SITE;
        $basedir = "$mosConfig_absolute_path/jomtubefiles";

        if (!is_dir($basedir))
        {
            $oldumask=umask(0);
            mkdir ($basedir,0755);
            umask($oldumask);
        }

        $dirname = eregi_replace("[^A-Za-z0-9 ]", "", $row->category_name);
        $dirname = eregi_replace(" ", "_", $dirname);

        if ($row->parent_id != 0) {
            //get parent directory
            $db = & JFactory::getDBO();
            $query = 'SELECT directory FROM #__jomtube_categories WHERE id = ' . $row->parent_id;
            $db->setQuery($query);
            $makedir = $mosConfig_absolute_path . $db->loadResult() . '/' . $dirname;
        } else {
            $makedir = $basedir . '/' . $dirname;
        }
        $thumbdir = $makedir . '/_thumbs';
        $displaydir = $makedir . '/_display';

        if (!is_dir($makedir))
        {
            $oldumask=umask(0);
            @mkdir ($makedir,0755);
            umask($oldumask);
        }

        if (!is_dir($thumbdir))
        {
            $oldumask=umask(0);
            mkdir ($thumbdir,0755);
            umask($oldumask);
        }

        if (!is_dir($displaydir))
        {
            $oldumask=umask(0);
            mkdir ($displaydir,0755);
            umask($oldumask);
        }

        return str_replace($mosConfig_absolute_path,"",$makedir);;
    }

    function &getParentOption() {
        $db		=& JFactory::getDBO();
        $sql = 'SELECT id, category_name, family_id FROM #__jomtube_categories '
        . ' ORDER BY family_id ';
        $db->setQuery($sql);
        $rows = $db->loadObjectList();
        return $rows;
    }

    function SizeImage($maxWidth, $maxHW, $fileName, $thumbFileName) {
        list($width, $height, $type, $attr) = @getimagesize($fileName);
        if ($width==null || $width==0) return $fileName;

        if ($width>$maxWidth) {
            $scaleX = $maxWidth / $width;
            $scaleY = $maxHW / $height;
            if ($scaleX > $scaleY) $scaleX = $scaleY;
            $maxWidth = $width * $scaleX;
            $maxHeight = $height * $scaleX;
            $img2 = imagecreatetruecolor($maxWidth, $maxHeight);
            switch ($type) {
                case 1:
                    $img = imagecreatefromgif($fileName);
                    break;
                case 2:
                    $img = imagecreatefromjpeg($fileName);
                    break;
                case 3:
                    $img = imagecreatefrompng($fileName);
                    break;
            }
            imagecopyresampled($img2, $img, 0, 0, 0, 0, $maxWidth, $maxHeight, $width, $height);
            imagejpeg($img2, $thumbFileName);
        }
    }
}
?>