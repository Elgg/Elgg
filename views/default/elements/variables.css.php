<?php
/**
 * CSS variables
 */

echo ':root {';
echo 'color-scheme: light;';

$css_vars = (array) elgg_extract('default', _elgg_services()->cssCompiler->getCssVars(), []);

ksort($css_vars);
foreach ($css_vars as $variable => $value) {
	echo "--elgg-{$variable}: {$value};";
}

echo '}';
