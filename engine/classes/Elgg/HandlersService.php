<?php
namespace Elgg;

use Elgg\Di\DiContainer;
use Elgg\HooksRegistrationService\Event;
use Elgg\HooksRegistrationService\Hook;
use Elgg\Request;

/**
 * Helpers for providing callable-based APIs
 *
 * getType() uses code from Zend\Code\Reflection\ParameterReflection::detectType.
 * https://github.com/zendframework/zend-code/blob/master/src/Reflection/ParameterReflection.php
 *
 * @copyright 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 *
 * @access private
 */
class HandlersService {

	/**
	 * Call the handler with the hook/event object
	 *
	 * @param callable          $callable Callable
	 * @param string|Hook|Event $object   Event object
	 * @param array             $args     Arguments for legacy events/hooks
	 *
	 * @return array [success, result, object]
	 */
	public function call($callable, $object, $args) {
		$original = $callable;

		$callable = $this->resolveCallable($callable);
		if (!is_callable($callable)) {
			$type = is_string($object) ? $object : $object::EVENT_TYPE;
			$description = $type . " [{$args[0]}, {$args[1]}]";
			$msg = "Handler for $description is not callable: " . $this->describeCallable($original);
			_elgg_services()->logger->warning($msg);

			return [false, null, $object];
		}

		$use_object = $this->acceptsObject($callable);
		if ($use_object) {
			if (is_string($object)) {
				switch ($object) {
					case 'hook' :
						$object = new Hook(elgg(), $args[0], $args[1], $args[2], $args[3]);
						break;

					case 'event' :
						$object = new Event(elgg(), $args[0], $args[1], $args[2]);
						break;

					case 'middleware' :
					case 'controller' :
					case 'action' :
						$object = new Request(elgg(), $args[0]);
						break;
				}
			}

			$result = call_user_func($callable, $object);
		} else {
			// legacy arguments
			$result = call_user_func_array($callable, $args);
		}

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
	 * Get the reflection interface for a callable
	 *
	 * @param callable $callable Callable
	 *
	 * @return \ReflectionFunctionAbstract
	 */
	public function getReflector($callable) {
		if (is_string($callable)) {
			if (false !== strpos($callable, '::')) {
				$callable = explode('::', $callable);
			} else {
				// function
				return new \ReflectionFunction($callable);
			}
		}
		if (is_array($callable)) {
			return new \ReflectionMethod($callable[0], $callable[1]);
		}
		if ($callable instanceof \Closure) {
			return new \ReflectionFunction($callable);
		}
		if (is_object($callable)) {
			return new \ReflectionMethod($callable, '__invoke');
		}

		throw new \InvalidArgumentException('invalid $callable');
	}

	/**
	 * Resolve a callable, possibly instantiating a class name
	 *
	 * @param callable|string $callable Callable or class name
	 *
	 * @return callable|null
	 */
	private function resolveCallable($callable) {
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
	 * Should we pass this callable a Hook/Event object instead of the 2.0 arguments?
	 *
	 * @param callable $callable Callable
	 *
	 * @return bool
	 */
	private function acceptsObject($callable) {
		// note: caching string callables didn't help any
		$type = (string) $this->getParamTypeForCallable($callable);
		if (0 === strpos($type, 'Elgg\\')) {
			// probably right. We can just assume and let PHP handle it
			return true;
		}

		return false;
	}

	/**
	 * Get the type for a parameter of a callable
	 *
	 * @param callable $callable Callable
	 * @param int      $index    Index of argument
	 *
	 * @return null|string Empty string = no type, null = no parameter
	 */
	public function getParamTypeForCallable($callable, $index = 0) {
		$params = $this->getReflector($callable)->getParameters();
		if (!isset($params[$index])) {
			return null;
		}

		return $this->getType($params[$index]);
	}

	/**
	 * Get the type of a parameter
	 *
	 * @param \ReflectionParameter $param Parameter
	 *
	 * @return string
	 */
	public function getType(\ReflectionParameter $param) {
		// @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
		// @license   http://framework.zend.com/license/new-bsd New BSD License

		if (method_exists($param, 'getType')
				&& ($type = $param->getType())
				&& $type->isBuiltin()) {
			return (string) $type;
		}

		// can be dropped when dropping PHP7 support:
		if ($param->isArray()) {
			return 'array';
		}

		// can be dropped when dropping PHP7 support:
		if ($param->isCallable()) {
			return 'callable';
		}

		// ReflectionParameter::__toString() doesn't require loading class
		if (preg_match('~\[\s\<\w+?>\s([\S]+)~s', (string) $param, $m)) {
			if ($m[1][0] !== '$') {
				return $m[1];
			}
		}

		return '';
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
