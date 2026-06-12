<?php

$vars = (array) get_input('vars', [], false);
$available_config = _elgg_services()->cssCompiler->getCssVars(false);

foreach ($vars as $color_scheme => $scheme_vars) {
	if (!isset($available_config[$color_scheme])) {
		unset($vars[$color_scheme]);
		continue;
	}
	
	foreach ($scheme_vars as $name => $value) {
		if (empty($value)) {
			unset($vars[$color_scheme][$name]);
			continue;
		}
		
		if ($color_scheme === 'default' && !isset($available_config[$color_scheme][$name])) {
			unset($vars[$color_scheme][$name]);
			continue;
		}
		
		if ($color_scheme !== 'default' && !isset($available_config['default'][$name]) && !isset($available_config[$color_scheme][$name])) {
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
