<?php

	// This page can only be run from within the Elgg framework
		if (!is_callable('elgg_view')) exit;
		
	// Get the name of the form field we need to inject into
		$internalname = get_input('internalname');
		
		if (!isloggedin()) exit;
		
		global $SESSION;
		
		$offset = (int) get_input('offset',0);
		$simpletype = get_input('simpletype');
		$entity_types = array('object' => array('file'));

		if (empty($simpletype)) {
			$count = elgg_get_entities(array('type' => 'object', 'subtype' => 'file', 'owner_guid' => $SESSION['user']->guid, 'count' => TRUE));
			$entities = elgg_get_entities(array('type' => 'object', 'subtype' => 'file', 'owner_guid' => $SESSION['user']->guid, 'limit' => 6, 'offset' => $offset));
		} else {
			$count = elgg_get_entities_from_metadata(array('metadata_name' => 'simpletype', 'metadata_value' => $simpletype, 'types' => 'object', 'subtypes' => 'file', 'owner_guid' => $SESSION['user']->guid, 'limit' => 6, 'offset' => $offset, 'count' => TRUE));
			$entities = elgg_get_entities_from_metadata(array('metadata_name' => 'simpletype', 'metadata_value' => $simpletype, 'types' => 'object', 'subtypes' => 'file', 'owner_guid' => $SESSION['user']->guid, 'limit' => 6, 'offset' => $offset));
		}
		
		$types = get_tags(0,10,'simpletype','object','file',$SESSION['user']->guid);
		
	// Echo the embed view
		echo elgg_view('embed/media', array(
							'entities' => $entities,
							'internalname' => $internalname,
							'offset' => $offset,
							'count' => $count,
							'simpletype' => $simpletype,
							'limit' => 6,
							'simpletypes' => $types,
					   ));

?>