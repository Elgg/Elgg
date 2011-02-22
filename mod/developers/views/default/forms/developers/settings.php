<?php
/**
 * Settings form body
 *
 * @uses $vars['values']  Array of current values
 * @uses $vars['options'] Array of possible options
 */

$form_body = '<p>' . elgg_echo('elgg_dev_tools:settings:explanation') . '</p>';

$sections = array(
	'simple_cache' => 'checkbox',
	'views_cache' => 'checkbox',
	//'display_errors' => 'checkbox',
	'debug_level' => 'pulldown',
);

foreach ($sections as $name => $type) {
	echo '<p>';
	if ($type == 'checkbox') {
		echo elgg_view("input/$type", array(
			'internalname' => $name,
			'value' => $vars['settings'][$name],
		));
		echo '<label>' . elgg_echo("developers:label:$name") . '</label>';
		echo '<span class="elgg-text-help">' . elgg_echo("developers:help:$name") . '</span>';
	} else {
		echo '<label>' . elgg_echo("developers:label:$name") . '</label>';
		echo elgg_view("input/$type", array(
			'internalname' => $name,
			'value' => $vars['settings'][$name],
			'options' => $vars['options'][$name],
		));
		echo '<span class="elgg-text-help">' . elgg_echo("developers:help:$name") . '</span>';
	}
	echo '</p>';
}

echo '<p>';
echo elgg_view('input/submit', array('value' => elgg_echo('save')));
echo '</p>';