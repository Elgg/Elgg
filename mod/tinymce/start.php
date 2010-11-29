<?php
/**
 * TinyMCE wysiwyg editor
 *
 * @package ElggTinyMCE
 */

function tinymce_init() {
	elgg_extend_view('css', 'tinymce/css');
	elgg_extend_view('embed/custom_insert_js', 'tinymce/embed_custom_insert_js');
}

elgg_register_event_handler('init', 'system', 'tinymce_init', 9999);
