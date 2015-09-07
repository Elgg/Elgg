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

	elgg_define_js('elgg/ckeditor/set-basepath', [
		'deps' => ['elgg'],
	]);
	elgg_define_js('ckeditor', [
		'deps' => ['elgg/ckeditor/set-basepath'],
		'exports' => 'CKEDITOR',
	]);
	elgg_define_js('jquery.ckeditor', [
		'deps' => ['jquery', 'ckeditor'],
		'exports' => 'jQuery.fn.ckeditor',
	]);

	elgg_extend_view('input/longtext', 'ckeditor/init');

	elgg_extend_view('embed/embed.js', 'elgg/ckeditor/insert.js');

	elgg_register_plugin_hook_handler('register', 'menu:longtext', 'ckeditor_longtext_menu');
}

function ckeditor_longtext_menu($hook, $type, $items, $vars) {

	$items[] = ElggMenuItem::factory(array(
		'name' => 'ckeditor_toggler',
		'link_class' => 'ckeditor-toggle-editor elgg-longtext-control hidden',
		'href' => "#{$vars['id']}",
		'text' => elgg_echo('ckeditor:html'),
	));

	return $items;
}
