<?php
namespace Elgg;

use Elgg\Di\DiContainer;

/**
 * Helpers for providing callable-based APIs
 *
 * @access private
 */
class HandlersService {

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

			// @todo Eventually a more advanced DIC could auto-inject dependencies
			$callable = new $callable;
		}
		return is_callable($callable) ? $callable : null;
	}

	/**
	 * Get a string description of a callback
	 *
	 * E.g. "function_name", "Static::method", "(ClassName)->method", "(Closure path/to/file.php:23)"
	 *
	 * @param mixed  $callable  The callable value to describe
	 * @param string $file_root if provided, it will be removed from the beginning of file names
	 * @return string
	 */
	public function describeCallable($callable, $file_root = '') {
		if (is_string($callable)) {
			return $callable;
		}
		if (is_array($callable) && array_keys($callable) === array(0, 1) && is_string($callable[1])) {
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
}
