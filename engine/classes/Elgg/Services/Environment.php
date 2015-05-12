<?php
namespace Elgg\Services;

/**
 * Describes an object that differentiates between different instances of the same site/codebase
 */
interface Environment {

	/**
	 * Is this site instance in production?
	 *
	 * @return bool
	 */
	public function isProd();

	/**
	 * Get the site instance name. E.g. "dev", "test", "prod"
	 *
	 * @return string
	 */
	public function getName();
}
