<?php
defined('_JEXEC') or die('Restricted access');
class toolsHelpers {
    function integrateVideoComments($row) {
        $c = jomtube_configs::get_instance();
        $jomtubeCommentingSystemCode = '';
        if ($c->commenting_system != 'No') {
            //integrate commenting system with JomComment
            if ($c->commenting_system == 'JomComment') {
                if (file_exists(JPATH_ROOT.DS.'plugins'.DS.'content'.DS.'jom_comment_bot.php')) {
                    require_once(JPATH_ROOT.DS.'plugins'.DS.'content'.DS.'jom_comment_bot.php');
                    $jomtubeCommentingSystemCode = jomcomment( $row->id, 'com_jomtube');
                } else {
                    $jomtubeCommentingSystemCode = '<b>You must install JomComment component to use comment function</b>';
                }
            }
            //integrate commenting system with JComment
            if ($c->commenting_system == 'JComment') {
                if (file_exists(JPATH_SITE.DS.'components/com_jcomments/jcomments.php')) {
                    require_once(JPATH_SITE.DS.'components/com_jcomments/jcomments.php');
                    $jomtubeCommentingSystemCode = JComments::showComments( $row->id, 'com_jomtube', $row->video_title );
                } else {
                    $jomtubeCommentingSystemCode = '<b>You must install JComment component to use comment function</b>';
                }
            }
        }

        return $jomtubeCommentingSystemCode;
    }
}
?>


