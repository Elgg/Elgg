<?php

namespace Elgg\Helpers;

/**
 * @see \Elgg\EventsServiceUnitTest
 */
class TestEventHandler {
	
	public static $invocations = [];
	
	function __invoke(\Elgg\Event $event) {
		self::$invocations[] = [
			'this' => $this,
			'args' => func_get_args(),
		];
		return false;
	}
}
