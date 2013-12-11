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

	const OPTION_STOPPABLE = 'stoppable';
	const OPTION_DEPRECATION_MESSAGE = 'deprecation_message';
	const OPTION_DEPRECATION_VERSION = 'deprecation_version';

	/**
	 * Triggers an Elgg event.
	 * 
	 * @see elgg_trigger_event
	 * @see elgg_trigger_after_event
	 * @access private
	 */
	public function trigger($event, $type, $object = null, array $options = array()) {
		$options = array_merge(array(
			self::OPTION_STOPPABLE => true,
			self::OPTION_DEPRECATION_MESSAGE => '',
			self::OPTION_DEPRECATION_VERSION => '',
		), $options);

		$events = $this->getOrderedHandlers($event, $type);
		if ($events && $options[self::OPTION_DEPRECATION_MESSAGE]) {
			elgg_deprecated_notice(
				$options[self::OPTION_DEPRECATION_MESSAGE],
				$options[self::OPTION_DEPRECATION_VERSION],
				2
			);
		}

		$args = array($event, $type, $object);

		foreach ($events as $callback) {
			if (!is_callable($callback)) {
				// @todo should this produce a warning?
				continue;
			}

			$return = call_user_func_array($callback, $args);
			if (!empty($options[self::OPTION_STOPPABLE]) && ($return === false)) {
				return false;
			}
		}

		return true;
	}
}