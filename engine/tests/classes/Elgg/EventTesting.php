<?php

namespace Elgg;

trait EventTesting {

	/**
	 * @return TestableEvent
	 */
	public function registerTestingEvent($name, $type, $handler, $priority = 500) {

		$event = new TestableEvent();
		$event->name = $name;
		$event->type = $type;
		$event->handler = $handler;
		$event->priority = $priority;

		return $event->register($this);
	}
}
