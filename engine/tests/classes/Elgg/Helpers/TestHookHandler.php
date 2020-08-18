<?php

namespace Elgg\Helpers;

/**
 * @see \Elgg\PluginHooksServiceUnitTest
 */
class TestHookHandler {
	
	public static $invocations = [];
	
	function __invoke(\Elgg\Hook $hook) {
		self::$invocations[] = [
			'this' => $this,
			'args' => func_get_args(),
		];
		return $hook->getValue() + 1;
	}
}
