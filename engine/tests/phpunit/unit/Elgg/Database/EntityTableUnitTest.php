<?php

namespace Elgg\Database;

use Elgg\Exceptions\Database\UserFetchFailureException;
use Elgg\Traits\TimeUsing;

/**
 * @group Database
 * @group EntityTable
 * @group ElggEntity
 * @group EntityCache
 * @group UnitTests
 */
class EntityTableUnitTest extends \Elgg\UnitTestCase {
    use TimeUsing;

    public function testCanGetUserForPermissionsCheckWhileLoggedOut() {
		$this->assertNull(_elgg_services()->entityTable->getUserForPermissionsCheck());
		
		$user = $this->createUser();
		$this->assertEquals($user, _elgg_services()->entityTable->getUserForPermissionsCheck($user->guid));
	}

	public function testCanGetUserForPermissionsCheckWhileLoggedIn() {
		$user = $this->createUser();
		_elgg_services()->session_manager->setLoggedInUser($user);

		$this->assertEquals($user, _elgg_services()->session_manager->getLoggedInUser());
		
		$this->assertEquals($user, _elgg_services()->entityTable->getUserForPermissionsCheck());

		$user2 = $this->createUser();
		$this->assertEquals($user2, _elgg_services()->entityTable->getUserForPermissionsCheck($user2->guid));
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

    public function testCanUpdateTimeSoftDeleted() {
        // Set up the current time
        _elgg_services()->entityTable->setCurrentTime();
        $currentTime = time();

        // Create an object
        $object = $this->createObject();

        // Create the update query for empty params
        $update = Update::table(EntityTable::TABLE_NAME);
        $update->set('time_soft_deleted', $update->param($this->getCurrentTime()->getTimestamp(), ELGG_VALUE_TIMESTAMP))
            ->where($update->compare('guid', '=', $object->guid, ELGG_VALUE_GUID));

        // Add the testing query specification
        $updateQuerySpec = [
            'sql' => $update->getSQL(),
            'params' => $update->getParameters(),
            'row_count' => 1,
        ];
        _elgg_services()->db->addQuerySpec($updateQuerySpec);

        // Call the updateTimeSoftDeleted function without passing a timestamp
        $time_soft_deleted = $object->updateTimeSoftDeleted();
        $this->assertEquals($currentTime, $time_soft_deleted);
        $this->assertEquals($currentTime, $object->time_soft_deleted);

        // Call the updateTimeSoftDeleted function with a new timestamp
        $new_time_soft_deleted = $currentTime + 3600; // Add 1 hour

        // Create the update query
        $update = Update::table(EntityTable::TABLE_NAME);
        $update->set('time_soft_deleted', $update->param($new_time_soft_deleted, ELGG_VALUE_TIMESTAMP))
            ->where($update->compare('guid', '=', $object->guid, ELGG_VALUE_GUID));

        // Add the testing query specification
        $updateQuerySpec = [
            'sql' => $update->getSQL(),
            'params' => $update->getParameters(),
            'row_count' => 1,
        ];
        _elgg_services()->db->addQuerySpec($updateQuerySpec);

        $time_soft_deleted = $object->updateTimeSoftDeleted($new_time_soft_deleted);
        $this->assertEquals($new_time_soft_deleted, $time_soft_deleted);
        $this->assertEquals($new_time_soft_deleted, $object->time_soft_deleted);

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
