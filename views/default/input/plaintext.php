<?php
/**
 * Elgg long text input (plaintext)
 * Displays a long text input field that should not be overridden by wysiwyg editors.
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['value']    The current value, if any
 * @uses $vars['name']     The name of the input field
 * @uses $vars['class']    Additional CSS class
 * @uses $vars['disabled']
 */

if (isset($vars['class'])) {
	$vars['class'] = "elgg-input-plaintext {$vars['class']}";
} else {
	$vars['class'] = "elgg-input-plaintext";
}

$defaults = array(
	'value' => '',
	'rows' => '10',
	'cols' => '50',
	'disabled' => false,
);

$vars = array_merge($defaults, $vars);

$value = $vars['value'];
unset($vars['value']);

?>

<textarea <?php echo elgg_format_attributes($vars); ?>>
<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false); ?>
</textarea>
