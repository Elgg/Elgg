<?php

	/**
	 * Elgg blog edit/add page
	 * 
	 * @package ElggBlog
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Ben Werdmuller <ben@curverider.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 * 
	 * @uses $vars['object'] Optionally, the blog post to edit
	 */

	// Set title, form destination
		if (isset($vars['object'])) {
			$title = sprintf(elgg_echo("blog:editpost"),$object->title);
			$action = "blog/edit";
			$title = $vars['object']->title;
			$body = $vars['object']->description;
			$tags = $vars['object']->tags;
		} else  {
			$title = elgg_echo("blog:addpost");
			$action = "blog/add";
			$tags = "";
			$title = "";
			$description = "";
		}

	// Just in case we have some cached details
		if (isset($vars['blogtitle'])) {
			$title = $vars['blogtitle'];
			$body = $vars['blogbody'];
			$tags = $vars['blogtags'];
		}

?>

	<h2>
		<?php echo $title; ?>
	</h2>
	<form action="<?php echo $vars['url']; ?>action/<?php echo $action; ?>" method="post">
	
		<p>
			<label><?php echo elgg_echo("title"); ?><br />
			<?php

				echo elgg_view("input/text", array(
									"internalname" => "blogtitle",
									"value" => $title,
													));
			
			?>
			</label>
		</p>
		<p>
			<label><?php echo elgg_echo("blog:text"); ?><br />
			<?php

				echo elgg_view("input/longtext",array(
									"internalname" => "blogbody",
									"value" => $body,
													));
			?>
			</label>
		</p>
		<p>
			<label><?php echo elgg_echo("tags"); ?><br />
			<?php

				echo elgg_view("input/tags", array(
									"internalname" => "blogtags",
									"value" => $tags,
													));
			
			?>
		</p>
		<p>
			<input type="submit" value="<?php echo elgg_echo('save'); ?>" />
		</p>
	
	</form>