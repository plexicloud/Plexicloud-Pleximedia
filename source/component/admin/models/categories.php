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
				$category->parent_category = $this->getParentCategory($category->family_id);
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
		$query = 'SELECT * '
					. ' FROM #__jomtube_categories '
					. ' ORDER BY family_id, id DESC '
					;
		return $query;
	}

	function delete($cid)
	{
		$cids = implode( ',', $cid );

		$query = 'SELECT c.id, c.category_name, COUNT( e.category_id ) AS numvideo'
				. ' FROM #__jomtube_categories AS c'
				. ' LEFT JOIN #__jomtube_videos AS e ON e.category_id = c.id'
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
			if ($row->numvideo == 0) {
				$cid[] = $row->id;
			} else {
				$err[] = $row->category_name;
			}
		}

		if (count( $cid ))
		{
			$cids = implode( ',', $cid );
			$query = 'DELETE FROM #__jomtube_categories'
					. ' WHERE id IN ('. $cids .')';

			$this->_db->setQuery( $query );

			if(!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		if (count( $err )) {
			$cids 	= implode( ', ', $err );
				$msg 	= JText::sprintf( 'VIDEO ASSIGNED CATEGORY', $cids );
				return $msg;
		} else {
			$total 	= count( $cid );
			$msg 	= $total.' '.JText::_('CATEGORIES DELETED');
			return $msg;
		}
	}

	function getParentCategory($family_id) {
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
}

?>
