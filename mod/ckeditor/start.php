<?php
/**
 * CKEditor wysiwyg editor
 *
 * @package ElggCKEditor
 */

elgg_register_event_handler('init', 'system', 'ckeditor_init');

function ckeditor_init() {
	elgg_extend_view('elgg.css', 'ckeditor.css');
	elgg_extend_view('admin.css', 'ckeditor.css');

	elgg_extend_view('elgg/wysiwyg.css', 'elements/reset.css', 100);
	elgg_extend_view('elgg/wysiwyg.css', 'elements/typography.css', 100);

	elgg_define_js('elgg_ckeditor', array(
		'deps' => ['elgg/ckeditor_essentials/set-basepath'],
		'exports' => 'CKEDITOR',
	));
	elgg_define_js('jquery.ckeditor', array(
		'deps' => array('jquery', 'elgg_ckeditor'),
		'exports' => 'jQuery.fn.ckeditor',
	));

	// need to set basepath early
	elgg_extend_view('elgg.js', 'elgg/ckeditor_essentials/set-basepath.js');
	
	elgg_extend_view('input/longtext', 'ckeditor/init');

	elgg_register_plugin_hook_handler('register', 'menu:longtext', 'ckeditor_longtext_menu');
}

function ckeditor_longtext_menu($hook, $type, $items, $vars) {

	$id = elgg_extract('id', $vars);
	if ($id === null) {
		return;
	}
	
	$items[] = ElggMenuItem::factory(array(
		'name' => 'ckeditor_toggler',
		'link_class' => 'ckeditor-toggle-editor elgg-longtext-control hidden',
		'href' => "#{$id}",
		'text' => elgg_echo('ckeditor:html'),
	));

	return $items;
}
