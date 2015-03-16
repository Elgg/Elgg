<?php
namespace Elgg;

/**
 * Identify a callable, even if contains an object to which you don't have a reference.
 */
class CallableMatcher {

	/**
	 * @var mixed
	 */
	private $spec;

	/**
	 * Constructor
	 *
	 * @param mixed $spec Callable or string specification
	 *   - A value like "The\ClassName->method" will match an array where element 0 is an instance of The\ClassName
	 *     and element 1 is "method".
	 *   - A value like "function /foo/bar.php" will match any anonymous function in any file whose filename ends
	 *     with "/foo/bar.php" or "\foo\bar.php".
	 *   - A value like "function /foo/bar.php:120" will additionally require that the anonymous function declaration
	 *     end on line 120.
	 *   - A value like "function /foo/bar.php:120+-5" will allow the declaration to end within lines 115 to 125.
	 *   - Anonymous functions include those created with create_function().
	 */
	public function __construct($spec) {
		$this->spec = $this->normalize($spec);
	}

	/**
	 * Does the given callable match the specification?
	 *
	 * @param callable $subject Callable to test
	 * @return bool
	 */
	public function matches($subject) {
		// We don't use the callable type-hint because it unnecessarily autoloads for static methods.

		// shortcut for common case
		if ($subject === $this->spec) {
			return true;
		}

		$subject = $this->normalize($subject);

		// try again
		if ($subject === $this->spec) {
			return true;
		}

		if (is_string($this->spec)) {
			if (false !== strpos($this->spec, '->')) {
				list ($type, $method) = explode('->', $this->spec);
				return is_array($subject)
					&& ($subject[0] instanceof $type)
					&& ($subject[1] === $method);
			}

			if (0 === strpos($this->spec, 'function ')) {
				if (is_string($subject)) {
					// allow matching use of create_function()
					if (0 !== strpos($subject, "\x00lambda_")) {
						return false;
					}
				} elseif (!$subject instanceof \Closure) {
					return false;
				}

				$spec_file = substr($this->spec, 9);
				$acceptable_line_error = 0;

				if (false === strpos($spec_file, ':')) {
					$spec_line = null;
				} else {
					list ($spec_file, $spec_line) = explode(':', $spec_file);
					if (false !== strpos($spec_line, '+-')) {
						list ($spec_line, $acceptable_line_error) = explode('+-', $spec_line);
					}
				}
				$spec_file = strtr($spec_file, '\\', '/');

				$reflection = new \ReflectionFunction($subject);
				$subject_file = $reflection->getFileName();

				if (is_string($subject)) {
					if (!preg_match('~^(.*?)\((\d+)\) \: runtime~', $subject_file, $m)) {
						// unrecognized filename
						return false;
					}

					$subject_file = $m[1];
					$subject_line = (int)$m[2];
				}

				$subject_file = strtr($subject_file, '\\', '/');

				if ($spec_file !== substr($subject_file, -strlen($spec_file))) {
					return false;
				}

				if ($spec_line === null) {
					return true;
				}

				if (!isset($subject_line)) {
					$subject_line = $reflection->getEndLine();
				}

				return (abs($spec_line - $subject_line) <= $acceptable_line_error);
			}

			if (0 === strpos($this->spec, 'instanceof ')) {
				$type = substr($this->spec, 11);

				return ($subject instanceof $type);
			}
		}

		return false;
	}

	/**
	 * Normalizes callables/specs to ease matching.
	 *
	 * @param mixed $spec Callable or specification string
	 * @return mixed
	 */
	protected function normalize($spec) {
		if (is_string($spec) && false !== strpos($spec, '::')) {
			$spec = explode('::', $spec);
		}

		if (is_string($spec)) {
			if (0 !== strpos($spec, 'function ')) {
				$spec = ltrim($spec, '\\');
			}
		} elseif (is_array($spec)) {
			if (is_string($spec[0])) {
				$spec[0] = ltrim($spec[0], '\\');
			}
		}

		return $spec;
	}
}
