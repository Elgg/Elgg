<?php

namespace Elgg\File;

use Elgg\Groups\ToolContainerLogicCheck;

/**
 * Prevent files from being created if the group tool option is disabled
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
		return 'file';
	}

	/**
	 * {@inheritDoc}
	 */
	public function getToolName(): string {
		return 'file';
	}
}
