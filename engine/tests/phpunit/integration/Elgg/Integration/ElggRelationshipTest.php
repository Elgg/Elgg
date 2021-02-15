<?php

namespace Elgg\Integration;

use Elgg\IntegrationTestCase;
use ElggEntity;
use ElggObject;

/**
 * @group IntegrationTests
 */
class ElggRelationshipTest extends IntegrationTestCase {

	/**
	 * @var ElggEntity
	 */
	protected $entity1;

	/**
	 * @var ElggEntity
	 */
	protected $entity2;

	/**
	 * @var ElggEntity
	 */
	protected $entity3;
	
	/**
	 * @var \ElggUser
	 */
	protected $user;

	public function up() {
		_elgg_services()->events->backup();

		$this->user = $this->createUser();
		elgg()->session->setLoggedInUser($this->user);
		
		$this->entity1 = new ElggObject();
		$this->entity1->setSubtype('elgg_relationship_test');
		$this->entity1->access_id = ACCESS_PUBLIC;
		$this->entity1->save();

		$this->entity2 = new ElggObject();
		$this->entity2->setSubtype('elgg_relationship_test');
		$this->entity2->access_id = ACCESS_PUBLIC;
		$this->entity2->save();

		$this->entity3 = new ElggObject();
		$this->entity3->setSubtype('elgg_relationship_test');
		$this->entity3->access_id = ACCESS_PUBLIC;
		$this->entity3->save();
	}

	public function down() {
		if ($this->entity1) {
			$this->entity1->delete();
		}

		if ($this->entity2) {
			$this->entity2->delete();
		}

		if ($this->entity3) {
			$this->entity3->delete();
		}
		
		if ($this->user) {
			$this->user->delete();
		}
		
		elgg()->session->removeLoggedInUser();

		_elgg_services()->events->restore();
	}

	public function testAddRelationship() {
		// test adding a relationship
		$this->assertTrue(add_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid));

		$r = check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid);
		$this->assertInstanceOf(\ElggRelationship::class, $r);
	}

	public function testRemoveRelationship() {
		// test adding a relationship
		$this->assertTrue(add_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid));

		$r = check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid);
		$this->assertInstanceOf(\ElggRelationship::class, $r);

		$this->assertTrue(remove_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid));

		$this->assertFalse(check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid));
	}

	public function testPreventAddRelationship() {
		// test event handler - should prevent the addition of a relationship
		elgg_register_event_handler('create', 'relationship', 'Elgg\Values::getFalse');

		$this->assertFalse(add_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid));

		elgg_unregister_event_handler('create', 'relationship', 'Elgg\Values::getFalse');

		$this->assertFalse(check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid));
	}

	public function testPreventRemoveRelationship() {
		$this->assertTrue(add_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid));
		$r = check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid);
		$this->assertInstanceOf(\ElggRelationship::class, $r);

		elgg_register_event_handler('delete', 'relationship', 'Elgg\Values::getFalse');

		$this->assertFalse(remove_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid));

		elgg_unregister_event_handler('delete', 'relationship', 'Elgg\Values::getFalse');

		$r = check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid);
		$this->assertInstanceOf(\ElggRelationship::class, $r);
	}

	public function testRelationshipSave() {
		$this->assertTrue(add_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid));
		$r = check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid);
		$this->assertInstanceOf(\ElggRelationship::class, $r);
		$old_id = $r->id;

		$r->guid_two = $this->entity3->guid;
		$this->assertTrue($r->save());
		$this->assertGreaterThan(0, $r->id);
		$this->assertNotEquals($old_id, $r->id);

		$test_r = check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity3->guid);
		$this->assertInstanceOf(\ElggRelationship::class, $test_r);
		$this->assertEquals($r->guid_one, $test_r->guid_one);
		$this->assertEquals($r->guid_two, $test_r->guid_two);
		$this->assertEquals($r->relationship, $test_r->relationship);

		// the original shouldn't exist anymore
		$this->assertFalse(check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid));
	}

	public function testRelationshipDelete() {
		$this->assertTrue(add_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid));
		$r = check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid);
		$this->assertInstanceOf(\ElggRelationship::class, $r);

		$this->assertTrue($r->delete());
		$this->assertFalse(check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid));
	}

	public function testRelationshipOnEntityDelete() {
		$this->assertTrue(add_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid));

		// test deleting entity in guid_one position
		$this->entity1->delete();
		$this->assertFalse(check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid));

		$this->assertTrue(add_entity_relationship($this->entity2->guid, 'test_relationship', $this->entity3->guid));

		// test deleting entity in guid_two position
		$this->entity3->delete();
		$this->assertFalse(check_entity_relationship($this->entity2->guid, 'test_relationship', $this->entity3->guid));
	}

	public function testPreventRelationshipOnEntityDelete() {
		$this->assertTrue(add_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid));
		$this->assertTrue(add_entity_relationship($this->entity2->guid, 'test_relationship', $this->entity1->guid));
		$guid = $this->entity1->guid;

		elgg_register_event_handler('delete', 'relationship', 'Elgg\Values::getFalse');

		$this->assertTrue($this->entity1->delete());

		elgg_unregister_event_handler('delete', 'relationship', 'Elgg\Values::getFalse');

		// relationships should still be gone as there is no entity
		// despite the fact we have a handler trying to prevent it
		$this->assertFalse(check_entity_relationship($guid, 'test_relationship', $this->entity2->guid));
		$this->assertFalse(check_entity_relationship($this->entity2->guid, 'test_relationship', $guid));
	}

	public function testEntityMethodAddRelationship() {
		$this->assertTrue($this->entity1->addRelationship($this->entity2->guid, 'test_relationship'));
		$r = check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid);
		$this->assertInstanceOf(\ElggRelationship::class, $r);
	}

	public function testEntityMethodRemoveRelationship() {
		$this->assertTrue($this->entity1->addRelationship($this->entity2->guid, 'test_relationship'));
		$r = check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid);
		$this->assertInstanceOf(\ElggRelationship::class, $r);

		$this->assertTrue($this->entity1->removeRelationship($this->entity2->guid, 'test_relationship'));
		$this->assertFalse(check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid));
	}

	public function testEntityMethodDeleteRelationships() {
		$this->assertTrue($this->entity1->addRelationship($this->entity2->guid, 'test_relationship'));
		$r = check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid);
		$this->assertInstanceOf(\ElggRelationship::class, $r);

		$this->assertTrue($this->entity1->addRelationship($this->entity3->guid, 'test_relationship'));
		$r = check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity3->guid);
		$this->assertInstanceOf(\ElggRelationship::class, $r);

		$this->assertTrue($this->entity3->addRelationship($this->entity1->guid, 'test_relationship'));
		$r = check_entity_relationship($this->entity3->guid, 'test_relationship', $this->entity1->guid);
		$this->assertInstanceOf(\ElggRelationship::class, $r);

		$this->assertTrue($this->entity1->deleteRelationships('test_relationship'));
		$this->assertFalse(check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid));
		$this->assertFalse(check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity3->guid));

		// inverse relationships should be gone too
		$this->assertFalse(check_entity_relationship($this->entity3->guid, 'test_relationship', $this->entity1->guid));


		// Repeat above test, but with no relationship - should remove all relationships
		$this->assertTrue($this->entity1->addRelationship($this->entity2->guid, 'test_relationship'));
		$r = check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid);
		$this->assertInstanceOf(\ElggRelationship::class, $r);

		$this->assertTrue($this->entity1->addRelationship($this->entity3->guid, 'test_relationship'));
		$r = check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity3->guid);
		$this->assertInstanceOf(\ElggRelationship::class, $r);

		$this->assertTrue($this->entity3->addRelationship($this->entity1->guid, 'test_relationship'));
		$r = check_entity_relationship($this->entity3->guid, 'test_relationship', $this->entity1->guid);
		$this->assertInstanceOf(\ElggRelationship::class, $r);

		$this->assertTrue($this->entity1->addRelationship($this->entity2->guid, 'test_relationship2'));
		$r = check_entity_relationship($this->entity1->guid, 'test_relationship2', $this->entity2->guid);
		$this->assertInstanceOf(\ElggRelationship::class, $r);

		$this->assertTrue($this->entity1->addRelationship($this->entity3->guid, 'test_relationship2'));
		$r = check_entity_relationship($this->entity1->guid, 'test_relationship2', $this->entity3->guid);
		$this->assertInstanceOf(\ElggRelationship::class, $r);

		$this->assertTrue($this->entity1->deleteRelationships());
		$this->assertFalse(check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity2->guid));
		$this->assertFalse(check_entity_relationship($this->entity1->guid, 'test_relationship', $this->entity3->guid));
		$this->assertFalse(check_entity_relationship($this->entity1->guid, 'test_relationship2', $this->entity2->guid));
		$this->assertFalse(check_entity_relationship($this->entity1->guid, 'test_relationship2', $this->entity3->guid));

		// inverse relationships should be gone too
		$this->assertFalse(check_entity_relationship($this->entity3->guid, 'test_relationship', $this->entity1->guid));
	}
}
