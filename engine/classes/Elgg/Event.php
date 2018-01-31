<?php
namespace Elgg;

use Elgg\Di\PublicContainer;

/**
 * Models an event passed to event handlers
 *
 * @since 2.0.0
 */
interface Event {

	/**
	 * Get the name of the event
	 *
	 * @return string
	 */
	public function getName();

	/**
	 * Get the type of the event object
	 *
	 * @return string
	 */
	public function getType();

	/**
	 * Get the object of the event
	 *
	 * @return mixed
	 */
	public function getObject();

	/**
	 * Get the DI container
	 *
	 * @return PublicContainer
	 */
	public function elgg();
}
