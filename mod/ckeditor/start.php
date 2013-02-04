<?php
/**
 * CKEditor wysiwyg editor
 *
 * @package ElggCKEditor
 */

elgg_register_event_handler('init', 'system', 'ckeditor_init');

function ckeditor_init() {
	elgg_extend_view('css/elgg', 'ckeditor/css');
	elgg_extend_view('css/admin', 'ckeditor/css');

	$url = elgg_get_simplecache_url('css', 'wysiwyg');
	elgg_register_css('elgg.wysiwyg', $url);
	elgg_extend_view('css/wysiwyg', 'css/elements/reset', 100);
	elgg_extend_view('css/wysiwyg', 'css/elements/typography', 100);

	elgg_register_js('ckeditor', 'mod/ckeditor/vendors/ckeditor/ckeditor.js');
	elgg_register_js('jquery-ckeditor', 'mod/ckeditor/vendors/ckeditor/adapters/jquery.js');
	elgg_register_js('elgg.ckeditor', elgg_get_simplecache_url('js', 'ckeditor'));
	elgg_register_simplecache_view('js/ckeditor');
	
	elgg_extend_view('input/longtext', 'ckeditor/init');
	
	elgg_extend_view('embed/custom_insert_js', 'ckeditor/embed_custom_insert_js');
	
	elgg_register_plugin_hook_handler('register', 'menu:longtext', 'ckeditor_longtext_menu');
}

function ckeditor_longtext_menu($hook, $type, $items, $vars) {
	
	$items[] = ElggMenuItem::factory(array(
		'name' => 'ckeditor_toggler',
		'link_class' => 'ckeditor-toggle-editor elgg-longtext-control',
		'href' => "#{$vars['id']}",
		'text' => elgg_echo('ckeditor:remove'),
	));
	
	return $items;
}
