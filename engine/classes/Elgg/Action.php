<?php

namespace Elgg;

/**
 * Models an event passed to action handlers
 *
 * @since 2.0.0
 */
interface Action {

	/**
	 * Get the name of the action
	 *
	 * @return string
	 */
	public function getName();

	/**
	 * Get the parameters from the request query
	 *
	 * @param bool $filter Sanitize input values
	 *
	 * @return array
	 */
	public function getParams($filter = true);

	/**
	 * Get an element of the params array. If the params array is not an array,
	 * the default will always be returned.
	 *
	 * @param string $key     The key of the value in the params array
	 * @param mixed  $default The value to return if missing
	 * @param bool   $filter  Sanitize input value
	 *
	 * @return mixed
	 */
	public function getParam($key, $default = null, $filter = true);

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
	 * Get the Elgg application
	 *
	 * @return \Elgg\Application
	 */
	public function elgg();
}
