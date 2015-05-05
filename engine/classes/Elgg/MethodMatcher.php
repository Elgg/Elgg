<?php
namespace Elgg;

/**
 * Identify a static/dynamic method callable, even if contains an object to which you don't have a reference.
 *
 * @access private
 * @since 1.11.0
 */
class MethodMatcher {

	/**
	 * @var string
	 */
	private $type;

	/**
	 * @var string
	 */
	private $method;

	/**
	 * Constructor
	 *
	 * @param string $type   Class to match
	 * @param string $method Method name to match
	 */
	public function __construct($type, $method) {
		$this->type = strtolower(ltrim($type, '\\'));
		$this->method = strtolower($method);
	}

	/**
	 * Does the given callable match the specification?
	 *
	 * @param callable $subject Callable to test
	 * @return bool
	 */
	public function matches($subject) {
		// We don't use the callable type-hint because it unnecessarily autoloads for static methods.

		if (is_string($subject)) {
			if (false === strpos($subject, '::')) {
				return false;
			}

			$subject = explode('::', $subject, 2);
		}

		if (!is_array($subject) || empty($subject[0]) || empty($subject[1]) || !is_string($subject[1])) {
			return false;
		}

		if (strtolower($subject[1]) !== $this->method) {
			return false;
		}

		if (is_object($subject[0])) {
			$subject[0] = get_class($subject[0]);
		}

		if (!is_string($subject[0])) {
			return false;
		}

		return (strtolower(ltrim($subject[0], '\\')) === $this->type);
	}


}
