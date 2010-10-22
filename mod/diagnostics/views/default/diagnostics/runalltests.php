<?php
	/**
	 * Elgg diagnostics - unit tester
	 * 
	 * @package ElggDiagnostics
	 */

	$form_body .= elgg_view('input/submit', array('internalname' => 'execute', 'value' => elgg_echo('diagnostics:test:executeall')));

	echo elgg_view('input/form', array('action' => $vars['url'] . "pg/diagnostics/tests/all", 'body' => $form_body));	
?>