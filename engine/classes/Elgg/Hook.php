<?php
namespace Elgg;

use Elgg\Di\PublicContainer;

/**
 * Models an event passed to hook handlers
 *
 * @since 2.0.0
 */
interface Hook {

	/**
	 * Get the name of the hook
	 *
	 * @return string
	 */
	public function getName();

	/**
	 * Get the type of the hook
	 *
	 * @return string
	 */
	public function getType();

	/**
	 * Get the current value of the hook
	 *
	 * @return mixed
	 */
	public function getValue();

	/**
	 * Get the parameters passed to the trigger call
	 *
	 * @return mixed
	 */
	public function getParams();

	/**
	 * Get an element of the params array. If the params array is not an array,
	 * the default will always be returned.
	 *
	 * @param string $key     The key of the value in the params array
	 * @param mixed  $default The value to return if missing
	 *
	 * @return mixed
	 */
	public function getParam($key, $default = null);

	/**
	 * Gets the "entity" key from the params if it holds an Elgg entity
	 *
	 * @return \ElggEntity|null
	 */
	public function getEntityParam();

	/**
	 * Gets the "user" key from the params if it holds an Elgg user
	 *
	 * @return \ElggUser|null
	 */
	public function getUserParam();

	/**
	 * Get the DI container
	 *
	 * @return PublicContainer
	 */
	public function elgg();
}
