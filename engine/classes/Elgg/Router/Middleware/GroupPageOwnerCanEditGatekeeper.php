<?php

namespace Elgg\Router\Middleware;

/**
 * Check if the current route page owner can be edited (by the current logged in user) and is an group
 *
 * @since 3.2
 */
class GroupPageOwnerCanEditGatekeeper extends PageOwnerCanEditGatekeeper {

	/**
	 * {@inheritDoc}
	 */
	protected function getType(): string {
		return 'group';
	}
}
