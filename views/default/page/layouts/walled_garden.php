<?php
/**
 * Walled Garden layout
 *
 * @uses $vars['content'] Main content
 * @uses $vars['class']   CSS classes
 * @uses $vars['id']      CSS id
 */

$class = elgg_extract('class', $vars, 'elgg-walledgarden-single');
echo elgg_view_module('walledgarden', '', $vars['content'], array(
	'class' => $class,
	'id' => elgg_extract('id', $vars, ''),
	'header' => ' ',
	'footer' => ' ',
));
