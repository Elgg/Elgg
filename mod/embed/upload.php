<?php

	// This page can only be run from within the Elgg framework
		if (!is_callable('elgg_view')) exit;
		
	// Get the name of the form field we need to inject into
		$internalname = get_input('internalname');
		
		global $SESSION;
		
	// Echo the embed view
		echo elgg_view('embed/upload', array(
							'entities' => $entities,
							'internalname' => $internalname,
					   ));

?>