<?php
/**
 * Elgg long text input (plaintext)
 * Displays a long text input field that should not be overridden by wysiwyg editors.
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['value'] The current value, if any
 * @uses $vars['name'] The name of the input field
 * @uses $vars['class']
 * @uses $vars['disabled']
 */

$defaults = array(
	'class' => 'elgg-input-textarea',
	'disabled' => FALSE,
);

$value = $vars['value'];
unset($vars['value']);

$attrs = array_merge($defaults, $vars);
?>

<textarea <?php echo elgg_format_attributes($attrs); ?>>
<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false); ?>
</textarea>
