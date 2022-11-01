<?php
/**
 * Elgg diagnostics
 */

// generating report could take some time
set_time_limit(0);

$output = elgg_echo('diagnostics:header', [date('r'), elgg_get_logged_in_user_entity()->getDisplayName()]);
$output = elgg_trigger_event_results('diagnostics:report', 'system', [], $output);

return elgg_download_response($output, 'elggdiagnostic.txt', false, [
	'Content-Type' => 'text/plain; charset=utf-8',
]);
