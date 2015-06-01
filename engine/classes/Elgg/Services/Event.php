<?php
namespace Elgg\Services;

/**
 * Models an event passed to event handlers
 */
interface Event {

	/**
	 * Get the name
	 *
	 * @return string
	 */
	public function getName();

	/**
	 * Get the type of the object
	 *
	 * @return string
	 */
	public function getType();

	/**
	 * Get the event object
	 *
	 * @return mixed
	 */
	public function getObject();

	/**
	 * Get the Elgg application
	 *
	 * @return \Elgg\Application
	 */
	public function elgg();
}
