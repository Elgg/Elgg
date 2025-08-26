<?php

namespace Elgg;

use Elgg\Database\AccessCollections;
use Elgg\Database\EntityTable;
use Elgg\Exceptions\HttpException;
use Elgg\Exceptions\Http\EntityNotFoundException;
use Elgg\Exceptions\Http\EntityPermissionsException;
use Elgg\Exceptions\Http\GatekeeperException;
use Elgg\Exceptions\Http\Gatekeeper\AdminGatekeeperException;
use Elgg\Exceptions\Http\Gatekeeper\AjaxGatekeeperException;
use Elgg\Exceptions\Http\Gatekeeper\GroupGatekeeperException;
use Elgg\Exceptions\Http\Gatekeeper\LoggedInGatekeeperException;
use Elgg\Exceptions\Http\Gatekeeper\LoggedOutGatekeeperException;
use Elgg\Http\Request as HttpRequest;
use Elgg\I18n\Translator;

/**
 * Gatekeeper
 *
 * Use elgg()->gatekeeper
 */
class Gatekeeper {

	/**
	 * Constructor
	 *
	 * @param SessionManagerService $session_manager Session manager
	 * @param HttpRequest           $request         HTTP Request
	 * @param RedirectService       $redirects       Redirects Service
	 * @param EntityTable           $entities        Entity table
	 * @param AccessCollections     $access          Access collection table
	 * @param Translator            $translator      Translator
	 */
	public function __construct(
		protected SessionManagerService $session_manager,
		protected HttpRequest $request,
		protected RedirectService $redirects,
		protected EntityTable $entities,
		protected AccessCollections $access,
		protected Translator $translator
	) {
	}

	/**
	 * Require a user to be authenticated to with code execution
	 *
	 * @return void
	 * @throws LoggedInGatekeeperException
	 */
	public function assertAuthenticatedUser(): void {
		if ($this->session_manager->isLoggedIn()) {
			return;
		}

		$this->redirects->setLastForwardFrom();

		throw new LoggedInGatekeeperException();
	}

	/**
	 * Require a user to be not authenticated (logged out) to with code execution
	 *
	 * @return void
	 * @throws LoggedOutGatekeeperException
	 */
	public function assertUnauthenticatedUser(): void {
		if (!$this->session_manager->isLoggedIn()) {
			return;
		}

		$exception = new LoggedOutGatekeeperException();
		$exception->setRedirectUrl(elgg_get_site_url());
		
		throw $exception;
	}

	/**
	 * Require an admin user to be authenticated to proceed with code execution
	 *
	 * @return void
	 * @throws GatekeeperException
	 * @throws AdminGatekeeperException
	 */
	public function assertAuthenticatedAdmin(): void {
		$this->assertAuthenticatedUser();

		$user = $this->session_manager->getLoggedInUser();
		if ($user->isAdmin()) {
			return;
		}

		$this->redirects->setLastForwardFrom();

		throw new AdminGatekeeperException();
	}

	/**
	 * Require an entity with a given guid, type and subtype to proceed with code execution
	 *
	 * @warning Returned entity has been retrieved with ignored access, as well including disabled entities.
	 *          You must validate entity access on the return of this method.
	 *
	 * @param int         $guid    GUID of the entity
	 * @param string|null $type    Entity type
	 * @param string|null $subtype Entity subtype
	 *
	 * @return \ElggEntity
	 * @throws EntityNotFoundException
	 */
	public function assertExists(int $guid, ?string $type = null, ?string $subtype = null): \ElggEntity {
		$entity = elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function () use ($guid, $type, $subtype) {
			return $this->entities->get($guid, $type, $subtype);
		});

		if (!$entity instanceof \ElggEntity) {
			$exception = new EntityNotFoundException();
			$exception->setParams([
				'guid' => $guid,
				'type' => $type,
				'subtype' => $subtype,
				'route' => $this->request->get('_route'),
			]);
			throw $exception;
		}

		return $entity;
	}

	/**
	 * Require that authenticated user has access to entity
	 *
	 * @param \ElggEntity    $entity            Entity
	 * @param \ElggUser|null $user              User
	 * @param bool           $validate_can_edit flag to check canEdit access
	 *
	 * @return void
	 * @throws HttpException
	 */
	public function assertAccessibleEntity(\ElggEntity $entity, ?\ElggUser $user = null, bool $validate_can_edit = false): void {

		$result = true;

		try {
			$user_guid = $user ? $user->guid : 0;
			if (!$this->session_manager->getIgnoreAccess() && !$entity->hasAccess($user_guid)) {
				// user is logged in but still does not have access to it
				$msg = $this->translator->translate('limited_access');
				$exception = new EntityPermissionsException($msg);
				$exception->setParams([
					'entity' => $entity,
					'user' => $user,
					'route' => $this->request->get('_route'),
				]);
				throw $exception;
			}
			
			if ($validate_can_edit && !$entity->canEdit($user_guid)) {
				// logged in user does not have edit or write access to it
				$msg = $this->translator->translate('limited_access');
				$exception = new EntityPermissionsException($msg);
				$exception->setParams([
					'entity' => $entity,
					'user' => $user,
					'route' => $this->request->get('_route'),
				]);
				throw $exception;
			}

			if (!$entity->isEnabled() && !$this->session_manager->getDisabledEntityVisibility()) {
				// entity exists, but is disabled
				$exception = new EntityNotFoundException();
				$exception->setParams([
					'entity' => $entity,
					'user' => $user,
					'route' => $this->request->get('_route'),
				]);
				throw $exception;
			}

			if ($entity instanceof \ElggGroup) {
				$this->assertAccessibleGroup($entity, $user);
			}

			foreach (['owner_guid', 'container_guid'] as $prop) {
				if (!$entity->$prop) {
					continue;
				}

				$parent = $this->assertExists($entity->$prop);
				$this->assertAccessibleEntity($parent, $user);
			}
		} catch (HttpException $ex) {
			$result = $ex;
		}

		$params = [
			'entity' => $entity,
			'user' => $user,
			'route' => $this->request->get('_route'),
		];

		$result = _elgg_services()->events->triggerResults('gatekeeper', "{$entity->type}:{$entity->subtype}", $params, $result);

		if ($result instanceof HttpException) {
			throw $result;
		} else if ($result === false) {
			throw new HttpException();
		}
	}

	/**
	 * Validate active user account
	 *
	 * @param \ElggUser      $user   User
	 * @param \ElggUser|null $viewer Viewing user
	 *
	 * @return void
	 * @throws EntityNotFoundException
	 */
	public function assertAccessibleUser(\ElggUser $user, ?\ElggUser $viewer = null): void {
		if (!$user->isBanned()) {
			return;
		}
		
		if (!isset($viewer)) {
			$viewer = $this->session_manager->getLoggedInUser();
		}

		if (!$viewer || !$viewer->isAdmin()) {
			$exception = new EntityNotFoundException();
			$exception->setParams([
				'entity' => $user,
				'user' => $viewer,
				'route' => $this->request->get('_route'),
			]);
			throw $exception;
		}
	}

	/**
	 * Validate group content visibility
	 *
	 * @param \ElggGroup     $group Group entity
	 * @param \ElggUser|null $user  User entity
	 *
	 * @return void
	 * @throws GroupGatekeeperException
	 * @throws GatekeeperException
	 */
	public function assertAccessibleGroup(\ElggGroup $group, ?\ElggUser $user = null): void {
		if ($group->canAccessContent($user)) {
			return;
		}
		
		$this->assertAuthenticatedUser();

		$this->redirects->setLastForwardFrom();

		$exception = new GroupGatekeeperException();
		$exception->setParams([
			'entity' => $group,
			'user' => $user,
			'route' => $this->request->get('_route'),
		]);
		$exception->setRedirectUrl($group->getURL());
		throw $exception;
	}

	/**
	 * Require XmlHttpRequest
	 *
	 * @return void
	 * @throws AjaxGatekeeperException
	 */
	public function assertXmlHttpRequest(): void {
		if ($this->request->isXmlHttpRequest()) {
			return;
		}

		throw new AjaxGatekeeperException();
	}
}
