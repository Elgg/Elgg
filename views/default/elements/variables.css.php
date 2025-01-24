<?php
/**
 * CSS variables
 */

echo ':root {';

$css_vars = _elgg_services()->cssCompiler->getCssVars();
ksort($css_vars);
foreach ($css_vars as $variable => $value) {
	echo "--elgg-{$variable}: {$value};";
}

echo '}';
