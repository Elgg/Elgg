<?php
namespace Elgg\Services;

/**
 * Models a user event passed to event handlers
 */
interface UserEvent {

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
	 * Get the user subject of the event
	 *
	 * @return \ElggUser
	 */
	public function getObject();

	/**
	 * Get the Elgg application
	 *
	 * @return \Elgg\Application
	 */
	public function elgg();
}
