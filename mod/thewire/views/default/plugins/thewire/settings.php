<?php
/**
 * The wire plugin settings
 */

$plugin = $vars['entity'];

$label = elgg_echo('thewire:settings:limit');
$input = elgg_view('input/select', array(
	'name' => 'params[limit]',
	'value' => (int)$vars['entity']->limit,
	'id' => 'thewire-limit',
	'options_values' => array(
		0 => elgg_echo('thewire:settings:limit:none'),
		140 => '140',
		250 => '250',
	),
));

echo <<<HTML
<div>
	<label for="thewire-limit">$label</label>
	$input
</div>
HTML;
