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
			$prefixLength = strlen(self::RELATIONSHIP_PREFIX);
			
			$records = $this->getSubscriptionRecords($object->getContainerGUID(), $methods);
			foreach ($records as $record) {
				if (empty($record->guid)) {
					// happens when no records are found
					continue;
				}
				$deliveryMethods = explode(',', $record->methods);
				$subscriptions[$record->guid] = substr_replace($deliveryMethods, '', 0, $prefixLength);
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

		$prefixLength = strlen(self::RELATIONSHIP_PREFIX);
		
		$records = $this->getSubscriptionRecords($container_guid, $methods);
		foreach ($records as $record) {
			if (empty($record->guid)) {
				// happens when no records are found
				continue;
			}
			$deliveryMethods = explode(',', $record->methods);
			$subscriptions[$record->guid] = substr_replace($deliveryMethods, '', 0, $prefixLength);
		}

		return $subscriptions;
	}

	/**
	 * Subscribe a user to notifications about a target entity
	 *
	 * This method will return false if the subscription already exists.
	 *
	 * @param int    $userGuid   The GUID of the user to subscribe to notifications
	 * @param string $method     The delivery method of the notifications
	 * @param int    $targetGuid The entity to receive notifications about
	 *
	 * @return bool
	 */
	public function addSubscription(int $userGuid, string $method, int $targetGuid) {
		$prefix = self::RELATIONSHIP_PREFIX;
		
		return $this->relationshipsTable->add($userGuid, "{$prefix}{$method}", $targetGuid);
	}

	/**
	 * Unsubscribe a user to notifications about a target entity
	 *
	 * @param int    $userGuid   The GUID of the user to unsubscribe to notifications
	 * @param string $method     The delivery method of the notifications to stop
	 * @param int    $targetGuid The entity to stop receiving notifications about
	 *
	 * @return bool
	 */
	public function removeSubscription(int $userGuid, string $method, int $targetGuid) {
		$prefix = self::RELATIONSHIP_PREFIX;
		
		return $this->relationshipsTable->remove($userGuid, "{$prefix}{$method}", $targetGuid);
	}

	/**
	 * Get subscription records from the database
	 *
	 * Records are an object with two vars: guid and methods with the latter
	 * being a comma-separated list of subscription relationship names.
	 *
	 * @param int   $container_guid The GUID of the subscription target
	 * @param array $methods        Notification methods
	 *
	 * @return array
	 */
	protected function getSubscriptionRecords(int $container_guid, array $methods) {
		// create IN clause
		$rels = $this->getMethodRelationships($methods);
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
	 * @param array $methods Notification methods
	 *
	 * @return array
	 */
	protected function getMethodRelationships(array $methods) {
		$prefix = self::RELATIONSHIP_PREFIX;
		
		$names = [];
		foreach ($methods as $method) {
			$names[] = "{$prefix}{$method}";
		}
		
		return $names;
	}
}
