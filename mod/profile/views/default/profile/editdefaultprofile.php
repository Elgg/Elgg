<?php
/**
 * Elgg profile index
 * 
 * @package ElggProfile
 */

$label_text = elgg_echo('profile:label');
$type_text = elgg_echo('profile:type');

$label_control = elgg_view('input/text', array('internalname' => 'label'));
$type_control = elgg_view('input/pulldown', array('internalname' => 'type', 'options_values' => array(
	'text' => elgg_echo('text'),
	'longtext' => elgg_echo('longtext'),
	'tags' => elgg_echo('tags'),
	'url' => elgg_echo('url'),
	'email' => elgg_echo('email')
)));

$submit_control = elgg_view('input/submit', array('internalname' => elgg_echo('add'), 'value' => elgg_echo('add')));

$formbody = <<< END
		<p>$label_text: $label_control
		$type_text: $type_control
		$submit_control</p>
END;

echo "<p class='margin-top'>" . elgg_echo('profile:explainchangefields') . "</p>";
echo elgg_view('input/form', array('body' => $formbody, 'action' => 'action/profile/editdefault'));
