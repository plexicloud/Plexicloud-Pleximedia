<?php
/**
 * @version 0.9 $Id: videos.php
 * @package Joomla
 * @subpackage jomtube
 * @copyright (C) 2005 - 2008 Tran Le Manh
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

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

/**
 * jomtube Component Videos Model
 *
 * @package Joomla
 * @subpackage jomtube
 * @since		0.9
 */
class jomtubeModelVideos extends JModel
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

        // ###########################################################
        // ###### GET ALL SETTINGS
        // ###########################################################
        $c = jomtube_configs::get_instance();

        //$limit		= $mainframe->getUserStateFromRequest( $option.'.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $limit = $c->videos_per_page;

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
    function getData($limit=-1)
    {
        // Lets load the content if it doesn't already exist
        if (empty($this->_data))
        {
            $query = $this->_buildQuery();
            //print_r($query);
            if ($limit == -1) {
                $this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
            } else {
                $this->_data = $this->_getList($query, 0, 1);
            }
            $k = 0;
            $count = count($this->_data);
            for($i = 0; $i < $count; $i++)
            {
                $this->_data[$i]->ratingAvg = intval($this->getRatingAvg($this->_data[$i]->id));
                $k = 1 - $k;
            }
        }
        return $this->_data;
    }

    function getRatingCount($id) {
        $query = 'SELECT COUNT(*) FROM #__jomtube_rating WHERE v_id = ' . $id;
        $this->_db->setQuery($query);
        return $this->_db->loadResult();
    }

    function getRatingAvg($id) {
        $query = 'SELECT AVG(rating) FROM #__jomtube_rating WHERE v_id = ' .$id;
        $this->_db->setQuery($query);
        return $this->_db->loadResult();
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
        // Get the WHERE and ORDER BY clauses for the query
        $where		= $this->_buildContentWhere();
        $orderby	= $this->_buildContentOrderBy();

        $query = 'SELECT v.*, c.directory, u.username '
        . ' FROM #__jomtube_videos AS v'
        . ' JOIN #__jomtube_categories AS c ON c.id = v.category_id '
        . ' LEFT JOIN #__users AS u ON v.user_id = u.id'
        . $where
        . $orderby
        ;
        return $query;
    }

    /**
	 * Method to build the orderby clause of the query for the categories
	 *
	 * @access private
	 * @return string
	 * @since 0.9
	 */
    function _buildContentOrderBy()
    {
        global $mainframe, $option;

        //$filter_order		= $mainframe->getUserStateFromRequest( $option.'.categories.filter_order', 		'filter_order', 	'c.ordering', 'cmd' );
        //$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.categories.filter_order_Dir',	'filter_order_Dir',	'', 'word' );
        $filter_order = JRequest::getVar('order', 'v.date_added');
        $filter_order_Dir = JRequest::getVar('by', 'DESC');
        $orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir . ', v.id DESC';

        return $orderby;
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

        //$filter_state 		= $mainframe->getUserStateFromRequest( $option.'.categories.filter_state', 'filter_state', '', 'word' );
        //$search 			= $mainframe->getUserStateFromRequest( $option.'.categories.search', 'search', '', 'string' );
        $category_id = JRequest::getVar('category_id', 0, '', 'int');
        $catid = JRequest::getVar('catid', 0, '', 'int');
        $search = JRequest::getVar('searchkey', '', '', 'string');
        $featured = JRequest::getVar('featured', 0, '', 'int');
        $search 			= $this->_db->getEscaped( trim(JString::strtolower( $search ) ) );
        $myvideo = JRequest::getVar('type', '');

        $where = array();

        $where[] = 'v.published = 1';

        if ($category_id) {
            $where[] = 'v.category_id = ' . $category_id;
        }

        if ($catid) {
            $where[] = 'v.category_id = ' . $catid;
        }

        if ($search) {
            $where[] = ' (LOWER(v.video_title) LIKE \'%'.$search.'%\' OR LOWER(v.video_desc) LIKE \'%'.$search.'%\' OR LOWER(v.tags) LIKE \'%'.$search.'%\' ) ';
        }
        if ($featured == 1) {
            $where[] = ' v.featured = 1 ';
        }

        if ($myvideo == 'myvideo') {
            $user = & JFactory::getUser();
            $where[] = ' v.user_id = ' . $user->id;
        }

        if ($myvideo == 'member') {
            $user_id = JRequest::getVar('user_id', 0, 'int');
            $where[] = ' v.user_id = ' . $user_id;
        }

        $where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );

        return $where;
    }

    /**
	 * Method to (un)publish a category
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	0.9
	 */
    function publish($cid = array(), $publish = 1)
    {
        $user 	=& JFactory::getUser();

        if (count( $cid ))
        {
            $cids = implode( ',', $cid );

            $query = 'UPDATE #__eventlist_categories'
            . ' SET published = ' . (int) $publish
            . ' WHERE id IN ('. $cids .')'
            . ' AND ( checked_out = 0 OR ( checked_out = ' . (int) $user->get('id'). ' ) )'
            ;
            $this->_db->setQuery( $query );
            if (!$this->_db->query()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }
        return true;
    }

    /**
	 * Method to move a category
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	0.9
	 */
    function move($direction)
    {
        $row =& JTable::getInstance('eventlist_categories', '');

        if (!$row->load( $this->_id ) ) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        if (!$row->move( $direction )) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        return true;
    }

    /**
	 * Method to order categories
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	0.9
	 */
    function saveorder($cid = array(), $order)
    {
        $row =& JTable::getInstance('eventlist_categories', '');

        // update ordering values
        for( $i=0; $i < count($cid); $i++ )
        {
            $row->load( (int) $cid[$i] );

            if ($row->ordering != $order[$i])
            {
                $row->ordering = $order[$i];
                if (!$row->store()) {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }
            }
        }

        return true;
    }
    /**
	 * Method to count the nr of assigned events to the category
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	0.9
	 */
    function _countcatevents($id)
    {
        $query = 'SELECT COUNT( e.id )'
        .' FROM #__eventlist_events AS e'
        .' WHERE e.catsid = ' . (int)$id
        ;

        $this->_db->setQuery($query);
        $number = $this->_db->loadResult();

        return $number;
    }


    /**
	 * Method to remove a event
	 *
	 * @access	public
	 * @return	string $msg
	 * @since	0.9
	 */
    function delete($cid)
    {
        $cids = implode( ',', $cid );

        $query = 'SELECT c.id, c.catname, COUNT( e.catsid ) AS numcat'
        . ' FROM #__eventlist_categories AS c'
        . ' LEFT JOIN #__eventlist_events AS e ON e.catsid = c.id'
        . ' WHERE c.id IN ('. $cids .')'
        . ' GROUP BY c.id'
        ;
        $this->_db->setQuery( $query );

        if (!($rows = $this->_db->loadObjectList())) {
            JError::raiseError( 500, $this->_db->stderr() );
            return false;
        }

        $err = array();
        $cid = array();
        foreach ($rows as $row) {
            if ($row->numcat == 0) {
                $cid[] = $row->id;
            } else {
                $err[] = $row->catname;
            }
        }

        if (count( $cid ))
        {
            $cids = implode( ',', $cid );
            $query = 'DELETE FROM #__eventlist_categories'
            . ' WHERE id IN ('. $cids .')';

            $this->_db->setQuery( $query );

            if(!$this->_db->query()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }

        if (count( $err )) {
            $cids 	= implode( ', ', $err );
            $msg 	= JText::sprintf( 'EVENT ASSIGNED CATEGORY', $cids );
            return $msg;
        } else {
            $total 	= count( $cid );
            $msg 	= $total.' '.JText::_('CATEGORIES DELETED');
            return $msg;
        }
    }

    function getBreadcrumb() {
        //get current category
        $catid = JRequest::getVar('category_id', '', 0, 'int');
        $query = 'SELECT family_id from #__jomtube_categories where id = ' . $catid;
        $this->_db->setQuery($query);
        $family_id = $this->_db->loadResult();
        $family_id = str_replace('/', ',', $family_id);
        $query = 'SELECT * FROM #__jomtube_categories WHERE ID IN (' . $family_id . ') ORDER BY family_id';
        echo $query;
        $this->_db->setQuery($query);
        return $this->_db->loadObjectList();
    }
}
?>