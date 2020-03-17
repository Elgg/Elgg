<?php

namespace Elgg\Input;

/**
 * Adds help text to input/file
 *
 * @since 4.0
 */
class AddFileHelpTextHandler {
	
	/**
	 * Add a help text to input/file about upload limit
	 *
	 * In order to not show the help text supply 'show_upload_limit' => false to elgg_view_field()
	 *
	 * @param \Elgg\Hook $hook 'view_vars' 'elements/forms/help'
	 *
	 * @return void|array
	 */
	public function __invoke(\Elgg\Hook $hook) {
		$return = $hook->getValue();
		if (elgg_extract('input_type', $return) !== 'file') {
			return;
		}
	
		if (!elgg_extract('show_upload_limit', $return, true)) {
			return;
		}
	
		$help = elgg_extract('help', $return, '');
	
		// Get post_max_size and upload_max_filesize
		$post_max_size = elgg_get_ini_setting_in_bytes('post_max_size');
		$upload_max_filesize = elgg_get_ini_setting_in_bytes('upload_max_filesize');
	
		// Determine the correct value
		$max_upload = $upload_max_filesize > $post_max_size ? $post_max_size : $upload_max_filesize;
	
		$help .= ' ' . elgg_echo('input:file:upload_limit', [elgg_format_bytes($max_upload)]);
	
		$return['help'] = trim($help);
	
		return $return;
	}
}
