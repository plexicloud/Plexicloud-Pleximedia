<?php
// no direct access

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');
class jomtubeController extends JController
{
    public $jvConfig,$jvProfile;

    function __construct()
    {
        parent::__construct();

        // ###########################################################
        // ###### GET ALL SETTINGS
        // ###########################################################
        $c = jomtube_configs::get_instance();

        $taskName = JRequest::getVar('task', '');
        $viewName = JRequest::getVar('view', '');
        $typeName = JRequest::getVar('type', '');
        $user =& JFactory::getUser();

        if ($taskName != "rate_video") {//IGNORE AJAX FUNCTION
            //###########################################
            //############ YOUTUBE TOP TABS HTML START
            //###########################################

            $tabs_toolbar = "<div id=\"glowtabs-container\" style=\"padding-left: auto; padding-right: auto; width: 98%\">
    		    <div id=\"glowingtabs\">
    		    	<ul>";

            if (!$c->disable_allvideos_tab) {
                $link=JRoute::_("index.php?view=videos");
                $tabs_toolbar.="<li ";
                if ($viewName=="videos" && $typeName == ""){$tabs_toolbar.=" id=\"current\"";}
                $tabs_toolbar.="><a href=\"".$link."\" title=\"Video Categories\"><span>" . _TABS_ALL_VIDEOS . "</span></a></li>";
            }

	if (!$c->disable_categories_tab) {
                $link=JRoute::_("index.php?view=categories");
                $tabs_toolbar.="<li";
                if ($viewName=="categories"){$tabs_toolbar.=" id=\"current\"";}
                $tabs_toolbar.="><a href=\"".$link."\" title=\"Add Video\"><span>" . _TABS_CATEGORIES . "</span></a></li>";
	}
            if($user->usertype == "Super Administrator" || $user->usertype == "Administrator" || $user->usertype == "Manager" || $user->usertype == "Content Partner" ) {


	                //### MY VIDEOS TAB ######
            if (!$c->disable_myvideos_tab) {
                $link=JRoute::_("index.php?view=videos&type=myvideo");
                $tabs_toolbar.="<li ";
                if ($viewName=="videos" && $typeName == "myvideo"){$tabs_toolbar.=" id=\"current\"";}
                $tabs_toolbar.="><a href=\"".$link."\" title=\"Video Categories\"><span>" . _TABS_MY_VIDEOS . "</span></a></li>";
            }



            if (!$c->disable_addvideo_tab) {
                $link=JRoute::_("index.php?view=addvideo");
                $tabs_toolbar.="<li ";
                if ($viewName=="addvideo"){$tabs_toolbar.=" id=\"current\"";}
                $tabs_toolbar.="><a href=\"".$link."\" title=\"Add Video\"><span>" . _TABS_ADD_VIDEO . "</span></a></li>";
            }

            if (!$c->disable_uploadvideo_tab) {
                $link=JRoute::_("index.php?view=uploadvideo");
                $tabs_toolbar.="<li";
                if ($viewName=="uploadvideo"){$tabs_toolbar.=" id=\"current\"";}
                $tabs_toolbar.="><a href=\"".$link."\" title=\"Upload Video\"><span>" . _TABS_UPLOAD_VIDEO . "</span></a></li>";
            }
     }
            $tabs_toolbar.="
    		    </ul>
    		    </div>
    		    </div>";

            //echo $tabs_toolbar;
 JRequest::setVar('toolbar', base64_encode($tabs_toolbar));
            //###########################################
            //############ YOUTUBE TOP TABS HTML END
            //###########################################

            //###########################################
            //############ YOUTUBE SEARCH FORM HTML START
            //###########################################

                /*$keysearch = JRequest::getVar('searchkey', '');
                $search_form = "
        		    <div id=\"masthead\">
        		      <div class=\"bar\">
        		        <span class=\"leftcap\"></span>
        		        <div class=\"search-bar\">
        		          <div id=\"search-form\">";
                if (!$c->disable_search_form) {
        		           $search_form .=  "<form action=\"index.php\" method=\"get\" name=\"adminForm\">
        		              <input type=\"text\" maxlength=\"128\" id=\"search-term\" name=\"searchkey\" value=\"$keysearch\">
        		              <input type=\"hidden\" name=\"option\" value=\"com_jomtube\">
        		              <input type=\"hidden\" name=\"controler\" value=\"videos\">
        		              <input type=\"hidden\" name=\"view\" value=\"videos\">
        		              <input type=\"hidden\" name=\"Itemid\" value=\"\">
        		              <button type=\"submit\" name=\"task\" value=\"searchvideos\">" . _SEARCH_BUTTON . "</button>
        		            </form>";
                }

        		 $search_form .= "</div>
        		        </div>
        		        <span class=\"rightcap\"></span>
        		      </div>
        		    </div>
        		    ";
				*/
                //echo $search_form;

            //###########################################
            //############ YOUTUBE SEARCH FORM HTML END
            //###########################################
        }
    }

    function display( )
    {
        global $mainframe;
        $document	= & JFactory::getDocument();
        $params = clone($mainframe->getParams('com_jomtube'));
        $viewName = JRequest::getCmd( 'view', 'videos' );
        $viewType = $document->getType();
        $viewLayout = JRequest::getCmd( 'layout', 'default' );
        $keysearch = JRequest::getVar('searchkey', '', '', 'string');

        $view = &$this->getView($viewName, $viewType);
        $model_video = &$this->getModel('video');

        // Configure the view.
        $view->setModel($model_video, false);

        parent::display();
    }

    function ajax() {

        $this->rate_video();

        // update rating

    }


    function rate_video()
    {
        // Rate the vide
        $model = &$this->getModel( 'video' );
        $rateResult = $model->rate_video();

        // Display the rating (again)
        $video = $model->getData();
        if (count($video) > 0)
        {
            $user =& JFactory::getUser();

            $ajaxReturn = "Rate: ";

            $emptyStar = JURI::base() . "components/com_jomtube/assets/images/emptyStar16x16.gif";
            $halfStar = JURI::base() . "components/com_jomtube/assets/images/halfStar16x16.gif";
            $filledStar = JURI::base() . "components/com_jomtube/assets/images/fullStar16x16.gif";

            $starMap = '00000';
            for ($i = 1; $i <= 5; $i++)
            {
                $starMap[$i - 1] = floor($video->ratingAvg) >= $i ? '2' : (round($video->ratingAvg) == $i ? '1' : '0');
            }

            $ajaxReturn .= "<span class=\"jomtube_ratingStars\" title=\"".round($video->ratingAvg, 1) ." out of 5 stars\">";

            $starMapRate = '00000';
            for ($i = 1; $i <= 5; $i++)
            {
                $star = floor($video->ratingAvg) >= $i ? $filledStar : (round($video->ratingAvg) == $i ? $halfStar : $emptyStar);
                $starMapRate[$i - 1] = '2';
                $ajaxReturn .= "<img src=\"".$star."\" id=\"videoStar".$i."\" vspace=\"0\" "
                ."hspace=\"0\" height=\"16\" width=\"16\" border=\"0\" />";

            }
            $ajaxReturn .= "</span>";

            if ($video->ratingCount == 1)
            {
                $ajaxReturn .= "<br /><span id=\"ratingThanks\" class=\"jomtube_ratingThanks\">Thanks for Voting!</span> <span class=\"jomtube_ratingCount\">1 rating</span>";
            }
            else if ($video->ratingCount == 0)
            {
                $ajaxReturn .= "<br /><span id=\"ratingThanks\" class=\"jomtube_ratingThanks\">Thanks for Voting!</span> <span class=\"jomtube_ratingCount\">Not yet rated</span>";
            }
            else
            {
                $ajaxReturn .= "<br /><span id=\"ratingThanks\" class=\"jomtube_ratingThanks\">Thanks for Voting!</span> <span class=\"jomtube_ratingCount\">".$video->ratingAvg." ratings</span>";
            }

            echo $ajaxReturn;

        }
    }

    function applylink() {
        JRequest::setVar('view', 'applylink');
        $model = $this->getModel('video');
        JRequest::setVar('category', $model->getParentOption());

        //load remote video detail from plugin
        $link = JRequest::getVar('inputlink', '');
        $vidlink=jomtube_urldecode($link);
        $current_dir = JPATH_ADMINISTRATOR.'/components/com_jomtube/plugins/videoserver/';
        $dir=opendir($current_dir);
        while($file=readdir($dir))
        {
            if ($file!="." and $file!=".."){
                $pluginname=str_replace(".php","",$file);
                $temporaryvidlink="Joomlaalem".$vidlink;
                $pos = strpos($temporaryvidlink, $pluginname);
                $pluginaddress=$current_dir.$file;
                if ($pos<>"0"){
                    require($pluginaddress);
                    $nodotservertype=str_replace(".","",$pluginname);
                    $funcname=$nodotservertype."getvideodetails";
                    $videodetails=$funcname($vidlink,  '', '', "new");

                    //echo utf8_encode($videodetails);
                    $aserverfound=1;
                }
            }
        }
        closedir($dir);
        JRequest::setVar('videodetails', $videodetails);

        //check duplicate video
        $remote_video = $videodetails['smallvideocode'];
        $existed_video = $model->checkDuplicateRemoteVideo($remote_video);
        JRequest::setVar('existed_video', $existed_video);

        parent::display();
    }

    function savelink()
    {
        // Check for request forgeries
        JRequest::checkToken() or die( 'Invalid Token' );

        $task		= JRequest::getVar('task');

        //Sanitize
        // $post = JRequest::get( 'post' );
        $post = $_POST;

        $model = $this->getModel('video');
        if ($returnid = $model->store($post, 1)) {
            $msg = JText::_( 'VIDEO SAVED' );
            $link 	= JRoute::_('index.php?view=videos');
            $cache = &JFactory::getCache('com_jomtube');
            $cache->clean();
        } else {
            $msg 	= '';
            $link 	= JRoute::_('index.php?view=video');
        }

        $this->setRedirect($link, $msg);
    }

    function categories() {
        JRequest::setVar('view', 'categories');
        $model = $this->getModel('videos');
        JRequest::setVar('items', $model->getData());
        JRequest::setVar('total', $model->getTotal());
        JRequest::setVar('pagNav', $model->getPagination());
        parent::display();
    }

    function uploadvideo() {
        $post = $_POST;
        //get category directory
        $categories_model = $this->getModel('categories');
        $category_directory = $categories_model->getCategoryDirectory($post['category_id']);
        $category_path = JPATH_SITE . $category_directory;
        //var_dump($category_path); exit();

        $c = jomtube_configs::get_instance();

        if ($c->uploader_type == "flashupload") {
            // ###########################################################
            // ###### USE FLASHUPLOADER PROGRESS
            // ###########################################################
            // === Instantiate the class
            $solmetraUploader = new SolmetraUploader(
            '../administrator/components/com_jomtube/assets/lib/FlashUploader/',           // a base path to Flash Uploader's directory (relative to the page)
            'upload.php',       // path to a file that handles file uploads (relative to uploader.swf) [optional]
            'administrator/components/com_jomtube/assets/lib/FlashUploader/config.php'  // path to a server-side config file (relative to the page) [optional]
            );

            // === Gather uploaded files
            // Flash Uploader populates PHP's own $_FILE global variable
            // with the information about uploaded files
            $solmetraUploader->gatherUploadedFiles();
            if (isset($_FILES) && sizeof($_FILES)) {
//                echo '<h2>Uploaded files</h2>';
//                echo '<pre class="info">';
//                print_r($_FILES);
//                echo '</pre>';
                $file_info = $_FILES['file'];
                //remove space
                $file_name = JTHelper::removeSpaceFileName($file_info['name']);
                $file_name = JTHelper::vietDecode($file_name);
                //check duplicate
                JTHelper::getNoDuplicateFileName($category_path, $file_name, 'flv');
                $tmp_name = $file_info['tmp_name'];
                if (!JFolder::exists($category_path))

				JFolder::create($category_path);
                @copy($tmp_name, $category_path . '/' . $file_name);
                @unlink($tmp_name);

                $post['video_url'] = $file_name;
                $post['catdir'] = $category_path;
            }
        } else if ($c->uploader_type == "normal") {
            // ###########################################################
            // ###### USE NORMAL UPLOAD
            // ###########################################################
            $file_info = $_FILES['file'];
            //remove space
            $file_name = JTHelper::removeSpaceFileName($file_info['name']);
            $file_name = JTHelper::vietDecode($file_name);
            //check duplicate
            JTHelper::getNoDuplicateFileName($category_path, $file_name, 'flv');

            $tmp_name = $file_info['tmp_name'];
			if (!JFolder::exists($category_path))

			JFolder::create($category_path);


            if (move_uploaded_file($tmp_name, $category_path . '/' . $file_name)) {
                $post['video_url'] = $file_name;
                $post['catdir'] = $category_path;
            }
        }

        // ###########################################################
        // ###### upload thumbnail if user dose not use ffmpeg
        // ###########################################################
        if (!$c->use_ffmpeg) {
            $thumb_file_tmp = $_FILES['thumbnail']['tmp_name'];
            $thumb_file = $_FILES['thumbnail']['name'];
            if (move_uploaded_file($thumb_file_tmp, $category_path . '/' . $thumb_file)) {
                $post['video_thumb'] = $thumb_file;
            }
        }

        //Store video info
        $model = $this->getModel('video');
        if ($returnid = $model->store($post, 0)) {
            $msg = JText::_( 'VIDEO SAVED' );
            $link 	= JRoute::_('index.php?option=com_jomtube&view=video&id=' . $returnid);
            $cache = &JFactory::getCache('com_jomtube');
            $cache->clean();
        } else {
            $msg 	= '';
            $link 	= JRoute::_('index.php?view=videos');
        }

        $this->setRedirect($link, $msg);

    }
}
?>
