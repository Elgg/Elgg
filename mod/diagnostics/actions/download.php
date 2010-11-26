<?php
	/**
	 * Elgg diagnostics
	 * 
	 * @package ElggDiagnostics
	 */

	admin_gatekeeper();
	
	$output = sprintf(elgg_echo('diagnostics:header'), date('r'), get_loggedin_user()->name);
	$output = trigger_plugin_hook('diagnostics:report', 'system', null, $output);
	
	header("Cache-Control: public");
	header("Content-Description: File Transfer");
	header('Content-disposition: attachment; filename=elggdiagnostic.txt');
	header("Content-Type: text/plain");
	header('Content-Length: '. strlen($output));

	echo $output;
	exit;
?>