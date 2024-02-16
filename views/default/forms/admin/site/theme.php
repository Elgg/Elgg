<?php

elgg_import_esm('forms/admin/site/theme');

echo elgg_view('output/longtext', ['value' => elgg_echo('admin:theme:info')]);

echo elgg_view_message('warning', elgg_echo('admin:theme:warning'));

$css_vars = _elgg_services()->cssCompiler->getCssVars();
$original_vars = _elgg_services()->cssCompiler->getCssVars([], false);

$config_values = (array) elgg_get_config('custom_theme_vars', []);

$headings = elgg_format_element('th', [], elgg_echo('admin:theme:css_variable:name'));
$headings .= elgg_format_element('th', [], elgg_echo('admin:theme:css_variable:value'));
$headings .= elgg_format_element('th', [], '&nbsp;');
$thead = elgg_format_element('tr', [], $headings);

$tbody = '';
foreach ($css_vars as $name => $value) {
	$original_value = (string) elgg_extract($name, $original_vars);
	$input_type = str_starts_with($original_value, '#') && strlen($original_value) === 7 ? 'color' : 'text';
	
	$reset = '&nbsp;';
	if (array_key_exists($name, $config_values)) {
		$reset = elgg_view_field([
			'#type' => 'button',
			'#class' => 'man',
			'text' => elgg_echo('reset'),
			'class' => 'elgg-button-action',
			'data-original-theme-var' => $original_vars[$name],
		]);
	}
	
	$row = elgg_format_element('th', [], $name);
	$row .= elgg_format_element('td', [], elgg_view_field([
		'#type' => $input_type,
		'#class' => 'man',
		'name' => "vars[{$name}]",
		'value' => $value,
	]));
	$row .= elgg_format_element('td', ['style' => 'width: 1%;'], $reset);
	
	$tbody .= elgg_format_element('tr', [], $row);
}

$table = elgg_format_element('thead', [], $thead);
$table .= elgg_format_element('tbody', [], $tbody);

echo elgg_format_element('table', ['class' => 'elgg-table-alt'], $table);

$footer = elgg_view_field([
	'#type' => 'submit',
	'text' => elgg_echo('save'),
]);
elgg_set_form_footer($footer);
