<?php
/**
 * CKEditor wysiwyg editor
 */

/**
 * CKEditor init
 *
 * @return void
 */
function ckeditor_init() {
	elgg_extend_view('elgg.css', 'ckeditor.css');
	elgg_extend_view('admin.css', 'ckeditor.css');

	elgg_extend_view('elgg/wysiwyg.css', 'elements/reset.css', 100);
	elgg_extend_view('elgg/wysiwyg.css', 'elements/typography.css', 100);

	elgg_define_js('ckeditor/ckeditor', [
		'exports' => 'CKEDITOR',
	]);
	elgg_define_js('jquery.ckeditor', [
		'deps' => ['jquery', 'ckeditor/ckeditor'],
		'exports' => 'jQuery.fn.ckeditor',
	]);

	elgg_extend_view('input/longtext', 'ckeditor/init');

	elgg_register_plugin_hook_handler('register', 'menu:longtext', 'ckeditor_longtext_menu');
	elgg_register_plugin_hook_handler('view_vars', 'input/longtext', 'ckeditor_longtext_id');
}

/**
 * Add a menu item to an longtext
 *
 * @param \Elgg\Hook $hook 'register', 'menu:longtext'
 *
 * @return void|ElggMenuItem[]
 */
function ckeditor_longtext_menu(\Elgg\Hook $hook) {

	$id = $hook->getParam('textarea_id');
	if ($id === null) {
		return;
	}
	$items = $hook->getValue();
	
	$items[] = ElggMenuItem::factory([
		'name' => 'ckeditor_toggler',
		'link_class' => 'ckeditor-toggle-editor elgg-longtext-control hidden',
		'href' => "#{$id}",
		'text' => elgg_echo('ckeditor:html'),
	]);

	return $items;
}

/**
 * Set an id on input/longtext
 *
 * @param \Elgg\Hook $hook 'view_vars', 'input/longtext'
 *
 * @return void|array
 */
function ckeditor_longtext_id(\Elgg\Hook $hook) {
	$vars = $hook->getValue();
	$id = elgg_extract('id', $vars);
	if ($id !== null) {
		return;
	}
	
	// input/longtext view vars need to contain an id for editors to be initialized
	// random id generator is the same as in input/longtext
	$vars['id'] = 'elgg-input-' . base_convert(mt_rand(), 10, 36);

	return $vars;
}

return function() {
	elgg_register_event_handler('init', 'system', 'ckeditor_init');
};
