<?php

namespace Elgg\Database;

use Elgg\Exceptions\Database\UserFetchFailureException;

class EntityTableUnitTest extends \Elgg\UnitTestCase {

	public function testCanGetUserForPermissionsCheckWhileLoggedOut() {
		$this->assertNull(_elgg_services()->entityTable->getUserForPermissionsCheck());
		
		$user = $this->createUser();
		$this->assertElggDataEquals($user, _elgg_services()->entityTable->getUserForPermissionsCheck($user->guid));
	}

	public function testCanGetUserForPermissionsCheckWhileLoggedIn() {
		$user = $this->createUser();
		_elgg_services()->session_manager->setLoggedInUser($user);

		$this->assertElggDataEquals($user, _elgg_services()->session_manager->getLoggedInUser());
		
		$this->assertElggDataEquals($user, _elgg_services()->entityTable->getUserForPermissionsCheck());

		$user2 = $this->createUser();
		$this->assertElggDataEquals($user2, _elgg_services()->entityTable->getUserForPermissionsCheck($user2->guid));
	}

	public function testThrowsWhenGettingUserForPermissionsCheckWithNonUserGuid() {
		$object = $this->createObject();
		
		$this->expectException(UserFetchFailureException::class);
		_elgg_services()->entityTable->getUserForPermissionsCheck($object->guid);
	}

	public function testCanUpdateLastAction() {

		_elgg_services()->entityTable->setCurrentTime();

		$time = strtotime('-1 day');

		$object = $this->createObject([
			'time_created' => $time,
			'time_updated' => $time,
			'last_action' => $time,
		]);
		
		$last_action = $object->updateLastAction();
		$this->assertEquals($last_action, $object->last_action);
		
		$new_last_action = strtotime('+2 days', $time);
		
		$update = Update::table(EntityTable::TABLE_NAME);
		$update->set('last_action', $update->param($new_last_action, ELGG_VALUE_TIMESTAMP))
			->where($update->compare('guid', '=', $object->guid, ELGG_VALUE_GUID));
		
		_elgg_services()->db->addQuerySpec([
			'sql' => $update->getSQL(),
			'params' => $update->getParameters(),
			'row_count' => 1,
		]);

		$last_action = $object->updateLastAction($new_last_action);
		$this->assertEquals($last_action, $new_last_action);
		$this->assertEquals($last_action, $object->last_action);
	}

	public function testCanUpdateTimeDeleted() {
		// Set up the current time
		_elgg_services()->entityTable->setCurrentTime();
		$currentTime = _elgg_services()->entityTable->getCurrentTime()->getTimestamp();
		
		// Create an object
		$object = $this->createObject();
		
		// Create the update query for empty params
		$update = Update::table(EntityTable::TABLE_NAME);
		$update->set('time_deleted', $update->param($currentTime, ELGG_VALUE_TIMESTAMP))
			->where($update->compare('guid', '=', $object->guid, ELGG_VALUE_GUID));
		
		// Add the testing query specification
		$updateQuerySpec = [
			'sql' => $update->getSQL(),
			'params' => $update->getParameters(),
			'row_count' => 1,
		];
		_elgg_services()->db->addQuerySpec($updateQuerySpec);
		
		// Call the updateTimeDeleted function without passing a timestamp
		$time_deleted = $object->updateTimeDeleted();
		$this->assertEquals($currentTime, $time_deleted);
		$this->assertEquals($currentTime, $object->time_deleted);
		
		// Call the updateTimeDeleted function with a new timestamp
		$new_time_deleted = $currentTime + 3600; // Add 1 hour
		
		// Create the update query
		$update = Update::table(EntityTable::TABLE_NAME);
		$update->set('time_deleted', $update->param($new_time_deleted, ELGG_VALUE_TIMESTAMP))
			->where($update->compare('guid', '=', $object->guid, ELGG_VALUE_GUID));
		
		// Add the testing query specification
		$updateQuerySpec = [
			'sql' => $update->getSQL(),
			'params' => $update->getParameters(),
			'row_count' => 1,
		];
		_elgg_services()->db->addQuerySpec($updateQuerySpec);
		
		$time_deleted = $object->updateTimeDeleted($new_time_deleted);
		$this->assertEquals($new_time_deleted, $time_deleted);
		$this->assertEquals($new_time_deleted, $object->time_deleted);
	}
	
	public function testGetRowWithNonExistingGUID() {
		$this->assertNull(_elgg_services()->entityTable->getRow(-1));
	}
	
	public function testGetRowWithExistingGUID() {
		$object = $this->createObject();
		
		$result = _elgg_services()->entityTable->getRow($object->guid);
		$this->assertNotEmpty($result); // should be a \stdClass with data
	}
}
