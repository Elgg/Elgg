<?php

class Elgg_Notifications_SubscriptionsServiceTest extends PHPUnit_Framework_TestCase {

	public function setUp() {
		$this->containerGuid = 42;

		// mock ElggObject that has a container guid
		$object = $this->getMock(
				'ElggObject',
				array('getContainerGUID'),
				array(),
				'',
				false);
		$object->expects($this->any())
				->method('getContainerGUID')
				->will($this->returnValue($this->containerGuid));

		// mock event that holds the mock object
		$this->event = $this->getMock(
				'Elgg_Notifications_Event',
				array('getObject'),
				array(),
				'',
				false);
		$this->event->expects($this->any())
				->method('getObject')
				->will($this->returnValue($object));
		$this->db = $this->getMock('Elgg_Database',
				array('getData', 'getTablePrefix', 'sanitizeString'),
				array(),
				'',
				false
		);
		$this->db->expects($this->any())
				->method('getTablePrefix')
				->will($this->returnValue('elgg_'));
		$this->db->expects($this->any())
				->method('sanitizeString')
				->will($this->returnArgument(0));

		// Event class has dependency on elgg_get_logged_in_user_guid()
		_elgg_services()->setValue('session', new ElggSession(new Elgg_Http_MockSessionStorage()));
	}

	public function testGetSubscriptionsWithNoMethodsRegistered() {
		$service = new Elgg_Notifications_SubscriptionsService($this->db);
		$this->assertEquals(array(), $service->getSubscriptions($this->event));
	}

	public function testGetSubscriptionsWithBadObject() {
		$this->event = $this->getMock(
				'Elgg_Notifications_Event',
				array('getObject'),
				array(),
				'',
				false);
		$this->event->expects($this->any())
				->method('getObject')
				->will($this->returnValue(null));
		$service = new Elgg_Notifications_SubscriptionsService($this->db);
		$service->methods = array('one', 'two');
		$this->assertEquals(array(), $service->getSubscriptions($this->event));
	}

	public function testQueryGenerationForRetrievingSubscriptionRelationships() {
		$methods = array('apples', 'bananas');
		$query = "SELECT guid_one AS guid, GROUP_CONCAT(relationship SEPARATOR ',') AS methods
			FROM elgg_entity_relationships
			WHERE guid_two = $this->containerGuid AND
					relationship IN ('notifyapples','notifybananas') GROUP BY guid_one";
		$this->db->expects($this->once())
				->method('getData')
				->with($this->equalTo($query))
				->will($this->returnValue(array()));
		$service = new Elgg_Notifications_SubscriptionsService($this->db);

		$service->methods = $methods;
		$this->assertEquals(array(), $service->getSubscriptions($this->event));
	}

	public function testGetSubscriptionsWithProperInput() {
		$methods = array('apples', 'bananas');
		$queryResult = array(
			$this->createObjectFromArray(array('guid' => '22', 'methods' => 'notifyapples')),
			$this->createObjectFromArray(array('guid' => '567', 'methods' => 'notifybananas,notifyapples')),
		);
		$subscriptions = array(
			22 => array('apples'),
			567 => array('bananas', 'apples'),
		);
		$this->db->expects($this->once())
				->method('getData')
				->will($this->returnValue($queryResult));
		$service = new Elgg_Notifications_SubscriptionsService($this->db);

		$service->methods = $methods;
		$this->assertEquals($subscriptions, $service->getSubscriptions($this->event));
	}

	public function testGetSubscriptionsForContainerWithNoMethodsRegistered() {
		$container_guid = 132;
		$service = new Elgg_Notifications_SubscriptionsService($this->db);
		$this->assertEquals(array(), $service->getSubscriptionsForContainer($container_guid));
	}

	public function testGetSubscriptionsForContainerWithProperInput() {
		$container_guid = 132;

		$methods = array('apples', 'bananas');
		$queryResult = array(
			$this->createObjectFromArray(array('guid' => '22', 'methods' => 'notifyapples')),
			$this->createObjectFromArray(array('guid' => '567', 'methods' => 'notifybananas,notifyapples')),
		);
		$subscriptions = array(
			22 => array('apples'),
			567 => array('bananas', 'apples'),
		);
		$this->db->expects($this->once())
				->method('getData')
				->will($this->returnValue($queryResult));
		$service = new Elgg_Notifications_SubscriptionsService($this->db);

		$service->methods = $methods;
		$this->assertEquals($subscriptions, $service->getSubscriptionsForContainer($container_guid));
	}

	protected function createObjectFromArray(array $data) {
		$obj = new stdClass();
		foreach ($data as $key => $value) {
			$obj->$key = $value;
		}
		return $obj;
	}
}
