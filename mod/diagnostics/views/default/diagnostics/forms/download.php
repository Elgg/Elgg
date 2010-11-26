<?php
	/**
	 * Elgg diagnostics
	 * 
	 * @package ElggDiagnostics
	 */
	
	$form_body = elgg_view('input/submit', array('value' => elgg_echo('diagnostics:download')));
	echo elgg_view('input/form', array('body' => $form_body, 'action' => $CONFIG->url . "action/diagnostics/download"));
?>