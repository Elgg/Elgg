<?php

namespace Elgg\Developers;

/**
 * Track events
 *
 * @since 4.0
 */
class HandlerLogger {
	
	/**
	 * Track an Elgg event
	 *
	 * @param \Elgg\Event $event 'all', 'all'
	 *
	 * @return void
	 */
	public static function trackEvent(\Elgg\Event $event) : void {
		$handlers = elgg()->events->getOrderedHandlers($event->getName(), $event->getType());
		if (count($handlers) === 1) {
			// only this handler
			return;
		}
		
		self::track($event->getName(), $event->getType());
	}
	
	/**
	 * Track the actual event
	 *
	 * @param string $name name of the event
	 * @param string $type type of the event
	 *
	 * @return void
	 */
	protected static function track(string $name, string $type) : void {
		// filter out some very common events
		$filter = [
			'classes',
			'debug',
			'display',
			'log',
			'validate',
			'view',
			'view_vars',
			'sanitize',
		];
		if (in_array($name, $filter)) {
			return;
		}
		
		// 0 => this function
		// 1 => calling function in this class
		// 2 => call_user_func_array
		// 3 => event class call
		// 4 => event class trigger
		$stack = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
		$event_type = 'Event';
		
		$trigger_functions = [
			'elgg_trigger_event',
			'elgg_trigger_event_results',
			'elgg_trigger_after_event',
			'elgg_trigger_before_event',
			'triggerResults',
			'triggerResultsSequence',
			'triggerSequence',
			'triggerBefore',
			'triggerAfter',
		];
		$index = 5;
		while (in_array($stack[$index]['function'], $trigger_functions)) {
			$index++;
		}
		
		if (isset($stack[$index]['class'])) {
			$function = $stack[$index]['class'] . '::' . $stack[$index]['function'] . '()';
		} else {
			$function = $stack[$index]['function'] . '()';
		}
		
		// when loading a PHP file report the source location
		if ($function == 'require_once()' || $function == 'include_once()') {
			$function = $stack[$index]['file'];
		}
		
		// add line number
		if (isset($stack[$index - 1]['line'])) {
			$function .= " (line: {$stack[$index - 1]['line']})";
		}
		
		$msg = elgg_echo('developers:event_log_msg', [
			$event_type,
			$name,
			$type,
			$function,
		]);
		elgg_dump($msg);
		
		unset($stack);
	}
}
