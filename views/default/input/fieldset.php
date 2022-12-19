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

$vars['class'] = elgg_extract_class($vars, ['elgg-fieldset']);

$align = elgg_extract('align', $vars, 'vertical');
unset($vars['align']);
$vars['class'][] = "elgg-fieldset-{$align}";

$justify = elgg_extract('justify', $vars);
unset($vars['justify']);
if (!empty($justify)) {
	$vars['class'][] = "elgg-justify-{$justify}";
}

$fieldset_vars = [];
$legend = elgg_extract('legend', $vars, '');
unset($vars['legend']);
if (!empty($legend)) {
	$fieldset_vars['class'][] = 'elgg-fieldset-has-legend';
	$legend = elgg_format_element('legend', [], $legend);
}

$fields = (array) elgg_extract('fields', $vars, []);
unset($vars['fields']);

$fieldset = '';
foreach ($fields as $field) {
	$fieldset .= elgg_view_field($field);
}

unset($vars['name']); // name isn't allowed on a DIV, but is commonly supplied to input views

$fieldset = elgg_format_element('div', $vars, $fieldset);
echo elgg_format_element('fieldset', $fieldset_vars, $legend . $fieldset);
