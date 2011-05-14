<?php
/**
 * Create a hidden data field
 *
 * @uses $vars['value'] The current value, if any
 * @uses $vars['name'] The name of the input field
 *
 */
?>
<input type="hidden" name="<?php echo $vars['name']; ?>" value="<?php echo htmlentities($vars['value'], ENT_QUOTES, 'UTF-8'); ?>" />