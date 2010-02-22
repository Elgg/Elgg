<?php
	/**
	 * Elgg diagnostics
	 * 
	 * @package ElggDiagnostics
	 * @author Curverider Ltd
	 * @link http://elgg.com/
	 */

	admin_gatekeeper();
	
	$output = sprintf(elgg_echo('diagnostics:header'), date('r'), $_SESSION['user']->name); 
	$output = trigger_plugin_hook('diagnostics:report', 'system', null, $output);
	
	header("Cache-Control: public");
	header("Content-Description: File Transfer");
	header('Content-disposition: attachment; filename=elggdiagnostic.txt');
	header("Content-Type: text/plain");
	header('Content-Length: '. strlen($output));

	echo $output;
	exit;
?>