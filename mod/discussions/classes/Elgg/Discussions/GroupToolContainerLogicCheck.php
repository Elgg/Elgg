<?php

namespace Elgg\Discussions;

use Elgg\Groups\ToolContainerLogicCheck;

/**
 * Prevent discussions from being created if the group tool option is disabled
 */
class GroupToolContainerLogicCheck extends ToolContainerLogicCheck {

	/**
	 * {@inheritDoc}
	 */
	public function getContentType(): string {
		return 'object';
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function getContentSubtype(): string {
		return 'discussion';
	}

	/**
	 * {@inheritDoc}
	 */
	public function getToolName(): string {
		return 'forum';
	}
}
