<?php

	/**
	 * Elgg blog individual post view
	 * 
	 * @package ElggBlog
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Ben Werdmuller <ben@curverider.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 * 
	 * @uses $vars['entity'] Optionally, the blog post to view
	 */

		if (isset($vars['entity'])) {
			
			if (get_context() == "search") {
				
				//display the correct layout depending on gallery or list view
				if (get_input('search_viewtype') == "gallery") {

					//display the gallery view
            				echo elgg_view("blog/gallery",$vars);

				} else {
				
					echo elgg_view("blog/listing",$vars);

				}

				
			} else {
			
?>

	<div class="blog_post">
		<h3><a href="<?php echo $vars['entity']->getURL(); ?>"><?php echo $vars['entity']->title; ?></a></h3>
		<!-- display the user icon -->
		<div class="blog_post_icon">
		    <?php
		        echo elgg_view("profile/icon",array('entity' => $vars['entity']->getOwnerEntity(), 'size' => 'tiny'));
			?>
	    </div>
			<p class="strapline">
				<?php
	                
					echo sprintf(elgg_echo("blog:strapline"),
									date("F j, Y",$vars['entity']->time_created)
					);
				
				?>
				<?php echo elgg_echo('by'); ?> <a href="<?php echo $vars['url']; ?>pg/blog/<?php echo $vars['entity']->getOwnerEntity()->username; ?>"><?php echo $vars['entity']->getOwnerEntity()->name; ?></a> &nbsp; 
				<!-- display the comments link -->
				<?php
			        //get the number of comments
			    	$num_comments = elgg_count_comments($vars['entity']);
			    ?>
			    <a href="<?php echo $vars['entity']->getURL(); ?>"><?php echo sprintf(elgg_echo("comments")) . " (" . $num_comments . ")"; ?></a><br />
			</p>
			<!-- display tags -->
			<p class="tags">
				<?php
	
					echo elgg_view('output/tags', array('tags' => $vars['entity']->tags));
				
				?>
			</p>
			<div class="blog_post_body">

			<!-- display the actual blog post -->
				<?php
			
							echo autop($vars['entity']->description);
				
				?>
			</div>			
			<!-- display edit options if it is the blog post owner -->
			<p class="options">
			<?php
	
				if ($vars['entity']->canEdit()) {
					
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

<?php

			// If we've been asked to display the full view
				if (isset($vars['full']) && $vars['full'] == true) {
					echo elgg_view_comments($vars['entity']);
				}
				
			}

		}

?>