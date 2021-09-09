<?php

namespace Elgg\Notifications;

use Elgg\Database;
use Elgg\Database\RelationshipsTable;
use Elgg\PluginHooksService;
use Elgg\Database\Select;
use Elgg\Database\Delete;
use Elgg\Database\QueryBuilder;
use Elgg\Database\Entities;
use Elgg\Exceptions\InvalidArgumentException;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @internal
 * @since 1.9.0
 */
class SubscriptionsService {

	/**
	 * @var string Elgg has historically stored subscriptions as relationships with the prefix 'notify'
	 */
	const RELATIONSHIP_PREFIX = 'notify';

	/**
	 * @var string Used when an entity no longer wishes to recieve notifications
	 */
	const MUTE_NOTIFICATIONS_RELATIONSHIP = 'mute_notifications';
	
	/**
	 * @var Database
	 */
	protected $db;
	
	/**
	 * @var RelationshipsTable
	 */
	protected $relationshipsTable;
	
	/**
	 * @var PluginHooksService
	 */
	protected $hooks;

	/**
	 * Constructor
	 *
	 * @param Database           $db                 Database service
	 * @param RelationshipsTable $relationshipsTable Relationship database table
	 * @param PluginHooksService $hooks              Plugin hooks service
	 */
	public function __construct(Database $db, RelationshipsTable $relationshipsTable, PluginHooksService $hooks) {
		$this->db = $db;
		$this->relationshipsTable = $relationshipsTable;
		$this->hooks = $hooks;
	}

	/**
	 * Get the subscriptions for this notification event
	 *
	 * The return array is of the form:
	 *
	 * array(
	 *     <user guid> => array('email', 'sms', 'ajax'),
	 * );
	 *
	 * @param NotificationEvent $event                     Notification event
	 * @param array             $methods                   Notification methods
	 * @param array             $exclude_guids_for_records GUIDs to exclude from fetching subscription records
	 *
	 * @return array
	 */
	public function getNotificationEventSubscriptions(NotificationEvent $event, array $methods, array $exclude_guids_for_records = []) {

		if (empty($methods)) {
			return [];
		}

		$object = $event->getObject();
		if (!$object instanceof \ElggData) {
			return [];
		}

		// get subscribers only for \ElggEntity if it isn't private
		if (!$object instanceof \ElggEntity || $object->access_id === ACCESS_PRIVATE) {
			return [];
		}

		$guids = [
			$object->owner_guid,
			$object->container_guid,
		];
		if ($object instanceof \ElggObject || $object instanceof \ElggGroup) {
			$guids[] = $object->guid;
		}
		
		$guids = array_diff($guids, $exclude_guids_for_records);
		if (empty($guids)) {
			return [];
		}
		
		$subscriptions = [];
		$records = $this->getSubscriptionRecords($guids, $methods, $object->type, $object->subtype, $event->getAction(), $event->getActorGUID());
		foreach ($records as $record) {
			if (empty($record->guid)) {
				// happens when no records are found
				continue;
			}
			
			if (!isset($subscriptions[$record->guid])) {
				$subscriptions[$record->guid] = [];
			}
			
			$deliveryMethods = explode(',', $record->methods);
			foreach ($deliveryMethods as $relationship) {
				$relationship_array = explode(':', $relationship);
				
				$subscriptions[$record->guid][] = end($relationship_array);
			}
		}

		return $subscriptions;
	}

	/**
	 * Get the subscriptions for the content created inside this container.
	 *
	 * The return array is of the form:
	 *
	 * array(
	 *     <user guid> => array('email', 'sms', 'ajax'),
	 * );
	 *
	 * @param int   $container_guid GUID of the entity acting as a container
	 * @param array $methods        Notification methods
	 *
	 * @return array User GUIDs (keys) and their subscription types (values).
	 */
	public function getSubscriptionsForContainer(int $container_guid, array $methods) {

		if (empty($methods)) {
			return [];
		}

		$subscriptions = [];

		$records = $this->getSubscriptionRecords([$container_guid], $methods);
		foreach ($records as $record) {
			if (empty($record->guid)) {
				// happens when no records are found
				continue;
			}
			
			if (!isset($subscriptions[$record->guid])) {
				$subscriptions[$record->guid] = [];
			}
			
			$deliveryMethods = explode(',', $record->methods);
			foreach ($deliveryMethods as $relationship) {
				$relationship_array = explode(':', $relationship);
				
				$subscriptions[$record->guid][] = end($relationship_array);
			}
		}

		return $subscriptions;
	}

	/**
	 * Subscribe a user to notifications about a target entity
	 *
	 * This method will return false if the subscription already exists.
	 *
	 * @param int    $user_guid   The GUID of the user to subscribe to notifications
	 * @param string $method      The delivery method of the notifications
	 * @param int    $target_guid The entity to receive notifications about
	 * @param string $type        (optional) entity type
	 * @param string $subtype     (optional) entity subtype
	 * @param string $action      (optional) notification action (eg. 'create')
	 *
	 * @return bool
	 * @throws InvalidArgumentException
	 */
	public function addSubscription(int $user_guid, string $method, int $target_guid, string $type = null, string $subtype = null, string $action = null) {
		$this->assertValidTypeSubtypeActionForSubscription($type, $subtype, $action);
		
		$rel = [
			self::RELATIONSHIP_PREFIX,
		];
		
		if (!_elgg_services()->notifications->isRegisteredMethod($method)) {
			return false;
		}
		
		// remove the muted notification relationship
		$this->unmuteNotifications($user_guid, $target_guid);
		
		if (!empty($type) && !empty($subtype) && !empty($action)) {
			$rel[] = $type;
			$rel[] = $subtype;
			$rel[] = $action;
		}
		
		$rel[] = $method;
		
		return $this->relationshipsTable->add($user_guid, implode(':', $rel), $target_guid);
	}
	
	/**
	 * Check if a subscription exists
	 *
	 * @param int    $user_guid   The GUID of the user to check subscriptions for
	 * @param string $method      The delivery method of the notifications
	 * @param int    $target_guid The entity to receive notifications about
	 * @param string $type        (optional) entity type
	 * @param string $subtype     (optional) entity subtype
	 * @param string $action      (optional) notification action (eg. 'create')
	 *
	 * @return bool
	 * @throws InvalidArgumentException
	 * @since 4.0
	 */
	public function hasSubscription(int $user_guid, string $method, int $target_guid, string $type = null, string $subtype = null, string $action = null): bool {
		$this->assertValidTypeSubtypeActionForSubscription($type, $subtype, $action);
		
		$rel = [
			self::RELATIONSHIP_PREFIX,
		];
		
		if (!empty($type) && !empty($subtype) && !empty($action)) {
			$rel[] = $type;
			$rel[] = $subtype;
			$rel[] = $action;
		}
		
		$rel[] = $method;
		
		return $this->relationshipsTable->check($user_guid, implode(':', $rel), $target_guid) instanceof \ElggRelationship;
	}
	
	/**
	 * Check if any subscription exists
	 *
	 * @param int   $user_guid   The GUID of the user to check subscriptions for
	 * @param int   $target_guid The entity to receive notifications about
	 * @param array $methods     The delivery method of the notifications
	 *
	 * @return bool
	 * @since 4.0
	 */
	public function hasSubscriptions(int $user_guid, int $target_guid, array $methods = []): bool {
		if (empty($methods)) {
			// all currently registered methods
			$methods = _elgg_services()->notifications->getMethods();
		}
		
		if (empty($methods)) {
			// no methods available
			return false;
		}
		
		$select = Select::fromTable('entity_relationships');
		$select->select('count(*) as total')
			->where($select->compare('guid_one', '=', $user_guid, ELGG_VALUE_GUID))
			->andWhere($select->compare('guid_two', '=', $target_guid, ELGG_VALUE_GUID));
		
		$ors = [];
		foreach ($methods as $method) {
			$ors[] = $select->compare('relationship', '=', self::RELATIONSHIP_PREFIX . ':' . $method, ELGG_VALUE_STRING);
			$ors[] = $select->compare('relationship', 'like', self::RELATIONSHIP_PREFIX . ':%:' . $method, ELGG_VALUE_STRING);
		}
		
		$select->andWhere($select->merge($ors, 'OR'));
		
		$result = $this->db->getDataRow($select);
		
		return (bool) $result->total;
	}

	/**
	 * Unsubscribe a user to notifications about a target entity
	 *
	 * @param int    $user_guid   The GUID of the user to unsubscribe to notifications
	 * @param string $method      The delivery method of the notifications to stop
	 * @param int    $target_guid The entity to stop receiving notifications about
	 * @param string $type        (optional) entity type
	 * @param string $subtype     (optional) entity subtype
	 * @param string $action      (optional) notification action (eg. 'create')
	 *
	 * @return bool
	 * @throws InvalidArgumentException
	 */
	public function removeSubscription(int $user_guid, string $method, int $target_guid, string $type = null, string $subtype = null, string $action = null) {
		$this->assertValidTypeSubtypeActionForSubscription($type, $subtype, $action);
		
		$rel = [
			self::RELATIONSHIP_PREFIX,
		];
		
		if (!empty($type) && !empty($subtype) && !empty($action)) {
			$rel[] = $type;
			$rel[] = $subtype;
			$rel[] = $action;
		}
		
		$rel[] = $method;
		
		if (!$this->relationshipsTable->check($user_guid, implode(':', $rel), $target_guid)) {
			// subscription doesn't exist
			return true;
		}
		
		return $this->relationshipsTable->remove($user_guid, implode(':', $rel), $target_guid);
	}
	
	/**
	 * Unsubscribe a user from all notifications about the target entity
	 *
	 * @param int   $user_guid   The GUID of the user to unsubscribe to notifications
	 * @param int   $target_guid The entity to stop receiving notifications about
	 * @param array $methods     (optional) The delivery method of the notifications to stop
	 *
	 * @return bool
	 * @since 4.0
	 */
	public function removeSubscriptions(int $user_guid, int $target_guid, array $methods = []): bool {
		$delete = Delete::fromTable('entity_relationships');
		$delete->where($delete->compare('guid_one', '=', $user_guid, ELGG_VALUE_GUID))
			->andWhere($delete->compare('guid_two', '=', $target_guid, ELGG_VALUE_GUID));
		
		if (empty($methods)) {
			$delete->andWhere($delete->compare('relationship', 'like', self::RELATIONSHIP_PREFIX . ':%', ELGG_VALUE_STRING));
		} else {
			$ors = [];
			foreach ($methods as $method) {
				$ors[] = $delete->compare('relationship', '=', self::RELATIONSHIP_PREFIX . ':' . $method, ELGG_VALUE_STRING);
				$ors[] = $delete->compare('relationship', 'like', self::RELATIONSHIP_PREFIX . ':%:' . $method, ELGG_VALUE_STRING);
			}
			
			$delete->andWhere($delete->merge($ors, 'OR'));
		}
		
		return (bool) $this->db->deleteData($delete);
	}
	
	/**
	 * Get all subscribers of the target guid
	 *
	 * @param int   $target_guid the entity of the subscriptions
	 * @param array $methods     (optional) The delivery method of the notifications
	 *
	 * @return \ElggEntity[]
	 */
	public function getSubscribers(int $target_guid, array $methods = []): array {
		return elgg_get_entities([
			'limit' => false,
			'wheres' => [
				function(QueryBuilder $qb, $main_alias) use ($target_guid) {
					$rel = $qb->joinRelationshipTable($main_alias, 'guid', null, true);
					
					return $qb->compare("{$rel}.guid_two", '=', $target_guid, ELGG_VALUE_GUID);
				},
				function(QueryBuilder $qb, $main_alias) use ($methods) {
					$rel = $qb->joinRelationshipTable($main_alias, 'guid', null, true);
					
					if (empty($methods)) {
						return $qb->compare("{$rel}.relationship", 'like', self::RELATIONSHIP_PREFIX . ':%', ELGG_VALUE_STRING);
					}
					
					$ors = [];
					foreach ($methods as $method) {
						$ors[] = $qb->compare("{$rel}.relationship", '=', self::RELATIONSHIP_PREFIX . ':' . $method, ELGG_VALUE_STRING);
						$ors[] = $qb->compare("{$rel}.relationship", 'like', self::RELATIONSHIP_PREFIX . ':%:' . $method, ELGG_VALUE_STRING);
					}
					
					return $qb->merge($ors, 'OR');
				},
			],
		]);
	}
	
	/**
	 * Get the current subscriptions for the given entity
	 *
	 * @param int    $target_guid The GUID of the entity to get subscriptions for
	 * @param int    $user_guid   The GUID of the user to check subscriptions for
	 * @param string $methods     The delivery method of the notifications
	 * @param string $type        (optional) entity type
	 * @param string $subtype     (optional) entity subtype
	 * @param string $action      (optional) notification action (eg. 'create')
	 *
	 * @return \ElggRelationship[]
	 * @throws InvalidArgumentException
	 */
	public function getEntitySubscriptions(int $target_guid = 0, int $user_guid = 0, array $methods = [], string $type = null, string $subtype = null, string $action = null): array {
		$this->assertValidTypeSubtypeActionForSubscription($type, $subtype, $action);
		
		if (empty($target_guid) && empty($user_guid)) {
			return [];
		}
		
		if (empty($target_guid)) {
			$target_guid = ELGG_ENTITIES_ANY_VALUE;
		}
		
		return elgg_get_relationships([
			'limit' => false,
			'wheres' => [
				function(QueryBuilder $qb, $main_alias) use ($target_guid) {
					if (empty($target_guid)) {
						return;
					}
					
					return $qb->compare("{$main_alias}.guid_two", '=', $target_guid, ELGG_VALUE_GUID);
				},
				function(QueryBuilder $qb, $main_alias) use ($user_guid) {
					if (empty($user_guid)) {
						return;
					}
					
					return $qb->compare("{$main_alias}.guid_one", '=', $user_guid, ELGG_VALUE_GUID);
				},
				function(QueryBuilder $qb, $main_alias) use ($methods, $type, $subtype, $action) {
					if (empty($methods) && (empty($type) || empty($subtype) || empty($action))) {
						return $qb->compare("{$main_alias}.relationship", 'like', self::RELATIONSHIP_PREFIX . ':%', ELGG_VALUE_STRING);
					}
					
					if (!empty($methods)) {
						if (empty($type) || empty($subtype) || empty($action)) {
							// only methods
							$ors = [];
							foreach ($methods as $method) {
								$ors[] = $qb->compare("{$main_alias}.relationship", '=', self::RELATIONSHIP_PREFIX . ':' . $method, ELGG_VALUE_STRING);
								$ors[] = $qb->compare("{$main_alias}.relationship", 'like', self::RELATIONSHIP_PREFIX . ':%:' . $method, ELGG_VALUE_STRING);
							}
							
							return $qb->merge($ors, 'OR');
						} else {
							// with type limitation
							return $qb->compare("{$main_alias}.relationship", 'in', $this->getMethodRelationships($methods, $type, $subtype, $action), ELGG_VALUE_STRING);
						}
					}
					
					// only type limitation
					return $qb->compare("{$main_alias}.relationship", 'like', self::RELATIONSHIP_PREFIX . ":{$type}:{$subtype}:{$action}:%", ELGG_VALUE_STRING);
				},
			],
		]);
	}
	
	/**
	 * Mute notifications about events affecting the target
	 *
	 * @param int $user_guid   The GUID of the user to mute notifcations for
	 * @param int $target_guid The GUID of the entity to for which to mute notifications
	 *
	 * @return bool
	 */
	public function muteNotifications(int $user_guid, int $target_guid): bool {
		// remove all current subscriptions
		$this->removeSubscriptions($user_guid, $target_guid);
		
		return $this->relationshipsTable->add($user_guid, self::MUTE_NOTIFICATIONS_RELATIONSHIP, $target_guid);
	}
	
	/**
	 * No longer nute notifications about events affecting the target
	 *
	 * @param int $user_guid   The GUID of the user to unmute notifcations for
	 * @param int $target_guid The GUID of the entity to for which to unmute notifications
	 *
	 * @return bool
	 */
	public function unmuteNotifications(int $user_guid, int $target_guid): bool {
		return $this->relationshipsTable->remove($user_guid, self::MUTE_NOTIFICATIONS_RELATIONSHIP, $target_guid);
	}
	
	/**
	 * Check if the user has notifications muted about events affecting the target
	 *
	 * @param int $user_guid   The GUID of the user to check muted notifcations for
	 * @param int $target_guid The GUID of the entity to for which to check muted notifications
	 *
	 * @return bool
	 */
	public function hasMutedNotifications(int $user_guid, int $target_guid): bool {
		return $this->relationshipsTable->check($user_guid, self::MUTE_NOTIFICATIONS_RELATIONSHIP, $target_guid) instanceof \ElggRelationship;
	}
	
	/**
	 * Apply filtering to subscriptions, like muted notifications etc
	 *
	 * @param array             $subscriptions List of subscribers to filter
	 * @param NotificationEvent $event         Notification event from which to get information
	 *
	 * @return array
	 */
	public function filterSubscriptions(array $subscriptions, NotificationEvent $event): array {
		// make methods unique and remove emptys
		$subscriptions = array_map(function($user_methods) {
			return array_values(array_filter(array_unique($user_methods)));
		}, $subscriptions);
		
		// apply filters
		$subscriptions = $this->filterMutedNotifications($subscriptions, $event);
		$subscriptions = $this->filterDelayedEmailSubscribers($subscriptions);
		$subscriptions = $this->filterTimedMutedSubscribers($subscriptions);
		
		return $subscriptions;
	}
	
	/**
	 * Filter subscriptions based on muted notification settings related to the notification event
	 *
	 * This filters out muted notifications based on:
	 * - Event actor
	 * - Event entity
	 * - Event entity owner
	 * - Event entity container
	 *
	 * @param array             $subscriptions List of subscribers to filter
	 * @param NotificationEvent $event         Notification event from which to get information
	 *
	 * @return array
	 */
	protected function filterMutedNotifications(array $subscriptions, NotificationEvent $event): array {
		$guids_to_check = [];
		
		// Event actor
		$guids_to_check[] = $event->getActorGUID();
		
		// Event object
		$entity = false;
		$object = $event->getObject();
		if ($object instanceof \ElggEntity) {
			$entity = $object;
		} elseif ($object instanceof \ElggAnnotation) {
			$entity = $object->getEntity();
		}
		
		if ($entity instanceof \ElggEntity) {
			$guids_to_check[] = $entity->guid;
			$guids_to_check[] = $entity->owner_guid;
			$guids_to_check[] = $entity->container_guid;
		}
		
		// are there GUIDs to check
		$guids_to_check = array_filter($guids_to_check);
		if (empty($guids_to_check)) {
			return $subscriptions;
		}
		
		// get muted relations
		$select = Select::fromTable('entity_relationships');
		$select->select('guid_one')
			->where($select->compare('relationship', '=', self::MUTE_NOTIFICATIONS_RELATIONSHIP, ELGG_VALUE_STRING))
			->andWhere($select->compare('guid_two', 'in', $guids_to_check, ELGG_VALUE_GUID));
		
		$muted = $this->db->getData($select, function($row) {
			return (int) $row->guid_one;
		});
		
		// filter subscriptions
		return array_diff_key($subscriptions, array_flip($muted));
	}
	
	/**
	 * When a user has both 'email' and 'delayed_email' subscription remove the delayed email as it would be a duplicate
	 *
	 * @param array $subscriptions List of subscribers to filter
	 *
	 * @return array
	 */
	protected function filterDelayedEmailSubscribers(array $subscriptions): array {
		return array_map(function ($user_methods) {
			if (!in_array('delayed_email', $user_methods) || !in_array('email', $user_methods)) {
				return $user_methods;
			}
			$pos = array_search('delayed_email', $user_methods);
			unset($user_methods[$pos]);
			
			return array_values($user_methods);
		}, $subscriptions);
	}
	
	/**
	 * Filter users who have set a period in which not to receive notifications
	 *
	 * @param array $subscriptions List of subscribers to filter
	 *
	 * @return array
	 */
	protected function filterTimedMutedSubscribers(array $subscriptions): array {
		$muted = Entities::find([
			'type' => 'user',
			'guids' => array_keys($subscriptions),
			'limit' => false,
			'callback' => function ($row) {
				return (int) $row->guid;
			},
			'private_setting_name_value_pairs' => [
				[
					'name' => 'timed_muting_start',
					'value' => time(),
					'operand' => '<=',
				],
				[
					'name' => 'timed_muting_end',
					'value' => time(),
					'operand' => '>=',
				],
			],
		]);
		
		return array_diff_key($subscriptions, array_flip($muted));
	}

	/**
	 * Get subscription records from the database
	 *
	 * Records are an object with two vars: guid and methods with the latter
	 * being a comma-separated list of subscription relationship names.
	 *
	 * @param int[]  $container_guid The GUID of the subscription target
	 * @param array  $methods        Notification methods
	 * @param string $type           (optional) entity type
	 * @param string $subtype        (optional) entity subtype
	 * @param string $action         (optional) notification action (eg. 'create')
	 * @param int    $actor_guid     (optional) Notification event actor to exclude from the database subscriptions
	 *
	 * @return array
	 */
	protected function getSubscriptionRecords(array $container_guid, array $methods, string $type = null, string $subtype = null, string $action = null, int $actor_guid = 0): array {
		// create IN clause
		$rels = $this->getMethodRelationships($methods, $type, $subtype, $action);
		if (!$rels) {
			return [];
		}
		
		$container_guid = array_unique(array_filter($container_guid));
		if (empty($container_guid)) {
			return [];
		}

		$select = Select::fromTable('entity_relationships');
		$select->select('guid_one AS guid')
			->addSelect("GROUP_CONCAT(relationship SEPARATOR ',') AS methods")
			->where($select->compare('guid_two', 'in', $container_guid, ELGG_VALUE_GUID))
			->andWhere($select->compare('relationship', 'in', $rels, ELGG_VALUE_STRING))
			->groupBy('guid_one');
		
		if (!empty($actor_guid)) {
			$select->andWhere($select->compare('guid_one', '!=', $actor_guid, ELGG_VALUE_GUID));
		}
		
		return $this->db->getData($select);
	}

	/**
	 * Get the relationship names for notifications
	 *
	 * @param array  $methods Notification methods
	 * @param string $type    (optional) entity type
	 * @param string $subtype (optional) entity subtype
	 * @param string $action  (optional) notification action (eg. 'create')
	 *
	 * @return array
	 */
	protected function getMethodRelationships(array $methods, string $type = null, string $subtype = null, string $action = null): array {
		$prefix = self::RELATIONSHIP_PREFIX;
		
		$names = [];
		foreach ($methods as $method) {
			$names[] = "{$prefix}:{$method}";
			
			if (!empty($type) && !empty($subtype) && !empty($action)) {
				$names[] = "{$prefix}:{$type}:{$subtype}:{$action}:{$method}";
			}
		}
		
		return $names;
	}
	
	/**
	 * Validate subscription input for type, subtype and action
	 *
	 * @param string $type    entity type
	 * @param string $subtype entity subtype
	 * @param string $action  notification action (eg. 'create')
	 *
	 * @return void
	 * @throws InvalidArgumentException
	 */
	protected function assertValidTypeSubtypeActionForSubscription($type, $subtype, $action): void {
		if (empty($type) && empty($subtype) && empty($action)) {
			// all empty, this is valid
			return;
		}
		
		if (!empty($type) && !empty($subtype) && !empty($action)) {
			// all set, also valid
			return;
		}
		
		throw new InvalidArgumentException('$type, $subtype and $action need to all be empty or all have a value');
	}
}
