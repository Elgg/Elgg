<?php

namespace Elgg\StaticConfig;

/**
 * Plugin hook callbacks
 */
class HookCallback {
	
	/**
	 * Called on a low (100) priority
	 *
	 * @param \Elgg\Event $event 'prevent', 'something'
	 *
	 * @return mixed
	 */
	public function __invoke(\Elgg\Event $event) {
		$result = $event->getValue();
		
		$result[] = __METHOD__;
		
		return $result;
	}
	
	/**
	 * Called on a high (900) priority
	 *
	 * @param \Elgg\Event $event 'prevent', 'something'
	 *
	 * @return mixed
	 */
	public static function highPriority(\Elgg\Event $event)  {
		$result = $event->getValue();
		
		$result[] = __METHOD__;
		
		return $result;
	}
}
