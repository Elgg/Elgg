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
		<h3><?php echo $vars['entity']->title; ?></h3>
		<p class="strapline">
			<span style="float:right">
			<?php

				echo sprintf(elgg_echo("blog:strapline"),
								date("F j",$vars['entity']->time_created)
				);
			
			?>
			</span>
			<?php

				$owner = get_entity($vars['entity']->getOwner());
				echo $owner->name;
			
			?>
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
			
		}

?>