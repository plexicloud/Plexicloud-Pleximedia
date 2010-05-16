<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class jomtubeModelVideo extends JModel
{
    /**
	 * Category id
	 *
	 * @var int
	 */
    var $_id = null;

    /**
	 * Category data array
	 *
	 * @var array
	 */
    var $_data = null;

    /**
	 * Groups data array
	 *
	 * @var array
	 */
    var $_groups = null;

    /**
	 * Constructor
	 *
	 * @since 0.9
	 */
    function __construct()
    {
        parent::__construct();

        $array = JRequest::getVar('cid',  0, '', 'array');
        $this->setId((int)$array[0]);
    }

    /**
	 * Method to set the identifier
	 *
	 * @access	public
	 * @param	int category identifier
	 */
    function setId($id)
    {
        // Set category id and wipe data
        $this->_id	    = $id;
        $this->_data	= null;
    }

    /**
	 * Method to get content category data
	 *
	 * @access	public
	 * @return	array
	 * @since	0.9
	 */
    function &getData()
    {
        if ($this->_loadData())
        {

        }
        else  $this->_initData();

        return $this->_data;
    }

    /**
	 * Method to load content event data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 * @since	0.9
	 */
    function _loadData()
    {
        // Lets load the content if it doesn't already exist
        if (empty($this->_data))
        {
            $query = 'SELECT v.*, c.directory'
            . ' FROM #__jomtube_videos AS v '
            . ' JOIN #__jomtube_categories AS c ON c.id = v.category_id'
            . ' WHERE v.id = '.$this->_id
            ;
            $this->_db->setQuery($query);
            $this->_data = $this->_db->loadObject();

            return (boolean) $this->_data;
        }
        return true;
    }

    /**
	 * Method to initialise the category data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 * @since	0.9
	 */
    function _initData()
    {
        // Lets load the content if it doesn't already exist
        if (empty($this->_data))
        {
            $video = new stdClass();
            $video->id					= 0;
            $video->video_title			= 0;

            $this->_data					= $video;
            return (boolean) $this->_data;
        }
        return true;
    }

    /**
	 * Method to checkin/unlock the item
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	0.9
	 */
    function checkin()
    {
        if ($this->_id)
        {
            $category = & JTable::getInstance('jomtube_categories', '');
            return $category->checkin($this->_id);
        }
        return false;
    }


    /**
	 * Method to store the category
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
    function store($data, $batchupload = 0)
    {
        $row  =& $this->getTable('jomtube_videos', '');
        $user 		= & JFactory::getUser();
        $c = jomtube_configs::get_instance();

        // bind it to the table
        if (!$row->bind($data)) {
            JError::raiseError(500, $this->_db->getErrorMsg() );
            return false;
        }
        //var_dump($row); exit();
        // Make sure the data is valid
        if (!$row->check()) {
            $this->setError($row->getError());
            return false;
        }

        if ($batchupload == 0) {
            // ###########################################################
            // ###### CONVERT VIDEO TO FLV OR MP4-H264
            // ###########################################################
            if ($c->use_ffmpeg) {
                //convert video to flv or mp4
                $path_file = $data['catdir'].'/'.$row->video_url;
                if ($c->h264_convert2mp4) {
                    $path_new = $data['catdir'].'/'.$row->video_url . '.mp4';
                    JTHelper::movieToMp4H264_alduccino($path_file . $vidfile, $path_new, $c);

                    //update video_url
                    $row->video_url = $data['video_url'] . '.mp4';
                } else {
                    $path_new = $path_file . $vidfile . '.flv';
                    $ext = JTHelper::getFileExt($vidfile);
                    if ($ext != 'flv') {
                        JTHelper::movieToFlv($path_file . $vidfile, $path_new, $c);
                        //update video_url
                        $row->video_url = $data['video_url'] . '.flv';
                    }
                }
            }

            //manual upload video thumbnail
            $thumb_dir = $data['catdir'] . "/_thumbs";
            if (!is_dir($thumb_dir))
            mkdir($thumb_dir);
            $thumb_file = $_FILES['thumbnail']['name'];
            if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], "$thumb_dir/$thumb_file")) {
                $row->video_thumb = $thumb_file;
                //resize image
                @JTHelper::SizeImage(120, 90, "$thumb_dir/$thumb_file", "$thumb_dir/$thumb_file");
                //if ($row->duration == "")
                    //$row->duration = "00:02";
            } else {//create thumbnail by FFMPEG
                //var_dump("$thumb_dir/$thumb_file"); exit();
                //create thumb
                if ($c->use_php_ffmpeg) {
                    $sec = JTHelper::getMovieDuration($data['catdir'].'/'.$row->video_url);
                } else {

                }
                //continue with rest of process
                if ($sec == "" || !is_numeric($sec)) {
                    $sec = 6;
                    $duration = JTHelper::sec2hms($sec);//covert to 00:00:00 i.e. hrs:min:sec
                } else {
                    $duration = JTHelper::sec2hms($sec);//covert to 00:00:00 i.e. hrs:min:sec
                    $row->duration = $duration;
                }

                if ($c->use_ffmpeg) {
                    //get the middle of the movie (time; 00:00:00 format) for thumbnail
                    $sec2 = $sec / 2;
                    $sec2 = @round($sec2);
                    $thumb_position = JTHelper::sec2hms($sec2);

                    //$thumb_position = 10;
                    JTHelper::flvToThumbnail($data['catdir'].'/'.$row->video_url, $data['catdir'].'/_thumbs/'.$row->video_url.'.jpg', 120, 90, $thumb_position, $c);

                    if (!is_dir($data['catdir'].'/_display'))
                    {
                        $oldumask=umask(0);
                        @mkdir ($data['catdir'].'/_display',0755);
                        umask($oldumask);
                    }
                    JTHelper::flvToThumbnail($data['catdir'].'/'.$row->video_url, $data['catdir'].'/_display/'.$row->video_url.'.jpg', 470, 320, $thumb_position, $c);
                    $row->video_thumb = $row->video_url.'.jpg';
                }//if ($c->use_ffmpeg)
            }
        }
        if ($row->id == 0)
        $row->user_id = $user->id;
        // Store it in the db
        if (!$row->store()) {
            JError::raiseError(500, $this->_db->getErrorMsg() );
            return false;
        }

        return $row->id;
    }

    function _getFamilyid($id) {
        $db = & JFactory::getDBO();
        $query = 'SElECT family_id FROM #__jomtube_categories WHERE id = ' . $id;
        $db->setQuery($query);
        return $db->loadResult();
    }

    function &getParentOption() {
        $db		=& JFactory::getDBO();
        $sql = 'SELECT id, category_name, family_id FROM #__jomtube_categories ORDER BY family_id';
        $db->setQuery($sql);
        $rows = $db->loadObjectList();

        return $rows;
    }

    function &getCatDirectory () {
        $db		=& JFactory::getDBO();
        $query = 'SELECT directory FROM #__jomtube_categories WHERE id = ' . $category_id = JRequest::getVar('category_id', 0);
        $db->setQuery($query);
        $directory = $db->loadResult();
        return  $directory;
    }

    function uploadbatch($data) {
        $db = & JFactory::getDBO();
        $counter = 0;
        $upload_batch_dir = JPATH_SITE."/jomtubefiles/_batch_upload/";
        $c = jomtube_configs::get_instance();

        //get category directory
        $query = 'SELECT id, directory FROM #__jomtube_categories WHERE id = \'' . $_POST['category_id'] . '\'';
        $db->setQuery($query);
        $category = $db->loadObject();
        $path_file = JPATH_SITE.$category->directory.'/';
        $path_thumb = JPATH_SITE.$category->directory.'/_thumbs/';
        $path_display = JPATH_SITE.$category->directory.'/_display/';
        if (!is_dir($path_display))
        {
            $oldumask=umask(0);
            @mkdir ($path_display,0755);
            umask($oldumask);
        }

        //get video from upload batch dir
        $dir = opendir($upload_batch_dir);
        $counter = 0;
        if ($category) {
            while($file=readdir($dir))
            {
                if ($file!="." and $file!=".."){
                    $vidfile=$file;
                    if (!is_dir($vidfile)){
                        //move file to the proper category directory
                        if (copy($upload_batch_dir . $vidfile, $path_file . $vidfile)) {
                            unlink($upload_batch_dir . $vidfile);//delete original file
                            //Capitalize the first letter
                            $video_title = str_replace('_', ' ', $vidfile);
                            //remove extension of file
                            $video_title = explode('.', $video_title);
                            $video_title = $video_title[0];
                            $video_title = ucwords($video_title);
                            $_POST['video_title'] = $_POST['video_desc'] = $video_title;
                            $_POST['video_url'] = $vidfile;
                            //get video duration
                            if ($c->use_php_ffmpeg) {//use php-ffmpeg
                                $sec = JTHelper::getMovieDuration($path_file . $vidfile);
                                $duration = JTHelper::sec2hms($sec); //covert to 00:00:00 i.e. hrs:min:sec*/
                            } else {//not use php-ffmpeg
                                $sec = 6;
                                $duration = '';
                            }
                            //get the middle of the movie (time; 00:00:00 format) for thumbnail
                            if ($c->use_ffmpeg) {//use ffmpeg
                                $sec2 = $sec / 2;
                                $sec2 = @round($sec2);
                                if ($sec<20) {
                                    $thumb_position = 5;
                                } else {
                                    $thumb_position = JTHelper::sec2hms($sec2);
                                }
                                //convert video to flv or mp4
                                if ($c->h264_convert2mp4) {
                                    $path_new = $path_file . $vidfile . '.mp4';
                                    JTHelper::movieToMp4H264_alduccino($path_file . $vidfile, $path_new, $c);

                                    //update video_url
                                    $_POST['video_url'] = $_POST['video_url'] . '.mp4';
                                } else {
                                    $path_new = $path_file . $vidfile . '.flv';
                                    $ext = JTHelper::getFileExt($vidfile);
                                    if ($ext != 'flv') {
                                        JTHelper::movieToFlv($path_file . $vidfile, $path_new, $c);
                                        //update video_url
                                        $_POST['video_url'] = $_POST['video_url'] . '.flv';
                                    }
                                }

                                $_POST['duration'] = $duration;
                                //create thumb
                                JTHelper::flvToThumbnail($path_file . $_POST['video_url'], $path_thumb . $_POST['video_url'] . '.jpg', 120, 90, $thumb_position, $c);
                                //create display image
                                JTHelper::flvToThumbnail($path_file . $_POST['video_url'], $path_display .$_POST['video_url'] . '.jpg', 470, 320, $thumb_position, $c);
                                $_POST['video_thumb'] = $_POST['video_url'] . '.jpg';
                                $this->store($_POST, 1);
                                $counter ++;
                            } else {// not use ffmpeg

                            }
                        }
                    }
                }//if
            }
            closedir($dir);
        }
        //var_dump($counter); exit();
        return $counter;
    }
}
?>