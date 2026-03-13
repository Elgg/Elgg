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

$language = elgg_get_current_language();

?>
<script type="module">
	import editor from 'ckeditor/editor';
	
	<?php if ($language !== 'en' && elgg_view_exists("ckeditor/translations/{$language}.js")) { ?>
	import translations from '<?php echo elgg_get_simplecache_url("ckeditor/translations/{$language}.js"); ?>';
	<?php } else { ?>
	let translations = undefined;	
	<?php } ?>
	
	editor.init('#<?php echo $id; ?>', undefined, translations);
</script>
