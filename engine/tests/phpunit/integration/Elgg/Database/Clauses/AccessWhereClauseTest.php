<?php

namespace Elgg\Database\Clauses;

use Elgg\IntegrationTestCase;

/**
 * @group Access
 */
class AccessWhereClauseTest extends IntegrationTestCase {

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

		$ia = elgg_set_ignore_access(true);

		$owner = $this->createUser();

		$object = $this->createObject([
			'owner_guid' => $owner->guid,
			'access_id' => ACCESS_PRIVATE,
		]);

		elgg_set_ignore_access($ia);

		$session->removeLoggedInUser();

		$this->assertFalse(has_access_to_entity($object));
		$this->assertFalse(has_access_to_entity($object, $viewer));
		$this->assertTrue(has_access_to_entity($object, $owner));

		$ia = elgg_set_ignore_access(true);
		$object->access_id = ACCESS_PUBLIC;
		$object->save();
		elgg_set_ignore_access($ia);

		$this->assertTrue(has_access_to_entity($object));
		$this->assertTrue(has_access_to_entity($object, $viewer));
		$this->assertTrue(has_access_to_entity($object, $owner));

		$ia = elgg_set_ignore_access(true);
		$object->access_id = ACCESS_LOGGED_IN;
		$object->save();
		elgg_set_ignore_access($ia);

		$this->assertFalse(has_access_to_entity($object));
		// even though user is logged out, existing users are presumed to have access to an entity
		$this->assertTrue(has_access_to_entity($object, $viewer));
		$this->assertTrue(has_access_to_entity($object, $owner));

		$session->setLoggedInUser($viewer);
		$this->assertTrue(has_access_to_entity($object));
		$this->assertTrue(has_access_to_entity($object, $viewer));
		$this->assertTrue(has_access_to_entity($object, $owner));
		$session->removeLoggedInUser();

		$ia = elgg_set_ignore_access(true);
		$owner->delete();
		$object->delete();
		elgg_set_ignore_access($ia);

		$session->setLoggedInUser($viewer);
	}
}