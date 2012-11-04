<?php
/**
 * Add a new field to the set of custom profile fields
 */

$label_text = elgg_echo('profile:label');
$type_text = elgg_echo('profile:type');

$label_control = elgg_view('input/text', array('name' => 'label'));
$type_control = elgg_view('input/dropdown', array('name' => 'type', 'options_values' => array(
	'text' => elgg_echo('profile:field:text'),
	'longtext' => elgg_echo('profile:field:longtext'),
	'tags' => elgg_echo('profile:field:tags'),
	'url' => elgg_echo('profile:field:url'),
	'email' => elgg_echo('profile:field:email'),
	'location' => elgg_echo('profile:field:location'),
	'date' => elgg_echo('profile:field:date'),
)));

$submit_control = elgg_view('input/submit', array('name' => elgg_echo('add'), 'value' => elgg_echo('add')));

$formbody = <<< END
		<div>$label_text: $label_control</div>
		<div class="elgg-foot">$type_text: $type_control
		$submit_control</div>
END;

echo elgg_autop(elgg_echo('profile:explainchangefields'));
echo $formbody;
