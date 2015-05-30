<?php
/**
 * Outputs a concatenated list of AMD modules with explicit names
 *
 * @internal Do not use or alter this view
 */

$modules = [
	// core defaults
	'elgg/spinner',
];
$modules = _elgg_services()->hooks->trigger("bundle_amd", "js/elgg", null, $modules);
$modules = array_unique($modules);

$filter = new \Elgg\Amd\ViewFilter();
$views = _elgg_services()->views;

foreach ($modules as $module) {
	if ($module === 'js/elgg' || 0 === strpos($module, 'languages/')) {
		continue;
	}

	$view = "js/$module.js";
	if (!$views->viewExists($view, 'default')) {
		continue;
	}

	$view_output = $views->renderView($view, [], false, 'default');
	if (!$view_output) {
		continue;
	}

	$filtered = $filter->filter($view, $view_output);
	if ($filtered === $view_output) {
		// may not be a module
		if (!preg_match('/^define\([\'"]/m', $view_output)) {
			// no define was found... this may not be a module
			continue;
		}
	}

	echo "$filtered;\n";
}
