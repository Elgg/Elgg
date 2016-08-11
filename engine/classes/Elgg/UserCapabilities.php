<?php

namespace Elgg;

use Elgg\Database\EntityTable;
use Elgg\Database\EntityTable\UserFetchFailureException;
use ElggAnnotation;
use ElggEntity;
use ElggRiverItem;
use ElggMetadata;
use ElggSession;
use InvalidArgumentException;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 * 
 * Use the elgg_* versions instead.
 * 
 * @access private
 * @since 2.2
 */
class UserCapabilities {

	/**
	 * @var PluginHooksService $hooks
	 */
	private $hooks;

	/**
	 * @var EntityTable
	 */
	private $entities;

	/**
	 * @var ElggSession
	 */
	private $session;

	/**
	 * Constructor
	 *
	 * @param PluginHooksService $hooks    Plugin hooks service
	 * @param EntityTable        $entities Entity table
	 * @param ElggSession        $session  Session
	 */
	public function __construct(PluginHooksService $hooks, EntityTable $entities, ElggSession $session) {
		$this->hooks = $hooks;
		$this->entities = $entities;
		$this->session = $session;
	}

	/**
	 * Can a user edit this entity?
	 *
	 * @tip Can be overridden by registering for the permissions_check plugin hook.
	 *
	 * @param ElggEntity $entity    Object entity
	 * @param int        $user_guid The user GUID, optionally (default: logged in user)
	 *
	 * @return bool Whether this entity is editable by the given user.
	 * @see elgg_set_ignore_access()
	 */
	public function canEdit(ElggEntity $entity, $user_guid = 0) {
		try {
			$user = $this->entities->getUserForPermissionsCheck($user_guid);
		} catch (UserFetchFailureException $e) {
			return false;
		}

		// Test user if possible - should default to false unless a plugin hook says otherwise
		$default = call_user_func(function () use ($entity, $user) {
			if (!$user) {
				return false;
			}

			// favor the persisted attributes if not saved
			$attrs = array_merge(
					[
				'owner_guid' => $entity->owner_guid,
				'container_guid' => $entity->container_guid,
					], $entity->getOriginalAttributes()
			);

			if ($attrs['owner_guid'] == $user->guid) {
				return true;
			}

			if ($attrs['container_guid'] == $user->guid) {
				return true;
			}

			if ($entity->guid == $user->guid) {
				return true;
			}

			$container = $this->entities->get($attrs['container_guid']);

			return ($container && $container->canEdit($user->guid));
		});

		$params = array('entity' => $entity, 'user' => $user);
		return $this->hooks->trigger('permissions_check', $entity->getType(), $params, $default);
	}

	/**
	 * Can a user delete this entity?
	 *
	 * @tip Can be overridden by registering for the permissions_check:delete plugin hook.
	 *
	 * @param ElggEntity $entity    Object entity
	 * @param int        $user_guid The user GUID, optionally (default: logged in user)
	 *
	 * @return bool Whether this entity is deletable by the given user.
	 * @since 1.11
	 * @see elgg_set_ignore_access()
	 */
	public function canDelete(ElggEntity $entity, $user_guid = 0) {
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
		return $this->hooks->trigger('permissions_check:delete', $entity->getType(), $params, $return);
	}

	/**
	 * Can a user delete this river item?
	 *
	 * @tip Can be overridden by registering for the "permissions_check:delete", "river" plugin hook.
	 *
	 * @note This is not called by elgg_delete_river().
	 *
	 * @param ElggRiverItem $item      River item
	 * @param int           $user_guid The user GUID, optionally (default: logged in user)
	 *
	 * @return bool Whether this river item should be considered deletable by the given user.
	 * @since 2.3
	 * @see elgg_set_ignore_access()
	 */
	public function canDeleteRiverItem(ElggRiverItem $item, $user_guid = 0) {
		try {
			$user = $this->entities->getUserForPermissionsCheck($user_guid);
		} catch (UserFetchFailureException $e) {
			return false;
		}

		$return = ($user && $user->isAdmin());

		$params = [
			'item' => $item,
			'user' => $user,
		];
		return $this->hooks->trigger('permissions_check:delete', 'river', $params, $return);
	}

	/**
	 * Can a user edit metadata on this entity?
	 *
	 * If no specific metadata is passed, it returns whether the user can
	 * edit any metadata on the entity.
	 *
	 * @tip Can be overridden by by registering for the permissions_check:metadata
	 * plugin hook.
	 *
	 * @param ElggEntity   $entity    Object entity
	 * @param int          $user_guid The user GUID, optionally (default: logged in user)
	 * @param ElggMetadata $metadata  The piece of metadata to specifically check or null for any metadata
	 *
	 * @return bool
	 * @see elgg_set_ignore_access()
	 */
	public function canEditMetadata(ElggEntity $entity, $user_guid = 0, ElggMetadata $metadata = null) {
		if (!$entity->guid) {
			// @todo cannot edit metadata on unsaved entity?
			return false;
		}

		try {
			$user = $this->entities->getUserForPermissionsCheck($user_guid);
		} catch (UserFetchFailureException $e) {
			return false;
		}

		if ($user) {
			$user_guid = $user->guid;
		}

		// if metadata is not owned or owned by the user, then can edit
		if ($metadata && ($metadata->owner_guid == 0 || $metadata->owner_guid == $user_guid)) {
			$return = true;
		} else {
			$return = $entity->canEdit($user_guid);
		}

		// metadata and user may be null
		$params = [
			'entity' => $entity,
			'user' => $user,
			'metadata' => $metadata
		];
		return $this->hooks->trigger('permissions_check:metadata', $entity->getType(), $params, $return);
	}

	/**
	 * Determines whether or not the user can edit this annotation
	 *
	 * @param Elggentity     $entity     Object entity
	 * @param int            $user_guid  The GUID of the user (defaults to currently logged in user)
	 * @param ElggAnnotation $annotation Annotation
	 *
	 * @return bool
	 * @see elgg_set_ignore_access()
	 */
	public function canEditAnnotation(ElggEntity $entity, $user_guid = 0, ElggAnnotation $annotation = null) {
		if (!$annotation) {
			return false;
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
			if ($result == false && $entity->canEdit($user->guid)) {
				$result = true;
			}
		}

		// Trigger plugin hook - note that $user may be null
		$params = [
			'entity' => $entity,
			'user' => $user,
			'annotation' => $annotation
		];

		return $this->hooks->trigger('permissions_check', 'annotation', $params, $result);
	}
	
	/**
	 * Can a user add an entity to this container
	 *
	 * @param ElggEntity $entity    Container entity
	 * @param int        $user_guid The GUID of the user creating the entity (0 for logged in user).
	 * @param string     $type      The type of entity we're looking to write
	 * @param string     $subtype   The subtype of the entity we're looking to write
	 *
	 * @return bool
	 * @see elgg_set_ignore_access()
	 */
	public function canWriteToContainer(ElggEntity $entity, $user_guid = 0, $type = 'all', $subtype = 'all') {
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
		// This hook can also be used to apply status logic, e.g. to disallow
		// new replies in closed discussions.
		// We do not take a stand hence the return is null. This can be used by
		// handlers to check if another hook has modified the value.
		$logic_check = $this->hooks->trigger('container_logic_check', $type, $params);

		if ($logic_check === false) {
			return false;
		}
		
		$return = false;
		if ($entity) {
			// If the user can edit the container, they can also write to it
			if ($entity->canEdit($user_guid)) {
				$return = true;
			}
		}

		// Container permissions can prevent users from writing to an entity.
		// For instance, these permissions can prevent non-group members from writing
		// content to the group.
		return $this->hooks->trigger('container_permissions_check', $type, $params, $return);
	}

	/**
	 * Can a user comment on an entity?
	 *
	 * @tip Can be overridden by registering for the permissions_check:comment,
	 * <entity type> plugin hook.
	 *
	 * @param ElggEntity $entity    Object entity
	 * @param int        $user_guid User guid (default is logged in user)
	 *
	 * @return bool
	 */
	public function canComment(ElggEntity $entity, $user_guid = 0) {
		try {
			$user = $this->entities->getUserForPermissionsCheck($user_guid);
		} catch (UserFetchFailureException $e) {
			return false;
		}

		// By default, we don't take a position of whether commenting is allowed
		// because it is handled by the subclasses of \ElggEntity
		$params = [
			'entity' => $entity,
			'user' => $user
		];
		return $this->hooks->trigger('permissions_check:comment', $entity->getType(), $params, null);
	}

	/**
	 * Can a user annotate an entity?
	 *
	 * @tip Can be overridden by registering for the plugin hook [permissions_check:annotate:<name>,
	 * <entity type>] or [permissions_check:annotate, <entity type>]. The hooks are called in that order.
	 *
	 * @tip If you want logged out users to annotate an object, do not call
	 * canAnnotate(). It's easier than using the plugin hook.
	 *
	 * @param ElggEntity $entity          Objet entity
	 * @param int        $user_guid       User guid (default is logged in user)
	 * @param string     $annotation_name The name of the annotation (default is unspecified)
	 *
	 * @return bool
	 */
	public function canAnnotate(ElggEntity $entity, $user_guid = 0, $annotation_name = '') {
		if ($annotation_name === null || $annotation_name === false) {
			// accepting these for BC
			$annotation_name = '';
		} elseif (!is_string($annotation_name)) {
			throw new InvalidArgumentException(__METHOD__ . ' expects \$annotation_name to be a string');
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
			$return = $this->hooks->trigger("permissions_check:annotate:$annotation_name", $entity->getType(), $params, $return);
		}

		return $this->hooks->trigger('permissions_check:annotate', $entity->getType(), $params, $return);
	}

}
