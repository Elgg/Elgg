<?php

namespace Elgg\Bookmarks;

use Elgg\Groups\ToolContainerLogicCheck;

/**
 * Prevent bookmarks from being created if the group tool option is disabled
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
		return 'bookmarks';
	}

	/**
	 * {@inheritDoc}
	 */
	public function getToolName(): string {
		return 'bookmarks';
	}
}
