<?php

namespace Elgg\Groups;

/**
 * Check if content can be created in a group based on the group tool option
 *
 * @since 3.3
 */
abstract class ToolContainerLogicCheck {
	
	/**
	 * Listen to the container logic check hook
	 *
	 * @param \Elgg\Hook $hook 'container_logic_check', <type>
	 *
	 * @return void|false
	 */
	public function __invoke(\Elgg\Hook $hook) {
		
		if ($hook->getType() !== $this->getContentType()) {
			// not the correct hook registration
			return;
		}
		
		$container = $hook->getParam('container');
		if (!$container instanceof \ElggGroup) {
			return;
		}
		
		if ($hook->getParam('subtype') !== $this->getContentSubtype()) {
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
