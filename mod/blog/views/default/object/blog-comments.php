<?php

	/**
	 * Elgg blog aggregate comments view
	 * 
	 * @package ElggBlog
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Ben Werdmuller <ben@curverider.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 * 
	 * @uses $vars['comments'] Array of comments
	 */

?>

		<div class="blog-comments">
		
<?php
		if (isset($vars['comments']) && is_array($vars['comments']) && sizeof($vars['comments']) > 0) {
			
			echo "<h3>". elgg_echo("comments") ."</h3><ol>";
			foreach($vars['comments'] as $comment) {
				
				echo elgg_view("object/blog-comment",array('entity' => $comment));
				
			}
			echo "</ol>";
			
		}

?>
			<form action="<?php echo $vars['url']; ?>action/blog/comments/add" method="post">
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
					<input type="hidden" name="blogpost_guid" value="<?php echo $vars['entity']->getGUID(); ?>" /> 
					<input type="submit" value="<?php echo elgg_echo("save"); ?>" />
				</p>
			</form>

		</div>