<?php
/**
 * Elgg profile index
 * 
 * @package ElggProfile
 */

$label_text = elgg_echo('profile:label');
$type_text = elgg_echo('profile:type');

$label_control = elgg_view('input/text', array('name' => 'label'));
$type_control = elgg_view('input/dropdown', array('name' => 'type', 'options_values' => array(
	'text' => elgg_echo('text'),
	'longtext' => elgg_echo('longtext'),
	'tags' => elgg_echo('tags'),
	'url' => elgg_echo('url'),
	'email' => elgg_echo('email')
)));

$submit_control = elgg_view('input/submit', array('name' => elgg_echo('add'), 'value' => elgg_echo('add')));

$formbody = <<< END
		<p>$label_text: $label_control
		$type_text: $type_control
		$submit_control</p>
END;

echo autop(elgg_echo('profile:explainchangefields'));
echo $formbody;
