<?php

namespace Elgg\Notifications;

use Elgg\Database;
use Elgg\Database\Select;
use Elgg\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @group NotificationsService
 * @group UnitTests
 */
class SubscriptionsServiceUnitTest extends \Elgg\UnitTestCase {

	/**
	 * @var \ElggObject
	 */
	protected $object;
	
	/**
	 * @var SubscriptionsService
	 */
	protected $service;
	
	/**
	 * @var Database
	 */
	protected $db;
	
	/**
	 * @var MockObject
	 */
	protected $event;

	public function up() {
		$this->object = $this->createObject();

		// mock event that holds the mock object
		$this->event = $this->createMock('\Elgg\Notifications\SubscriptionNotificationEvent');

		$this->event->expects($this->any())
			->method('getObject')
			->will($this->returnValue($this->object));
		
		$this->event->expects($this->any())
			->method('getActorGUID')
			->will($this->returnValue(0));

		$this->db = $this->createMock('\Elgg\Database');
		
		$this->service = $this->setupService();
	}

	public function down() {

	}
	
	/**
	 * Create a SubscriptionService
	 *
	 * @return \Elgg\Notifications\SubscriptionsService
	 */
	protected function setupService() {
		return new SubscriptionsService(
			$this->db,
			_elgg_services()->relationshipsTable,
			_elgg_services()->hooks
		);
	}
	
	/**
	 * Convert an array to an object
	 *
	 * @param array $data the array with data
	 *
	 * @return \stdClass
	 */
	protected function createObjectFromArray(array $data) {
		$obj = new \stdClass();
		foreach ($data as $key => $value) {
			$obj->$key = $value;
		}
		
		return $obj;
	}

	public function testGetSubscriptionsWithNoMethodsProvided() {
		$this->assertEquals([], $this->service->getNotificationEventSubscriptions($this->event, []));
	}

	public function testGetSubscriptionsWithBadObject() {
		$this->event = $this->createMock(
			'\Elgg\Notifications\SubscriptionNotificationEvent', ['getObject'], [], '', false);
		$this->event->expects($this->any())
			->method('getObject')
			->will($this->returnValue(null));
		
		$methods = [
			'one',
			'two'
		];
		$this->assertEquals([], $this->service->getNotificationEventSubscriptions($this->event, $methods));
	}

	public function testQueryGenerationForRetrievingSubscriptionRelationships() {
		$methods = [
			'apples',
			'bananas'
		];

		$guids = array_unique(array_filter([$this->object->container_guid, $this->object->guid]));
		$select = Select::fromTable('entity_relationships');
		$select->select('guid_one AS guid')
			->addSelect("GROUP_CONCAT(relationship SEPARATOR ',') AS methods")
			->where($select->compare('guid_two', 'in', $guids, ELGG_VALUE_GUID))
			->andWhere($select->compare('relationship', 'in', ['notify:apples', 'notify:bananas'], ELGG_VALUE_STRING))
			->groupBy('guid_one');
		
		$this->db->expects($this->once())
			->method('getData')
			->with($this->equalTo($select))
			->will($this->returnValue([]));

		$this->assertEquals([], $this->service->getNotificationEventSubscriptions($this->event, $methods));
	}

	public function testGetSubscriptionsWithProperInput() {
		$methods = [
			'apples',
			'bananas'
		];
		$queryResult = [
			$this->createObjectFromArray([
				'guid' => '22',
				'methods' => 'notify:apples'
			]),
			$this->createObjectFromArray([
				'guid' => '567',
				'methods' => 'notify:bananas,notify:apples'
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

		$this->assertEquals($subscriptions, $this->service->getNotificationEventSubscriptions($this->event, $methods));
	}

	public function testGetSubscriptionsForContainerWithNoMethodsProvided() {
		$container_guid = 132;
		
		$this->assertEquals([], $this->service->getSubscriptionsForContainer($container_guid, []));
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
				'methods' => 'notify:apples'
			]),
			$this->createObjectFromArray([
				'guid' => '567',
				'methods' => 'notify:bananas,notify:apples'
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
		
		$this->assertEquals($subscriptions, $this->service->getSubscriptionsForContainer($container_guid, $methods));
	}
	
	/**
	 * @dataProvider invalidTypeSubtypeActionProvider
	 */
	public function testAddSubscriptionThrowsExceptionWithInvalidTypeSubtypeActionInput($type, $subtype, $action) {
		$this->expectException(InvalidArgumentException::class);
		$this->service->addSubscription($this->object->owner_guid, 'apples', $this->object->guid, $type, $subtype, $action);
	}
	
	/**
	 * @dataProvider invalidTypeSubtypeActionProvider
	 */
	public function testHasSubscriptionThrowsExceptionWithInvalidTypeSubtypeActionInput($type, $subtype, $action) {
		$this->expectException(InvalidArgumentException::class);
		$this->service->hasSubscription($this->object->owner_guid, 'apples', $this->object->guid, $type, $subtype, $action);
	}
	
	/**
	 * @dataProvider invalidTypeSubtypeActionProvider
	 */
	public function testRemoveSubscriptionThrowsExceptionWithInvalidTypeSubtypeActionInput($type, $subtype, $action) {
		$this->expectException(InvalidArgumentException::class);
		$this->service->removeSubscription($this->object->owner_guid, 'apples', $this->object->guid, $type, $subtype, $action);
	}
	
	/**
	 * @dataProvider invalidTypeSubtypeActionProvider
	 */
	public function testGetEntitySubscriptionsThrowsExceptionWithInvalidTypeSubtypeActionInput($type, $subtype, $action) {
		$this->expectException(InvalidArgumentException::class);
		$this->service->getEntitySubscriptions($this->object->guid, $this->object->owner_guid, ['apples'], $type, $subtype, $action);
	}
	
	public function invalidTypeSubtypeActionProvider() {
		return [
			['foo', null, null],
			[null, 'foo', null],
			[null, null, 'foo'],
			['foo', 'bar', null],
			['foo', null, 'bar'],
			[null, 'foo', 'bar'],
		];
	}
}
