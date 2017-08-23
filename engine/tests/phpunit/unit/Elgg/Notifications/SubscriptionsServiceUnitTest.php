<?php

namespace Elgg\Notifications;

/**
 * @group NotificationsService
 * @group UnitTests
 */
class SubscriptionsServiceUnitTest extends \Elgg\UnitTestCase {

	private $containerGuid;

	public function up() {
		$this->containerGuid = 42;

		// mock ElggObject that has a container guid
		$object = $this->createMock('\ElggObject');

		$object->expects($this->any())
			->method('getContainerGUID')
			->will($this->returnValue($this->containerGuid));

		// mock event that holds the mock object
		$this->event = $this->createMock('\Elgg\Notifications\Event');

		$this->event->expects($this->any())
			->method('getObject')
			->will($this->returnValue($object));

		$this->db = $this->createMock('\Elgg\Database');

		$this->db->expects($this->any())
			->method('sanitizeString')
			->will($this->returnArgument(0));

		$this->db->expects($this->any())
			->method('sanitizeInt')
			->will($this->returnArgument(0));
	}

	public function down() {

	}

	public function testGetSubscriptionsWithNoMethodsRegistered() {
		$service = new \Elgg\Notifications\SubscriptionsService($this->db);
		$this->assertEquals([], $service->getSubscriptions($this->event));
	}

	public function testGetSubscriptionsWithBadObject() {
		$this->event = $this->createMock(
			'\Elgg\Notifications\Event', ['getObject'], [], '', false);
		$this->event->expects($this->any())
			->method('getObject')
			->will($this->returnValue(null));
		$service = new \Elgg\Notifications\SubscriptionsService($this->db);
		$service->methods = [
			'one',
			'two'
		];
		$this->assertEquals([], $service->getSubscriptions($this->event));
	}

	public function testQueryGenerationForRetrievingSubscriptionRelationships() {
		$methods = [
			'apples',
			'bananas'
		];

		$query = "SELECT guid_one AS guid, GROUP_CONCAT(relationship SEPARATOR ',') AS methods
			FROM {$this->db->prefix}entity_relationships
			WHERE guid_two = $this->containerGuid AND
					relationship IN ('notifyapples','notifybananas') GROUP BY guid_one";

		$this->db->expects($this->once())
			->method('getData')
			->with($this->equalTo($query))
			->will($this->returnValue([]));

		$service = new \Elgg\Notifications\SubscriptionsService($this->db);

		$service->methods = $methods;
		$this->assertEquals([], $service->getSubscriptions($this->event));
	}

	public function testGetSubscriptionsWithProperInput() {
		$methods = [
			'apples',
			'bananas'
		];
		$queryResult = [
			$this->createObjectFromArray([
				'guid' => '22',
				'methods' => 'notifyapples'
			]),
			$this->createObjectFromArray([
				'guid' => '567',
				'methods' => 'notifybananas,notifyapples'
			]),
		];
		$subscriptions = [
			22 => ['apples'],
			567 => [
				'bananas',
				'apples'
			],
		];

		$this->db->expects($this->once())
			->method('getData')
			->will($this->returnValue($queryResult));

		$service = new \Elgg\Notifications\SubscriptionsService($this->db);

		$service->methods = $methods;
		$this->assertEquals($subscriptions, $service->getSubscriptions($this->event));
	}

	public function testGetSubscriptionsForContainerWithNoMethodsRegistered() {
		$container_guid = 132;
		$service = new \Elgg\Notifications\SubscriptionsService($this->db);
		$this->assertEquals([], $service->getSubscriptionsForContainer($container_guid));
	}

	public function testGetSubscriptionsForContainerWithProperInput() {
		$container_guid = 132;

		$methods = [
			'apples',
			'bananas'
		];
		$queryResult = [
			$this->createObjectFromArray([
				'guid' => '22',
				'methods' => 'notifyapples'
			]),
			$this->createObjectFromArray([
				'guid' => '567',
				'methods' => 'notifybananas,notifyapples'
			]),
		];
		$subscriptions = [
			22 => ['apples'],
			567 => [
				'bananas',
				'apples'
			],
		];
		$this->db->expects($this->once())
			->method('getData')
			->will($this->returnValue($queryResult));
		$service = new \Elgg\Notifications\SubscriptionsService($this->db);

		$service->methods = $methods;
		$this->assertEquals($subscriptions, $service->getSubscriptionsForContainer($container_guid));
	}

	protected function createObjectFromArray(array $data) {
		$obj = new \stdClass();
		foreach ($data as $key => $value) {
			$obj->$key = $value;
		}

		return $obj;
	}

}
