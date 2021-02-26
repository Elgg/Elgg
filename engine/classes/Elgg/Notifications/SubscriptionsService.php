<?php

namespace Elgg\Notifications;

use Elgg\Database;
use Elgg\Database\RelationshipsTable;
use Elgg\PluginHooksService;
use Elgg\Database\Select;

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
	 * @param NotificationEvent $event   Notification event
	 * @param array             $methods Notification methods
	 *
	 * @return array
	 */
	public function getSubscriptions(NotificationEvent $event, array $methods) {

		if (empty($methods)) {
			return [];
		}

		$object = $event->getObject();
		if (!$object instanceof \ElggData) {
			return [];
		}

		$subscriptions = [];

		// get subscribers only for \ElggEntity if it isn't private
		if (($object instanceof \ElggEntity) && ($object->access_id !== ACCESS_PRIVATE)) {
			$records = $this->getSubscriptionRecords($object->getContainerGUID(), $methods, $object->type, $object->subtype, $event->getAction());
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
		}

		$params = [
			'event' => $event,
			'origin' => Notification::ORIGIN_SUBSCRIPTIONS,
			'methods' => $methods,
		];
		return $this->hooks->trigger('get', 'subscriptions', $params, $subscriptions);
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

		$records = $this->getSubscriptionRecords($container_guid, $methods);
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
	 */
	public function addSubscription(int $user_guid, string $method, int $target_guid, string $type = null, string $subtype = null, string $action = null) {
		$rel = [
			self::RELATIONSHIP_PREFIX,
		];
		
		if (!empty($type) && !empty($subtype) && !empty($action)) {
			$rel[] = $type;
			$rel[] = $subtype;
			$rel[] = $action;
		}
		
		$rel[] = $method;
		
		return $this->relationshipsTable->add($user_guid, implode(':', $rel), $target_guid);
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
	 */
	public function removeSubscription(int $user_guid, string $method, int $target_guid, string $type = null, string $subtype = null, string $action = null) {
		$rel = [
			self::RELATIONSHIP_PREFIX,
		];
		
		if (!empty($type) && !empty($subtype) && !empty($action)) {
			$rel[] = $type;
			$rel[] = $subtype;
			$rel[] = $action;
		}
		
		$rel[] = $method;
		
		return $this->relationshipsTable->remove($user_guid, implode(':', $rel), $target_guid);
	}

	/**
	 * Get subscription records from the database
	 *
	 * Records are an object with two vars: guid and methods with the latter
	 * being a comma-separated list of subscription relationship names.
	 *
	 * @param int    $container_guid The GUID of the subscription target
	 * @param array  $methods        Notification methods
	 * @param string $type           (optional) entity type
	 * @param string $subtype        (optional) entity subtype
	 * @param string $action         (optional) notification action (eg. 'create')
	 *
	 * @return array
	 */
	protected function getSubscriptionRecords(int $container_guid, array $methods, string $type = null, string $subtype = null, string $action = null): array {
		// create IN clause
		$rels = $this->getMethodRelationships($methods, $type, $subtype, $action);
		if (!$rels) {
			return [];
		}

		$select = Select::fromTable('entity_relationships');
		$select->select('guid_one AS guid')
			->addSelect("GROUP_CONCAT(relationship SEPARATOR ',') AS methods")
			->where($select->compare('guid_two', '=', $container_guid, ELGG_VALUE_GUID))
			->andWhere($select->compare('relationship', 'in', $rels, ELGG_VALUE_STRING));
		
		return $this->db->getData($select);
	}

	/**
	 * Get the relationship names for notifications
	 *
	 * @param array  $methods  Notification methods
	 * @param string $type     (optional) entity type
	 * @param string $subtype  (optional) entity subtype
	 * @param string $action   (optional) notification action (eg. 'create')
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
}
