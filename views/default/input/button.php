<?php
/**
 * Create a input button
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['src'] Src of an image
 * 
 * @todo Handle classes better
 */

$defaults = array(
	'type' => 'button',
	'class' => 'elgg-button elgg-button-action',
);

$vars = array_merge($defaults, $vars);

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