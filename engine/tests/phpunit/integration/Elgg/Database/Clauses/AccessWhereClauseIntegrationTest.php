<?php

namespace Elgg\Database\Clauses;

use Elgg\IntegrationTestCase;

/**
 * @group Access
 */
class AccessWhereClauseIntegrationTest extends IntegrationTestCase {

	protected \ElggUser $user;
	
	public function up() {
		$this->user = $this->createUser();
		_elgg_services()->session_manager->setLoggedInUser($this->user);
		_elgg_services()->events->backup();
	}

	public function down() {
		_elgg_services()->events->restore();
	}

	public function testHasAccessToEntity() {
		$session = _elgg_services()->session_manager;

		$viewer = $session->getLoggedInUser();
		$owner = $this->createUser();

		/* @var $object \ElggObject */
		$object = elgg_call(ELGG_IGNORE_ACCESS, function() use ($owner) {
			return $this->createObject([
				'owner_guid' => $owner->guid,
				'access_id' => ACCESS_PRIVATE,
			]);
		});

		$session->removeLoggedInUser();

		$this->assertFalse($object->hasAccess());
		$this->assertFalse($object->hasAccess($viewer->guid));
		$this->assertTrue($object->hasAccess($owner->guid));

		elgg_call(ELGG_IGNORE_ACCESS, function() use ($object) {
			$object->access_id = ACCESS_PUBLIC;
			$object->save();
		});

		$this->assertTrue($object->hasAccess());
		$this->assertTrue($object->hasAccess($viewer->guid));
		$this->assertTrue($object->hasAccess($owner->guid));

		elgg_call(ELGG_IGNORE_ACCESS, function() use ($object) {
			$object->access_id = ACCESS_LOGGED_IN;
			$object->save();
		});
		
		$this->assertFalse($object->hasAccess());
		// even though user is logged out, existing users are presumed to have access to an entity
		$this->assertTrue($object->hasAccess($viewer->guid));
		$this->assertTrue($object->hasAccess($owner->guid));

		$session->setLoggedInUser($viewer);
		$this->assertTrue($object->hasAccess());
		$this->assertTrue($object->hasAccess($viewer->guid));
		$this->assertTrue($object->hasAccess($owner->guid));
		$session->removeLoggedInUser();

		$session->setLoggedInUser($viewer);
	}
}