<?php

namespace Elgg\Groups;

/**
 * Check if content can be created in a group based on the group tool option
 *
 * @since 3.3
 */
abstract class ToolContainerLogicCheck {
	
	/**
	 * Listen to the container logic check event
	 *
	 * @param \Elgg\Event $event 'container_logic_check', <type>
	 *
	 * @return void|false
	 */
	public function __invoke(\Elgg\Event $event) {
		
		if ($event->getType() !== $this->getContentType()) {
			// not the correct event registration
			return;
		}
		
		$container = $event->getParam('container');
		if (!$container instanceof \ElggGroup) {
			return;
		}
		
		if ($event->getParam('subtype') !== $this->getContentSubtype()) {
			return;
		}
		
		if ($container->isToolEnabled($this->getToolName())) {
			return;
		}
		
		return false;
	}
	
	/**
	 * Returns the type of the content affected by the group tool option
	 *
	 * @return string
	 */
	abstract public function getContentType(): string;
	
	/**
	 * Returns the subtype affected by the group tool option
	 *
	 * @return string
	 */
	abstract public function getContentSubtype(): string;
	
	/**
	 * Returns the name of the group tool option to check if is enabled
	 *
	 * @return string
	 */
	abstract public function getToolName(): string;
}
