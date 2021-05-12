<?php

namespace Elgg;

use Elgg\Di\DiContainer;
use Elgg\HooksRegistrationService\Event as HrsEvent;
use Elgg\HooksRegistrationService\Hook as HrsHook;
use Elgg\Traits\Loggable;

/**
 * Helpers for providing callable-based APIs
 *
 * @copyright 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 *
 * @internal
 */
class HandlersService {
	
	use Loggable;
	
	/**
	 * Call the handler with the hook/event object
	 *
	 * @param callable $callable Callable
	 * @param mixed    $object   Event object
	 * @param array    $args     Arguments for legacy events/hooks
	 *
	 * @return array [success, result, object]
	 */
	public function call($callable, $object, $args) {
		$original = $callable;

		$callable = $this->resolveCallable($callable);
		if (!is_callable($callable)) {
			$type = is_string($object) ? $object : $object::EVENT_TYPE;
			$description = $type . " [{$args[0]}, {$args[1]}]";

			$this->getLogger()->warning("Handler for {$description} is not callable: " . $this->describeCallable($original));

			return [false, null, $object];
		}

		if (is_string($object)) {
			switch ($object) {
				case 'hook' :
					$object = new HrsHook(elgg(), $args[0], $args[1], $args[2], $args[3]);
					break;

				case 'event' :
					$object = new HrsEvent(elgg(), $args[0], $args[1], $args[2]);
					break;

				case 'middleware' :
				case 'controller' :
				case 'action' :
					$object = new Request(elgg(), $args[0]);
					break;
			}
		}

		$result = call_user_func($callable, $object);

		return [true, $result, $object];
	}

	/**
	 * Test is callback is callable
	 * Unlike is_callable(), this function also tests invokable classes
	 *
	 * @see is_callable()
	 *
	 * @param mixed $callback Callable
	 * @return bool
	 */
	public function isCallable($callback) {
		$callback = $this->resolveCallable($callback);
		return $callback && is_callable($callback);
	}

	/**
	 * Resolve a callable, possibly instantiating a class name
	 *
	 * @param callable|string $callable Callable or class name
	 *
	 * @return callable|null
	 */
	public function resolveCallable($callable) {
		if (is_callable($callable)) {
			return $callable;
		}

		if (is_string($callable)
			&& preg_match(DiContainer::CLASS_NAME_PATTERN_53, $callable)
			&& class_exists($callable)) {
			$callable = new $callable;
		}

		return is_callable($callable) ? $callable : null;
	}

	/**
	 * Get a string description of a callback
	 *
	 * E.g. "function_name", "Static::method", "(ClassName)->method", "(Closure path/to/file.php:23)"
	 *
	 * @param mixed  $callable  Callable
	 * @param string $file_root If provided, it will be removed from the beginning of file names
	 * @return string
	 */
	public function describeCallable($callable, $file_root = '') {
		if (is_string($callable)) {
			return $callable;
		}
		if (is_array($callable) && array_keys($callable) === [0, 1] && is_string($callable[1])) {
			if (is_string($callable[0])) {
				return "{$callable[0]}::{$callable[1]}";
			}
			return "(" . get_class($callable[0]) . ")->{$callable[1]}";
		}
		if ($callable instanceof \Closure) {
			$ref = new \ReflectionFunction($callable);
			$file = $ref->getFileName();
			$line = $ref->getStartLine();

			if ($file_root && 0 === strpos($file, $file_root)) {
				$file = substr($file, strlen($file_root));
			}

			return "(Closure {$file}:{$line})";
		}
		if (is_object($callable)) {
			return "(" . get_class($callable) . ")->__invoke()";
		}
		return print_r($callable, true);
	}

	/**
	 * Get a string that uniquely identifies a callback across requests (for caching)
	 *
	 * @param callable $callable Callable
	 *
	 * @return string Empty if cannot uniquely identify this callable
	 */
	public function fingerprintCallable($callable) {
		if (is_string($callable)) {
			return $callable;
		}
		if (is_array($callable)) {
			if (is_string($callable[0])) {
				return "{$callable[0]}::{$callable[1]}";
			}
			return get_class($callable[0]) . "::{$callable[1]}";
		}
		if ($callable instanceof \Closure) {
			return '';
		}
		if (is_object($callable)) {
			return get_class($callable) . "::__invoke";
		}
		// this should not happen
		return '';
	}
}
