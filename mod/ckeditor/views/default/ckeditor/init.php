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

$editor_language = elgg_get_current_language();

?>
<script>
	<?php
	if ($editor_language !== 'en' && elgg_view_exists("ckeditor/translations/{$editor_language}.js")) {
		$simple_cache_url = elgg_get_simplecache_url("ckeditor/translations/{$editor_language}.js");
		echo "import('{$simple_cache_url}');" . PHP_EOL;
	}
	?>
	import('ckeditor/editor').then((editor) => {
		editor.default.init('#<?php echo $id; ?>');
	});
</script>
