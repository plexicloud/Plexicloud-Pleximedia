<?php // no direct access
defined('_JEXEC') or die('Restricted access');
?>

<script src="<?php echo JURI::base() ?>components/com_jomtube/assets/js/jomtubeajax.js" language="javascript"></script>

<script>
function selectCategory(catid) {
    var link = "index.php?option=com_jomtube&task=categories&catid=" + catid.value;
    location.href = link;
}
</script>

<div class="jomtube_wrapper" style="float: left; width: 100%;">
        <div style="padding-top: 10px;">
        <a href="<?php echo JRoute::_("index.php?view=videos")?>">Main</a>
        <?php foreach ($this->breadcrum as $cat):?>
        <img src="components/com_jomtube/assets/images/arrow.png">
                <a href="<?php echo JRoute::_("index.php?view=videos&category_id=$cat->id")?>"><?php echo $cat->category_name?></a>
        <?php endforeach;?>
        </div>
        <div id="jomtube_container" name="jomtube">
                <!-- ###################### VIDEO PLAYER START ################-->
                <div id="watch-vid-title-container">
                        <div id="watch-vid-title" style="width:<?php echo $this->c->video_player_width;?>px;">
                                <?php echo stripslashes($this->row->video_title);?>
                        </div>
                        <!--<div id="watch-vid-category">
                                Select Category:<br><?php echo $this->parentSelect ?>
                        </div>-->
                </div>
<div id="facebook_like">
<?php function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;

}
?>
<iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo curPageURL();?>&layout=standard&show_faces=false&width=450&action=recommend&font&colorscheme=light&height=35" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:35px;" allowTransparency="true"></iframe>
</div>
                <div id="watch-this-vid">
                        <?php include("video_player.php");?>
                </div>
                <!-- ###################### VIDEO PLAYER END ################-->

 
                
                <!--############## CUSTOM MODULE POSITION ###########-->
                <div style="float: left; width: 324px; border: 0px solid #CCCCCC; margin-top: 10px;"?>
                      <?php
                      $jomtube_player_modules = &JModuleHelper::getModules('jomtube_player');
                      foreach ($jomtube_player_modules as $player_module) {
                          echo "<div id=\"module-padding\">";
                          echo JModuleHelper::renderModule($player_module);
                          echo "</div>";
                      }
                        ?>
                </div>
                <!--############## CUSTOM MODULE POSITION END ###########-->
        </div>
        <div class="clearL"></div>
                <div id="watch-ratings-views" style="width:<?php echo $this->c->video_player_width;?>px;">
<!---------------- RATINGS AND VIEWS CONTAINER --------------------->
                        <div id="ratings-container">

                                <?php if ($this->c->allow_ratings): ?>
                                <span id="ajaxRating" class="jomtube_ajaxrating">

                                <?php
                                echo "Rate: ";

                                $emptyStar = JURI::base() . "components/com_jomtube/assets/images/emptyStar16x16.gif";
                                $halfStar = JURI::base() . "components/com_jomtube/assets/images/halfStar16x16.gif";
                                $filledStar = JURI::base() . "components/com_jomtube/assets/images/fullStar16x16.gif";

                                $starMap = '00000';
                                for ($i = 1; $i <= 5; $i++)
                                {
                                    $starMap[$i - 1] = floor($this->row->ratingAvg) >= $i ? '2' : (round($this->row->ratingAvg) == $i ? '1' : '0');
                                }

                                if ($this->row->hits == 0) {
                                    echo "<span class=\"jomtube_ratingStars\" title=\"Not yet rated\">";
                                } else {
                                    echo "<span class=\"jomtube_ratingStars\" title=\"".round($this->row->ratingAvg, 1) ." out of 5 stars\">";
                                }

                                $starMapRate = '00000';
                                for ($i = 1; $i <= 5; $i++)
                                {
                                    $star = floor($this->row->ratingAvg) >= $i ? $filledStar : (round($this->row->ratingAvg) == $i ? $halfStar : $emptyStar);
                                    $starMapRate[$i - 1] = '2';

                                    if ($this->user_id != "")
                                    {
                                        echo "<a id=\"jomtube_rate_href\""
                                        ." onClick=\"jomtubeAJAX.prototype.rateVideo(".$this->row->id.", ".$this->user_id.",".$i."); return false;\""
                                        ." onMouseOver=\"javascript: highlightStars('".$starMapRate."', '".$filledStar."', '".$halfStar."', '".$emptyStar."');\" "
                                        ." onMouseOut=\"javascript: highlightStars('".$starMap."', '".$filledStar."', '".$halfStar."', '".$emptyStar."');\" "
                                        ." href=\"#jomtube\">"
                                        ."<img src=\"".$star."\" id=\"videoStar".$i."\" vspace=\"0\" hspace=\"0\" height=\"16\" width=\"16\" border=\"0\">"
                                        ."</a>";
                                    }
                                    else
                                    {
                                        echo "<img src=\"".$star."\" id=\"videoStar".$i."\" vspace=\"0\" "
                                        ."hspace=\"0\" height=\"16\" width=\"16\" border=\"0\" />";
                                    }
                                }
                                echo "</span>";


                                if ($this->user_id == "")
                                echo "<span id=\"ratingThanks\" class=\"jomtube_ratingThanks\"></span><span class=\"jomtuberatingcount\">Sign in to rate</span>";
                                else if ($this->row->ratingCount == 1)
                                echo "<span id=\"ratingThanks\" class=\"jomtube_ratingThanks\"></span><span class=\"jomtuberatingcount\">1 rating</span>";
                                else if ($this->row->ratingCount)
                                echo "<span id=\"ratingThanks\" class=\"jomtube_ratingThanks\"></span><span class=\"jomtuberatingcount\">Not yet rated</span>";
                                else
                                echo "<span id=\"ratingThanks\" class=\"jomtube_ratingThanks\"></span><span class=\"jomtuberatingcount\">".$this->row->ratingAvg." ratings</span>";

                                ?>
                                </span>
                        <?php endif;?>
                        <div id="video-views">
                        <span class="jomtube_views"><strong>Views:</strong> <?php echo $this->row->hits == 0 ? 1 : $this->row->hits; ?></span>
                        </div>

                        </div>

                        <div id="vidinfocontainer">
<!---------------- AUTHOR DURATION CONTAINER --------------------->
                        <div id="author-container">
                                <div id="video-author">
                                        <strong>From: </strong><span class="grayText"><a href="<?php echo JRoute::_("index.php?option=com_community&view=profile&userid=" . $this->row->user_id) ?>"><?php echo $this->row->username;?></a></span><br />
                                        <strong>Added: </strong><span class="grayText"><?php echo $this->row->date_added;?></span><br />
                                </div>
                                <div id="duration-category">
                                        <strong>Category: </strong><span class="grayText"><a href="<?php echo JRoute::_("index.php?view=videos&category_id=" . $this->row->category_id)?>"><?php echo $this->row->category_name;?></a></span><br />
                                        <strong>Duration: </strong><span class="grayText"><?php echo $this->row->duration;?></span>
                                </div>
                        </div>
<!---------------- DESCRIPTION AND KEYWORDS CONTAINER --------------------->
                        <div id="description-container">
                                <div id="video-tags">
                                        <strong>Tags:</strong> <?php echo stripslashes($this->videotaglinked);?>
                                </div>
                                <div id="video-desc">
                                        <strong>Description: </strong><?php echo stripslashes($this->row->video_desc);?>
                                </div>
                        </div>
<!---------------- EMBED AND LINK CONTAINER --------------------->
                        <div id="embed-container">
                                <div id="video-link">
                                        <label for="link_to_page"><strong>Link: </strong></label>
                                        <input class="videodirectlink" type="text" id="link_to_page" name="link_to_page" value="<?php echo $this->basUrl.JRoute::_('index.php?option=com_jomtube&view=video&id='.$this->row->id); ?>" onClick="javascript:this.select();" />
                                </div>
                                 <?php if ($this->c->show_embed_code){?>
								 <?php if ($this->row->video_type != 'local' && $this->row->video_type != NULL){?>
								 <label for="embed_video"><strong>Embed: </strong></label>
                                        <input style="width:50.7em" class="videoembedcode" type="text" id="embed_video" name="embed_video" value="<?php echo htmlspecialchars($this->embed) ?>"  onClick="javascript:this.select();" />
								 <?php }else { ?>
                                <div id="video-embed">
                                	<?php $embedurl = "<embed src='".$this->basUrl."/components/com_jomtube/assets/swf/player.swf' width='560' height='441' allowscriptaccess='always' allowfullscreen='true' flashvars='height=441&width=560&file=".
												$this->basUrl.$this->row->directory."/".$this->row->video_url."&autostart=true'/>";?>
                                        <label for="embed_video"><strong>Embed: </strong></label>
                                        <input style="width:50.7em" class="videoembedcode" type="text" id="embed_video" name="embed_video" value="<?php echo $embedurl ?>" onClick="javascript:this.select();" />
                                </div>
									<?php } ?>
                                <?php }?>
                                
                                
                                <!-- AddThis Button BEGIN
								<div class="addthis_toolbox addthis_default_style">
								<a href="http://www.addthis.com/bookmark.php?v=250&pub=museamp" class="addthis_button_compact">Share</a>-->
								<div style="float:right;margin-top: 5px;">
								<table>
								<tr>
								<td>Share This</td><td><a class="addthis_button_facebook"></a></td><td><a class="addthis_button_twitter"></a></td>
								</tr></table></div>
								<!--<a class="addthis_button_myspace"></a>
								<a class="addthis_button_google"></a> -->
								
								
								<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js?pub=museamp"></script>
								<!-- AddThis Button END 

								</div>-->
                        </div>
                        </div>
                        <div style="clear: both;"></div>
                             <?php if ($this->user->usertype == "Administrator" || $this->user->usertype == "Super Administrator"):?>
                                        Options:
                                        <a href="<?php echo $this->basUrl?>/administrator/index.php?option=com_jomtube&controller=videos&task=edit&cid[]=<?php echo $this->row->id?>">Edit</a>
                                        | <a onclick="return confirm('Are You Sure?')" href="<?php echo $this->basUrl?>/administrator/index.php?option=com_jomtube&controller=videos&task=remove&cid[]=<?php echo $this->row->id?>">Delete</a>
                                                </p>
                                <?php endif;?>
                        <div style="clear: both;"></div>

                        <?php if ($this->c->allow_comment):?>

							<?php if($this->user->id == '0'){ ?>
							<br>
							<div id="jcNotice">The comment section is restricted to members only.</div>
							<?php } else { ?>
                                        <div class="jomtube_comments" style="width:<?php echo $this->c->video_player_width;?>px;">
                                            <?php if ($this->c->commenting_system == "JomComment"):?>
                                                 <?php if ($this->jomtubeComments == "notinstall"):?>
                                                     <?php echo "<b>You must install jomcomment component to use comment function</b>"?>
                                                 <?php else:?>
                                                    <?php $this->jomtubeComments->displayComments();?>
                                                <?php endif;?>
                                            <?php endif;?>
                                        </div>
							<?php } ?>
                        <?php endif; ?>
        </div>
</div>
<div style="clear: both;"></div>