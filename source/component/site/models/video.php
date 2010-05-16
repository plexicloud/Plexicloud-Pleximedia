<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

/**
 * jomtube Component Video Model
 *
 * @package Joomla
 * @subpackage jomtube
 * @since		0.9
 */
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

        $array = JRequest::getVar('id',  0, '', 'array');
        if (is_array($array))
            $this->setId((int)$array[0]);
        else
            $this->setId((int)$array);
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
        if ($this->_loadData()) {
            //add count+1;
            $this->_addCount();
        }	else  $this->_initData();

        return $this->_data;
    }

    /**
	 * Method to get the group data
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	0.9
	 */
    /*function &getGroups()
    {
    $query = 'SELECT id AS value, name AS text'
    . ' FROM #__eventlist_groups'
    . ' ORDER BY name'
    ;
    $this->_db->setQuery( $query );

    $this->_groups = $this->_db->loadObjectList();

    return $this->_groups;
    }*/

    /**
	 * Method to load content event data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 * @since	0.9
	 */
    function _loadData()
    {
        $c = jomtube_configs::get_instance();
        // Lets load the content if it doesn't already exist
        if (empty($this->_data))
        {
            $where = ' WHERE v.id = ' . $this->_id;
            $join = ' LEFT JOIN #__users AS u ON u.id = v.user_id';
            $select = ' v.*, u.name, u.username';
            if ($c->community == 'JomSocial') {
            	$join.= ' LEFT JOIN #__community_users AS p ON p.userid = v.user_id';
            	$select.= ', p.avatar';
            }
            $select .= ', c.directory, c.family_id, c.category_name';
            $join .= ' JOIN #__jomtube_categories AS c ON c.id = v.category_id';

            $query = 'SELECT ' . $select . ' FROM #__jomtube_videos AS v ' . $join . $where;

            /*
            $query = 'SELECT v.*, c.directory, u.username, c.category_name, c.family_id'
            . ' FROM #__jomtube_videos AS v '
            . ' LEFT JOIN #__users AS u ON v.user_id = u.id'
            . ' JOIN #__jomtube_categories AS c ON c.id = v.category_id'
            . ' WHERE v.id = '.$this->_id
            ;*/

            $this->_db->setQuery($query);
            $this->_data = $this->_db->loadObject();
            $this->_data->ratingAvg = $this->getRatingAvg();
            $this->_data->ratingCount = $this->getRatingCount();
            return (boolean) $this->_data;
        }
        return true;
    }

    function getVideosincategory() {
        $query = 'SELECT v.*, c.directory, u.username, c.category_name, u.id as userid '
        . ' FROM #__jomtube_videos AS v '
        . ' LEFT JOIN #__users AS u ON v.user_id = u.id'
        . ' JOIN #__jomtube_categories AS c ON c.id = v.category_id'
        . ' WHERE v.category_id = '.$this->_data->category_id
        . ' AND v.id != ' . $this->_id
        . ' AND v.published = 1 '
        . ' ORDER BY v.date_added DESC'
        . ' LIMIT 20'
        ;
        $this->_db->setQuery($query);
        return $this->_db->loadObjectList();
    }

    function getCountVideoincategory() {
        $query = 'SELECT COUNT(*) '
        . ' FROM #__jomtube_videos AS v '
        . ' JOIN #__jomtube_categories AS c ON c.id = v.category_id'
        . ' WHERE v.category_id = '.$this->_data->category_id
        . ' AND v.id != ' . $this->_id
        ;
        $this->_db->setQuery($query);
        return $this->_db->loadResult();
    }

    function _addCount() {
        $query = 'UPDATE #__jomtube_videos SET hits = hits+1 WHERE id = ' . $this->_id;
        $this->_db->setQuery($query);
        $this->_db->query();
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
     * Enter description here...
     *
     * @param unknown_type $data
     * @param unknown_type $type
     * $type = 0: batchupload, = 1: add remote video, =2: upload from local
     *  @return unknown
     */
    function store($data, $type = 0)
    {
        $row  =& $this->getTable('jomtube_videos', '');
        $user 		= & JFactory::getUser();
        // bind it to the table
        if (!$row->bind($data)) {
            JError::raiseError(500, $this->_db->getErrorMsg() );
            return false;
        }

        // Make sure the data is valid
        if (!$row->check()) {
            $this->setError($row->getError());
            return false;
        }

        if ($this->checkDuplicateRemoteVideo($row->remote_id) > 0) {
            return 1;
        }

        //var_dump($row); exit();

        if ($type == 0) { //batchupload
            // ###########################################################
            // ###### CONVERT VIDEO TO FLV OR MP4-H264
            // ###########################################################
            //get all settings
            $path_original = $data['catdir'] . '/' . $row->video_url;

            $c = jomtube_configs::get_instance();

            if ($c->use_ffmpeg) {
                if ($c->h264_convert2mp4) {
                    $path_new = $data['catdir'] . '/' . $row->video_url . '.mp4';
                    JTHelper::movieToMp4H264_alduccino($path_original, $path_new, $c);

                    //update video_url
                    $row->video_url = $row->video_url . '.mp4';
                } else {
                    $path_new = $data['catdir'] . '/' . $row->video_url . '.flv';
                    JTHelper::movieToFlv($path_original, $path_new, $c);

                    //update video_url
                    $row->video_url = $row->video_url . '.flv';
                }

                //delete original file
                if ($c->delete_orignial_file) {
                    @unlink($path_original);
                }
            } else {//not use ffmpeg

            }

            // ###########################################################
            // ###### CREATE THUMBNAIL FROM FLV OR MP4
            // ###########################################################
            $flv_path = $data['catdir'].'/'.$row->video_url;
            $thumb_path = $data['catdir'].'/_thumbs/'.$row->video_url.'.jpg';
            $display_path = $data['catdir'].'/_display/'.$row->video_url.'.jpg';

            //get movie duration
            if ($c->use_php_ffmpeg) {//use php-ffmpeg
                $sec = JTHelper::getMovieDuration($flv_path);
                $duration = JTHelper::sec2hms($sec); //covert to 00:00:00 i.e. hrs:min:sec*/
            } else {//not use php-ffmpeg
                $sec = 6;
                $duration = '';
            }
            $row->duration = $duration;

            if (!is_dir($display_path))
            {
                $oldumask=umask(0);
                @mkdir ($display_path, 0755);
                umask($oldumask);
            }
            //get the middle of the movie (time; 00:00:00 format) for thumbnail
            if ($c->use_ffmpeg) {//use ffmpeg
                $sec2 = $sec / 2;
                $sec2 = @round($sec2);
                $thumb_position = JTHelper::sec2hms($sec2);

                JTHelper::flvToThumbnail($flv_path, $thumb_path, 120, 90, $thumb_position, $c);

                JTHelper::flvToThumbnail($flv_path, $display_path, $c->video_player_width, $c->video_player_height, $thumb_position, $c);

                $row->video_thumb = $row->video_url.'.jpg';
            } else {// not use ffmpeg
                $orignial_thumb = $data['catdir'] . '/' . $row->video_thumb;
                JTHelper::SizeImage(120, 90, $orignial_thumb, $thumb_path);
                JTHelper::SizeImage($c->video_player_width, $c->video_player_height, $orignial_thumb, $display_path);
                $row->video_thumb = $row->video_url.'.jpg';
                if (file_exists($orignial_thumb))
                    unlink($orignial_thumb);
            }
        }

        $row->user_id = $user->id;
        // Store it in the db
        //var_dump($row); exit();
        if (!$row->store()) {
            JError::raiseError(500, $this->_db->getErrorMsg() );
            return false;
        }

        return $row->id;
    }

    function checkDuplicateRemoteVideo($remote_id) {
        $db = & JFactory::getDBO();
        $query = 'SELECT  * FROM #__jomtube_videos WHERE video_type != \'local\' AND remote_id = \'' . $remote_id . '\' LIMIT 1' ;
        $db->setQuery($query);
        return $db->loadObject();
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
        return  $db->loadResult();
    }

    function _createThumb($video_path, $thumb_path, $width=120, $height=90, $time=0) {
        $c = jomtube_configs::get_instance();
        $ffmpeg_path = $c->ffmpeg_path;
        //$command = "$ffmpeg_path -y -v 0 -i ". escapeshellarg($video_path) ." -s 120x90 -f mjpeg -t 0.001 -ss  $time". escapeshellarg($thumb_path);
        //$command = "$ffmpeg_path -y -v 0 -i ". escapeshellarg($video_path) ." -s ".$width ."x". $height . " -f mjpeg -t 0.001 -ss $time ". escapeshellarg($thumb_path);
        if ($time<10)
        $command = "$ffmpeg_path -y -itsoffset -2 -i ".escapeshellarg($video_path)." -vcodec mjpeg -vframes 1 -an -f rawvideo -s " . $width . "x" . $height . " -ss $time " . escapeshellarg($thumb_path);
        else
        $command = "$ffmpeg_path -y -itsoffset -10 -i ".escapeshellarg($video_path)." -vcodec mjpeg -vframes 1 -an -f rawvideo -s " . $width . "x" . $height . " -ss $time " . escapeshellarg($thumb_path);
        $command .= " 2>&1";
        //echo $command; exit();
        return @exec($command, $output);
    }

    function rate_video()
    {
        $db	=& JFactory::getDBO();
        $id = JFilterInput::clean(JRequest::getVar('id'), 'INT');
        $user_id = JFilterInput::clean(JRequest::getVar('user_id'), 'INT');
        $rating = JFilterInput::clean(JRequest::getVar('rating'), 'INT');

        if ($id && $user_id && $rating)
        {
            $sql = "SELECT COUNT(*) FROM #__jomtube_rating v "
            ."WHERE v.`user_id` = " . JRequest::getVar('user_id')
            ." AND v.`v_id` = " . JRequest::getVar('id');
            $db->setQuery($sql);
            $db->query();

            $count = $db->loadResult();

            if (!$count)
            {
                $sql = "INSERT INTO #__jomtube_rating (`v_id`,`user_id`,`rating`) "
                ."VALUES ("
                . JRequest::getVar('id') . ","
                . JRequest::getVar('user_id') . ","
                . JRequest::getVar('rating') . ")";

                $db->setQuery( $sql );
                $db->query();
            }
            else
            {
                $sql = "UPDATE #__jomtube_rating "
                ."SET `rating` = " . JRequest::getVar('rating') . " "
                ."WHERE `v_id` = " . JRequest::getVar('id') . " "
                ."AND `user_id` = " . JRequest::getVar('user_id') . " "
                ."LIMIT 1";

                $db->setQuery( $sql );
                $db->query();
            }
            //update ratetotal
            $query = 'UPDATE #__jomtube_videos SET votetotal = ' . $this->getRatingAvg() . ' WHERE id = ' . $this->_id;
            $db->setQuery($query);
            $db->query();

            return true;
        }
        else
        {
            return false;
        }
    }

    function getRatingCount() {
        $query = 'SELECT COUNT(*) FROM #__jomtube_rating WHERE v_id = ' . $this->_id;
        $this->_db->setQuery($query);
        return $this->_db->loadResult();
    }

    function getRatingAvg() {
        $query = 'SELECT AVG(rating) FROM #__jomtube_rating WHERE v_id = ' .$this->_id;
        $this->_db->setQuery($query);
        return $this->_db->loadResult();
    }

    function getBreadcrumb() {
        $family_id = $this->_data->family_id;
        $family_id = str_replace('/', ',', $family_id);
        $query = 'SELECT * FROM #__jomtube_categories WHERE ID IN (' . $family_id . ') ORDER BY family_id';
        $this->_db->setQuery($query);
        return $this->_db->loadObjectList();
    }

}
?>