<?php
/**
 * TinyMCE wysiwyg editor
 *
 * @package ElggTinyMCE
 */

function tinymce_init() {
	elgg_extend_view('css/elgg', 'tinymce/css');
	elgg_extend_view('css/admin', 'tinymce/css');

	elgg_register_js('tinymce', 'mod/tinymce/vendor/tinymce/jscripts/tiny_mce/tiny_mce.js');
	elgg_register_js('elgg.tinymce', elgg_get_simplecache_url('js', 'tinymce'));
	
	elgg_extend_view('input/longtext', 'tinymce/init');
	
	elgg_extend_view('embed/custom_insert_js', 'tinymce/embed_custom_insert_js');
	
	elgg_register_plugin_hook_handler('register', 'menu:longtext', 'tinymce_longtext_menu');
}

function tinymce_longtext_menu($hook, $type, $items, $vars) {
	
	$items[] = ElggMenuItem::factory(array(
		'name' => 'tinymce_toggler',
		'link_class' => 'tinymce-toggle-editor elgg-longtext-control',
		'href' => "#{$vars['id']}",
		'text' => elgg_echo('tinymce:remove'),
	));
	
	return $items;
}

elgg_register_event_handler('init', 'system', 'tinymce_init', 9999);
