<?php
/**
 * Elgg text input
 * Displays a text input field
 *
 * @uses $vars['value'] The current value, if any
 * @uses $vars['name']  The name of the input field
 * @uses $vars['class'] CSS class
 * @uses $vars['id']    CSS id
 */

if (isset($vars['class'])) {
	$class = "class=\"{$vars['class']}\"";
} else {
	$class = "";
}

if (isset($vars['id'])) {
	$id = "id=\"{$vars['id']}\"";
} else {
	$id = '';
}

?>
<input type="text" name="<?php echo $vars['name']; ?>" value="<?php echo htmlentities($vars['value'], ENT_QUOTES, 'UTF-8'); ?>" <?php echo $class; ?> <?php echo $id; ?>/>