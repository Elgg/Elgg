<?php

namespace Elgg\Page;

use Elgg\Database\EntityTable;
use Elgg\Database\UsersTable;
use Elgg\Exceptions\InvalidArgumentException;
use Elgg\Http\Request;
use Elgg\Invoker;
use Elgg\PluginHooksService;
use Elgg\Router\Route;

/**
 * Holds page owner related functions
 *
 * @internal
 *
 * @since 3.1
 */
class PageOwnerService {

	/**
	 * @var Request
	 */
	protected $request;

	/**
	 * @var EntityTable
	 */
	protected $entity_table;

	/**
	 * @var UsersTable
	 */
	protected $users_table;

	/**
	 * @var PluginHooksService
	 */
	protected $hooks;

	/**
	 * @var Invoker
	 */
	protected $invoker;

	/**
	 * @var int
	 */
	protected $page_owner_guid = 0;
	
	/**
	 * Constructor
	 *
	 * @param Request            $request      Request
	 * @param EntityTable        $entity_table Entity table
	 * @param PluginHooksService $hooks        Hooks
	 * @param UsersTable         $users_table  Users table
	 * @param Invoker            $invoker      Invoker
	 */
	public function __construct(
			Request $request,
			EntityTable $entity_table,
			PluginHooksService $hooks,
			UsersTable $users_table,
			Invoker $invoker
	) {
		$this->request = $request;
		$this->entity_table = $entity_table;
		$this->hooks = $hooks;
		$this->users_table = $users_table;
		$this->invoker = $invoker;
		
		$this->initializePageOwner();
	}
	
	/**
	 * Initialize the page owner by trying to autodetect or let a hook to provide the page owner
	 *
	 * @return void
	 */
	protected function initializePageOwner() {
		
		$page_owner_guid = $this->detectPageOwnerFromRoute();
		if (!empty($page_owner_guid)) {
			$this->page_owner_guid = $page_owner_guid;
			return;
		}
		
		$page_owner_guid = $this->detectLegacyPageOwner();
		if (!empty($page_owner_guid)) {
			$this->page_owner_guid = $page_owner_guid;
			return;
		}
		
		$this->page_owner_guid = (int) $this->hooks->trigger('page_owner', 'system', null, $this->page_owner_guid);
	}
	
	/**
	 * Detects page owner from route
	 *
	 * @return int|void detected page owner guid or void if none detected
	 */
	protected function detectPageOwnerFromRoute() {
		$route = $this->request->getRoute();
		if (!$route instanceof Route) {
			return;
		}
		
		$page_owner = $route->resolvePageOwner();
		if (!$page_owner instanceof \ElggEntity) {
			return;
		}
		
		return $page_owner->guid;
	}
	
	/**
	 * Sets the page owner based on request
	 *
	 * Tries to figure out the page owner by looking at the URL or a request
	 * parameter. The request parameters used are 'username' and 'owner_guid'.
	 * Otherwise, this function attempts to figure out the owner if the url
	 * fits the patterns of:
	 *   <identifier>/owner/<username>
	 *   <identifier>/friends/<username>
	 *   <identifier>/view/<entity guid>
	 *   <identifier>/add/<container guid>
	 *   <identifier>/edit/<entity guid>
	 *   <identifier>/group/<group guid>
	 *
	 * @note Access is disabled while finding the page owner for the group gatekeeper functions.
	 *
	 * @return int|void
	 */
	private function detectLegacyPageOwner() {
	
		$guid = $this->invoker->call(ELGG_IGNORE_ACCESS, function() {
		
			$username = $this->request->getParam('username');
			if ($user = $this->users_table->getByUsername($username)) {
				return $user->guid;
			}
		
			$owner = $this->request->getParam('owner_guid');
			if (is_numeric($owner)) {
				if ($user = $this->entity_table->get((int) $owner)) {
					return $user->guid;
				}
			}
		});
		
		if (is_int($guid)) {
			return $guid;
		}
		
		// @todo feels hacky
		$guid = $this->invoker->call(ELGG_IGNORE_ACCESS, function() {
			$segments = $this->request->getUrlSegments();
			if (!isset($segments[1]) || !isset($segments[2])) {
				return;
			}
			
			switch ($segments[1]) {
				case 'owner':
				case 'friends':
					$user = $this->users_table->getByUsername($segments[2]);
					if ($user) {
						return $user->guid;
					}
					break;
				case 'view':
				case 'edit':
					$entity = $this->entity_table->get($segments[2]);
					if ($entity) {
						return $entity->container_guid;
					}
					break;
				case 'add':
				case 'group':
					$entity = $this->entity_table->get($segments[2]);
					if ($entity) {
						return $entity->guid;
					}
					break;
			}
		});
	
		if (is_int($guid)) {
			return $guid;
		}
	}
	
	/**
	 * Sets a new page owner guid
	 *
	 * @param int $guid the new page owner
	 *
	 * @throws InvalidArgumentException
	 *
	 * @return void
	 */
	public function setPageOwnerGuid(int $guid = 0) {
		if ($guid < 0) {
			throw new InvalidArgumentException(__METHOD__ . ' requires a positive integer.');
		}
		$this->page_owner_guid = $guid;
	}
	
	/**
	 * Return the current page owner guid
	 *
	 * @return int
	 */
	public function getPageOwnerGuid() {
		return $this->page_owner_guid;
	}
	
	/**
	 * Returns the page owner entity
	 *
	 * @return \ElggEntity|false the current page owner or false if none.
	 */
	public function getPageOwnerEntity() {
		return $this->entity_table->get($this->getPageOwnerGuid());
	}
}
