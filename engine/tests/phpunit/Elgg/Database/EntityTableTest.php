<?php

namespace Elgg\Database;

/**
 * @group Database
 * @group EntityTable
 * @group ElggEntity
 * @group EntityCache
 */
class EntityTableTest extends \Elgg\TestCase {

	protected function setUp() {
		$this->setupMockServices();
	}

	public function testCanGetUserForPermissionsCheckWhileLoggedOut() {
		$this->assertNull(_elgg_services()->entityTable->getUserForPermissionsCheck());
		
		$user = $this->mocks()->getUser();
		$this->assertEquals($user, _elgg_services()->entityTable->getUserForPermissionsCheck($user->guid));
	}

	public function testCanGetUserForPermissionsCheckWhileLoggedIn() {
		$user = $this->mocks()->getUser();
		_elgg_services()->session->setLoggedInUser($user);

		$this->assertEquals($user, _elgg_services()->session->getLoggedInUser());
		
		$this->assertEquals($user, _elgg_services()->entityTable->getUserForPermissionsCheck());

		$user2 = $this->mocks()->getUser();
		$this->assertEquals($user2, _elgg_services()->entityTable->getUserForPermissionsCheck($user2->guid));

		_elgg_services()->session->removeLoggedInUser();
	}

	/**
	 * @expectedException \Elgg\Database\EntityTable\UserFetchFailureException
	 */
	public function testThrowsWhenGettingUserForPermissionsCheckWithNonUserGuid() {
		$object = $this->mocks()->getObject();
		_elgg_services()->entityTable->getUserForPermissionsCheck($object->guid);
	}

	public function testCanUpdateLastAction() {

		_elgg_services()->entityTable->setCurrentTime();

		$time = strtotime('-1 day');

		$object = $this->mocks()->getObject([
			'time_created' => $time,
			'time_updated' => $time,
			'last_action' => $time,
		]);
		
		$last_action = $object->updateLastAction();
		$this->assertEquals($last_action, $object->last_action);

		$dbprefix = elgg_get_config('dbprefix');
		$sql = "
			UPDATE {$dbprefix}entities
			SET last_action = :last_action
			WHERE guid = :guid
		";

		$new_last_action = strtotime('+2 days', $time);
		_elgg_services()->db->addQuerySpec([
			'sql' => $sql,
			'params' => [
				':last_action' => $new_last_action,
				':guid' => $object->guid,
			],
			'row_count' => 1,
		]);

		$last_action = $object->updateLastAction($new_last_action);
		$this->assertEquals($last_action, $new_last_action);
		$this->assertEquals($last_action, $object->last_action);
	}
}
