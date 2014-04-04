<?php
/**
 * Elgg 1.9.0-dev upgrade 2014032200
 * tinymce_to_ck
 *
 * Activates CKEditor if TinyMCE is active.
 * Deactivates TinyMCE.
 */

$tiny = elgg_get_plugin_from_id('tinymce');

if ($tiny instanceof ElggPlugin && $tiny->isActive()) {
	$ck = elgg_get_plugin_from_id('ckeditor');
	if ($ck instanceof ElggPlugin) {
		$ck->activate();
	}

	$tiny->deactivate();
}