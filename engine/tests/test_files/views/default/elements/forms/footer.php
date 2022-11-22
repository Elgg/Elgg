<?php

$footer = elgg_extract('footer', $vars);

// add automatic sticky form support
$form_vars = (array) elgg_extract('form_vars', $vars);
if ((bool) elgg_extract('sticky_enabled', $form_vars, false)) {
	// can't use elgg_view_field() because the underlying fields might not not available
	$footer .= elgg_format_element('input', [
		'type' => 'hidden',
		'name' => '_elgg_sticky_form_name',
		'value' => (string) elgg_extract('sticky_form_name', $form_vars),
	]);
	
	$ignored_fields = (array) elgg_extract('sticky_ignored_fields', $form_vars);
	if (!empty($ignored_fields)) {
		$footer .= elgg_format_element('input', [
			'type' => 'hidden',
			'name' => '_elgg_sticky_ignored_fields',
			'value' => implode(',', $ignored_fields),
		]);
	}
}

if (empty($footer)) {
	return;
}

echo $footer;
