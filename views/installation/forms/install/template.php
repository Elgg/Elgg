<?php
/**
 * Generic form template for install forms
 *
 * @uses $vars['variables']
 * @uses $vars['type'] Type of form: admin, database, settings
 */

$variables = $vars['variables'];
$type = $vars['type'];

foreach ($variables as $name => $params) {
	$label = elgg_echo("install:$type:label:$name");
	$help = elgg_echo("install:$type:help:$name");
	$params['name'] = $name;
	
	$input = elgg_view("input/{$params['type']}", $params);

	$field = <<<FIELD
<label class="elgg-form-field">
	<span class="elgg-form-field-label">$label</span>
	<span class="elgg-form-field-help">$help</span>
	$input
</label>
FIELD;

	$form_body .= $field;
}

$form_body .= elgg_view('input/submit', array(
	'value' => elgg_echo('install:next'),
));

echo $form_body;
