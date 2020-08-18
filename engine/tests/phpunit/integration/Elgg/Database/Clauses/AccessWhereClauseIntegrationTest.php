<?php

namespace Elgg\Database\Clauses;

use Elgg\IntegrationTestCase;

/**
 * @group Access
 */
class AccessWhereClauseIntegrationTest extends IntegrationTestCase {

	public function up() {
		$this->user = $this->createOne('user');
		_elgg_services()->session->setLoggedInUser($this->user);
		_elgg_services()->hooks->backup();
	}

	public function down() {
		_elgg_services()->session->removeLoggedInUser();
		if ($this->user) {
			$this->user->delete();
		}
		_elgg_services()->hooks->restore();
	}

	public function testHasAccessToEntity() {
		$session = elgg_get_session();

		$viewer = $session->getLoggedInUser();
		$owner = $this->createUser();

		$object = elgg_call(ELGG_IGNORE_ACCESS, function() use ($owner) {
			return $this->createObject([
				'owner_guid' => $owner->guid,
				'access_id' => ACCESS_PRIVATE,
			]);
		});

		$session->removeLoggedInUser();

		$this->assertFalse(has_access_to_entity($object));
		$this->assertFalse(has_access_to_entity($object, $viewer));
		$this->assertTrue(has_access_to_entity($object, $owner));

		elgg_call(ELGG_IGNORE_ACCESS, function() use ($object) {
			$object->access_id = ACCESS_PUBLIC;
			$object->save();
		});

		$this->assertTrue(has_access_to_entity($object));
		$this->assertTrue(has_access_to_entity($object, $viewer));
		$this->assertTrue(has_access_to_entity($object, $owner));

		elgg_call(ELGG_IGNORE_ACCESS, function() use ($object) {
			$object->access_id = ACCESS_LOGGED_IN;
			$object->save();
		});
		
		$this->assertFalse(has_access_to_entity($object));
		// even though user is logged out, existing users are presumed to have access to an entity
		$this->assertTrue(has_access_to_entity($object, $viewer));
		$this->assertTrue(has_access_to_entity($object, $owner));

		$session->setLoggedInUser($viewer);
		$this->assertTrue(has_access_to_entity($object));
		$this->assertTrue(has_access_to_entity($object, $viewer));
		$this->assertTrue(has_access_to_entity($object, $owner));
		$session->removeLoggedInUser();

		elgg_call(ELGG_IGNORE_ACCESS, function() use ($owner, $object) {
			$owner->delete();
			$object->delete();
		});

		$session->setLoggedInUser($viewer);
	}
}