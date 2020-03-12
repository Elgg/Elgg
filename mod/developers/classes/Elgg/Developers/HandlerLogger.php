<?php

namespace Elgg\Developers;

/**
 * Track events and hooks
 *
 * @since 4.0
 * @internal
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
		
		self::track($event->getName(), $event->getType(), $event);
	}
	
	/**
	 * Track an Elgg hook
	 *
	 * @param \Elgg\Hook $hook 'all', 'all'
	 *
	 * @return void
	 */
	public static function trackHook(\Elgg\Hook $hook) : void {
		$handlers = elgg()->hooks->getOrderedHandlers($hook->getName(), $hook->getType());
		if (count($handlers) === 1) {
			// only this handler
			return;
		}
		
		self::track($hook->getName(), $hook->getType(), $hook);
	}
	
	/**
	 * Track the actual event / plugin hook
	 *
	 * @param string $name   name of the event / hook
	 * @param string $type   type of the event / hook
	 * @param mixed  $source source event / hook
	 *
	 * @return void
	 */
	protected static function track(string $name, string $type, $source) : void {
		// filter out some very common events
		$filter = [
			'classes',
			'debug',
			'display',
			'log',
			'validate',
			'view',
			'view_vars',
		];
		if (in_array($name, $filter)) {
			return;
		}
		
		// 0 => this function
		// 1 => calling function in this class
		// 2 => call_user_func_array
		// 3 => hook class call
		// 4 => hook class trigger
		$stack = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
		if ($source instanceof \Elgg\Event) {
			$event_type = 'Event';
		} else {
			$event_type = 'Hook';
		}
		
		$trigger_functions = [
			'elgg_trigger_event',
			'elgg_trigger_plugin_hook',
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
