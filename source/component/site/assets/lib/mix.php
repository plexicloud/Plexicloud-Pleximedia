<?php
function jomtube_urldecode($url){
    $url=str_replace("*am*","&",$url);
    $url=str_replace("*eq*","=",$url);
    $url = str_replace( "25ja25", "/",$url );
    $url = str_replace( "1twodots1", ":",$url );
    $url = str_replace( "p_p_p", ".",$url );
    $url = str_replace( "h_h_h", " ",$url );
    $url = str_replace( "m_m_m", "-",$url );
    return $url;
}

class jomtube {
    function showShortAuthor($username) {
        if ($username == '')
        return 'Guest';
        return strlen($username) > 12 ? substr($username, 0, 12) . '...' : $username;
    }

    function showAuthor($username) {
        //var_dump($username); exit();
        if ($username == '')
        return 'Guest';
        return $username;
    }

    function checkPermission($function) {
        $c = jomtube_configs::get_instance();
        $user 		= & JFactory::getUser();
        if ($user->gid < $c->$function &&  $c->$function != 29) {
            // Redirect to login
            $uri		= JFactory::getURI();
            $return		= $uri->toString();
            $url  = 'index.php?option=com_user&view=login';
            $url .= '&return='.base64_encode($return);

            $r = new JApplication();
            $r->redirect($url, JText::_('You do not have permission to upload videos') );
            return ;
        }
    }
}
?>