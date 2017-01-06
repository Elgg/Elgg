<?php
namespace Elgg;

/**
 * Models a user event passed to event handlers
 *
 * @since 2.0.0
 */
interface UserEvent extends Event {

	/**
	 * Get the user subject of the event
	 *
	 * @return \ElggUser
	 */
	public function getObject();
}
