<?php
/*
 * jomtube Default Comments Plugin
 *  
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class JVComments extends BaseJVComments {
	
	public function displayComments() {
		include_once(JPATH_ROOT.DS.'plugins'.DS.'content'.DS.'jom_comment_bot.php');
		echo jomcomment(JRequest::getVar('id'), "com_jomtube");
	}
}
?>