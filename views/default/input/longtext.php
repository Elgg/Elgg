<?php
/**
 * Elgg long text input
 * Displays a long text input field that can use WYSIWYG editor
 *
 * @uses $vars['value']          The current value, if any - will be html encoded
 * @uses $vars['disabled']       Is the input field disabled?
 * @uses $vars['class']          Additional CSS class
 * @uses $vars['editor']         Enable WYSIWYG support
 *                               Requires a plugin that implements a WYWIWYG library
 *                               (e.g. bundled ckeditor plugin)
 * @uses $vars['editor_type']    The type of editor eg. 'simple'. It determines the style of the editor (if supported).
 * @uses $vars['editor_options'] Additional options to pass to the editor
 */

$vars['class'] = elgg_extract_class($vars, 'elgg-input-longtext');

$defaults = [
	'rows' => '10',
	'cols' => '50',
	'id' => 'elgg-input-' . base_convert(mt_rand(), 10, 36),
];

$vars = array_merge($defaults, $vars);

$editor_opts = (array) elgg_extract('editor_options', $vars, []);
$editor_opts['disabled'] = !elgg_extract('editor', $vars, true);
$editor_opts['required'] = elgg_extract('required', $vars);

unset($vars['editor']);
unset($vars['editor_options']);
unset($vars['editor_type']);

$vars['data-editor-opts'] = json_encode($editor_opts);

$value = htmlspecialchars(elgg_extract('value', $vars, ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
unset($vars['value']);

echo elgg_format_element('textarea', $vars, $value);
