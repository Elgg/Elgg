<?php
/**
 * TinyMCE wysiwyg editor
 *
 * @package ElggTinyMCE
 */

elgg_register_event_handler('init', 'system', 'tinymce_init');

function tinymce_init() {
	elgg_extend_view('css/elgg', 'tinymce/css');
	elgg_extend_view('css/admin', 'tinymce/css');

	elgg_register_js('tinymce', 'mod/tinymce/vendor/tinymce/jscripts/tiny_mce/tiny_mce.js');
	elgg_register_js('elgg.tinymce', elgg_get_simplecache_url('js', 'tinymce'));
	elgg_register_simplecache_view('js/tinymce');
	
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

function tinymce_get_site_language() {

	if ($site_language = elgg_get_config('language')) {
		$path = elgg_get_plugins_path() . "tinymce/vendor/tinymce/jscripts/tiny_mce/langs";
		if (file_exists("$path/$site_language.js")) {
			return $site_language;
		}
	}

	return 'en';
}
