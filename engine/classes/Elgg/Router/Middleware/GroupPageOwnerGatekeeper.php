<?php

namespace Elgg\Router\Middleware;

/**
 * Check if the current route has a page owner entity and it is a group
 *
 * @since 5.0
 */
class GroupPageOwnerGatekeeper extends PageOwnerGatekeeper {
	
	/**
	 * {@inheritDoc}
	 */
	protected function getType(): string {
		return 'group';
	}
}
