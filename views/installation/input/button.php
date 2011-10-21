<?php
/**
 * Create a input button
 *
 * @uses $vars['value'] The current value, if any
 * @uses $vars['name'] The name of the input field
 * @uses $vars['type'] submit or button.
 */

if (isset($vars['class'])) {
	$class = $vars['class'];
} else {
	$class = "elgg-button-submit";
}

if (isset($vars['name'])) {
	$name = $vars['name'];
} else {
	$name = '';
}

if (isset($vars['type'])) {
	$type = strtolower($vars['type']);
} else {
	$type = 'submit';
}

switch ($type) {
	case 'button' :
		$type='button';
		break;
	case 'submit':
	default:
		$type = 'submit';
}

$value = htmlentities($vars['value'], ENT_QUOTES, 'UTF-8');

?>
<input type="<?php echo $type; ?>" value="<?php echo $value; ?>" class="<?php echo $class; ?>" />