<?php
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

class AIOJomtubeHelper
{
    /**
     * Returns a list of post items
    */
    function getItems($displaymode, $numVideos,$where)
    {
        $db = &JFactory::getDBO();

        if ($displaymode=='L') {
            $stringMode='ORDER BY v.date_added DESC, v.id DESC';
        }
        else if ($displaymode=='V')
        {
            $stringMode='ORDER BY v.hits DESC, v.id DESC';
        }
        else if ($displaymode=='R')
        {
            $stringMode='ORDER BY v.votetotal DESC, v.id DESC';
        }
        else if ($displaymode=='F')
        {
            $stringMode= ' WHERE v.featured = 1 ORDER BY v.date_added DESC, v.id DESC';
        }

        //        $query = "SELECT *,a.title as imagename,b.id as graid
        //		FROM #__morfeoshow_img as a, #__morfeoshow  as b where a.gallery_id=b.id and a.gallery_id in (".$where.") ".$stringMode;
        //        $db->setQuery($query, 0, $numPosts);
        //        return ($items = $db->loadObjectList())?$items:array();

        $query = 'SELECT v.*, c.directory, u.username '
        . ' FROM #__jomtube_videos AS v'
        . ' JOIN #__jomtube_categories AS c ON c.id = v.category_id '
        . ' LEFT JOIN #__users AS u ON v.user_id = u.id '
        . ' WHERE v.published = 1 '
        . $stringMode . ' limit ' . $numVideos
        ;
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
}
?>

