<?php
namespace Elgg;

/**
 * Allow executing scripts without $this context or local vars
 *
 * @access private
 */
final class Includer {

	/**
	 * @var array
	 * @internal
	 */
	static $_setups;

	/**
	 * Include a file with as little context as possible
	 *
	 * @param string $file File to include
	 * @return mixed
	 */
	static public function includeFile($file) {
		return include $file;
	}

	/**
	 * Require a file with as little context as possible
	 *
	 * @param string $file File to require
	 * @return mixed
	 */
	static public function requireFile($file) {
		return require $file;
	}

	/**
	 * Require a file once with as little context as possible
	 *
	 * @param string $file File to require
	 * @return mixed
	 */
	static public function requireFileOnce($file) {
		$result = require_once $file;
		if ($result instanceof \Closure) {
			self::$_setups[] = $result;
		}
		return $result;
	}
}
