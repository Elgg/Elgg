<?php

echo elgg_view('output/longtext', ['value' => elgg_echo('admin:theme:info')]);

echo elgg_view_message('warning', elgg_echo('admin:theme:warning'));

$css_vars = _elgg_services()->cssCompiler->getCssVars();

$tabs = [];

$headings = elgg_format_element('th', [], elgg_echo('admin:theme:css_variable:name'));
$headings .= elgg_format_element('th', [], elgg_echo('admin:theme:css_variable:value'));
$thead = elgg_format_element('tr', [], $headings);

$default_scheme_vars = [];
if (isset($css_vars['default'])) {
	$default_scheme_vars = array_map(function() {
		// empty function to clear the values
	}, $css_vars['default']);
}

foreach ($css_vars as $color_scheme => $scheme_vars) {
	$tbody = '';
	
	$configurable_vars = $color_scheme === 'default' ? $scheme_vars : array_merge($default_scheme_vars, $scheme_vars);
	
	foreach ($configurable_vars as $name => $value) {
		$row = elgg_format_element('th', [], $name);
		$row .= elgg_format_element('td', [], elgg_view_field([
			'#type' => 'text',
			'#class' => 'man',
			'name' => "vars[{$color_scheme}][{$name}]",
			'value' => $value,
		]));

		$tbody .= elgg_format_element('tr', [], $row);
	}

	$table = elgg_format_element('thead', [], $thead);
	$table .= elgg_format_element('tbody', [], $tbody);

	$tabs[$color_scheme] = [
		'text' => elgg_echo("color_scheme:{$color_scheme}"),
		'content' => elgg_format_element('table', ['class' => 'elgg-table-alt'], $table),
		'selected' => $color_scheme === 'default',
	];
}

echo elgg_view('page/components/tabs', [
	'tabs' => $tabs,
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'text' => elgg_echo('save'),
]);
elgg_set_form_footer($footer);
