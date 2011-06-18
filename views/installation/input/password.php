<?php
/**
 * Elgg password input
 * Displays a password input field
 *
 * @uses $vars['value'] The current value, if any
 * @uses $vars['name'] The name of the input field
 *
 */

$class = $vars['class'];
if (!$class) {
	$class = "input-password";
}
?>

<input type="password" <?php if ($vars['disabled']) echo ' disabled="yes" '; ?> name="<?php echo $vars['name']; ?>" <?php if (isset($vars['id'])) echo "id=\"{$vars['id']}\""; ?> value="<?php echo htmlentities($vars['value'], ENT_QUOTES, 'UTF-8'); ?>" class="<?php echo $class; ?>" />
