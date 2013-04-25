<?php

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage Notifications
 * @since      1.9.0
 */
class Elgg_Notifications_SubscriptionsService {

	/**
	 *  Elgg has historically stored subscriptions as relationships with the prefix 'notify'
	 */
	const RELATIONSHIP_PREFIX = 'notify';

	/** @var array Notification delivery method names */
	protected $methods;

	/** @var Elgg_Database */
	protected $db;

	/**
	 * Constructor
	 *
	 * @param Elgg_Database $db      Database object
	 * @param array         $methods Notification delivery method names
	 */
	public function __construct(Elgg_Database $db, array $methods = array()) {
		$this->db = $db;
		$this->setNotificationMethods($methods);
	}

	/**
	 * Set the delivery method names
	 *
	 * @param array $methods Array of delivery method names
	 * @return void
	 */
	public function setNotificationMethods(array $methods) {
		$prefix = self::RELATIONSHIP_PREFIX;
		$this->methods = array();
		foreach ($methods as $method) {
			$this->methods[] = "$prefix$method";
		}
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
	 * @param Elgg_Notifications_Event $event Notification event
	 * @return array
	 */
	public function getSubscriptions(Elgg_Notifications_Event $event) {

		$subscriptions = array();

		if (!$this->methods) {
			return $subscriptions;
		}

		$object = $event->getObject();
		if (!$object) {
			return $subscriptions;
		}

		$prefixLength = strlen(self::RELATIONSHIP_PREFIX);
		$records = $this->getRecords($object->getContainerGUID());
		foreach ($records as $record) {
			$deliveryMethods = explode(',', $record->methods);
			$subscriptions[$record->guid] = substr_replace($deliveryMethods, '', 0, $prefixLength);
		}

		return $subscriptions;
	}

	/**
	 * Get subscription records from the database
	 *
	 * Records are an object with two vars: guid and methods with the latter
	 * being a comma-separated list of subscription relationship names.
	 *
	 * @param int $container_guid The GUID of the subscription target
	 * @return array
	 */
	protected function getRecords($container_guid) {
		if (!$this->methods) {
			return array();
		}

		$container_guid = sanitize_int($container_guid);

		// create IN clause
		$methods = $this->methods;
		array_walk($methods, 'sanitize_string');
		$methods_string = "'" . implode("','", $methods) . "'";

		$db_prefix = elgg_get_config('dbprefix');
		$query = "SELECT guid_one AS guid, GROUP_CONCAT(relationship SEPARATOR ',') AS methods
			FROM {$db_prefix}entity_relationships
			WHERE guid_two = $container_guid AND
					relationship IN ($methods_string) GROUP BY guid_one";
		return $this->db->getData($query);
	}
}
