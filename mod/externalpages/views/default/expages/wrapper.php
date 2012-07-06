<?php
/**
 * Wrapper for site pages content area
 *
 * @uses $vars['content']
 */

echo $vars['content'];

echo '<div class="mtm">';
echo elgg_view('output/url', array(
	'text' => elgg_echo('back'),
	'href' => $_SERVER['HTTP_REFERER'],
	'class' => 'float-alt'
));
echo '</div>';
