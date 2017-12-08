<?php

/**
 * Renders a set of fields wrapped in a <fieldset> tag
 *
 * @uses $vars['class']   Additional CSS classes
 * @uses $vars['align']   Field alignment (vertical|horizontal)
 *                        If set to horizontal, fields will be rendered
 *                        with inline display
 * @uses $vars['justify'] Text justification (left|right|center)
 * @uses $vars['legend']  Optional fieldset legend
 * @uses $vars['fields']  An array of field options
 *                        Field options should be suitable for use in
 *                        elgg_view_field()
 */

$vars['class'] = elgg_extract_class($vars, [
	'elgg-fieldset',
	'clearfix',
]);

$align = elgg_extract('align', $vars, 'vertical');
unset($vars['align']);
$vars['class'][] = "elgg-fieldset-$align";

$justify = elgg_extract('justify', $vars, '');
unset($vars['justify']);
if ($justify) {
	$vars['class'][] = "elgg-justify-$justify";
}

$legend = elgg_extract('legend', $vars);
unset($vars['legend']);

$fields = (array) elgg_extract('fields', $vars, []);
unset($vars['fields']);

$fieldset = '';
if ($legend) {
	$vars['class'][] = 'elgg-fieldset-has-legend';
	$fieldset .= elgg_format_element('legend', [], $legend);
}

foreach ($fields as $field) {
	$fieldset .= elgg_view_field($field);
}

$fieldset = elgg_format_element('div', $vars, $fieldset);
echo elgg_format_element('fieldset', [], $fieldset);
