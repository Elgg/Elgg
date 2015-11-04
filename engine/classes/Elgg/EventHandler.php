<?php
namespace Elgg;

/**
 * Defines an event handler
 *
 * @since 2.0.0
 */
interface EventHandler {

	/**
	 * Handle the event
	 *
	 * @param Event $event The event object
	 *
	 * @return bool if false, the handler is requesting to cancel the event
	 */
	public function __invoke(Event $event);
}
