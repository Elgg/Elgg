<?php
/**
 * TinyMCE wysiwyg editor
 *
 * @package TinyMCE
 **/

function tinymce_init() {
	global $CONFIG;

	elgg_extend_view('css', 'tinymce/css');
	elgg_extend_view('embed/custom_insert_js', 'tinymce/embed_custom_insert_js');
}

register_elgg_event_handler('init', 'system', 'tinymce_init', 9999);
