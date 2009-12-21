<?php
/**
 * Create a input button
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 * @uses $vars['value'] The current value, if any
 * @uses $vars['js'] Any Javascript to enter into the input tag
 * @uses $vars['internalname'] The name of the input field
 * @uses $vars['internalid'] The id of the input field
 * @uses $vars['type'] Submit, button, or reset, defaults to submit.
 * @uses $vars['src'] Src of an image
 *
 */

global $CONFIG;

if (isset($vars['class'])) {
	$class = $vars['class'];
} else {
	$class = "submit_button";
}

// defaults to submit button
if (isset($vars['type'])) {
	$type = strtolower($vars['type']);
} else {
	$type = 'submit';
}

switch ($type) {
	case 'button' :
		$type='button';
		break;
	case 'reset' :
		$type='reset';
		break;
	case 'submit':
	default:
		$type = 'submit';
}

$value = htmlentities($vars['value'], ENT_QUOTES, 'UTF-8');

$name = '';
if (isset($vars['internalname'])) {
	$name = $vars['internalname'];
}

$src = '';
if (isset($vars['src'])) {
	$src = "src=\"{$vars['src']}\"";
}
// blank src if trying to access an offsite image.
if (strpos($src,$CONFIG->wwwroot)===false) {
	$src = "";
}
?>
<input name="<?php echo $name; ?>" <?php if (isset($vars['internalid'])) echo "id=\"{$vars['internalid']}\""; ?> type="<?php echo $type; ?>" class="<?php echo $class; ?>" <?php echo $vars['js']; ?> value="<?php echo $value; ?>" <?php echo $src; ?> />