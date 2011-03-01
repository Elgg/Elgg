<?php
/**
 * TinyMCE wysiwyg editor
 *
 * @package ElggTinyMCE
 */

function tinymce_init() {
	elgg_extend_view('css/elgg', 'tinymce/css');
	elgg_extend_view('css/admin', 'tinymce/css');
	
	elgg_extend_view('input/longtext', 'tinymce/init');
	
	elgg_extend_view('embed/custom_insert_js', 'tinymce/embed_custom_insert_js');
	
	elgg_register_plugin_hook_handler('register', 'menu:longtext', 'tinymce_longtext_menu');
}

function tinymce_longtext_menu($hook, $type, $items, $vars) {
	
	$items[] = ElggMenuItem::factory(array(
		'name' => 'tinymce_toggler',
		'class' => 'tinymce-toggle-editor elgg-longtext-control',
		'href' => "javascript:elgg.tinymce.toggleEditor('{$vars['id']}');",
		'text' => elgg_echo('tinymce:remove'),
	));
	
	return $items;
}

elgg_register_event_handler('init', 'system', 'tinymce_init', 9999);
