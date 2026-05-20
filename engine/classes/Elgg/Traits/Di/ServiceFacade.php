<?php

namespace Elgg\Traits\Di;

use DI\NotFoundException;

/**
 * Utility trait that can be used by public services to provide better IDE support and type-hinting
 */
trait ServiceFacade {

	/**
	 * Returns registered service name
	 *
	 * @return string
	 */
	abstract public static function name(): string;

	/**
	 * Returns service instance
	 *
	 * @return static
	 * @throws NotFoundException
	 */
	final public static function instance(): static {
		$name = static::name();
		
		return elgg()->{$name} ?? throw new NotFoundException("No entry or class found for '{$name}'");
	}

	/**
	 * Call a method in this DI service
	 *
	 * @return mixed
	 */
	final public static function call(): mixed {
		$arguments = func_get_args();
		$method = array_shift($arguments);

		return elgg()->call([self::instance(), $method], $arguments);
	}
}
