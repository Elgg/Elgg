<?php

namespace Elgg\Router\Middleware;

/**
 * Check if the current route page owner can be edited (by the current logged in user) and is an user
 *
 * @since 3.2
 */
class GroupPageOwnerCanEditGatekeeper extends PageOwnerCanEditGatekeeper {

	/**
	 * {@inheritDoc}
	 * @see \Elgg\Router\Middleware\PageOwnerCanEditGatekeeper::__invoke()
	 */
	public function __invoke(\Elgg\Request $request) {
		$this->assertAccess($request, 'group');
	}
}
