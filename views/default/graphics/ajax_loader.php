<?php
/**
 * Elgg AJAX loader
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['id']     CSS id
 * @uses $vars['class']  Optional additional CSS class
 * @uses $vars['hidden'] Begin hidden? (true)
 */

if (isset($vars['id'])) {
	$id = "id=\"{$vars['id']}\"";
}

$class = 'elgg-ajax-loader';
if (isset($vars['class'])) {
	$class = "$class {$vars['class']}";
}

if (elgg_extract('hidden', $vars, true)) {
	$class = "$class hidden";
}

$loader = <<< END

<div class="$class" $id></div>

END;

echo $loader;