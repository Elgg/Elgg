<?php
/**
 * Prompt the user to install a tinymce language after activating
 */

if (elgg_get_config('language') != tinymce_get_site_language()) {
	$message = elgg_echo('tinymce:lang_notice', array(
		elgg_echo(elgg_get_config('language')),
		"http://www.tinymce.com/i18n/index.php?ctrl=lang&act=download",
		elgg_get_plugins_path() . "tinymce/vendor/tinymce/jscripts/tiny_mce/",
		elgg_add_action_tokens_to_url(elgg_normalize_url('action/admin/site/flush_cache')),
	));
	elgg_add_admin_notice('tinymce_admin_notice_no_lang', $message);
}
