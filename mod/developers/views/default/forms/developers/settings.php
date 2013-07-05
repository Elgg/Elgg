<?php
/**
 * Settings form body
 *
 * @uses $vars['values']
 */

echo '<p>' . elgg_echo('elgg_dev_tools:settings:explanation') . '</p>';

foreach ($vars['data'] as $name => $info) {
	$label = $info['readonly'] ? '<label class="elgg-state-disabled">' : '<label>';
	$class = $info['readonly'] ? 'elgg-state-disabled' : '';
	echo '<div>';
	if ($info['type'] == 'checkbox') {
		echo $label;
		echo elgg_view("input/checkbox", array(
			'name' => $name,
			'value' => $info['value'],
			'checked' => $info['checked'],
			'class' => $class,
		));
		echo elgg_echo("developers:label:$name") . '</label>';
	} else {
		echo $label . elgg_echo("developers:label:$name") . ' ';
		echo elgg_view("input/{$info['type']}", array(
			'name' => $name,
			'value' => $info['value'],
			'options_values' => $info['options_values'],
			'class' => $class,
		));
		echo '</label>';
	}
	echo '<span class="elgg-text-help">' . elgg_echo("developers:help:$name") . '</span>';
	if ($info['readonly']) {
		echo '<span class="elgg-text-help">' . elgg_echo('admin:settings:in_settings_file') . '</span>';
	}
	echo '</div>';
}

echo '<div class="elgg-foot">';
echo elgg_view('input/submit', array('value' => elgg_echo('save')));
echo '</div>';
