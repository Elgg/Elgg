<?php
/**
 * Elgg text input
 * Displays a text input field
 *
 *
 * @uses $vars['value'] The current value, if any
 * @uses $vars['name'] The name of the input field
 * @uses $vars['disabled'] If true then control is read-only
 * @uses $vars['class'] Class override
 */

$class = $vars['class'];
if (!$class) {
	$class = "input-text";
}

?>
<input type="text" <?php if ($vars['disabled']) echo ' disabled="yes" '; ?> name="<?php echo $vars['name']; ?>" value="<?php echo htmlentities($vars['value'], ENT_QUOTES, 'UTF-8'); ?>" class="<?php echo $class ?>"/>