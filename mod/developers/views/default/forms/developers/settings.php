<?php
/**
 * Settings form body
 *
 * @uses $vars['values']
 */

echo '<p>' . elgg_echo('elgg_dev_tools:settings:explanation') . '</p>';

foreach ($vars['data'] as $name => $info) {
	echo '<div>';
	if ($info['type'] == 'checkbox') {
		echo elgg_view("input/checkbox", array(
			'name' => $name,
			'value' => $info['value'],
			'checked' => $info['checked'],
		));
		echo '<label>' . elgg_echo("developers:label:$name") . '</label>';
		echo '<span class="elgg-text-help">' . elgg_echo("developers:help:$name") . '</span>';
	} else {
		echo '<label>' . elgg_echo("developers:label:$name") . '</label>';
		echo elgg_view("input/{$info['type']}", array(
			'name' => $name,
			'value' => $info['value'],
			'options_values' => $info['options_values'],
		));
		echo '<span class="elgg-text-help">' . elgg_echo("developers:help:$name") . '</span>';
	}
	echo '</div>';
}

echo '<div>';
echo elgg_view('input/submit', array('value' => elgg_echo('save')));
echo '</div>';
