<?php

	/**
	 * Elgg hoverover extender for blog
	 * 
	 * @package ElggBlog
	 */

?>

	<p class="user_menu_blog">
		<a href="<?php echo $vars['url']; ?>pg/blog/owner/<?php echo $vars['entity']->username; ?>"><?php echo elgg_echo("blog"); ?></a>
	</p>