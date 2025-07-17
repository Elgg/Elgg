<?php

namespace Elgg\Page;

use Elgg\Database\EntityTable;
use Elgg\EventsService;
use Elgg\Exceptions\RangeException;
use Elgg\Http\Request;
use Elgg\Invoker;
use Elgg\Router\Route;

/**
 * Holds page owner related functions
 *
 * @internal
 *
 * @since 3.1
 */
class PageOwnerService {

	protected int $page_owner_guid = 0;
	
	/**
	 * Constructor
	 *
	 * @param Request       $request      Request
	 * @param EntityTable   $entity_table Entity table
	 * @param EventsService $events       Events
	 * @param Invoker       $invoker      Invoker
	 */
	public function __construct(
		protected Request $request,
		protected EntityTable $entity_table,
		protected EventsService $events,
		protected Invoker $invoker
	) {
		$this->page_owner_guid = $this->detectPageOwnerFromRoute();
	}
	
	/**
	 * Detects page owner from route
	 *
	 * @return int detected page owner guid or void if none detected
	 */
	protected function detectPageOwnerFromRoute(): int {
		$route = $this->request->getRoute();
		if (!$route instanceof Route) {
			return 0;
		}
		
		$page_owner = $route->resolvePageOwner();
		return $page_owner instanceof \ElggEntity ? $page_owner->guid : 0;
	}
	
	/**
	 * Sets a new page owner guid
	 *
	 * @param int $guid the new page owner
	 *
	 * @return void
	 * @throws RangeException
	 */
	public function setPageOwnerGuid(int $guid = 0): void {
		if ($guid < 0) {
			throw new RangeException(__METHOD__ . ' requires a positive integer.');
		}
		
		$this->page_owner_guid = $guid;
	}
	
	/**
	 * Return the current page owner guid
	 *
	 * @return int
	 */
	public function getPageOwnerGuid(): int {
		return $this->page_owner_guid;
	}
	
	/**
	 * Returns the page owner entity
	 *
	 * @return null|\ElggEntity the current page owner or null if none.
	 */
	public function getPageOwnerEntity(): ?\ElggEntity {
		if ($this->getPageOwnerGuid() < 1) {
			return null;
		}

		return $this->entity_table->get($this->getPageOwnerGuid());
	}
}
