<?php
namespace Elgg;

/**
 * Models an object event passed to event handlers
 *
 * @since 2.0.0
 */
interface ObjectEvent extends Event {

	/**
	 * Get the object of the event
	 *
	 * @return \ElggObject
	 */
	public function getObject();
}
