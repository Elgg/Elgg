<?php

namespace Elgg;

use Elgg\Database\AccessCollections;
use Elgg\Database\EntityTable;
use Elgg\Http\Request;
use Elgg\I18n\Translator;
use ElggEntity;
use ElggGroup;
use ElggSession;
use ElggUser;
use Exception;

/**
 * Gatekeeper
 *
 * API in flux. Use elgg_* functions intead
 *
 * @access private
 */
class Gatekeeper {

	/**
	 * @var ElggSession
	 */
	protected $session;

	/**
	 * @var Request
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
	 * @param Request           $request    HTTP Request
	 * @param RedirectService   $redirects  Redirects Service
	 * @param EntityTable       $entities   Entity table
	 * @param AccessCollections $access     Access collection table
	 * @param Translator        $translator Translator
	 */
	public function __construct(
		ElggSession $session,
		Request $request,
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
	 * @throws GatekeeperException
	 */
	public function assertAuthenticatedUser() {
		if ($this->session->isLoggedIn()) {
			return;
		}

		$this->redirects->setLastForwardFrom();

		$msg = $this->translator->translate('loggedinrequired');
		throw new GatekeeperException($msg);
	}

	/**
	 * Require an admin user to be authenticated to proceed with code execution
	 * @return void
	 * @throws GatekeeperException
	 */
	public function assertAuthenticatedAdmin() {
		$this->assertAuthenticatedUser();

		$user = $this->session->getLoggedInUser();
		if ($user->isAdmin()) {
			return;
		}

		$this->redirects->setLastForwardFrom();

		$msg = $this->translator->translate('adminrequired');
		throw new GatekeeperException($msg);
	}

	/**
	 * Require an entity with a given guid, type and subtype to proceed with code execution
	 *
	 * @warning Returned entity has been retrieved with ignored access, as well including disabled entities.
	 *          You must validate entity access on the return of this method.
	 *
	 * @param int  $guid    GUID of the entity
	 * @param null $type    Entity type
	 * @param null $subtype Entity subtype
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
			throw new EntityNotFoundException();
		}

		return $entity;
	}

	/**
	 * Require that authenticated user has access to entity
	 *
	 * @param ElggEntity $entity Entity
	 * @param ElggUser   $user   User
	 *
	 * @return void
	 * @throws HttpException
	 */
	public function assertAccessibleEntity(ElggEntity $entity, ElggUser $user = null) {

		$result = true;

		try {
			if (!$this->session->getIgnoreAccess() && !$this->access->hasAccessToEntity($entity, $user)) {
				// user is logged in but still does not have access to it
				$msg = $this->translator->translate('limited_access');
				throw new EntityNotFoundException($msg);
			}

			if (!$entity->isEnabled() && !access_get_show_hidden_status()) {
				throw new EntityNotFoundException();
			}

			if ($entity instanceof ElggUser) {
				$this->assertAccessibleUser($entity, $user);
			}

			if ($entity instanceof ElggGroup) {
				$this->assertAccessibleGroup($entity, $user);
			}

			foreach (['owner_guid', 'container_guid'] as $prop) {
				if (!$entity->$prop) {
					continue;
				}

				$parent = $this->assertExists($entity->$prop);
				$this->assertAccessibleEntity($parent);
			}
		} catch (HttpException $ex) {
			$result = $ex;
		}

		$hook_params = [
			'entity' => $entity,
			'user' => $user,
		];

		$result = elgg_trigger_plugin_hook('gatekeeper', "{$entity->type}:{$entity->subtype}", $hook_params, $result);

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
		if ($user->isBanned()) {
			if (!isset($viewer)) {
				$viewer = $this->session->getLoggedInUser();
			}

			if (!$viewer || !$viewer->isAdmin()) {
				throw new EntityNotFoundException();
			}
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
		if (!$group->canAccessContent($user)) {
			$this->assertAuthenticatedUser();

			$this->redirects->setLastForwardFrom();

			throw new GroupGatekeeperException();
		}
	}

	/**
	 * Require XmlHttpRequest
	 *
	 * @return void
	 * @throws BadRequestException
	 */
	public function assertXmlHttpRequest() {
		if ($this->request->isXmlHttpRequest()) {
			return;
		}

		$msg = $this->translator->translate('ajax:not_is_xhr');
		throw new BadRequestException($msg);
	}

}
