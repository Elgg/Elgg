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
			
?>

	<div class="blog-post">
		<h3><a href="<?php echo $vars['url']; ?>mod/blog/read.php?blogpost=<?php echo $vars['entity']->getGUID(); ?>"><?php echo $vars['entity']->title; ?></a></h3>
		<p class="strapline">
			<span style="float:right">
			<?php

				echo sprintf(elgg_echo("blog:strapline"),
								date("F j",$vars['entity']->time_created)
				);
			
			?>
			</span>
			<a href="<?php echo $vars['url']; ?>mod/blog/?username=<?php echo $vars['entity_owner']->username; ?>"><?php echo $vars['entity_owner']->name; ?></a>
		</p>
		<p>
			<?php

				echo nl2br($vars['entity']->description);
			
			?>
		</p>
		<p>
			<?php

				echo $vars['entity']->tags;
			
			?>
		</p>
	</div>

<?php

		// If we've been asked to display the full view
			if (isset($vars['full']) && $vars['full'] == true) {
				
?>

		<div class="blog-comments">
		
<?php

		// Display comments if any
			echo elgg_view('object/blog-comments',array('comments' => $vars['comments']));

?>
			<form action="<?php echo $vars['url']; ?>action/blog/comment/add" method="post">
				<h3>
					<?php echo elgg_echo("blog:comment:add"); ?>
				</h3>
				<p>
					<label><?php echo elgg_echo("blog:comment:text"); ?>
						<?php

							echo elgg_view("input/longtext",array('internalname' => 'comment'));
						
						?>
					</label>
				</p>
				<p>
					<input type="submit" value="<?php echo elgg_echo("save"); ?>" />
				</p>
			</form>
		
		</div>

<?php
				
			}
		}

?>