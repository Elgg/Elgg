<?php
/**
 * Elgg diagnostics
 *
 * @package ElggDiagnostics
 */

$output = elgg_echo('diagnostics:header', [date('r'), elgg_get_logged_in_user_entity()->getDisplayName()]);
$output = elgg_trigger_plugin_hook('diagnostics:report', 'system', null, $output);

header("Cache-Control: public");
header("Content-Description: File Transfer");
header('Content-disposition: attachment; filename=elggdiagnostic.txt');
header("Content-Type: text/plain");
header('Content-Length: ' . strlen($output));

echo $output;
exit;
