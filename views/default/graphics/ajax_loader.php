<?php
/**
 * Elgg AJAX loader
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['id']     CSS id
 * @uses $vars['hidden'] Begin hidden? (true)
 */

if (isset($vars['id'])) {
	$id = "id=\"{$vars['id']}\"";
}

$hidden = 'hidden';
if (isset($vars['hidden']) && $vars['hidden'] == false) {
	$hidden = '';
}

$loader = <<< END

<div class="elgg-ajax-loader $hidden" $id></div>

END;

echo $loader;