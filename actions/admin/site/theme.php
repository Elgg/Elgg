<?php

use Symfony\Component\HttpFoundation\File\UploadedFile;

$vars = (array) get_input('vars', [], false);
$vars = array_filter($vars);

$available_config = _elgg_services()->cssCompiler->getCssVars([], false);
foreach ($vars as $name => $value) {
	if (empty($value)) {
		unset($vars[$name]);
		continue;
	}
	
	if (!array_key_exists($name, $available_config)) {
		unset($vars[$name]);
		continue;
	}
	
	if (strtolower($value) === strtolower((string) $available_config[$name])) {
		unset($vars[$name]);
		continue;
	}
}

if (empty($vars)) {
	elgg_remove_config('custom_theme_vars');
} else {
	elgg_save_config('custom_theme_vars', $vars);
}

elgg_invalidate_caches();

return elgg_ok_response('', elgg_echo('save:success'));
