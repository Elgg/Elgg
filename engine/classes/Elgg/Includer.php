<?php

namespace Elgg;

/**
 * Allow executing scripts without $this context or local vars
 *
 * @internal
 */
final class Includer {

	/**
	 * Include a file with as little context as possible
	 *
	 * @param string $file File to include
	 * @return mixed
	 */
	public static function includeFile($file) {
		return include $file;
	}

	/**
	 * Require a file with as little context as possible
	 *
	 * @param string $file File to require
	 * @return mixed
	 */
	public static function requireFile($file) {
		return require $file;
	}

	/**
	 * Require a file once with as little context as possible
	 *
	 * @param string $file File to require
	 * @return mixed
	 */
	public static function requireFileOnce($file) {
		return require_once $file;
	}
}
