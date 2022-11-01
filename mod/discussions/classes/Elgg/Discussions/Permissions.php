<?php

namespace Elgg\Discussions;

/**
 * Permission related functions
 */
class Permissions {
	
	/**
	 * Only allow global discussions if enabled in plugin settings
	 *
	 * @param \Elgg\Event $event 'container_logic_check', 'object'
	 *
	 * @return void|false
	 *
	 * @since 3.3
	 */
	public static function containerLogic(\Elgg\Event $event) {
		
		$subtype = $event->getParam('subtype');
		if ($subtype !== 'discussion') {
			return;
		}
		
		$container = $event->getParam('container');
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
	 * @param \Elgg\Event $event 'container_logic_check', 'object'
	 *
	 * @return void|false
	 */
	public static function preventCommentOnClosedDiscussion(\Elgg\Event $event) {
		
		$discussion = $event->getEntityParam();
		if (!$discussion instanceof \ElggDiscussion) {
			return;
		}
	
		if ($discussion->status == 'closed') {
			// do not allow new comments in closed discussions
			return false;
		}
	}
}
