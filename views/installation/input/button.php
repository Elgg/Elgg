<?php
/**
 * Create a input button
 *
 * @uses $vars['value'] The current value, if any
 * @uses $vars['name'] The name of the input field
 * @uses $vars['type'] submit or button.
 */

$class = $vars['class'];
if (!$class) {
	$class = "elgg-button-submit";
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
$name = $vars['name'];
?>
<input type="<?php echo $type; ?>" <?php if (isset($vars['id'])) echo "id=\"{$vars['id']}\"";?> value="<?php echo $value; ?>" class="<?php echo $class; ?>" />