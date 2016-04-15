<?php

echo "<p>" . elgg_echo('admin:settings:early_access:description') . "</p>";

foreach (_elgg_get_known_features() as $feature) {

	$help = elgg_language_key_exists("feature:help:$feature") ? elgg_echo("feature:help:$feature") : '';

	echo elgg_view_input('checkbox', [
		'name' => "feat_" . md5($feature),
		'checked' => _elgg_feature_is_enabled($feature),
		'label' => elgg_echo("feature:label:$feature"),
		'help' => $help,
	]);
}

echo elgg_view('input/submit', ['value' => elgg_echo('save')]);
