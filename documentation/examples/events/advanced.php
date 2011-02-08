<?php

elgg_register_event_handler('create', 'object', 'example_event_handler');

function example_event_handler($event, $type, $params) {
	// Don't allow any non-admin users to create objects
	// Returning false from this function will halt the creation of the object.
	return elgg_is_admin_logged_in();
}

