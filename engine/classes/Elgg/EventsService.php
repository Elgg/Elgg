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
	 * @see elgg_trigger_after_event
	 * @access private
	 */
	public function trigger($event, $type, $object = null, $stoppable = true) {
		$events = $this->getOrderedHandlers($event, $type);
		$args = array($event, $type, $object);

		foreach ($events as $callback) {
			if (!is_callable($callback)) {
				// @todo should this produce a warning?
				continue;
			}

			$return = call_user_func_array($callback, $args);
			if ($stoppable && ($return === false)) {
				return false;
			}
		}

		return true;
	}
}