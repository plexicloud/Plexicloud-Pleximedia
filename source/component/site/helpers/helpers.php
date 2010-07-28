<?php
defined('_JEXEC') or die('Restricted access');

/**
 * Holds some usefull functions to keep the code a bit cleaner
 */
class JTHelper {
    /**
     * Return extension of a file name
     *
     * @param unknown_type $filename
     * @return unknown
     */
    public function getFileExt($filename) {
        $names = explode('.', $filename);
        $i = count($names);
        if ($i>0) return  strtolower($names[$i-1]); else return '';
    }

    /**
     * return safe file name (lower byte character)
     *
     * @param unknown_type $filename
     */
    public function getSafefileName($filename) {
        $safe = '';
        for ($i=0, $n=strlen($fileName); $i<$n; $i++) {
            $c = $fileName[$i];
            if (ord($c) < 128 && ord($c) > 32)
            $safe .= $c;
        }
        if ($safe=='') $safe = '0';
        return $safe;
    }

    /**
     * Check hard disk for not exist fileName, if file exists, change it name
     *
     * @param unknown_type $pathName
     * @param unknown_type $fileName
     * @param unknown_type $extName
     */
    public function getNoDuplicateFileName($pathName, &$fileName, $extName) {
        $i = 0;
        $newFileName = $fileName;
        while (file_exists("$pathName/$newFileName.$extName")) {
            $newFileName = $fileName . (++$i);
        }
        $fileName = $newFileName;
    }

    public  function removeSpaceFileName($fileName) {
        return str_replace(" ", "_", $fileName);
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $original_file
     * @param unknown_type $new_file
     * @param unknown_type $c  : config oblject
     * @return unknown
     */
    public  function movieToFlv($path_original, $path_new, $c) {
        ini_set("max_execution_time",300000);
        $ffmpeg_command = "$c->ffmpeg_path -y -i $path_original -ab $c->convert_audio_bitrate*1000 -ar 22050 -b $c->convert_video_bitrate*1000 -s $c->convert_frame_size $path_new";
        return exec("$ffmpeg_command 2>&1");
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $original_file
     * @param unknown_type $new_file
     * @param unknown_type $c  : config oblject
     * @return unknown
     */
    public  function movieToMp4H264($path_original, $path_new, $c) {
        ini_set("max_execution_time",300000);
        $ffmpegpath = $c->ffmpeg_path;

        // Define standard video ratios, as used within the television and motion picture industries
        // To be consistent, round everything to 2-decimal places so all numbers are in the same format
        $broadcastTV = round(4/3, 2); // the aspect ratio is 4:3, which is 640/480; rounded to two decimals, this is 1.33
        $widescreeenTV = round(16/9, 2); //the aspect ratio is 16:9, which is 640/360; rounded to two decimals, this is 1.78
        $cinemaScope = 2.35; //the aspect ratio is 2.35:1, which is roughly 640/272 rounded to two decimal places

        // Let's set our absolute dimensions, as dicated by IPod and other external media players

        $maxWidth = 640; // per IPod specs (and other similar players), 640 is maximum width, period
        $wideMaxHeight = 360; //this number keeps the proper Widescreen aspect ratio when coupled with IPod's absolute maximum width of 640
        $cinemaMaxHeight = 272; // this is the proper number for proper CinemaScope ratio coupled with IPod's absolute maximum width of 640
        $standardMaxHeight = 480; // this is the standard height for broadcast ratio, and the largest video height Ipod can handle.

        $movie = new ffmpeg_movie($path_original);
        $fcodec = $movie->getVideoCodec();
        // Now, check to see the size of the original video to make sure we're not enlarging something smaller than the max dimensions...
        $vidWith = $movie->getFrameWidth();
        $vidHeight =  $movie->getFrameHeight();

        // Set up the encode variables that will actually be passed to the encoder.
        // Remember, our absolute maximum width is 640, no matter what the aspect ratio is
        if ($vidWidth >= $maxWidth) {
            $encodeWidth = $maxWidth;
        } else {
            $encodeWidth = $vidWidth;
        }
        //						if ($encodeWidth == '')
        //							$encodeWidth = $maxWidth;
        // Because video height is what determines the ratio, we have to compare actual video height to each of the three standard types
        // First, get the aspect ratio for the current movie - remember to round it so it's in two-decimal format like the other values
        $vidRatio = round($vidWidth/$vidHeight, 2);

        // Check to see if the video has a Widescreen or Cinematic ratio. If the video for some reason is neither Widescreen nor Cinematic,
        // then let's just use Standard ratio.
        $encodeHeight = $vidHeight; // Unless of course we have to adjust the height; see code below

        switch($vidRatio) {
            case ($vidRatio == $widescreenTV):
                if ($encodeHeight >= $wideMaxHeight) {$encodeHeight = $wideMaxHeight;}
                break;
            case ($vidRatio == $cinemaScope):
                if ($encodeHeight >= $cinemaMaxHeight) {$encodeHeight = $cinemaMaxHeight;}
                break;
            default: // This is Broadcast TV, which is standard 640x480 ratio
            if ($encodeHeight >= $standardMaxHeight) {$encodeHeight = $standardMaxHeight;}
        }

        // Now, we can build the correct WxH (width x Height) ratio variable and pass this to the encode instructions.

        $encodeRatio = $encodeWidth."x".$encodeHeight;
        //echo $encodeRatio; exit();
        if ($c->h264_quality == 'highest') {
            $cmd_mencoder = "$ffmpegpath -y -chromaoffset 0 -i $path_original -acodec libfaac -ab 150k -pass 2 -s 640x352 -vcodec libx264 -b 1.5M -flags +loop -cmp +chroma -partitions +parti4x4+partp8x8+partb8x8 -me umh -subq 5 -trellis 1 -refs 1 -coder 0 -me_range 16 -g 300 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -bt 1.5M -maxrate 1.5M -bufsize 10M -rc_eq 'blurCplx^(1-qComp)' -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -level 30 -aspect 16:9 $path_new";
            //$cmd_mencoder = "$ffmpegpath -y -chromaoffset 0 -i $path_original -an -pass 2 -s 640x480 -vcodec libx264 -b 1.5M -flags +loop -cmp +chroma -partitions 0 -me epzs -subq 1 -trellis 0 -refs 1 -coder 0 -me_range 16 -g 300 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -bt 1.5M -maxrate 1.5M -bufsize 10M -rc_eq 'blurCplx^(1-qComp)' -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -level 30 -aspect 1:1 $path_new";
            //$cmd_mencoder = "$ffmpegpath -y -chromaoffset 0 -i $path_original -an -pass 1 -s ".$encodeRatio." -vcodec libx264 -b 1.5M -flags +loop -cmp +chroma -partitions 0 -me epzs -subq 1 -trellis 0 -refs 1 -coder 0 -me_range 16 -g 300 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -bt 1.5M -maxrate 1.5M -bufsize 10M -rc_eq 'blurCplx^(1-qComp)' -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -level 30 -aspect 1:1 $path_new";
            //$cmd_mencoder2 = "$ffmpegpath -y -chromaoffset 0 -i $path_original -acodec libfaac -ab 128k -pass 2 -s ".$encodeRatio." -vcodec libx264 -b 1.5M -flags +loop -cmp +chroma -partitions +parti4x4+partp8x8+partb8x8 -me umh -subq 5 -trellis 1 -refs 1 -coder 0 -me_range 16 -g 300 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -bt 1.5M -maxrate 1.5M -bufsize 10M -rc_eq 'blurCplx^(1-qComp)' -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -level 30 -aspect 16:9 $path_new";
            //$cmd_mencoder3 = "/usr/local/bin/AtomicParsley $path_new --DeepScan --iPod-uuid 1200 --overWrite";
        }
        if ($mp4_quality == 'default') {
            $cmd_mencoder = "$ffmpegpath -y -chromaoffset 0 -i $path_original -acodec libfaac -ab 128k -pass 2 -s 640x352 -vcodec libx264 -b 786K -flags +loop -cmp +chroma -partitions +parti4x4+partp8x8+partb8x8 -me umh -subq 5 -trellis 1 -refs 1 -coder 0 -me_range 16 -g 300 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -bt 786K -maxrate 1.5M -bufsize 10M -rc_eq 'blurCplx^(1-qComp)' -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -level 30 -aspect 16:9 $path_new";
            //$cmd_mencoder = "$ffmpegpath -y -chromaoffset 0 -i $path_original -an -pass 2 -s 640x480 -vcodec libx264 -b 786K -flags +loop -cmp +chroma -partitions 0 -me epzs -subq 1 -trellis 0 -refs 1 -coder 0 -me_range 16 -g 300 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -bt 786K -maxrate 1.5M -bufsize 10M -rc_eq 'blurCplx^(1-qComp)' -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -level 30 -aspect 1:1 $path_new";
            //$cmd_mencoder ="$ffmpegpath -y -chromaoffset 0 -i $path_original -an -pass 1 -s ".$encodeRatio." -vcodec libx264 -b 768K -flags +loop -cmp +chroma -partitions 0 -me epzs -subq 1 -trellis 0 -refs 1 -coder 0 -me_range 16 -g 300 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -bt 768K -maxrate 1.5M -bufsize 10M -rc_eq 'blurCplx^(1-qComp)' -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -level 30 -aspect 1:1 $path_new";
            //$cmd_mencoder2 ="$ffmpegpath -y -chromaoffset 0 -i $path_original -acodec libfaac -ab 128k -pass 2 -s ".$encodeRatio." -vcodec libx264 -b 768K -flags +loop -cmp +chroma -partitions +parti4x4+partp8x8+partb8x8 -me umh -subq 5 -trellis 1 -refs 1 -coder 0 -me_range 16 -g 300 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -bt 768K -maxrate 1.5M -bufsize 10M -rc_eq 'blurCplx^(1-qComp)' -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -level 30 -aspect 16:9 $path_new";
            //$cmd_mencoder3 ="/usr/local/bin/AtomicParsley $path_new --DeepScan --iPod-uuid 1200 --overWrite";
        }
        if ($mp4_quality == 'lowest') {
            $cmd_mencoder = "$ffmpegpath -y -chromaoffset 0 -i $path_original -acodec libfaac -ab 128k -pass 1 -s 640x352 -vcodec libx264 -b 786K -flags +loop -cmp +chroma -partitions +parti4x4+partp8x8+partb8x8 -me umh -subq 5 -trellis 1 -refs 1 -coder 0 -me_range 16 -g 300 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -bt 786K -maxrate 1.5M -bufsize 10M -rc_eq 'blurCplx^(1-qComp)' -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -level 30 -aspect 16:9 $path_new";
            //$cmd_mencoder = "$ffmpegpath -y -chromaoffset 0 -i $path_original -an -pass 1 -s 640x480 -vcodec libx264 -b 786K -flags +loop -cmp +chroma -partitions 0 -me epzs -subq 1 -trellis 0 -refs 1 -coder 0 -me_range 16 -g 300 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -bt 786K -maxrate 1.5M -bufsize 10M -rc_eq 'blurCplx^(1-qComp)' -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -level 30 -aspect 1:1 $path_new";
            //$cmd_mencoder ="$ffmpegpath -y -chromaoffset 0 -i $path_original -an -pass 1 -s ".$encodeRatio." -vcodec libx264 -b 768K -flags +loop -cmp +chroma -partitions 0 -me epzs -subq 1 -trellis 0 -refs 1 -coder 0 -me_range 16 -g 300 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -bt 768K -maxrate 1.5M -bufsize 10M -rc_eq 'blurCplx^(1-qComp)' -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -level 30 -aspect 1:1 $path_new";
            //$cmd_mencoder2 ="$ffmpegpath -y -chromaoffset 0 -i $path_original -acodec libfaac -ab 128k -pass 2 -s ".$encodeRatio." -vcodec libx264 -b 768K -flags +loop -cmp +chroma -partitions +parti4x4+partp8x8+partb8x8 -me umh -subq 5 -trellis 1 -refs 1 -coder 0 -me_range 16 -g 300 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -bt 768K -maxrate 1.5M -bufsize 10M -rc_eq 'blurCplx^(1-qComp)' -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -level 30 -aspect 16:9 $path_new";
            //$cmd_mencoder3 ="/usr/local/bin/AtomicParsley $path_new --DeepScan --iPod-uuid 1200 --overWrite";
        }

        return exec("$cmd_mencoder 2>&1");
    }

    public  function movieToMp4H264_alduccino($path_original, $path_new, $c) {
        ini_set("max_execution_time",300000);
        $ffmpegpath = $c->ffmpeg_path;
        $ffmpeg_command = "$ffmpegpath -i $path_original -acodec libfaac -ab 128k -ac 2 -vcodec libx264 -vpre lossless_medium -crf 22 -threads 0 -s 600x388 $path_new.mp4";
        exec("$ffmpeg_command 2>&1");

        return exec("/usr/bin/qt-faststart $path_new.mp4 $path_new");
        unlink("$path_new.mp4");
    }
    /**
     * Enter description here...
     *
     * @param unknown_type $video_path
     * @param unknown_type $thumb_path
     * @param unknown_type $width
     * @param unknown_type $height
     * @param unknown_type $time
     * @param unknown_type $c
     * @return unknown
     */
    public function flvToThumbnail($video_path, $thumb_path, $width=120, $height=90, $time=0, $c) {
        $ffmpeg_path = $c->ffmpeg_path;
		$videoInfo = JTHelper::getVideoInfo($video_path, $c);
		$videoFrame = JTHelper::cFormatDuration( (int) ($videoInfo['duration']['sec'] / 2), 'HH:MM:SS' );
		
        //if ($time<10)
       // $command = "$ffmpeg_path -y -itsoffset -2 -i ".escapeshellarg($video_path)." -vcodec mjpeg -vframes 1 -an -f rawvideo -s " . $width . "x" . $height . " -ss $time " . escapeshellarg($thumb_path);
        $command	= "$ffmpeg_path" . ' -i ' . $video_path . ' -ss ' . $videoFrame . ' -t 00:00:01 -s ' . '120x90' . ' -r 1 -f mjpeg ' . $thumb_path;
	//	$cmdOut = shell_exec($cmd);
		//else
       // $command = "$ffmpeg_path -y -itsoffset -10 -i ".escapeshellarg($video_path)." -vcodec mjpeg -vframes 1 -an -f rawvideo -s " . $width . "x" . $height . " -ss $time " . escapeshellarg($thumb_path);
       // $command .= " 2>&1";
	   
       // echo $command; exit();
      // return @exec($command, $output);
	  return shell_exec($command);
    }
	
	
		/*
	 * Return Video's information
	 * bitrate, duration, video and frame properties
	 * 
	 * @params string $videoFilePath path to the Video
	 * @return array of video's info	 
	 * @since Jomsocial 1.2.0
	 */
	function getVideoInfo($videoFile, $c,$cmdOut = '')
	{
		$ffmpeg_path = $c->ffmpeg_path;
		$data = array();

		if (!is_file($videoFile) && empty($cmdOut))
			return $data;

		if (!$cmdOut) {
			//$cmd	= $this->converter . ' -v 10 -i ' . $videoFile . ' 2>&1';
			// Some FFmpeg version only accept -v value from -2 to 2 
			$cmd	= "$ffmpeg_path" . ' -i ' . $videoFile . ' 2>&1';
			
			$cmdOut	= shell_exec($cmd);
		}

		if (!$cmdOut) {
			return $data;
		}
		
		preg_match_all('/Duration: (.*)/', $cmdOut , $matches);
		if (count($matches) > 0 && isset($matches[1][0]))
		{
			
			
			$parts = explode(', ', trim($matches[1][0]));
			
			$data['bitrate']			= intval(ltrim($parts[2], 'bitrate: '));
			$data['duration']['hms']	= substr($parts[0], 0, 8);
			$data['duration']['exact']	= $parts[0];
			$data['duration']['sec']	= $videoFrame = JTHelper::cFormatDuration($data['duration']['hms'], 'seconds');
			$data['duration']['excess']	= intval(substr($parts[0], 9));
		}
		else
		{
			if ($this->debug) {
				echo '<pre>FFmpeg failed to read video\'s duration</pre>';
				echo '<pre>' . $cmd . '<pre>';
				echo '<pre>' . $cmdOut . '</pre>';
			}
			return false;
		}
		$file_size = filesize($videoFile);
		$duration_in_sec = ($file_size*8)/($data['bitrate']*1000);
		//echo $duration_in_sec;
		$duration = JTHelper::sec2hms($duration_in_sec);
		//echo $duration;
		$data['duration']['hms'] = $duration;
		$data['duration']['exact'] = $duration;
		$data['duration']['sec'] = round($duration_in_sec);
		$data['duration']['excess']	= intval(substr($duration, 9));
		//echo '<pre>';print_r($data);print_r($cmdOut);die();
		preg_match('/Stream(.*): Video: (.*)/', $cmdOut, $matches);
		if (count($matches) > 0 && isset($matches[0]) && isset($matches[2]))
		{
			$data['video']	= array();

			preg_match('/([0-9]{1,5})x([0-9]{1,5})/', $matches[2], $dimensions_matches);
			$data['video']['width']		= floatval($dimensions_matches[1]);
			$data['video']['height']	= floatval($dimensions_matches[2]);

			preg_match('/([0-9\.]+) (fps|tb)/', $matches[0], $fps_matches);

			if (isset($fps_matches[1]))
				$data['video']['frame_rate']= floatval($fps_matches[1]);

			preg_match('/\[PAR ([0-9\:\.]+) DAR ([0-9\:\.]+)\]/', $matches[0], $ratio_matches);
			if(count($ratio_matches))
			{
				$data['video']['pixel_aspect_ratio']	= $ratio_matches[1];
				$data['video']['display_aspect_ratio']	= $ratio_matches[2];
			}

			if (!empty($data['duration']) && !empty($data['video']))
			{
				$data['video']['frame_count'] = ceil($data['duration']['sec'] * $data['video']['frame_rate']);
				$data['frames']				= array();
				$data['frames']['total']	= $data['video']['frame_count'];
				$data['frames']['excess']	= ceil($data['video']['frame_rate'] * ($data['duration']['excess']/10));
				$data['frames']['exact']	= $data['duration']['hms'] . '.' . $data['frames']['excess'];
			}

			$parts			= explode(',', $matches[2]);
			$other_parts	= array($dimensions_matches[0], $fps_matches[0]);

			$formats = array();
			foreach ($parts as $key => $part)
			{
				$part = trim($part);
				if (!in_array($part, $other_parts))
					array_push($formats, $part);
			}
			$data['video']['pixel_format']	= $formats[1];
			$data['video']['codec']			= $formats[0];
		}

		return $data;
	}
	
	
	/**
	 * Convert seconds to HOURS:MINUTES:SECONDS format or vice versa
	 * 
	 * @params string $duration
	 * @params string $format either seconds or HH:MM::SS
	 * @since Jomsocial 1.2.0 
	 */
	function cFormatDuration ($duration = 0, $format = 'HH:MM:SS')
	{
		if ($format == 'seconds' || $format == 'sec') {
			$arg = explode(":", $duration);
	
			$hour	= isset($arg[0]) ? intval($arg[0]) : 0;
			$minute	= isset($arg[1]) ? intval($arg[1]) : 0;
			$second	= isset($arg[2]) ? intval($arg[2]) : 0;
	
			$sec = ($hour*3600) + ($minute*60) + ($second);
			return (int) $sec;
		}
	
		if ($format == 'HH:MM:SS' || $format == 'hms') {
			$timeUnits = array
			(
				'HH' => $duration / 3600 % 24,
				'MM' => $duration / 60 % 60,
				'SS' => $duration % 60
			);
	
			$arg = array();
			foreach ($timeUnits as $timeUnit => $value) {
				$arg[$timeUnit] = ($value > 0) ? $value : 0;
			}
	
			$hms = '%02s:%02s:%02s';
			$hms = sprintf($hms, $arg['HH'], $arg['MM'], $arg['SS']);
			return $hms;
		}
	}

    /**
     * Get movie duration by php-ffmpeg
     *
     * @param unknown_type $filename
     */
    public function getMovieDuration($filename) {
        ini_set("max_execution_time",300000);
        $video_info = @ new ffmpeg_movie($filename); //duration of new flv file.
        $sec = $video_info->getDuration(); // Gets the duration in secs.
        //continue with rest of process
        if ($sec == "" || !is_numeric($sec)) {
            $sec = 2;
        }
        return $sec;
    }

    /**
     * Load Vertical Module in jomtube component
     *
     * @param unknown_type $modulePosition
     * @param unknown_type $width
     * @param unknown_type $type: 'left' ore 'right'
     */
    public function loadCustomVerticalModule($modulePosition, $width, $type ) {
        $modules = &JModuleHelper::getModules($modulePosition);
        if ($width != '') {
            echo '<div id="video'.$type.'-column" style="min-height:140px; width: '.$width.'px">';
            foreach ($modules as $module) {
                echo "<div id=\"jomtube-module-box\">";
                if($module->showtitle=="1") {
                    echo "<div id=\"jomtube-module-title\">";
                    echo $module->title;
                    echo "</div>";
                }
                echo "<div id=\"jomtube-module-content\">";
                echo JModuleHelper::renderModule($module);
                echo "</div>";
                echo "</div>";
            }
            echo '</div>';
        }
    }

    public function showLocalThumbnail($thumb_file) {
        if (!is_dir(JPATH_SITE.$thumb_file)) {
            if (file_exists(JPATH_SITE.$thumb_file)) {
                echo "<img class='vimg120' src='".JURI::root( true ).$thumb_file."' border='0'/>";
            } else {
                echo "<img class='vimg120' src='".JURI::root( true )."/administrator/components/com_jomtube/assets/images/no-thumbnail.jpg' border='0'/>";
            }
        } else {
            echo "<img class='vimg120' src='".JURI::root( true )."/administrator/components/com_jomtube/assets/images/no-thumbnail.jpg' border='0'/>";
        }
    }
    /**
    * Convert seconds to HOURS:MINUTES:SECONDS format
    **/
    public function sec2hms ($sec, $padHours = false)
    {

        // holds formatted string
        $hms = "";

        // there are 3600 seconds in an hour, so if we
        // divide total seconds by 3600 and throw away
        // the remainder, we've got the number of hours
        $hours = intval(intval($sec) / 3600);

        // add to $hms, with a leading 0 if asked for
        $hms .= ($padHours)
        ? str_pad($hours, 2, "0", STR_PAD_LEFT). ':'
        : $hours. ':';

        // dividing the total seconds by 60 will give us
        // the number of minutes, but we're interested in
        // minutes past the hour: to get that, we need to
        // divide by 60 again and keep the remainder
        $minutes = intval(($sec / 60) % 60);

        // then add to $hms (with a leading 0 if needed)
        $hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT). ':';

        // seconds are simple - just divide the total
        // seconds by 60 and keep the remainder
        $seconds = intval($sec % 60);

        // add to $hms, again with a leading 0 if needed
        $hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);

        // done!
        return $hms;
    }

    /**
     * Convert from UTF-8 to non mark Vietnamese text
     *
     * @param String $value: input text
     * @return Non marke Vietnamese text
     */
    public static function vietDecode($value)
    {
        #---------------------------------a^
        $value = str_replace("ấ", "a", $value);
        $value = str_replace("ầ", "a", $value);
        $value = str_replace("ẩ", "a", $value);
        $value = str_replace("ẫ", "a", $value);
        $value = str_replace("ậ", "a", $value);
        #---------------------------------A^
        $value = str_replace("Ấ", "A", $value);
        $value = str_replace("Ầ", "A", $value);
        $value = str_replace("Ẩ", "A", $value);
        $value = str_replace("Ẫ", "A", $value);
        $value = str_replace("Ậ", "A", $value);
        #---------------------------------a(
        $value = str_replace("ắ", "a", $value);
        $value = str_replace("ằ", "a", $value);
        $value = str_replace("ẳ", "a", $value);
        $value = str_replace("ẵ", "a", $value);
        $value = str_replace("ặ", "a", $value);
        #---------------------------------A(
        $value = str_replace("Ắ", "A", $value);
        $value = str_replace("Ằ", "A", $value);
        $value = str_replace("Ẳ", "A", $value);
        $value = str_replace("Ẵ", "A", $value);
        $value = str_replace("Ặ", "A", $value);
        #---------------------------------a
        $value = str_replace("á", "a", $value);
        $value = str_replace("à", "a", $value);
        $value = str_replace("ả", "a", $value);
        $value = str_replace("ã", "a", $value);
        $value = str_replace("ạ", "a", $value);
        $value = str_replace("â", "a", $value);
        $value = str_replace("ă", "a", $value);
        #---------------------------------A
        $value = str_replace("Á", "A", $value);
        $value = str_replace("À", "A", $value);
        $value = str_replace("Ả", "A", $value);
        $value = str_replace("Ã", "A", $value);
        $value = str_replace("Ạ", "A", $value);
        $value = str_replace("Â", "A", $value);
        $value = str_replace("Ă", "A", $value);
        #---------------------------------e^
        $value = str_replace("ế", "e", $value);
        $value = str_replace("ề", "e", $value);
        $value = str_replace("ể", "e", $value);
        $value = str_replace("ễ", "e", $value);
        $value = str_replace("ệ", "e", $value);
        #---------------------------------E^
        $value = str_replace("Ế", "E", $value);
        $value = str_replace("Ề", "E", $value);
        $value = str_replace("Ể", "E", $value);
        $value = str_replace("Ễ", "E", $value);
        $value = str_replace("Ệ", "E", $value);
        #---------------------------------e
        $value = str_replace("é", "e", $value);
        $value = str_replace("è", "e", $value);
        $value = str_replace("ẻ", "e", $value);
        $value = str_replace("ẽ", "e", $value);
        $value = str_replace("ẹ", "e", $value);
        $value = str_replace("ê", "e", $value);
        #---------------------------------E
        $value = str_replace("É", "E", $value);
        $value = str_replace("È", "E", $value);
        $value = str_replace("Ẻ", "E", $value);
        $value = str_replace("Ẽ", "E", $value);
        $value = str_replace("Ẹ", "E", $value);
        $value = str_replace("Ê", "E", $value);
        #---------------------------------i
        $value = str_replace("í", "i", $value);
        $value = str_replace("ì", "i", $value);
        $value = str_replace("ỉ", "i", $value);
        $value = str_replace("ĩ", "i", $value);
        $value = str_replace("ị", "i", $value);
        #---------------------------------I
        $value = str_replace("Í", "I", $value);
        $value = str_replace("Ì", "I", $value);
        $value = str_replace("Ỉ", "I", $value);
        $value = str_replace("Ĩ", "I", $value);
        $value = str_replace("Ị", "I", $value);
        #---------------------------------o^
        $value = str_replace("ố", "o", $value);
        $value = str_replace("ồ", "o", $value);
        $value = str_replace("ổ", "o", $value);
        $value = str_replace("ỗ", "o", $value);
        $value = str_replace("ộ", "o", $value);
        #---------------------------------O^
        $value = str_replace("Ố", "O", $value);
        $value = str_replace("Ồ", "O", $value);
        $value = str_replace("Ổ", "O", $value);
        $value = str_replace("Ô", "O", $value);
        $value = str_replace("Ộ", "O", $value);
        #---------------------------------o*
        $value = str_replace("ớ", "o", $value);
        $value = str_replace("ờ", "o", $value);
        $value = str_replace("ở", "o", $value);
        $value = str_replace("ỡ", "o", $value);
        $value = str_replace("ợ", "o", $value);
        #---------------------------------O*
        $value = str_replace("Ớ", "O", $value);
        $value = str_replace("Ờ", "O", $value);
        $value = str_replace("Ở", "O", $value);
        $value = str_replace("Ỡ", "O", $value);
        $value = str_replace("Ợ", "O", $value);
        #---------------------------------u*
        $value = str_replace("ứ", "u", $value);
        $value = str_replace("ừ", "u", $value);
        $value = str_replace("ử", "u", $value);
        $value = str_replace("ữ", "u", $value);
        $value = str_replace("ự", "u", $value);
        #---------------------------------U*
        $value = str_replace("Ứ", "U", $value);
        $value = str_replace("Ừ", "U", $value);
        $value = str_replace("Ử", "U", $value);
        $value = str_replace("Ữ", "U", $value);
        $value = str_replace("Ự", "U", $value);
        #---------------------------------y
        $value = str_replace("ý", "y", $value);
        $value = str_replace("ỳ", "y", $value);
        $value = str_replace("ỷ", "y", $value);
        $value = str_replace("ỹ", "y", $value);
        $value = str_replace("ỵ", "y", $value);
        #---------------------------------Y
        $value = str_replace("?", "Y", $value);
        $value = str_replace("Ỳ", "Y", $value);
        $value = str_replace("Ỷ", "Y", $value);
        $value = str_replace("Ỹ", "Y", $value);
        $value = str_replace("Ỵ", "Y", $value);
        #---------------------------------DD
        $value = str_replace("Đ", "D", $value);
        $value = str_replace("Đ", "D", $value);
        $value = str_replace("đ", "d", $value); #Supplemental

        #---------------------------------o
        $value = str_replace("ó", "o", $value);
        $value = str_replace("ò", "o", $value);
        $value = str_replace("ỏ", "o", $value);
        $value = str_replace("õ", "o", $value);
        $value = str_replace("ọ", "o", $value);
        $value = str_replace("ô", "o", $value);
        $value = str_replace("ơ", "o", $value);
        #---------------------------------O
        $value = str_replace("Ó", "O", $value);
        $value = str_replace("Ò", "O", $value);
        $value = str_replace("Ỏ", "O", $value);
        $value = str_replace("Õ", "O", $value);
        $value = str_replace("Ọ", "O", $value);
        $value = str_replace("Ô", "O", $value);
        $value = str_replace("Ơ", "O", $value);
        #---------------------------------u
        $value = str_replace("ú", "u", $value);
        $value = str_replace("ù", "u", $value);
        $value = str_replace("ủ", "u", $value);
        $value = str_replace("ũ", "u", $value);
        $value = str_replace("ụ", "u", $value);
        $value = str_replace("ư", "u", $value);
        #---------------------------------U
        $value = str_replace("Ú", "U", $value);
        $value = str_replace("Ù", "U", $value);
        $value = str_replace("Ủ", "U", $value);
        $value = str_replace("Ũ", "U", $value);
        $value = str_replace("Ụ", "U", $value);
        $value = str_replace("Ư", "U", $value);
        $value = str_replace(array('"', "'", '/', '\\', '?', ',', ':', '|', '>', '<'), '', $value);
        return $value;
    }

    function SizeImage($maxWidth, $maxHW, $fileName, $thumbFileName) {
        list($width, $height, $type, $attr) = @getimagesize($fileName);
        if ($width==null || $width==0) return $fileName;

        if ($width>$maxWidth) {
            $scaleX = $maxWidth / $width;
            $scaleY = $maxHW / $height;
            if ($scaleX > $scaleY) $scaleX = $scaleY;
            $maxWidth = $width * $scaleX;
            $maxHeight = $height * $scaleX;
            $img2 = imagecreatetruecolor($maxWidth, $maxHeight);
            switch ($type) {
                case 1:
                    $img = imagecreatefromgif($fileName);
                    break;
                case 2:
                    $img = imagecreatefromjpeg($fileName);
                    break;
                case 3:
                    $img = imagecreatefrompng($fileName);
                    break;
            }
            imagecopyresampled($img2, $img, 0, 0, 0, 0, $maxWidth, $maxHeight, $width, $height);
            imagejpeg($img2, $thumbFileName);
        }
    }
	
	/**
 * Generate new random file name with specified extension
 * create the directory if it does not exist
 * 
 * @params string	$directory	Directory path
 * $params string	$filename	File name, optional
 * @params string	$extension	File extension, optional
 * $params int		$length		The length of filename
 * @return string	File name with extension
 * @since Jomsocial 1.2.0
 */
function cGenRandomFilename($directory, $filename = '' , $extension = '', $length = 11)
{
	if (strlen($directory) < 1)
		return false;

	$directory = JPath::clean($directory);
	
	
	jimport('joomla.filesystem.file');
	jimport('joomla.filesystem.folder');

	if (!JFile::exists($directory))
		JFolder::create( $directory, 0775 );

	if (strlen($filename) > 0)
		$filename	= JFile::makeSafe($filename);

	if (!strlen($extension) > 0)
		$extension	= '';

	$dotExtension 	= $filename ? JFile::getExt($filename) : $extension;
	$dotExtension 	= $dotExtension ? '.' . $dotExtension : '';

	$map			= 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	$len 			= strlen($map);
	$stat			= stat(__FILE__);
	$randFilename	= '';

	if(empty($stat) || !is_array($stat))
		$stat = array(php_uname());

	mt_srand(crc32(microtime() . implode('|', $stat)));
	for ($i = 0; $i < $length; $i ++) {
		$randFilename .= $map[mt_rand(0, $len -1)];
	}

	$randFilename .= $dotExtension;

	if (JFile::exists($directory . DS . $randFilename)) {
		cGenRandomFilename($directory, $filename, $extension, $length);
	}

	return $randFilename;
}
}
?>