<?php

elgg_register_event_handler('all', 'object', 'example_event_handler');

// This function will be called for any event of type 'object'
function example_event_handler($event, $type, $params) {
	// check what sort of object is passed
	if ($params instanceof ElggObject) {
		$subtype = $params->getSubtype();

		switch($subtype) {
			case 'blog':
			case 'thewire':
			case 'pages':
				return false;
			default:
				return true;
		}

	}

	return true;
}

