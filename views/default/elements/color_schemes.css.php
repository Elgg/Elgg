<?php
if (!elgg_get_config('color_schemes_enabled')) {
	return;
}

$css_vars = _elgg_services()->cssCompiler->getCssVars();
unset($css_vars['default']);
if (empty($css_vars)) {
	return;
}

foreach ($css_vars as $color_scheme => $css_variables) {
	$scheme_vars = '';
	foreach ($css_variables as $variable => $value) {
		$scheme_vars .= "--elgg-{$variable}: {$value};";
	}
	
	$variables_output = '';
	if ($color_scheme === 'dark') {
		// set a global setting to let browser know it is dark mode
		$variables_output .= 'color-scheme: dark;';
	}
	
	$variables_output .= 'body {' . $scheme_vars . '}';
	
	// css output
	echo ":root[data-color-scheme={$color_scheme}] {";
	echo $variables_output;
	echo '}';
	
	// special browser detection for dark scheme
	if ($color_scheme === 'dark') {
		echo '@media (prefers-color-scheme: dark) {';
		echo ':root:not([data-color-scheme]) {';
		echo $variables_output;
		echo '}';
		echo '}';
	}
}
