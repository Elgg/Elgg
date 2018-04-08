<?php

namespace Elgg\Di;

/**
 * Utility trait that can be used by public services to provide better IDE support and type-hinting
 */
trait ServiceFacade {

	/**
	 * Returns registered service name
	 * @return string
	 */
	abstract public static function name();

	/**
	 * Returns service instance
	 * @return static
	 */
	public static function instance() {
		$name = static::name();
		return elgg()->$name;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function call() {
		$arguments = func_get_args();
		$method = array_shift($arguments);

		return elgg()->call([self::instance(), $method], $arguments);
	}

}