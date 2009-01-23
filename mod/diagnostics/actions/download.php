<?php
	/**
	 * Elgg diagnostics
	 * 
	 * @package ElggDiagnostics
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	admin_gatekeeper();
	
	$output = sprintf(elgg_echo('diagnostics:header'), date('r'), $_SESSION['user']->name); 
	$output = trigger_plugin_hook('diagnostics:report', 'all', null, $output);
	
	header("Cache-Control: public");
	header("Content-Description: File Transfer");
	header('Content-disposition: attachment; filename=elggdiagnostic.txt');
	header("Content-Type: text/plain");
	header('Content-Length: '. strlen($output));

	echo $output;
?>