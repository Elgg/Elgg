<?php

/**
 * Service for Events
 *
 * @since 1.9.0
 * @access private
 */
class ElggEventService extends ElggPluginAPIService {

	/**
	 * Triggers an Elgg event.
	 * 
	 * @see elgg_trigger_event
	 * @since 1.9.0
	 * @access private
	 */
	function trigger($event, $type, $object = null) {
		$events = $this->getOrderedHandlers($event, $type);
		$args = array($event, $type, $object);

		foreach ($events as $callback) {
			if (is_callable($callback) && (call_user_func_array($callback, $args) === false)) {
				return false;
			}
		}

		return true;
	}
}