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
use ElggEntity;
use ElggGroup;
use ElggSession;
use ElggUser;
use Exception;

/**
 * Gatekeeper
 *
 * Use elgg()->gatekeeper
 */
class Gatekeeper {

	/**
	 * @var ElggSession
	 */
	protected $session;

	/**
	 * @var \Elgg\Http\Request
	 */
	protected $request;

	/**
	 * @var RedirectService
	 */
	protected $redirects;

	/**
	 * @var EntityTable
	 */
	protected $entities;

	/**
	 * @var AccessCollections
	 */
	protected $access;

	/**
	 * @var Translator
	 */
	protected $translator;

	/**
	 * Constructor
	 *
	 * @param ElggSession       $session    Session
	 * @param HttpRequest       $request    HTTP Request
	 * @param RedirectService   $redirects  Redirects Service
	 * @param EntityTable       $entities   Entity table
	 * @param AccessCollections $access     Access collection table
	 * @param Translator        $translator Translator
	 */
	public function __construct(
		ElggSession $session,
		HttpRequest $request,
		RedirectService $redirects,
		EntityTable $entities,
		AccessCollections $access,
		Translator $translator
	) {
		$this->session = $session;
		$this->request = $request;
		$this->redirects = $redirects;
		$this->entities = $entities;
		$this->access = $access;
		$this->translator = $translator;
	}

	/**
	 * Require a user to be authenticated to with code execution
	 * @return void
	 * @throws LoggedInGatekeeperException
	 */
	public function assertAuthenticatedUser() {
		if ($this->session->isLoggedIn()) {
			return;
		}

		$this->redirects->setLastForwardFrom();

		throw new LoggedInGatekeeperException();
	}

	/**
	 * Require a user to be not authenticated (logged out) to with code execution
	 * @return void
	 * @throws LoggedOutGatekeeperException
	 */
	public function assertUnauthenticatedUser() {
		if (!$this->session->isLoggedIn()) {
			return;
		}

		$exception = new LoggedOutGatekeeperException();
		$exception->setRedirectUrl(elgg_get_site_url());
		
		throw $exception;
	}

	/**
	 * Require an admin user to be authenticated to proceed with code execution
	 * @return void
	 * @throws GatekeeperException
	 * @throws AdminGatekeeperException
	 */
	public function assertAuthenticatedAdmin() {
		$this->assertAuthenticatedUser();

		$user = $this->session->getLoggedInUser();
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
	 * @param int    $guid    GUID of the entity
	 * @param string $type    Entity type
	 * @param string $subtype Entity subtype
	 *
	 * @return ElggEntity
	 * @throws EntityNotFoundException
	 * @throws Exception
	 */
	public function assertExists($guid, $type = null, $subtype = null) {
		$entity = elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function () use ($guid, $type, $subtype) {
			return $this->entities->get($guid, $type, $subtype);
		});

		if (!$entity) {
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
	 * @param ElggEntity $entity            Entity
	 * @param ElggUser   $user              User
	 * @param bool       $validate_can_edit flag to check canEdit access
	 *
	 * @return void
	 * @throws HttpException
	 */
	public function assertAccessibleEntity(ElggEntity $entity, ElggUser $user = null, $validate_can_edit = false) {

		$result = true;

		try {
			if (!$this->session->getIgnoreAccess() && !$this->access->hasAccessToEntity($entity, $user)) {
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

			$user_guid = isset($user) ? (int) $user->guid : $this->session->getLoggedInUserGuid();
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

			if (!$entity->isEnabled() && !$this->session->getDisabledEntityVisibility()) {
				// entity exists, but is disabled
				$exception = new EntityNotFoundException();
				$exception->setParams([
					'entity' => $entity,
					'user' => $user,
					'route' => $this->request->get('_route'),
				]);
				throw $exception;
			}

			if ($entity instanceof ElggGroup) {
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

		$hook_params = [
			'entity' => $entity,
			'user' => $user,
			'route' => $this->request->get('_route'),
		];

		$result = _elgg_services()->hooks->trigger('gatekeeper', "{$entity->type}:{$entity->subtype}", $hook_params, $result);

		if ($result instanceof HttpException) {
			throw $result;
		} else if ($result === false) {
			throw new HttpException();
		}
	}

	/**
	 * Validate active user account
	 *
	 * @param ElggUser $user   User
	 * @param ElggUser $viewer Viewing user
	 *
	 * @return void
	 * @throws EntityNotFoundException
	 */
	public function assertAccessibleUser(ElggUser $user, ElggUser $viewer = null) {
		if (!$user->isBanned()) {
			return;
		}
		
		if (!isset($viewer)) {
			$viewer = $this->session->getLoggedInUser();
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
	 * @param ElggGroup $group Group entity
	 * @param ElggUser  $user  User entity
	 *
	 * @return void
	 * @throws GroupGatekeeperException
	 * @throws GatekeeperException
	 */
	public function assertAccessibleGroup(ElggGroup $group, ElggUser $user = null) {
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
	public function assertXmlHttpRequest() {
		if ($this->request->isXmlHttpRequest()) {
			return;
		}

		throw new AjaxGatekeeperException();
	}

}
