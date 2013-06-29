<?php
/**
 * Elgg long text input
 * Displays a long text input field that can use WYSIWYG editor
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['value']    The current value, if any - will be html encoded
 * @uses $vars['disabled'] Is the input field disabled?
 * @uses $vars['class']    Additional CSS class
 */

if (isset($vars['class'])) {
	$vars['class'] = "elgg-input-longtext {$vars['class']}";
} else {
	$vars['class'] = "elgg-input-longtext";
}

$defaults = array(
	'value' => '',
	'rows' => '10',
	'cols' => '50',
	'id' => 'elgg-input-' . rand(), //@todo make this more robust
);

$vars = array_merge($defaults, $vars);

$value = $vars['value'];
unset($vars['value']);

echo elgg_view_menu('longtext', array(
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
	'id' => $vars['id'],
));

?>

<textarea <?php echo elgg_format_attributes($vars); ?>>
<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?>
</textarea>
