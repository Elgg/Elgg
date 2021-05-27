<?php

namespace Elgg\StaticConfig;

/**
 * Event callbacks
 */
class EventCallback {
	
	/**
	 * Called on a low (100) priority
	 *
	 * @param \Elgg\Event $event 'do', 'something'
	 *
	 * @return void
	 */
	public function __invoke(\Elgg\Event $event) {
		
	}
	
	/**
	 * Called on a high (900) priority
	 *
	 * @param \Elgg\Event $event 'do', 'something'
	 *
	 * @return void
	 */
	public static function highPriority(\Elgg\Event $event)  {
		
	}
}
