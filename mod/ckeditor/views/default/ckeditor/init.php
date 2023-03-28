<?php
/**
 * Initialize the CKEditor script
 *
 * Doing this inline enables the editor to initialize textareas loaded through ajax
 */

// someone does not want the editor enabled for this field
if (!elgg_extract('editor', $vars, true)) {
	return;
}

$id = elgg_extract('id', $vars);
if (!$id) {
	return;
}

// need to inline css as it can be used solely in popups
echo elgg_format_element('link', ['rel' => 'stylesheet', 'href' => elgg_get_simplecache_url('ckeditor/editor.css')]);

$editor_language = elgg_get_current_language();
if ($editor_language !== 'en' && elgg_view_exists("ckeditor/translations/{$editor_language}.js")) {
	elgg_require_js("ckeditor/translations/{$editor_language}");
}

?>
<script>
	require(['ckeditor/editor'], function (editor) {
		editor.init('#<?php echo $id; ?>');
	});
</script>
