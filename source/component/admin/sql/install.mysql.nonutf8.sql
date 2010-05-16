--
-- jomtube Component SQL Installation Script
-- http://jomtube.com
--

-- DROP TABLE IF EXISTS `#__jomtube_videos`;

CREATE TABLE IF NOT EXISTS `#__jomtube_videos` (                                                                
                    `id` int(11) NOT NULL auto_increment,                                                          
                    `user_id` int(11) default NULL,                                                                
                    `video_title` varchar(200) default NULL,                                                       
                    `video_desc` text,                                                                             
                    `category_id` int(11) NOT NULL default '0',                                                    
                    `tags` varchar(255) default NULL,                                                              
                    `status` enum('pending','complete','error','cancelled','deleted') NOT NULL default 'pending',  
                    `transaction_dt` timestamp NOT NULL default '0000-00-00 00:00:00',                             
                    `hits` int(11) NOT NULL default '0',                                                           
                    `infin_vid_id` varchar(50) default NULL,                                                       
                    `thumb_url` varchar(1000) default NULL,                                                        
                    `duration` varchar(20) default NULL,                                                           
                    `date_added` date default NULL,                                                                
                    `published` tinyint(1) NOT NULL default '1',                                                   
                    `video_type` varchar(255) default NULL,                                                        
                    `video_url` text,                                                                              
                    `video_thumb` text,                                                                            
                    `downloadable` tinyint(1) default '1',                                                         
                    `featured` tinyint(1) default '0',                                                             
                    `votetotal` int(11) default '0',                                                               
                    `date_updated` date default NULL,                                                              
                    `remote_id` text,                                                                              
                    `display_thumb` varchar(255) default NULL,  
                    `checked_out` int(11) default 0,                                                   
                    PRIMARY KEY  (`id`)                                                                            
                  )  ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- insert into `#__jomtube_videos` values 
-- (1,62,'Add youtube remote video','Add A Video To JomTube From The Front End from YOUTUBE ',1,'jomtube, joomla 1.5, 1.5 native, mvc framework','pending','0000-00-00 00:00:00',1,NULL,NULL,'0:02:52','2009-03-05',1,'youtube','http://www.youtube.com/watch?v=w6L3kgPAP9o&eurl=http://www.jomtube.com/index.php?option=com_content&view=article&id=3824:add-a-video-from-youtube-tutorial&cat','http://i.ytimg.com/vi/w6L3kgPAP9o/1.jpg',1,0,0,'2009-03-05','w6L3kgPAP9o','http://i.ytimg.com/vi/w6L3kgPAP9o/0.jpg'),
-- (2,62,'Left And Right Columns Video List','Adding Modules in Left And Right Custom JomTube Position ',1,'joomla 1.5, video component, jomtube','pending','0000-00-00 00:00:00',1,NULL,NULL,'0:02:24','2009-03-05',1,'youtube','http://www.youtube.com/watch?v=sFXCJicTjo8&eurl=http://www.jomtube.com/index.php?option=com_content&view=article&id=3823:add-custom-right-and-left-column-module','http://i.ytimg.com/vi/sFXCJicTjo8/1.jpg',1,0,0,'2009-03-05','sFXCJicTjo8','http://i.ytimg.com/vi/sFXCJicTjo8/0.jpg'),
-- (3,62,'JomTube Custom Player Position','JomTube Custom Player Position Tutorial ',1,'jomtube, joomla, component, video component, joomla 1.5, native','pending','0000-00-00 00:00:00',0,NULL,NULL,'0:03:03','2009-02-22',1,'youtube','http://www.youtube.com/watch?v=R1JZMuYy8oc&eurl=http://www.jomtube.com/index.php?option=com_content&view=article&id=3820:custom-module-postion-on-video-player-p','http://i.ytimg.com/vi/R1JZMuYy8oc/1.jpg',1,0,0,'2009-02-23','R1JZMuYy8oc','http://i.ytimg.com/vi/R1JZMuYy8oc/0.jpg'),
-- (4,62,'Install Vertical Module','Installing the Vertical Module For JomTube Video Component for Joomla 1.5 ',1,'video component, jomtube, joomla 1.5','pending','0000-00-00 00:00:00',0,NULL,NULL,'0:04:52','2009-02-23',1,'youtube','http://www.youtube.com/watch?v=LFirCNH6cBk&eurl=http://www.jomtube.com/index.php?option=com_content&view=article&id=3821:installing-the-vertical-module-for-jomt','http://i.ytimg.com/vi/LFirCNH6cBk/1.jpg',1,0,0,'2009-02-23','LFirCNH6cBk','http://i.ytimg.com/vi/LFirCNH6cBk/0.jpg'),
-- (5,62,'JomTube Batch Upload Feature','JomTube Batch Upload Feature ',1,'joomla, video component, 1.5 native, jomtube','pending','0000-00-00 00:00:00',1,NULL,NULL,'0:04:39','2009-02-18',1,'youtube','http://www.youtube.com/watch?v=home62dt15w&eurl=http://www.jomtube.com/index.php?option=com_content&view=article&id=3819:batch-uploading-local-videos-from-serve','http://i.ytimg.com/vi/home62dt15w/1.jpg',1,0,0,'2009-02-20','home62dt15w','http://i.ytimg.com/vi/home62dt15w/0.jpg'),
-- (6,62,'Installing Main Categories Module For JomTube','Installing Main Categories Module For JomTube Video Component ',1,'jomtube, joomla, 1.5 native, mvc, video component, joomla 1.5','pending','0000-00-00 00:00:00',1,NULL,NULL,'0:02:25','2009-03-06',1,'youtube','http://www.youtube.com/watch?v=YnrvOEpCYbY&eurl=http://www.jomtube.com/index.php?option=com_content&view=article&id=3826:video-main-category-module-tutorial-&','http://i.ytimg.com/vi/YnrvOEpCYbY/1.jpg',1,0,0,'2009-03-06','YnrvOEpCYbY','');

-- DROP TABLE IF EXISTS `#__jomtube_categories`;

CREATE TABLE IF NOT EXISTS `#__jomtube_categories` (                   
                          `id` int(11) NOT NULL auto_increment,
                          `category_name` varchar(100) default NULL,
                          `parent_id` int(11) default NULL,
                          `family_id` varchar(255) default NULL,
                          `category_info` text,
                          `directory` varchar(255) default NULL,
                          `thumbnail` varchar(255) default NULL,
                          `checked_out` int(11) default NULL,
                          `ordering` int(11) default NULL,
                          PRIMARY KEY  (`id`)                       
                      ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- insert into `#__jomtube_categories` values 
-- (1,'JomTube Tutorials',0,'1','JomTube Tutorials','/jomtubefiles/JomTube_Tutorials',NULL);

-- DROP TABLE IF EXISTS `#__jomtube_rating`;

CREATE TABLE IF NOT EXISTS `#__jomtube_rating` (
	`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	`v_id` INT( 11 ) NOT NULL ,
	`user_id` INT ( 11 ) NOT NULL ,
	`rating` INT( 11 ) NOT NULL ,
	UNIQUE KEY `v_id` (`v_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- insert into `#__jomtube_config` values 
-- (1,NULL,1,1,1,NULL,NULL,18,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2008-09-06 22:34:53',338,600,1,'default','default','JomComment','','','','','','','/usr/local/bin/ffmpeg');


CREATE TABLE IF NOT EXISTS `#__jomtube_plugins` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `element` varchar(255) default NULL,
  `type` varchar(255) default NULL,
  `folder` varchar(255) default NULL,
  `published` tinyint(1) default '1',
  `author` varchar(255) default 'JomTube.com',
  `checked_out` int(11) default NULL,
  `url` varchar(255) default NULL,
  `params` text,
  `description` text,
  `iscore` tinyint(1) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;