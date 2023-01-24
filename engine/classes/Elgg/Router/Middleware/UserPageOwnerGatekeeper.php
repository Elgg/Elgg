<?php

namespace Elgg\Router\Middleware;

/**
 * Check if the current route has a page owner entity and it is a user
 *
 * @since 5.0
 */
class UserPageOwnerGatekeeper extends PageOwnerGatekeeper {

	/**
	 * {@inheritDoc}
	 */
	protected function getType(): string {
		return 'user';
	}
}
