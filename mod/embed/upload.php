<?php

	// This page can only be run from within the Elgg framework
		if (!is_callable('elgg_view')) exit;
		
	// Get the name of the form field we need to inject into
		$internalname = sanitise_string(get_input('internalname'));
		$internalname  = mb_convert_encoding($internalname, 'HTML-ENTITIES', 'UTF-8');
		$internalname  = htmlspecialchars($internalname, ENT_QUOTES, 'UTF-8', false);
		
		global $SESSION;
		
	// Echo the embed view
		echo elgg_view('embed/upload', array(
							'entities' => $entities,
							'internalname' => $internalname,
					   ));

?>