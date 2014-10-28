<?php

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * Use the elgg_* versions instead.
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage Hooks
 * @since      1.9.0
 */
abstract class Elgg_HooksRegistrationService {

	private $handlers = array();

	/**
	 * @var Elgg_Logger
	 */
	protected $logger;

	/**
	 * Set a logger instance, e.g. for reporting uncallable handlers
	 *
	 * @param Elgg_Logger $logger The logger
	 * @return self
	 */
	public function setLogger(Elgg_Logger $logger = null) {
		$this->logger = $logger;
		return $this;
	}

	/**
	 * Registers a handler.
	 *
	 * @warning This doesn't check if a callback is valid to be called, only if it is in the
	 *          correct format as a callable.
	 *
	 * @access private
	 */
	public function registerHandler($name, $type, $callback, $priority = 500) {
		if (empty($name) || empty($type) || !is_callable($callback, true)) {
			return false;
		}

		if (!isset($this->handlers[$name])) {
			$this->handlers[$name] = array();
		}

		if (!isset($this->handlers[$name][$type])) {
			$this->handlers[$name][$type] = array();
		}

		// Priority cannot be lower than 0
		$priority = max((int) $priority, 0);

		while (isset($this->handlers[$name][$type][$priority])) {
			$priority++;
		}

		$this->handlers[$name][$type][$priority] = $callback;
		ksort($this->handlers[$name][$type]);

		return true;
	}

	/**
	 * Unregister a handler
	 *
	 * @param string   $name
	 * @param string   $type
	 * @param callable $callback
	 *
	 * @return bool
	 * @access private
	 */
	public function unregisterHandler($name, $type, $callback) {
		if (isset($this->handlers[$name]) && isset($this->handlers[$name][$type])) {
			foreach ($this->handlers[$name][$type] as $key => $name_callback) {
				if ($name_callback == $callback) {
					unset($this->handlers[$name][$type][$key]);
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Returns all registered handlers as array(
	 * $name => array(
	 *     $type => array(
	 *         $priority => callback, ...
	 *     )
	 * )
	 *
	 * @access private
	 * @return array
	 */
	public function getAllHandlers() {
		return $this->handlers;
	}

	/**
	 * Does the hook have a handler?
	 *
	 * @param string $name The name of the hook
	 * @param string $type The type of the hook
	 * @return boolean
	 */
	public function hasHandler($name, $type) {
		return isset($this->handlers[$name][$type]);
	}

	/**
	 * Returns an ordered array of handlers registered for $name and $type.
	 *
	 * @param string $name The name of the hook
	 * @param string $type The type of the hook
	 * @return array
	 * @see Elgg_HooksRegistrationService::getAllHandlers()
	 */
	protected function getOrderedHandlers($name, $type) {
		$handlers = array();

		if (isset($this->handlers[$name][$type])) {
			if ($name != 'all' && $type != 'all') {
				$handlers = array_merge($handlers, array_values($this->handlers[$name][$type]));
			}
		}
		if (isset($this->handlers['all'][$type])) {
			if ($type != 'all') {
				$handlers = array_merge($handlers, array_values($this->handlers['all'][$type]));
			}
		}
		if (isset($this->handlers[$name]['all'])) {
			if ($name != 'all') {
				$handlers = array_merge($handlers, array_values($this->handlers[$name]['all']));
			}
		}
		if (isset($this->handlers['all']['all'])) {
			$handlers = array_merge($handlers, array_values($this->handlers['all']['all']));
		}

		return $handlers;
	}

	/**
	 * Get a string description of a callback
	 *
	 * E.g. "function_name", "Static::method", "(ClassName)->method", "(Closure path/to/file.php:23)"
	 *
	 * @param mixed $callable The callable value to describe
	 * @return string
	 */
	protected function describeCallable($callable) {
		if (is_string($callable)) {
			return $callable;
		}
		if (is_array($callable) && array_keys($callable) === array(0, 1) && is_string($callable[1])) {
			if (is_string($callable[0])) {
				return "{$callable[0]}::{$callable[1]}";
			}
			return "(" . get_class($callable[0]) . ")->{$callable[1]}";
		}
		if ($callable instanceof Closure) {
			$ref = new ReflectionFunction($callable);
			$file = $ref->getFileName();
			$line = $ref->getStartLine();
			return "(Closure {$file}:{$line})";
		}
		if (is_object($callable)) {
			return "(" . get_class($callable) . ")->__invoke()";
		}
		return "(unknown)";
	}
}