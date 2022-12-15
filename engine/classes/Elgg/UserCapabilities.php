<?php

namespace Elgg;

use Elgg\Database\EntityTable;
use Elgg\Exceptions\Database\UserFetchFailureException;

/**
 * User capabilities service
 *
 * @internal
 * @since 2.2
 */
class UserCapabilities {

	/**
	 * @var EventsService
	 */
	protected $events;

	/**
	 * @var EntityTable
	 */
	protected $entities;

	/**
	 * @var SessionManagerService
	 */
	protected $session_manager;

	/**
	 * Constructor
	 *
	 * @param EventsService         $events          Events service
	 * @param EntityTable           $entities        Entity table
	 * @param SessionManagerService $session_manager Session
	 */
	public function __construct(EventsService $events, EntityTable $entities, SessionManagerService $session_manager) {
		$this->events = $events;
		$this->entities = $entities;
		$this->session_manager = $session_manager;
	}

	/**
	 * Decides if the access system should be ignored for a user.
	 *
	 * Returns true (meaning ignore access) if either of these 2 conditions are true:
	 *   1) an admin user guid is passed to this function.
	 *   2) {@link elgg_get_ignore_access()} returns true.
	 *
	 * @param int $user_guid The user to check against.
	 *
	 * @return bool
	 */
	public function canBypassPermissionsCheck(int $user_guid = 0): bool {
		if ($this->session_manager->getIgnoreAccess()) {
			// Checking ignored access first to avoid infinite loops,
			// when trying to fetch a user by guid
			return true;
		}

		try {
			$user = $this->entities->getUserForPermissionsCheck($user_guid);
		} catch (UserFetchFailureException $e) {
			return false;
		}

		return $user && $user->isAdmin();
	}

	/**
	 * Can a user edit this entity?
	 *
	 * @tip Can be overridden by registering for the 'permissions_check' event.
	 *
	 * @param \ElggEntity $entity    Object entity
	 * @param int         $user_guid The user GUID, optionally (default: logged in user)
	 *
	 * @return bool Whether this entity is editable by the given user.
	 */
	public function canEdit(\ElggEntity $entity, int $user_guid = 0): bool {
		if ($this->canBypassPermissionsCheck($user_guid)) {
			return true;
		}

		try {
			$user = $this->entities->getUserForPermissionsCheck($user_guid);
		} catch (UserFetchFailureException $e) {
			return false;
		}

		// Test user if possible - should default to false unless an event says otherwise
		$default = call_user_func(function () use ($entity, $user) {
			if (!$user) {
				return false;
			}

			// favor the persisted attributes if not saved
			$attrs = array_merge([
				'owner_guid' => $entity->owner_guid,
				'container_guid' => $entity->container_guid,
			], $entity->getOriginalAttributes());

			if ($attrs['owner_guid'] == $user->guid) {
				return true;
			}

			if ($attrs['container_guid'] == $user->guid) {
				return true;
			}

			if ($entity->guid == $user->guid) {
				return true;
			}

			if (!isset($attrs['container_guid'])) {
				return false;
			}

			$container = $this->entities->get($attrs['container_guid']);

			return ($container && $container->canEdit($user->guid));
		});

		$params = ['entity' => $entity, 'user' => $user];
		return $this->events->triggerResults('permissions_check', $entity->getType(), $params, $default);
	}

	/**
	 * Can a user delete this entity?
	 *
	 * @tip Can be overridden by registering for the 'permissions_check:delete' event.
	 *
	 * @param \ElggEntity $entity    Object entity
	 * @param int         $user_guid The user GUID, optionally (default: logged in user)
	 *
	 * @return bool Whether this entity is deletable by the given user.
	 * @since 1.11
	 */
	public function canDelete(\ElggEntity $entity, int $user_guid = 0): bool {
		if ($this->canBypassPermissionsCheck($user_guid)) {
			return true;
		}

		try {
			$user = $this->entities->getUserForPermissionsCheck($user_guid);
		} catch (UserFetchFailureException $e) {
			return false;
		}

		$return = $entity->canEdit($user_guid);

		$params = [
			'entity' => $entity,
			'user' => $user
		];
		return $this->events->triggerResults('permissions_check:delete', $entity->getType(), $params, $return);
	}

	/**
	 * Can a user delete this river item?
	 *
	 * @tip Can be overridden by registering for the "permissions_check:delete", "river" event.
	 *
	 * @param \ElggRiverItem $item      River item
	 * @param int            $user_guid The user GUID, optionally (default: logged in user)
	 *
	 * @return bool Whether this river item should be considered deletable by the given user.
	 * @since 2.3
	 */
	public function canDeleteRiverItem(\ElggRiverItem $item, int $user_guid = 0): bool {
		if ($this->canBypassPermissionsCheck($user_guid)) {
			return true;
		}

		try {
			$user = $this->entities->getUserForPermissionsCheck($user_guid);
		} catch (UserFetchFailureException $e) {
			return false;
		}

		$params = [
			'item' => $item,
			'user' => $user,
		];
		return $this->events->triggerResults('permissions_check:delete', 'river', $params, false);
	}

	/**
	 * Determines whether or not the user can edit this annotation
	 *
	 * @param \Elggentity     $entity     Object entity
	 * @param int             $user_guid  The GUID of the user (defaults to currently logged in user)
	 * @param \ElggAnnotation $annotation Annotation
	 *
	 * @return bool
	 */
	public function canEditAnnotation(\ElggEntity $entity, int $user_guid = 0, \ElggAnnotation $annotation = null): bool {
		if (!$annotation) {
			return false;
		}

		if ($this->canBypassPermissionsCheck($user_guid)) {
			return true;
		}

		try {
			$user = $this->entities->getUserForPermissionsCheck($user_guid);
		} catch (UserFetchFailureException $e) {
			return false;
		}

		$result = false;

		if ($user) {
			// If the owner of annotation is the specified user, they can edit.
			if ($annotation->owner_guid == $user->guid) {
				$result = true;
			}

			// If the user can edit the entity this is attached to, they can edit.
			if ($result === false && $entity->canEdit($user->guid)) {
				$result = true;
			}
		}

		// Trigger event - note that $user may be null
		$params = [
			'entity' => $entity,
			'user' => $user,
			'annotation' => $annotation,
		];

		return $this->events->triggerResults('permissions_check', 'annotation', $params, $result);
	}
	
	/**
	 * Can a user add an entity to this container
	 *
	 * @param \ElggEntity $entity    Container entity
	 * @param string      $type      The type of entity we're looking to write
	 * @param string      $subtype   The subtype of the entity we're looking to write
	 * @param int         $user_guid The GUID of the user creating the entity (0 for logged in user).
	 *
	 * @return bool
	 */
	public function canWriteToContainer(\ElggEntity $entity, string $type, string $subtype, int $user_guid = 0): bool {
		try {
			$user = $this->entities->getUserForPermissionsCheck($user_guid);
		} catch (UserFetchFailureException $e) {
			return false;
		}

		if ($user) {
			$user_guid = $user->guid;
		}

		$params = [
			'container' => $entity,
			'user' => $user,
			'subtype' => $subtype
		];

		// Unlike permissions, logic check can be used to prevent certain entity
		// types from being contained by other entity types,
		// e.g. discussion reply objects can only be contained by discussion objects.
		// This event can also be used to apply status logic, e.g. to disallow
		// new replies in closed discussions.
		// We do not take a stand hence the return is null. This can be used by
		// handlers to check if another event has modified the value.
		$logic_check = $this->events->triggerResults('container_logic_check', $type, $params);

		if ($logic_check === false) {
			return false;
		}

		if ($this->canBypassPermissionsCheck($user_guid)) {
			return true;
		}

		// If the user can edit the container, they can also write to it
		$return = $entity->canEdit($user_guid);

		// Container permissions can prevent users from writing to an entity.
		// For instance, these permissions can prevent non-group members from writing
		// content to the group.
		return $this->events->triggerResults('container_permissions_check', $type, $params, $return);
	}

	/**
	 * Can a user comment on an entity?
	 *
	 * @tip Can be overridden by registering for the permissions_check:comment,
	 * <entity type> event.
	 *
	 * @param \ElggEntity $entity    Object entity
	 * @param int         $user_guid User guid (default is logged in user)
	 *
	 * @return bool
	 */
	public function canComment(\ElggEntity $entity, int $user_guid = 0): bool {
		try {
			$user = $this->entities->getUserForPermissionsCheck($user_guid);
		} catch (UserFetchFailureException $e) {
			return false;
		}
		
		$container_result = $this->canWriteToContainer($entity, 'object', 'comment', $user_guid);
		if ($this->canBypassPermissionsCheck($user_guid)) {
			// doing this again here to prevent bypassed users to be influenced by 'permissions_check:comment' event
			return $container_result;
		}
		
		// By default, we don't take a position of whether commenting is allowed
		// because it is handled by the subclasses of \ElggEntity
		$params = [
			'entity' => $entity,
			'user' => $user,
		];
		return $this->events->triggerResults('permissions_check:comment', $entity->getType(), $params, $container_result);
	}

	/**
	 * Can a user annotate an entity?
	 *
	 * @tip Can be overridden by registering for the event [permissions_check:annotate:<name>,
	 * <entity type>] or [permissions_check:annotate, <entity type>]. The events are called in that order.
	 *
	 * @tip If you want logged out users to annotate an object, do not call
	 * canAnnotate(). It's easier than using the event.
	 *
	 * @param \ElggEntity $entity          Objet entity
	 * @param int         $user_guid       User guid (default is logged in user)
	 * @param string      $annotation_name The name of the annotation (default is unspecified)
	 *
	 * @return bool
	 */
	public function canAnnotate(\ElggEntity $entity, int $user_guid = 0, string $annotation_name = ''): bool {
		if ($this->canBypassPermissionsCheck($user_guid)) {
			return true;
		}

		try {
			$user = $this->entities->getUserForPermissionsCheck($user_guid);
		} catch (UserFetchFailureException $e) {
			return false;
		}

		$return = (bool) $user;

		$params = [
			'entity' => $entity,
			'user' => $user,
			'annotation_name' => $annotation_name,
		];

		if (!empty($annotation_name)) {
			$return = $this->events->triggerResults("permissions_check:annotate:$annotation_name", $entity->getType(), $params, $return);
		}

		return $this->events->triggerResults('permissions_check:annotate', $entity->getType(), $params, $return);
	}

	/**
	 * Can a user download a file?
	 *
	 * @tip Can be overridden by registering for the 'permissions_check:download', 'file' event.
	 *
	 * @param \ElggFile $entity    File entity
	 * @param int       $user_guid User guid (default is logged in user)
	 * @param bool      $default   Default permission
	 *
	 * @return bool
	 */
	public function canDownload(\ElggFile $entity, int $user_guid = 0, bool $default = true): bool {
		if ($this->canBypassPermissionsCheck($user_guid)) {
			return true;
		}

		try {
			$user = $this->entities->getUserForPermissionsCheck($user_guid);
		} catch (UserFetchFailureException $e) {
			return false;
		}

		$params = [
			'entity' => $entity,
			'user' => $user
		];

		return $this->events->triggerResults('permissions_check:download', 'file', $params, $default);
	}
}
