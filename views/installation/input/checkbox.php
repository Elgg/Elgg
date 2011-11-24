<?php
/**
 * Elgg checkbox input
 * Displays a checkbox input tag
 *
 * @uses $var['name']
 * @uses $vars['value']
 * @uses $vars['class']
 */


if (isset($vars['class'])) {
	$id = "class=\"{$vars['class']}\"";
} else {
	$id = '';
}

if (!isset($vars['value'])) {
	$vars['value'] = $vars['name'];
}

?>

<input type="checkbox" <?php echo $class; ?> name="<?php echo $vars['name']; ?>" value="<?php echo $vars['value']; ?>" />