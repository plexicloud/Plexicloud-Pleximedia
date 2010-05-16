<?php
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


				$k = 1 - $k;
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
		// Get the WHERE and ORDER BY clauses for the query
		$where		= $this->_buildContentWhere();
		$orderby	= $this->_buildContentOrderBy();

		$query = 'SELECT v.*, c.directory, c.category_name'
					. ' FROM #__jomtube_videos AS v '
					. ' JOIN #__jomtube_categories AS c ON c.id = v.category_id '
					. $this->_buildContentWhere()
					. ' order by v.date_added DESC, v.id DESC'
				;
	  //echo $query; exit();
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

		$filter_order		= $mainframe->getUserStateFromRequest( $option.'.categories.filter_order', 		'filter_order', 	'c.ordering', 'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $option.'.categories.filter_order_Dir',	'filter_order_Dir',	'', 'word' );

		$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir.', c.ordering';

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
		//$search 			= $this->_db->getEscaped( trim(JString::strtolower( $search ) ) );
    $category_id = JRequest::getVar('category_id', 0, '', 'int');
    $video_server = JRequest::getVar('video_server', '', '', 'string');
    $featured = JRequest::getVar('featured', -1, '', 'int' );
    $publish = JRequest::getVar('publish', -1, '', 'int' );
    //var_dump($category_id); exit();
		$where = array();

		if ($category_id) {
		  $where[] = 'v.category_id = ' . $category_id;
		}
    if ($video_server != '') {
      $where[] = 'v.video_type like \'' . $video_server . '\'';
    }
    if ($featured != -1) {
      $where[] = 'v.featured = ' . $featured;
    }
    if ($publish != -1) {
      $where[] = 'v.published = ' . $publish;
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

			$query = 'UPDATE #__jomtube_videos'
				. ' SET published = ' . (int) $publish
				. ' WHERE id IN ('. $cids .')'
				//. ' AND ( checked_out = 0 OR ( checked_out = ' . (int) $user->get('id'). ' ) )'
			;
			$this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		return true;
	}

	function featured($id, $featured = 1) {
	  if ($id) {
	    $query = 'UPDATE #__jomtube_videos'
	            . ' SET featured = ' . (int) $featured
	            . ' WHERE id = ' . $id
	            ;
      $this->_db->setQuery($query);
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
		if (count( $cid ))
		{
		  $cids = implode( ',', $cid );
		  //get information video deleted
		  $query = 'SELECT v.*, c.directory, c.category_name'
					. ' FROM #__jomtube_videos AS v '
					. ' JOIN #__jomtube_categories AS c ON c.id = v.category_id '
					. ' WHERE v.id IN (' . $cids . ')'
				;
		  $this->_db->setQuery($query);
		  $videos = $this->_db->loadObjectList();

		  //delete video from database
			$query = 'DELETE FROM #__jomtube_videos'
					. ' WHERE id IN ('. $cids .')';
			$this->_db->setQuery( $query );
			if(!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}

			//delete file and thumbnail and display
      foreach ($videos as $video) {
        $video_file = JPATH_ROOT . $video->directory. '/' . $video->video_url;
        $display_file = JPATH_ROOT . $video->directory. '/_display/' . $video->video_thumb;
        $thumb_file = JPATH_ROOT . $video->directory. '/_thumbs/' . $video->video_thumb;
        //delete video file
        if (file_exists($video_file)) {
          unlink($video_file);
        }
        //delete dislplay file
        if (file_exists($display_file)) {
          unlink($display_file);
        }
        //delete thumbnail file
        if (file_exists($thumb_file)) {
          unlink($thumb_file);
        }
      }

		}

		$total 	= count( $cid );
		$msg 	= $total.' '.JText::_('VIDEOS DELETED');
		return $msg;

	}

	function &getParentOption() {
	  $db		=& JFactory::getDBO();
		$sql = 'SELECT id, category_name, family_id FROM #__jomtube_categories ORDER BY family_id';
		$db->setQuery($sql);
		$rows = $db->loadObjectList();

		return $rows;
	}

	function &getVideoType() {
	  $db = & JFactory::getDBO();
	  $query = 'SELECT video_type FROM #__jomtube_videos GROUP BY video_type';
	  $db->setQuery($query);
	  $type = $db->loadObjectList();
	  return $type;
	}
}
?>