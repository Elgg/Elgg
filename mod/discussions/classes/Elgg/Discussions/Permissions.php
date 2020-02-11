<?php

namespace Elgg\Discussions;

/**
 * Permission related functions
 */
class Permissions {
	
	/**
	 * Only allow global discussions if enabled in plugin settings
	 *
	 * @param \Elgg\Hook $hook 'container_logic_check', 'object'
	 *
	 * @return void|false
	 *
	 * @since 3.3
	 */
	public static function containerLogic(\Elgg\Hook $hook) {
		
		$subtype = $hook->getParam('subtype');
		if ($subtype !== 'discussion') {
			return;
		}
		
		$container = $hook->getParam('container');
		if ($container instanceof \ElggGroup) {
			return;
		}
		
		if (!elgg_get_plugin_setting('enable_global_discussions', 'discussions')) {
			return false;
		}
	}
	
	/**
	 * Make sure that discussion comments can not be written to a discussion after it has been closed
	 *
	 * @param \Elgg\Hook $hook 'container_logic_check', 'object'
	 *
	 * @return void|false
	 */
	public static function preventCommentOnClosedDiscussion(\Elgg\Hook $hook) {
		
		$discussion = $hook->getEntityParam();
		if (!$discussion instanceof \ElggDiscussion) {
			return;
		}
	
		if ($discussion->status == 'closed') {
			// do not allow new comments in closed discussions
			return false;
		}
	}
}
