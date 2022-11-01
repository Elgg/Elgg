<?php

namespace Elgg\Friends;

/**
 * Returns friends ACL name
 *
 * @since 4.0
 */
class AclNameHandler {
	
	/**
	 * Return the name of a friends ACL
	 *
	 * @param \Elgg\Event $event 'access_collection:name', 'access_collection'
	 *
	 * @return string|void
	 */
	public function __invoke(\Elgg\Event $event) {
		$access_collection = $event->getParam('access_collection');
		if (!$access_collection instanceof \ElggAccessCollection) {
			return;
		}
		
		if ($access_collection->getSubtype() !== 'friends') {
			return;
		}
		
		return elgg_echo('access:label:friends');
	}
}
