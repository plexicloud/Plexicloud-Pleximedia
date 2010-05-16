<?php
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

function com_install() {
    global $database, $mainframe;
    $db =& JFactory::getDBO();

    # Show installation result to user
  ?>
  <center>
  <table width="100%" border="0" cellpadding="2"  cellspacing="2">
    <tr>
      <td style="border:1px solid #333;" align="left" colspan="2">
        <p style="font-size: 180%"><b>JomTube 1.0.5 <b></p>
        <p>Welcome to <a target="_blank" href='http://jomtube.com'>http://JomTube.com</a> which is the official home of a new Video Component JomTube which is designed specifically for Joomla 1.5x with the Native MVC Framework as there will never be any version for made Joomla 1.0x. This site is currently designed and updated along with the Development of the JomTube Video Component for Joomla 1.5x which we have been designing for the past 2 months and got most of the Major Framework done</p>
      </td>
    </tr>


    <?php
        //check exited database
        $db->setQuery("SELECT COUNT(*) FROM #__jomtube_videos");
        if ($db->loadResult() == 0) {
            $db->setQuery("
              insert into `#__jomtube_videos` values
                (1,62,'Add youtube remote video','Add A Video To JomTube From The Front End from YOUTUBE ',1,'jomtube, joomla 1.5, 1.5 native, mvc framework','pending','0000-00-00 00:00:00',1,NULL,NULL,'0:02:52','2009-03-05',1,'youtube','http://www.youtube.com/watch?v=w6L3kgPAP9o&eurl=http://www.jomtube.com/index.php?option=com_content&view=article&id=3824:add-a-video-from-youtube-tutorial&cat','http://i.ytimg.com/vi/w6L3kgPAP9o/1.jpg',1,0,0,'2009-03-05','w6L3kgPAP9o','http://i.ytimg.com/vi/w6L3kgPAP9o/0.jpg'),
                (2,62,'Left And Right Columns Video List','Adding Modules in Left And Right Custom JomTube Position ',1,'joomla 1.5, video component, jomtube','pending','0000-00-00 00:00:00',1,NULL,NULL,'0:02:24','2009-03-05',1,'youtube','http://www.youtube.com/watch?v=sFXCJicTjo8&eurl=http://www.jomtube.com/index.php?option=com_content&view=article&id=3823:add-custom-right-and-left-column-module','http://i.ytimg.com/vi/sFXCJicTjo8/1.jpg',1,0,0,'2009-03-05','sFXCJicTjo8','http://i.ytimg.com/vi/sFXCJicTjo8/0.jpg'),
                (3,62,'JomTube Custom Player Position','JomTube Custom Player Position Tutorial ',1,'jomtube, joomla, component, video component, joomla 1.5, native','pending','0000-00-00 00:00:00',0,NULL,NULL,'0:03:03','2009-02-22',1,'youtube','http://www.youtube.com/watch?v=R1JZMuYy8oc&eurl=http://www.jomtube.com/index.php?option=com_content&view=article&id=3820:custom-module-postion-on-video-player-p','http://i.ytimg.com/vi/R1JZMuYy8oc/1.jpg',1,0,0,'2009-02-23','R1JZMuYy8oc','http://i.ytimg.com/vi/R1JZMuYy8oc/0.jpg'),
                (4,62,'Install Vertical Module','Installing the Vertical Module For JomTube Video Component for Joomla 1.5 ',1,'video component, jomtube, joomla 1.5','pending','0000-00-00 00:00:00',0,NULL,NULL,'0:04:52','2009-02-23',1,'youtube','http://www.youtube.com/watch?v=LFirCNH6cBk&eurl=http://www.jomtube.com/index.php?option=com_content&view=article&id=3821:installing-the-vertical-module-for-jomt','http://i.ytimg.com/vi/LFirCNH6cBk/1.jpg',1,0,0,'2009-02-23','LFirCNH6cBk','http://i.ytimg.com/vi/LFirCNH6cBk/0.jpg'),
                (5,62,'JomTube Batch Upload Feature','JomTube Batch Upload Feature ',1,'joomla, video component, 1.5 native, jomtube','pending','0000-00-00 00:00:00',1,NULL,NULL,'0:04:39','2009-02-18',1,'youtube','http://www.youtube.com/watch?v=home62dt15w&eurl=http://www.jomtube.com/index.php?option=com_content&view=article&id=3819:batch-uploading-local-videos-from-serve','http://i.ytimg.com/vi/home62dt15w/1.jpg',1,0,0,'2009-02-20','home62dt15w','http://i.ytimg.com/vi/home62dt15w/0.jpg'),
                (6,62,'Installing Main Categories Module For JomTube','Installing Main Categories Module For JomTube Video Component ',1,'jomtube, joomla, 1.5 native, mvc, video component, joomla 1.5','pending','0000-00-00 00:00:00',1,NULL,NULL,'0:02:25','2009-03-06',1,'youtube','http://www.youtube.com/watch?v=YnrvOEpCYbY&eurl=http://www.jomtube.com/index.php?option=com_content&view=article&id=3826:video-main-category-module-tutorial-&','http://i.ytimg.com/vi/YnrvOEpCYbY/1.jpg',1,0,0,'2009-03-06','YnrvOEpCYbY','')
            ");
            $db->query();

            $db->setQuery("insert into `#__jomtube_categories` values
                (1,'JomTube Tutorials',0,'1','JomTube Tutorials','/jomtubefiles/JomTube_Tutorials',NULL);");
            }
            $db->query();
    ?>
    <tr>
      <td style="border:1px solid #333;" align="left" colspan="2">
        <p><b>Setting File and Folder Permissions:</b><br />
        <?php
        if(@chmod(JPATH_SITE . '/administrator/components/com_jomtube/configs/configs.jomtube.php', 0755)) {
            print "<img src=\"../administrator/components/com_jomtube/assets/images/tick.png\" border=\"0\" alt=\"\" title=\"\" style=\"padding:1px 5px;vertical-align:bottom;\" /><font color=\"green\"><b><b>FINISHED:</b></b></font> Successfully set default file permissions for configuration file.<br />";
        } else {
            print "<img src=\"../administrator/components/com_jomtube/assets/images/delete.png\" border=\"0\" alt=\"\" title=\"\" style=\"padding:1px 5px;vertical-align:bottom;\" /><font color=\"red\"><b><b>ERROR:</b></b></font> Failed to set default file permissions for configuration file.<br />";
        }
        ?>
 		</td>
    </tr>
    <tr>
      <td style="border:1px solid #333;" align="left" colspan="2">
        <p><b>Directory Creation Process:</b><br />
		<?php
		/*CREATE JOMTUBEFILES DIRECTORY*/
		if(!file_exists(JPATH_SITE . "/jomtubefiles/")){
		    if(@mkdir(JPATH_SITE . "/jomtubefiles/")) {
		        print "<img src=\"../administrator/components/com_jomtube/assets/images/tick.png\" border=\"0\" alt=\"\" title=\"\" style=\"padding:1px 5px;vertical-align:bottom;\" /><font color=\"green\"><b>FIXED:</b></font> Directory <b>".JPATH_SITE . "/jomtubefiles/</b> successfully created.<br />";
		        if(!is_writable(JPATH_SITE . "/jomtubefiles/")){
		            if(@chmod(JPATH_SITE . "/jomtubefiles/", 0777)) {
		                print "<img src=\"../administrator/components/com_jomtube/assets/images/tick.png\" border=\"0\" alt=\"\" title=\"\" style=\"padding:1px 5px;vertical-align:bottom;\" /><font color=\"green\"><b><b>FINISHED:</b></b></font> The file mode of directory <b>".JPATH_SITE . "/jomtubefiles/</b> successfully updated to chmod 0777.<br />";
		            } else {
		                print "<img src=\"../administrator/components/com_jomtube/assets/images/delete.png\" border=\"0\" alt=\"\" title=\"\" style=\"padding:1px 5px;vertical-align:bottom;\" /><font color=\"red\"><b><b>ERROR:</b></b></font> The file mode of directory <b>".JPATH_SITE . "/jomtubefiles/<b> failed to be updated, please chmod this directory to 0777 manually.<br />";
		            }
		        }
		    } else {
		        print "<img src=\"../administrator/components/com_jomtube/assets/images/delete.png\" border=\"0\" alt=\"\" title=\"\" style=\"padding:1px 5px;vertical-align:bottom;\" /><font color=\"red\"><b><b>ERROR:</b></b></font> Directory <b>".JPATH_SITE . "/jomtubefiles/</b> failed to be created, please create it manually.<br />";
		    }
		} else {
		    print "<img src=\"../administrator/components/com_jomtube/assets/images/tick.png\" border=\"0\" alt=\"\" title=\"\" style=\"padding:1px 5px;vertical-align:bottom;\" /><font color=\"green\"><b><b>FINISHED:</b></b></font> Directory <b>".JPATH_SITE . "/jomtubefiles/</b> already exists.<br />";
		    if(!is_writable(JPATH_SITE . "/jomtubefiles/")){
		        if(@chmod(JPATH_SITE . "/jomtubefiles/", 0777)) {
		            print "<img src=\"../administrator/components/com_jomtube/assets/images/tick.png\" border=\"0\" alt=\"\" title=\"\" style=\"padding:1px 5px;vertical-align:bottom;\" /><font color=\"green\"><b><b>FINISHED:</b></b></font> The file mode of directory <b>".JPATH_SITE . "/jomtubefiles/</b> successfully updated to chmod 0777.<br />";
		        } else {
		            print "<img src=\"../administrator/components/com_jomtube/assets/images/delete.png\" border=\"0\" alt=\"\" title=\"\" style=\"padding:1px 5px;vertical-align:bottom;\" /><font color=\"red\"><b><b>ERROR:</b></b></font> The file mode of directory <b>".JPATH_SITE . "/jomtubefiles/</b> failed to be updated, please chmod this directory to 0777 manually.<br />";
		        }
		    }
		}
                  /*CREATE BATCHUPLOAD DIRECTORY*/
		if(!file_exists(JPATH_SITE . "/jomtubefiles/_batch_upload/")){
		    if(@mkdir(JPATH_SITE . "/jomtubefiles/_batch_upload/")) {
		        print "<img src=\"../administrator/components/com_jomtube/assets/images/tick.png\" border=\"0\" alt=\"\" title=\"\" style=\"padding:1px 5px;vertical-align:bottom;\" /><font color=\"green\"><b>FIXED:</b></font> Directory <b>".JPATH_SITE . "/jomtubefiles/_batch_upload/</b> successfully created.<br />";
		        if(!is_writable(JPATH_SITE . "/jomtubefiles/_batch_upload/")){
		            if(@chmod(JPATH_SITE . "/jomtubefiles/_batch_upload/", 0777)) {
		                print "<img src=\"../administrator/components/com_jomtube/assets/images/tick.png\" border=\"0\" alt=\"\" title=\"\" style=\"padding:1px 5px;vertical-align:bottom;\" /><font color=\"green\"><b><b>FINISHED:</b></b></font> The file mode of directory <b>".JPATH_SITE . "/jomtubefiles/_batch_upload/</b> successfully updated to chmod 0777.<br />";
		            } else {
		                print "<img src=\"../administrator/components/com_jomtube/assets/images/delete.png\" border=\"0\" alt=\"\" title=\"\" style=\"padding:1px 5px;vertical-align:bottom;\" /><font color=\"red\"><b><b>ERROR:</b></b></font> The file mode of directory <b>".JPATH_SITE . "/jomtubefiles/_batch_upload/<b> failed to be updated, please chmod this directory to 0777 manually.<br />";
		            }
		        }
		    } else {
		        print "<img src=\"../administrator/components/com_jomtube/assets/images/delete.png\" border=\"0\" alt=\"\" title=\"\" style=\"padding:1px 5px;vertical-align:bottom;\" /><font color=\"red\"><b><b>ERROR:</b></b></font> Directory <b>".JPATH_SITE . "/jomtubefiles/_batch_upload/</b> failed to be created, please create it manually.<br />";
		    }
		} else {
		    print "<img src=\"../administrator/components/com_jomtube/assets/images/tick.png\" border=\"0\" alt=\"\" title=\"\" style=\"padding:1px 5px;vertical-align:bottom;\" /><font color=\"green\"><b><b>FINISHED:</b></b></font> Directory <b>".JPATH_SITE . "/jomtubefiles/_batch_upload/</b> already exists.<br />";
		    if(!is_writable(JPATH_SITE . "/jomtubefiles/_batch_upload/")){
		        if(@chmod(JPATH_SITE . "/jomtubefiles/_batch_upload/", 0777)) {
		            print "<img src=\"../administrator/components/com_jomtube/assets/images/tick.png\" border=\"0\" alt=\"\" title=\"\" style=\"padding:1px 5px;vertical-align:bottom;\" /><font color=\"green\"><b><b>FINISHED:</b></b></font> The file mode of directory <b>".JPATH_SITE . "/jomtubefiles/_batch_upload/</b> successfully updated to chmod 0777.<br />";
		        } else {
		            print "<img src=\"../administrator/components/com_jomtube/assets/images/delete.png\" border=\"0\" alt=\"\" title=\"\" style=\"padding:1px 5px;vertical-align:bottom;\" /><font color=\"red\"><b><b>ERROR:</b></b></font> The file mode of directory <b>".JPATH_SITE . "/jomtubefiles/_batch_upload/</b> failed to be updated, please chmod this directory to 0777 manually.<br />";
		        }
		    }
		}

		/*CREATE EXAMPLE CATEGORY DIRECTORY*/
		if(!file_exists(JPATH_SITE . "/jomtubefiles/JomTube_Tutorials/")){
		    if(@mkdir(JPATH_SITE . "/jomtubefiles/JomTube_Tutorials/")) {
		       @chmod(JPATH_SITE . "/jomtubefiles/JomTube_Tutorials/", 0777);
		       if(!file_exists(JPATH_SITE . "/jomtubefiles/JomTube_Tutorials/_thumbs/")){
            		       if(@mkdir(JPATH_SITE . "/jomtubefiles/JomTube_Tutorials/_thumbs/")) {
            		            @chmod(JPATH_SITE . "/jomtubefiles/JomTube_Tutorials/_thumbs/", 0777);
            		       }
		       }
		       if(!file_exists(JPATH_SITE . "/jomtubefiles/JomTube_Tutorials/_display/")){
            		       if(@mkdir(JPATH_SITE . "/jomtubefiles/JomTube_Tutorials/_display/")) {
            		            @chmod(JPATH_SITE . "/jomtubefiles/JomTube_Tutorials/_display", 0777);
            		       }
		       }
		    }
		}

		if(@chmod(JPATH_SITE . "/administrator/components/com_jomtube/assets/lib/FlashUploader/tmp/", 0777)) {
		    print "<img src=\"../administrator/components/com_jomtube/assets/images/tick.png\" border=\"0\" alt=\"\" title=\"\" style=\"padding:1px 5px;vertical-align:bottom;\" /><font color=\"green\"><b><b>FINISHED:</b></b></font> The file mode of directory <b>".JPATH_SITE . "/administrator/components/com_jomtube/assets/lib/FlashUploader/tmp/</b> successfully updated to chmod 0777.<br />";
		} else {
		    print "<img src=\"../administrator/components/com_jomtube/assets/images/delete.png\" border=\"0\" alt=\"\" title=\"\" style=\"padding:1px 5px;vertical-align:bottom;\" /><font color=\"red\"><b><b>ERROR:</b></b></font> The file mode of directory <b>".JPATH_SITE . "/administrator/components/com_jomtube/assets/lib/FlashUploader/tmp/<b> failed to be updated, please chmod this directory to 0777 manually.<br />";
		}

		if(@chmod(JPATH_SITE . "/administrator/components/com_jomtube/assets/lib/FlashUploader/uploads/", 0777)) {
		    print "<img src=\"../administrator/components/com_jomtube/assets/images/tick.png\" border=\"0\" alt=\"\" title=\"\" style=\"padding:1px 5px;vertical-align:bottom;\" /><font color=\"green\"><b><b>FINISHED:</b></b></font> The file mode of directory <b>".JPATH_SITE . "/administrator/components/com_jomtube/assets/lib/FlashUploader/uploads/</b> successfully updated to chmod 0777.<br />";
		} else {
		    print "<img src=\"../administrator/components/com_jomtube/assets/images/delete.png\" border=\"0\" alt=\"\" title=\"\" style=\"padding:1px 5px;vertical-align:bottom;\" /><font color=\"red\"><b><b>ERROR:</b></b></font> The file mode of directory <b>".JPATH_SITE . "/administrator/components/com_jomtube/assets/lib/FlashUploader/uploads/<b> failed to be updated, please chmod this directory to 0777 manually.<br />";
		}
		?>
      </td>
    </tr>

    <tr>
      <td style="border:1px solid #333;" align="left" colspan="2">
        <p><b>Summary:</b><br />
        <font color="green"><b>Installation Finished.</b></font> Important: Please read the above report to check if you need to manually create any directories.</b></font>
      </td>
    </tr>
  </table>
 </div>
  <?php
}
?>











































