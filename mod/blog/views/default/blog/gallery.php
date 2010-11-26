<?php

	/**
	 * Elgg blog listing
	 * 
	 * @package ElggBlog
	 */

		$owner = $vars['entity']->getOwnerEntity();
		$friendlytime = elgg_view_friendly_time($vars['entity']->time_created);
		$icon = elgg_view(
				"profile/icon", array(
										'entity' => $owner,
										'size' => 'small',
									  )
			);
		$info = "<p>" . elgg_echo('blog') . ": <a href=\"{$vars['entity']->getURL()}\">{$vars['entity']->title}</a></p>";
		$info .= "<p><a href=\"{$owner->getURL()}\">{$owner->name}</a> {$friendlytime}</p>";

		//display
		echo "<div class=\"blog_gallery\">";
		echo "<div class=\"blog_gallery_icon\">" . $icon . "</div>";
		echo "<div class=\"blog_gallery_content\">" . $info . "</div>";
		echo "</div>";


?>