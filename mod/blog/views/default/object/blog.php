<?php

	/**
	 * Elgg blog individual post view
	 * 
	 * @package ElggBlog
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Ben Werdmuller <ben@curverider.co.uk>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 * 
	 * @uses $vars['entity'] Optionally, the blog post to view
	 */

		if (isset($vars['entity'])) {
			
			//display comments link?
			if ($vars['entity']->comments_on == 'Off') {
				$comments_on = false;
			} else {
				$comments_on = true;
			}
			
			if (get_context() == "search" && $vars['entity'] instanceof ElggObject) {
				
				//display the correct layout depending on gallery or list view
				if (get_input('search_viewtype') == "gallery") {

					//display the gallery view
            				echo elgg_view("blog/gallery",$vars);

				} else {
				
					echo elgg_view("blog/listing",$vars);

				}

				
			} else {
			
				if ($vars['entity'] instanceof ElggObject) {
					
					$url = $vars['entity']->getURL();
					$owner = $vars['entity']->getOwnerEntity();
					$canedit = $vars['entity']->canEdit();
					
				} else {
					
					$url = 'javascript:history.go(-1);';
					$owner = $vars['user'];
					$canedit = false;
					
				}
?>

	<div class="contentWrapper singleview">
	
	<div class="blog_post">
		<h3><a href="<?php echo $url; ?>"><?php echo $vars['entity']->title; ?></a></h3>
		<!-- display the user icon -->
		<div class="blog_post_icon">
		    <?php
		        echo elgg_view("profile/icon",array('entity' => $owner, 'size' => 'tiny'));
			?>
	    </div>
			<p class="strapline">
				<?php
	                
					echo sprintf(elgg_echo("blog:strapline"),
									date("F j, Y",$vars['entity']->time_created)
					);
				
				?>
				<?php echo elgg_echo('by'); ?> <a href="<?php echo $vars['url']; ?>pg/blog/<?php echo $owner->username; ?>"><?php echo $owner->name; ?></a> &nbsp; 
				<!-- display the comments link -->
				<?php
					if($comments_on && $vars['entity'] instanceof ElggObject){
			        //get the number of comments
			    		$num_comments = elgg_count_comments($vars['entity']);
			    ?>
			    	<a href="<?php echo $url; ?>"><?php echo sprintf(elgg_echo("comments")) . " (" . $num_comments . ")"; ?></a><br />
			    <?php
		    		}
		    	?>
			</p>
			<!-- display tags -->
				<?php
	
					$tags = elgg_view('output/tags', array('tags' => $vars['entity']->tags));
					if (!empty($tags)) {
						echo '<p class="tags">' . $tags . '</p>';
					}
				
					$categories = elgg_view('categories/view', $vars);
					if (!empty($categories)) {
						echo '<p class="categories">' . $categories . '</p>';
					}
				
				?>
			<div class="clearfloat"></div>
			<div class="blog_post_body">

			<!-- display the actual blog post -->
				<?php
			
							echo elgg_view('output/longtext',array('value' => $vars['entity']->description));
				
				?>
			</div><div class="clearfloat"></div>			
			<!-- display edit options if it is the blog post owner -->
			<p class="options">
			<?php
	
				if ($canedit) {
					
				?>
					<a href="<?php echo $vars['url']; ?>mod/blog/edit.php?blogpost=<?php echo $vars['entity']->getGUID(); ?>"><?php echo elgg_echo("edit"); ?></a>  &nbsp; 
					<?php
					
						echo elgg_view("output/confirmlink", array(
							'href' => $vars['url'] . "action/blog/delete?blogpost=" . $vars['entity']->getGUID(),
							'text' => elgg_echo('delete'),
							'confirm' => elgg_echo('deleteconfirm'),
						));
	
						// Allow the menu to be extended
						echo elgg_view("editmenu",array('entity' => $vars['entity']));
					
					?>
				<?php
				}
			
			?>
			</p>
		</div>
		</div>

<?php

			// If we've been asked to display the full view 
			// Now handled by annotation framework
				/*if (isset($vars['full']) && $vars['full'] == true && $comments_on == 'on' && $vars['entity'] instanceof ElggEntity) {
					echo elgg_view_comments($vars['entity']);
				}*/
				
			}

		}

?>
