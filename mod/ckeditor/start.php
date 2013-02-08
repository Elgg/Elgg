<?php
/**
 * CKEditor wysiwyg editor
 *
 * @package ElggCKEditor
 */

elgg_register_event_handler('init', 'system', 'ckeditor_init');

function ckeditor_init() {
	elgg_extend_view('css/elgg', 'css/ckeditor.css');
	elgg_extend_view('css/admin', 'css/ckeditor.css');

	elgg_extend_view('css/elgg/wysiwyg.css', 'css/elements/reset', 100);
	elgg_extend_view('css/elgg/wysiwyg.css', 'css/elements/typography', 100);

	elgg_register_js('ckeditor', array(
		'src' => '/mod/ckeditor/vendors/ckeditor/ckeditor.js',
		'exports' => 'CKEDITOR',
	));
	elgg_register_js('jquery.ckeditor', array(
		'src' => '/mod/ckeditor/vendors/ckeditor/adapters/jquery.js',
		'deps' => array('jquery', 'ckeditor'),
		'exports' => 'jQuery.fn.ckeditor',
	));

	elgg_extend_view('input/longtext', 'ckeditor/init');

	elgg_extend_view('embed/custom_insert_js', 'ckeditor/embed_custom_insert_js');

	elgg_register_plugin_hook_handler('register', 'menu:longtext', 'ckeditor_longtext_menu');

	elgg_register_page_handler('uploads', 'ckeditor_uploads_page_handler');
	elgg_register_admin_menu_item('administer', 'uploads', 'administer_utilities');

	$actions_base = elgg_get_plugins_path() . 'ckeditor/actions/ckeditor';
	elgg_register_action("ckeditor/upload", "$actions_base/upload.php");
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

/**
 * Serve assets that have been uploaded by users
 *
 * @param array $segments URL segments
 */
function ckeditor_uploads_page_handler($segments) {
	// uploads/images/$user_guid/$filename
	$guid = elgg_extract(1, $segments, 0);
	$filename = elgg_extract(2, $segments);
	$user = get_user($guid);
	if (!$user || !$filename) {
		header("HTTP/1.1 404 Not Found");
		return true;
	}

	$filename = preg_replace('/[^\w\.]+/', '', $filename);
	$service = new CKEditorUploadService();
	$filepath = $service->retrieve($user, $filename);
	if (!$filepath) {
		header("HTTP/1.1 404 Not Found");
		return true;
	}

	$mime = pathinfo($filepath, PATHINFO_EXTENSION);

	header("Content-type: image/$mime");
	header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', strtotime("+6 months")), true);
	header("Pragma: public");
	header("Cache-Control: public");
	readfile($filepath);

	return true;
}
