<?php
/**
 * Settings form body
 *
 * @uses $vars['values']
 */

if (!elgg_is_xhr()) {
	echo '<p>' . elgg_echo('elgg_dev_tools:settings:explanation') . '</p>';
}

foreach ($vars['data'] as $name => $info) {
	$label = $info['readonly'] ? '<label class="elgg-state-disabled">' : '<label>';
	$class = $info['readonly'] ? 'elgg-state-disabled' : '';
	$echo_vars = ($name === 'show_gear') ? [elgg_view_icon('settings-alt')] : [];

	echo '<div>';
	if ($info['type'] == 'checkbox') {
		echo $label;
		echo elgg_view("input/checkbox", [
			'name' => $name,
			'value' => $info['value'],
			'checked' => $info['checked'],
			'class' => $class,
			'disabled' => $info['disabled'],
		]);
		echo ' ' . elgg_echo("developers:label:$name", $echo_vars) . '</label>';
	} else {
		echo $label . elgg_echo("developers:label:$name") . ' ';
		echo elgg_view("input/{$info['type']}", [
			'name' => $name,
			'value' => $info['value'],
			'options_values' => $info['options_values'],
			'class' => $class,
			'disabled' => $info['disabled'],
		]);
		echo '</label>';
	}
	echo '<span class="elgg-text-help">' . elgg_echo("developers:help:$name") . '</span>';
	if ($info['readonly']) {
		echo '<span class="elgg-text-help">' . elgg_echo('admin:settings:in_settings_file2') . '</span>';
	}
	echo '</div>';
}

echo '<div class="elgg-foot">';
echo elgg_view('input/submit', ['value' => elgg_echo('developers:label:submit')]);
echo '</div>';
