<?php
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

class JomtubeTabsHelper
{
    function getItems($displaymode, $numVideos,$where)
    {
        $db = &JFactory::getDBO();

        $order = '';
        $where = ' WHERE v.published = 1 ';

        if ($displaymode=='L') {
            $order='ORDER BY v.date_added DESC, v.id DESC';
        }
        else if ($displaymode=='V')
        {
            $order='ORDER BY v.hits DESC, v.id DESC';
        }
        else if ($displaymode=='R')
        {
            $order='ORDER BY v.votetotal DESC, v.id DESC';
        }
        else if ($displaymode=='F')
        {
            $where .= ' AND v.featured = 1 ORDER BY v.date_added DESC, v.id DESC';
        } else if ($displaymode == 'random') {
            $order='ORDER BY RAND()';
        } else if ($displaymode == 'A') {
            $order='ORDER BY v.date_added DESC, v.id DESC';
        }

        $query = 'SELECT v.*, c.directory, u.username '
        . ' FROM #__jomtube_videos AS v'
        . ' JOIN #__jomtube_categories AS c ON c.id = v.category_id '
        . ' LEFT JOIN #__users AS u ON v.user_id = u.id ' . $where . $order . ' limit  ' . $numVideos;

        $db->setQuery($query);
        $row = $db->loadObjectList();

        $count = count($row);
        for($i = 0; $i < $count; $i++)
        {
             $query = 'SELECT AVG(rating) FROM #__jomtube_rating WHERE v_id = ' . $row[$i]->id;
             $db->setQuery($query);
             $row[$i]->ratingAvg = $db->loadResult();
        }

        return $row;
    } //end getItems

    function generateJomTubeItemid($current=null){
  		$db =& JFactory::getDBO();

		if (isset($current)) {
			$db->SetQuery( 'SELECT id FROM #__menu WHERE `link` LIKE "%com_jomtube%" AND id = '.$current );
			$Itemid = $db->loadResult();
			if (!empty($Itemid)) {
				return $Itemid;
			}
		}

		$db->SetQuery( 'SELECT id FROM #__menu WHERE `link` LIKE "%com_jomtube%" ' );
		$Itemid = $db->loadResult();

		if (empty($Itemid)) {
			$Itemid = "0";
		}

		return $Itemid;
	}

	public function showSrcLocalThumbnail($thumb_file) {
        if (!is_dir(JPATH_BASE.$thumb_file)) {
            if (file_exists(JPATH_SITE.$thumb_file)) {
                echo JURI::root( true ).$thumb_file;
            } else {
                echo JURI::root( true )."/administrator/components/com_jomtube/assets/images/no-thumbnail.jpg";
            }
        } else {
            echo JURI::root( true )."/administrator/components/com_jomtube/assets/images/no-thumbnail.jpg";
        }
    }

    public function showSrcLocalThumbnailAjax($thumb_file) {
        $root = substr(JURI::root( false ), 0, strlen(JURI::root( false ))-25);
        if (!is_dir(JPATH_BASE.$thumb_file)) {
            if (file_exists(JPATH_SITE.$thumb_file)) {
                echo $root.$thumb_file;
            } else {
                echo $root."/administrator/components/com_jomtube/assets/images/no-thumbnail.jpg";
            }
        } else {
            echo $root."/administrator/components/com_jomtube/assets/images/no-thumbnail.jpg";
        }
    }

}
?>