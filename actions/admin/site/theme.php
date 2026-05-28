<?php

$vars = (array) get_input('vars', [], false);

$available_config = _elgg_services()->cssCompiler->getCssVars();

$default_scheme_vars = [];
if (isset($available_config['default'])) {
	$default_scheme_vars = array_map(function() {
		// empty function to clear the values
	}, $available_config['default']);
}

foreach ($vars as $color_scheme => $scheme_vars) {
	$allowed_keys = $color_scheme === 'default' ? $scheme_vars : array_merge($default_scheme_vars, $scheme_vars);
	
	foreach ($scheme_vars as $name => $value) {
		if (empty($value)) {
			unset($vars[$color_scheme][$name]);
			continue;
		}
		
		if (!array_key_exists($name, $allowed_keys)) {
			unset($vars[$color_scheme][$name]);
			continue;
		}
		
		if (strtolower($value) === strtolower((string) $available_config[$color_scheme][$name])) {
			unset($vars[$color_scheme][$name]);
			continue;
		}
	}
}

if (empty($vars)) {
	elgg_remove_config('custom_theme_vars');
} else {
	elgg_save_config('custom_theme_vars', $vars);
}

elgg_invalidate_caches();

return elgg_ok_response('', elgg_echo('save:success'));
