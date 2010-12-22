<?php
/**
 * Create a input button
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['src'] Src of an image
 */

global $CONFIG;

$defaults = array(
	'type' => 'button',
	'class' => 'elgg-submit-button',
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
if (strpos($vars['src'], $CONFIG->wwwroot) === false) {
	$vars['src'] = "";
}
?>
<input <?php echo elgg_format_attributes($vars); ?> />