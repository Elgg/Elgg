<?php
/**
 * Elgg tag input
 * Displays a tag input field
 *
 * @package Elgg
 * @subpackage Core
 */

$defaults = array(
	'class' => 'input-tags',
	'disabled' => FALSE,
);

$vars = array_merge($defaults, $vars);

if (!isset($vars['value']) || $vars['value'] === FALSE) {
	$vars['value'] = elgg_get_sticky_value($vars['internalname']);
}

if (is_array($vars['value'])) {
	$tags = array();

	foreach ($vars['value'] as $tag) {
		if (is_string($tag)) {
			$tags[] = $tag;
		} else {
			$tags[] = $tag->value;
		}
	}

	$vars['value'] = implode(", ", $tags);
}

?>
<input type="text" <?php echo elgg_format_attributes($vars); ?> />