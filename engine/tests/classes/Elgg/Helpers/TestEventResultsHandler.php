<?php

namespace Elgg\Helpers;

/**
 * @see \Elgg\EventsServiceResultsUnitTest
 */
class TestEventResultsHandler {
	
	public static $invocations = [];
	
	function __invoke(\Elgg\Event $event) {
		self::$invocations[] = [
			'this' => $this,
			'args' => func_get_args(),
		];
		return $event->getValue() + 1;
	}
}
