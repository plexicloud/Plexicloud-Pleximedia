<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

function jomtubeBuildRoute( &$query )
{
    $segments = array();
    if (isset($query['view']))
    {
        $segments[] = $query['view'];
        unset( $query['view'] );
    }

    if (isset($query['task']))
    {
        $segments[] = $query['task'];
        unset( $query['task'] );
    }

    if (isset($query['user_id']))
    {
        $segments[] = $query['user_id'];
        unset( $query['user_id'] );
    }

    if (isset($query['id']))
    {
        $segments[] = $query['id'];
        unset( $query['id'] );
    }

    if (isset($query['videoGuid']))
    {
        $segments[] = $query['videoGuid'];
        unset( $query['videoGuid']);
    }

    if (isset($query['uid']))
    {
        $segments[] = $query['uid'];
        unset( $query['uid']);
    }

    if (isset($query['catid']))
    {
        $segments[] = $query['catid'];
        unset( $query['catid']);
    }
    return $segments;
}

function jomtubeParseRoute( $segments )
{
    $vars = array();

    //var_dump($segments); exit();

    switch($segments[0])
    {
        case "categories":
            $vars['view'] = "categories";

            //var_dump($segments); exit();
            if (count($segments)> 1) {
                if ($segments[1] != "")
                {
                    $vars['task'] = "categories";
                    $vars['catid'] = $segments[1];
                }
            }
            break;
        case "upload":
            $vars['view'] = "upload";
            if ($segments[1] == "add")
            {
                $vars['task'] = "add";
                $vars['videoGuid'] = str_replace(':','-', $segments[2]);
                $vars['uid'] = $segments[3];
            }
            break;
        case "watch":
            $vars['view'] = "watch";
            $id = explode( ':', $segments[1] );
            $vars['id'] = (int) $id[0];
            break;
        case "videos":
            $vars['view'] = "videos";
            //$id = explode( ':', $segments[1] );
            //$vars['id'] = (int) $id[0];
            break;
        case "user":
            $vars['view'] = "user";
            $user_id = explode( ':', $segments[1] );
            $vars['user_id'] = (int) $user_id[0];
            break;
        case "user_edit":
            $vars['task'] = "user_edit";
            $user_id = explode( ':', $segments[1] );
            $vars['user_id'] = (int) $user_id[0];
            break;
        case "user_allvideos":
            $vars['task'] = "user_allvideos";
            $user_id = explode( ':', $segments[1] );
            $vars['user_id'] = (int) $user_id[0];
            break;
        case "delete_video":
            $vars['task'] = "delete_video";
            $id = explode( ':', $segments[1] );
            $vars['id'] = (int) $id[0];
            break;
        default: //default view
        $vars['view'] = $segments[0];
        $id = explode( ':', $segments[1] );
        $vars['id'] = (int) $id[0];
        break;
    }

    //var_dump($vars); exit();

    return $vars;
}
