<?php

namespace Elgg\Pages;

use Elgg\Groups\ToolContainerLogicCheck;

/**
 * Prevent pages from being created if the group tool option is disabled
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
		return 'page';
	}

	/**
	 * {@inheritDoc}
	 */
	public function getToolName(): string {
		return 'pages';
	}
}
