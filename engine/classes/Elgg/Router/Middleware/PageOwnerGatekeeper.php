<?php

namespace Elgg\Router\Middleware;

use Elgg\Exceptions\Http\EntityNotFoundException;
use Elgg\Router\Route;

/**
 * Check if the current route has a page owner entity
 *
 * @since 5.0
 */
class PageOwnerGatekeeper {
	
	protected \ElggEntity $page_owner;

	/**
	 * Validate the current request
	 *
	 * @param \Elgg\Request $request the current request
	 *
	 * @return void
	 * @throws EntityNotFoundException
	 */
	public function __invoke(\Elgg\Request $request) {
		
		$route = $request->getHttpRequest()->getRoute();
		if (!$route instanceof Route) {
			return;
		}
		
		// force detection of page owner for legacy routes
		$route->setDefault('_detect_page_owner', true);
		
		$page_owner = $route->resolvePageOwner();
		if (!$page_owner instanceof \ElggEntity) {
			throw new EntityNotFoundException();
		}
		
		$this->page_owner = $page_owner;
		
		$this->assert($request, $route);
	}
	
	/**
	 * Performs assertions
	 *
	 * @param \Elgg\Request      $request the current request
	 * @param \Elgg\Router\Route $route   the current route
	 *
	 * @return void
	 */
	protected function assert(\Elgg\Request $request, Route $route): void {
		$this->assertPageOwner();
	}
	
	/**
	 * Asserts the pageowner
	 *
	 * @return void
	 * @throws EntityNotFoundException
	 */
	protected function assertPageOwner(): void {
		if (!empty($this->getType()) && $this->page_owner->getType() !== $this->getType()) {
			throw new EntityNotFoundException();
		}
		
		if (!empty($this->getSubtype()) && $this->page_owner->getSubtype() !== $this->getType()) {
			throw new EntityNotFoundException();
		}
		
		_elgg_services()->gatekeeper->assertAccessibleEntity($this->page_owner);
	}
	
	/**
	 * Returns the type of the page owner to validate
	 *
	 * @return string
	 */
	protected function getType(): string {
		return '';
	}
	
	/**
	 * Returns the subtype of the page owner to validate
	 *
	 * @return string
	 */
	protected function getSubtype(): string {
		return '';
	}
}
