<?php

namespace Elgg\Blog;

/**
 * Change permissions related to blogs
 *
 * @since 7.0
 */
class Permissions {
	
	/**
	 * Prevent comments on unpublished blogs or when comments are disabled
	 *
	 * @param \Elgg\Event $event 'container_logic_check', 'object'
	 *
	 * @return bool|null
	 */
	public static function preventCommentsWhenDisabledOnBlog(\Elgg\Event $event): ?bool {
		if ($event->getParam('subtype') !== 'comment') {
			return null;
		}
		
		$container = $event->getParam('container');
		if (!$container instanceof \ElggBlog) {
			return null;
		}
		
		if ($container->comments_on === 'Off' || $container->status !== 'published') {
			return false;
		}
		
		return null;
	}
}
