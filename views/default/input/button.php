<?php
/**
 * Create a input button
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['src']   Src of an image
 * @uses $vars['class'] Class to add to elgg-button
 *
 * @todo Handle classes better
 */

$defaults = array(
	'type' => 'button',
	'class' => '',
);

$vars = array_merge($defaults, $vars);

$vars['class'] = trim("elgg-button {$vars['class']}");

switch ($vars['type']) {
	case 'button':
	case 'reset':
	case 'submit':
	case 'image':
		break;
	default:
		$vars['type'] = 'button';
		break;
}

// blank src if trying to access an offsite image. @todo why?
if (strpos($vars['src'], elgg_get_site_url()) === false) {
	$vars['src'] = "";
}
?>
<input <?php echo elgg_format_attributes($vars); ?> />