<?php
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

class JomtubeModCategoriesHelper
{
    function getItems($ordering, $show_subs)
    {
        $db = &JFactory::getDBO();
        if (!$show_subs)
            $query = 'SELECT c.* FROM #__jomtube_categories AS c WHERE parent_id = 0 ORDER BY c.' . $ordering . ', c.id DESC ';
        else
            $query = 'SELECT c.* FROM #__jomtube_categories AS c ORDER BY c.' . $ordering . ', c.id DESC ';
        $db->setQuery($query);
        $row = $db->loadObjectList();
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

		$db->SetQuery( 'SELECT id FROM #__menu WHERE `link` LIKE "%com_jomtube%"' );
		$Itemid = $db->loadResult();

		if (empty($Itemid)) {
			$Itemid = "0";
		}

		return $Itemid;
	}
}
?>

