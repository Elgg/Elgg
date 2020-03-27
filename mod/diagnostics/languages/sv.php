<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'admin:diagnostics' => 'Systemdiagnostik',
	'diagnostics' => 'Systemdiagnostik',
	'diagnostics:report' => 'Diagnostisk Rapport',
	'diagnostics:description' => 'Följande diagnostiska rapport kan vara användbar för att diagnosticera problem med Elgg. Utvecklarna av Elgg kan begära att du skickar med det i en buggrapport.',
	'diagnostics:header' => '========================================================================
Elgg Diagnostisk Rapport
Genererad %s av %s
========================================================================

',
	'diagnostics:report:basic' => '
Elgg Release %s, version %s

------------------------------------------------------------------------',
	'diagnostics:report:php' => '
PHP info:
%s
------------------------------------------------------------------------',
	'diagnostics:report:md5' => '
Installerade filer och kontrollsummor:

%s
------------------------------------------------------------------------',
	'diagnostics:report:globals' => '
Globala variabler:

%s
------------------------------------------------------------------------',
);
