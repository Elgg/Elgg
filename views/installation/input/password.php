<?php
/**
 * Elgg password input
 * Displays a password input field
 *
 * @uses $vars['value'] The current value, if any
 * @uses $vars['name'] The name of the input field
 *
 */

$class = "input-password";

$value = htmlentities($vars['value'], ENT_QUOTES, 'UTF-8');

?>

<input type="password" name="<?php echo $vars['name']; ?>" value="<?php echo $value; ?>" class="<?php echo $class; ?>" />
