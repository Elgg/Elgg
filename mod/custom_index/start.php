<?php
/**
 * Elgg demo custom index page plugin
 */

elgg_register_event_handler('init', 'system', 'custom_index_init');

function custom_index_init() {

	// TODO(ewinslow): Want to just call elgg_load_css('./index.css') in
	// the index.php view, but can't because:
	//   1. Elgg can only load CSS from views in the css/* folder.
	//   2. Elgg can't do relative css inclusion.
	// Fix this.
	elgg_extend_view('elgg.css', 'resources/index.css');
}