<?php
/**
 * Elgg default layout
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['title']   Optional title string
 * @uses $vars['content'] Content string
 */

$title = elgg_extract('title', $vars, '');
if ($title) {
	echo '<div class="elgg-head clearfix">';
	echo elgg_view_title($title);
	echo '</div>';
}

// @todo deprecated so remove in Elgg 2.0
if (isset($vars['area1'])) {
	echo $vars['area1'];
}

if (isset($vars['content'])) {
	echo $vars['content'];
}
