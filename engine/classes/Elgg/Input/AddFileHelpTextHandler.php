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
	 * @param \Elgg\Event $event 'view_vars' 'elements/forms/help'
	 *
	 * @return void|array
	 */
	public function __invoke(\Elgg\Event $event) {
		$return = $event->getValue();
		if (elgg_extract('input_type', $return) !== 'file') {
			return;
		}
		
		$max_size = elgg_extract('max_size', $return, true);
		if (!elgg_extract('show_upload_limit', $return, true) || $max_size === false) {
			return;
		}
		
		$help = elgg_extract('help', $return, '');
		
		// Get post_max_size and upload_max_filesize
		if ($max_size === true) {
			// Determine the correct value
			$post_max_size = elgg_get_ini_setting_in_bytes('post_max_size');
			$upload_max_filesize = elgg_get_ini_setting_in_bytes('upload_max_filesize');
			
			$max_size = min($upload_max_filesize, $post_max_size);
		}
		
		$help .= ' ' . elgg_echo('input:file:upload_limit', [elgg_format_bytes((int) $max_size)]);
		
		$return['help'] = trim($help);
		
		return $return;
	}
}
