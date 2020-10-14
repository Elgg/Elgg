<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'admin:diagnostics' => 'Systemüberprüfung',
	'diagnostics' => 'Systemüberprüfung',
	'diagnostics:report' => 'Report',
	'diagnostics:description' => 'Der Report, der im folgenden erstellt werden kann, kann bei der Fehlersuche hilfreich sein. In manchen Fällen bitten die Entwickler von Elgg, dass er zu einem Bugreport beigefügt wird.',
	'diagnostics:header' => '========================================================================
Elgg-Diagnose-Report
Generiert %s von %s
========================================================================

',
	'diagnostics:report:basic' => '
Elgg-Release %s, Version %s

------------------------------------------------------------------------',
	'diagnostics:report:php' => '
PHP-Info:
%s
------------------------------------------------------------------------',
	'diagnostics:report:md5' => '
Installierte Dateien und Prüfsummen:

%s
------------------------------------------------------------------------',
	'diagnostics:report:globals' => '
Globale Variablen:

%s
------------------------------------------------------------------------',
);
