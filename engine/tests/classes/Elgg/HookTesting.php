<?php

namespace Elgg;

trait HookTesting {

	/**
	 * @return TestableHook
	 */
	public function registerTestingHook($name, $type, $handler, $priority = 500) {

		$hook = new TestableHook();
		$hook->name = $name;
		$hook->type = $type;
		$hook->handler = $handler;
		$hook->priority = $priority;

		return $hook->register($this);
	}
}