<?php

namespace Elgg\Router\Middleware;

use Elgg\Exceptions\Http\EntityPermissionsException;

/**
 * Check if the current route page owner can be edited (by the current logged in user)
 *
 * @since 3.2
 */
class PageOwnerCanEditGatekeeper extends PageOwnerGatekeeper {
	
	/**
	 * {@inheritdoc}
	 *
	 * @throws EntityPermissionsException
	 */
	protected function assert(\Elgg\Request $request, \Elgg\Router\Route $route): void {
		
		// assert we have a logged in user
		$request->elgg()->gatekeeper->assertAuthenticatedUser();
		
		parent::assert($request, $route);
		
		if (!$this->page_owner->canEdit()) {
			throw new EntityPermissionsException();
		}
	}
}
