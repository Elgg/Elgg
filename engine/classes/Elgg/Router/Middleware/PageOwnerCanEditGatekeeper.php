<?php

namespace Elgg\Router\Middleware;

use Elgg\Exceptions\Http\EntityPermissionsException;
use Elgg\Router\Route;

/**
 * Check if the current route page owner can be edited (by the current logged in user)
 *
 * @since 3.2
 */
class PageOwnerCanEditGatekeeper {

	/**
	 * Validate the current request
	 *
	 * @param \Elgg\Request $request the current request
	 *
	 * @return void
	 * @throws EntityPermissionsException
	 */
	public function __invoke(\Elgg\Request $request) {
		$this->assertAccess($request);
	}
	
	/**
	 * Validate the current request
	 *
	 * @param \Elgg\Request $request the current request
	 * @param string        $type    (optional) the required type of the page owner
	 * @param string        $subtype (optional) the required subtype of the page owner
	 *
	 * @return void
	 * @throws EntityPermissionsException
	 */
	protected function assertAccess(\Elgg\Request $request, string $type = '', string $subtype = '') {
		
		$route = $request->getHttpRequest()->getRoute();
		if (!$route instanceof Route) {
			return;
		}
		
		$page_owner = $route->resolvePageOwner();
		if (!$page_owner instanceof \ElggEntity) {
			return;
		}
		
		if (!$page_owner->canEdit()) {
			throw new EntityPermissionsException();
		}
		
		if (!empty($type) && $page_owner->getType() !== $type) {
			throw new EntityPermissionsException();
		}
		
		if (!empty($subtype) && $page_owner->getSubtype() !== $subtype) {
			throw new EntityPermissionsException();
		}
	}
}
