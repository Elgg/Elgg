<?php
namespace Elgg\Services;

/**
 * Models an object event passed to event handlers
 */
interface ObjectEvent {

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
	 * Get the object of the event
	 *
	 * @return \ElggObject
	 */
	public function getObject();

	/**
	 * Get the Elgg application
	 *
	 * @return \Elgg\Application
	 */
	public function elgg();
}
