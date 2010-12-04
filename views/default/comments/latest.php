<?php
/**
 * Latest comments on an entity
 *
 * @uses $vars['comments']  Array of comment objects
 */

if (isset($vars['comments'])) {
	echo '<ul class="elgg-latest-comments">';
	foreach ($vars['comments'] as $comment) {
		$html = elgg_view_annotation($comment, false);
		if ($html) {
			echo "<li>$html</li>";
		}
	}
	echo '</ul>';
}