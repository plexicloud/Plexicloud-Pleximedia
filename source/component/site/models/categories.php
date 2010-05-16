<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

/**
* @package		Joomla
* @subpackage	Polls
*/
class jomtubeModelcategories  extends JModel
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

        $limit		= $mainframe->getUserStateFromRequest( $option.'.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $limitstart = $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );
        $limitstart = JRequest::getVar('limitstart', 0, '', 'int');

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);

        $array = JRequest::getVar('vid',  0, '', 'array');
        $this->setId((int)$array[0]);
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

    /**
	 * Method to get categories item data
	 *
	 * @access public
	 * @return array
	 */
    function getData()
    {
        // Lets load the content if it doesn't already exist
        if (empty($this->_data))
        {
            $query = $this->_buildQuery();
            $this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));

            $k = 0;
            $count = count($this->_data);
            for($i = 0; $i < $count; $i++)
            {
                $category =& $this->_data[$i];
                //$category->parent_category = $this->getParentCategory($category->family_id);
                if ($category->thumbnail == '')
                $category->thumb = $this->_getLastestThumbVideo($category->id);
                else
                $category->thumb = $category->thumbnail;
                $category->childs = $this->_getChildCategories($category->id);
            }
        }

        return $this->_data;
    }

    /**
	 * Method to get the total nr of the categories
	 *
	 * @access public
	 * @return integer
	 */
    function getTotal()
    {
        // Lets load the content if it doesn't already exist
        if (empty($this->_total))
        {
            $query = $this->_buildQuery();
            $this->_total = $this->_getListCount($query);
        }

        return $this->_total;
    }

    /**
	 * Method to get a pagination object for the categories
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
	 * Method to build the query for the categories
	 *
	 * @access private
	 * @return integer
	 * @since 0.9
	 */
    function _buildQuery()
    {
        $query = 'SELECT c.*, (SELECT COUNT(*) FROM #__jomtube_videos AS v WHERE v.category_id = c.id) AS video_count '
        . ' FROM #__jomtube_categories AS c '
        . $this->_buildContentWhere()
        . ' ORDER BY c.id DESC '
        ;
        return $query;
    }

    /**
	 * Method to build the where clause of the query for the categories
	 *
	 * @access private
	 * @return string
	 * @since 0.9
	 */
    function _buildContentWhere()
    {
        global $mainframe, $option;

        $parent_id = JRequest::getVar('catid', '', 0, 'int');

        $where = array();

        $where[] = ' c.parent_id = ' . $parent_id;

        $where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );

        return $where;
    }

    /**
	 * Enter description here...
	 *
	 * @param unknown_type $catid
	 */
    function _getLastestThumbVideo($catid) {
        $query = 'SELECT video_thumb FROM #__jomtube_videos '
        . ' WHERE (video_type = \'local\' OR video_type is null) AND category_id = ' . $catid
        . ' ORDER BY date_added '
        . ' LIMIT 1'
        ;
        $this->_db->setQuery($query);
        return $this->_db->loadResult();
    }

    /**
	 * Enter description here...
	 *
	 * @param unknown_type $catid
	 */
    function _getChildCategories($catid) {
        $query = 'SELECT c.*, (SELECT COUNT(*) FROM #__jomtube_videos AS v WHERE v.category_id = c.id) AS video_count FROM #__jomtube_categories AS c '
        . ' WHERE c.parent_id = ' . $catid
        ;
        $this->_db->setQuery($query);
        return $this->_db->loadObjectList();
    }

    public function getParentCategory($family_id) {
        $parent_id_array = explode('/', $family_id);
        $parentstree = '';
        foreach ($parent_id_array as $key => $parent_id) {
            if ($key < count($parent_id_array)-1 ) {
                $query = 'SELECT category_name FROM #__jomtube_categories '
                . ' WHERE id = ' . $parent_id
                ;
                $this->_db->setQuery($query);
                $parentstree = $parentstree . $this->_db->loadResult() . '/';
            }
        }
        return  $parentstree;
    }

    function getBreadcrumb() {
        //get current category
        $catid = JRequest::getVar('catid', '', 0, 'int');
        $query = 'SELECT family_id from #__jomtube_categories where id = ' . $catid;
        $this->_db->setQuery($query);
        $family_id = $this->_db->loadResult();
        $family_id = str_replace('/', ',', $family_id);
        $query = 'SELECT * FROM #__jomtube_categories WHERE ID IN (' . $family_id . ') ORDER BY family_id';
        $this->_db->setQuery($query);
        return $this->_db->loadObjectList();
    }

    function getVideos() {
        $query = 'SELECT * FROM ';
    }



    function &getCategoryList() {
        $db		=& JFactory::getDBO();
        $sql = 'SELECT id, category_name, family_id FROM #__jomtube_categories ORDER BY family_id';
        $db->setQuery($sql);
        $rows = $db->loadObjectList();

        return $rows;
    }

    function getCategoryDirectory($catid) {
        $db		=& JFactory::getDBO();
        $sql = 'SELECT directory FROM #__jomtube_categories WHERE id = ' . $catid;
        $db->setQuery($sql);
        $directory = $db->loadResult();;

        return $directory;
    }
}

?>
