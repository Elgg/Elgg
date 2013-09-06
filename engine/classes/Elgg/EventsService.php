<?php

/**
 * Service for Events
 *
 * @access private
 * 
 * @package    Elgg.Core
 * @subpackage Hooks
 * @since      1.9.0
 */
class Elgg_EventsService extends Elgg_HooksRegistrationService {

	/**
	 * Triggers an Elgg event.
	 * 
	 * @see elgg_trigger_event
	 * @access private
	 */
	public function trigger($event, $type, $object = null, $params = null) {
		$events = $this->getOrderedHandlers($event, $type);
		$args = array($event, $type, $object, $params);

		foreach ($events as $callback) {
			if (is_callable($callback) && (call_user_func_array($callback, $args) === false)) {
				return false;
			}
		}

		return true;
	}
}