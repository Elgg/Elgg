<?php
namespace Elgg;
use Elgg\Debug\Inspector;

/**
 * Service for Events
 *
 * @access private
 * 
 * @package    Elgg.Core
 * @subpackage Hooks
 * @since      1.9.0
 */
class EventsService extends \Elgg\HooksRegistrationService {

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

		$events = $this->hasHandler($event, $type);
		if ($events && $options[self::OPTION_DEPRECATION_MESSAGE]) {
			elgg_deprecated_notice(
				$options[self::OPTION_DEPRECATION_MESSAGE],
				$options[self::OPTION_DEPRECATION_VERSION],
				2
			);
		}

		$events = $this->getOrderedHandlers($event, $type);
		$args = array($event, $type, $object);

		foreach ($events as $callback) {
			if (!is_callable($callback)) {
				if ($this->logger) {
					$inspector = new Inspector();
					$this->logger->warn("handler for event [$event, $type] is not callable: "
										. $inspector->describeCallable($callback));
				}
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
