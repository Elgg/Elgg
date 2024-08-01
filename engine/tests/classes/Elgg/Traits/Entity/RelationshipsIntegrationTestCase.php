<?php

namespace Elgg\Traits\Entity;

use Elgg\IntegrationTestCase;

abstract class RelationshipsIntegrationTestCase extends IntegrationTestCase {

	/**
	 * @var \ElggEntity
	 */
	protected $entity1;

	/**
	 * @var \ElggEntity
	 */
	protected $entity2;

	/**
	 * @var \ElggEntity
	 */
	protected $entity3;
	
	/**
	 * @var \ElggUser
	 */
	protected $user;

	public function up() {
		_elgg_services()->events->backup();

		$this->user = $this->createUser();
		elgg()->session_manager->setLoggedInUser($this->user);
		
		$this->entity1 = $this->getEntity();
		$this->entity2 = $this->createObject(['subtype' => 'elgg_relationship_test']);
		$this->entity3 = $this->createObject(['subtype' => 'elgg_relationship_test']);
	}

	public function down() {
		_elgg_services()->events->restore();
	}
	
	/**
	 * Get the testing entity
	 *
	 * @return \ElggEntity
	 */
	abstract protected function getEntity(): \ElggEntity;

	public function testAddRelationship() {
		// test adding a relationship
		$this->assertTrue($this->entity1->addRelationship($this->entity2->guid, 'test_relationship'));

		$this->assertTrue($this->entity1->hasRelationship($this->entity2->guid, 'test_relationship'));
		$this->assertTrue($this->entity1->getRelationship($this->entity2->guid, 'test_relationship') instanceof \ElggRelationship);
	}

	public function testRemoveRelationship() {
		// test adding a relationship
		$this->assertTrue($this->entity1->addRelationship($this->entity2->guid, 'test_relationship'));

		$this->assertTrue($this->entity1->hasRelationship($this->entity2->guid, 'test_relationship'));

		$this->assertTrue($this->entity1->removeRelationship($this->entity2->guid, 'test_relationship'));

		$this->assertFalse($this->entity1->hasRelationship($this->entity2->guid, 'test_relationship'));
		$this->assertNull($this->entity1->getRelationship($this->entity2->guid, 'test_relationship'));
	}

	public function testPreventAddRelationship() {
		// test event handler - should prevent the addition of a relationship
		elgg_register_event_handler('create', 'relationship', 'Elgg\Values::getFalse');

		$this->assertFalse($this->entity1->addRelationship($this->entity2->guid, 'test_relationship'));

		elgg_unregister_event_handler('create', 'relationship', 'Elgg\Values::getFalse');

		$this->assertFalse($this->entity1->hasRelationship($this->entity2->guid, 'test_relationship'));
	}

	public function testPreventRemoveRelationship() {
		$this->assertTrue($this->entity1->addRelationship($this->entity2->guid, 'test_relationship'));
		$this->assertTrue($this->entity1->hasRelationship($this->entity2->guid, 'test_relationship'));

		elgg_register_event_handler('delete', 'relationship', 'Elgg\Values::getFalse');

		$this->assertFalse($this->entity1->removeRelationship($this->entity2->guid, 'test_relationship'));

		elgg_unregister_event_handler('delete', 'relationship', 'Elgg\Values::getFalse');

		$this->assertTrue($this->entity1->hasRelationship($this->entity2->guid, 'test_relationship'));
	}

	public function testRelationshipSave() {
		$relationship = new \ElggRelationship();
		$relationship->guid_one = $this->entity1->guid;
		$relationship->relationship = 'test_relationship';
		$relationship->guid_two = $this->entity2->guid;
		
		$this->assertTrue($relationship->save());
		
		$rel_id = $relationship->id;
		$rel = elgg_get_relationship($rel_id);
		$this->assertInstanceOf(\ElggRelationship::class, $rel);
		$this->assertEquals($relationship, $rel);

		$rel->guid_two = $this->entity3->guid;
		$this->assertTrue($rel->save());
		$this->assertGreaterThan(0, $rel->id);
		$this->assertNotEquals($rel_id, $rel->id);

		$new_rel = elgg_get_relationship($rel->id);
		$this->assertInstanceOf(\ElggRelationship::class, $new_rel);
		$this->assertEquals($rel->guid_one, $new_rel->guid_one);
		$this->assertEquals($rel->guid_two, $new_rel->guid_two);
		$this->assertEquals($rel->relationship, $new_rel->relationship);

		// the original shouldn't exist anymore
		$this->assertFalse($this->entity1->hasRelationship($this->entity2->guid, 'test_relationship'));
	}

	public function testRelationshipDelete() {
		$relationship = new \ElggRelationship();
		$relationship->guid_one = $this->entity1->guid;
		$relationship->relationship = 'test_relationship';
		$relationship->guid_two = $this->entity2->guid;
		
		$this->assertTrue($relationship->save());
		
		$rel = elgg_get_relationship($relationship->id);
		$this->assertInstanceOf(\ElggRelationship::class, $rel);
		$this->assertEquals($relationship, $rel);

		$this->assertTrue($rel->delete());
		$this->assertFalse($this->entity1->hasRelationship($this->entity2->guid, 'test_relationship'));
	}

	public function testRelationshipOnEntityDelete() {
		$this->assertTrue($this->entity1->addRelationship($this->entity2->guid, 'test_relationship'));

		// test deleting entity in guid_one position
		$deleted = elgg_call(ELGG_IGNORE_ACCESS, function() {
			return $this->entity1->delete();
		});
		$this->assertTrue($deleted);
		$this->assertFalse($this->entity1->hasRelationship($this->entity2->guid, 'test_relationship'));

		$this->assertTrue($this->entity2->addRelationship($this->entity3->guid, 'test_relationship'));

		// test deleting entity in guid_two position
		$this->assertTrue($this->entity3->delete());
		$this->assertFalse($this->entity2->hasRelationship($this->entity3->guid, 'test_relationship'));
	}

	public function testPreventRelationshipOnEntityDelete() {
		$this->assertTrue($this->entity1->addRelationship($this->entity2->guid, 'test_relationship'));
		$this->assertTrue($this->entity2->addRelationship($this->entity1->guid, 'test_relationship'));

		elgg_register_event_handler('delete', 'relationship', 'Elgg\Values::getFalse');

		$deleted = elgg_call(ELGG_IGNORE_ACCESS, function() {
			return $this->entity1->delete();
		});
		$this->assertTrue($deleted);

		elgg_unregister_event_handler('delete', 'relationship', 'Elgg\Values::getFalse');

		// relationships should still be gone as there is no entity
		// despite the fact we have a handler trying to prevent it
		$this->assertFalse($this->entity1->hasRelationship($this->entity2->guid, 'test_relationship'));
		$this->assertFalse($this->entity2->hasRelationship($this->entity1->guid, 'test_relationship'));
	}

	public function testEntityMethodRemoveRelationship() {
		$this->assertTrue($this->entity1->addRelationship($this->entity2->guid, 'test_relationship'));
		$this->assertTrue($this->entity1->hasRelationship($this->entity2->guid, 'test_relationship'));

		$this->assertTrue($this->entity1->removeRelationship($this->entity2->guid, 'test_relationship'));
		$this->assertFalse($this->entity1->hasRelationship($this->entity2->guid, 'test_relationship'));
	}

	public function testEntityMethodDeleteSpecificRelationships() {
		$this->assertTrue($this->entity1->addRelationship($this->entity2->guid, 'test_relationship'));
		$this->assertTrue($this->entity1->hasRelationship($this->entity2->guid, 'test_relationship'));

		$this->assertTrue($this->entity1->addRelationship($this->entity3->guid, 'test_relationship'));
		$this->assertTrue($this->entity1->hasRelationship($this->entity3->guid, 'test_relationship'));

		$this->assertTrue($this->entity3->addRelationship($this->entity1->guid, 'test_relationship'));
		$this->assertTrue($this->entity3->hasRelationship($this->entity1->guid, 'test_relationship'));

		$this->assertTrue($this->entity1->removeAllRelationships('test_relationship'));
		$this->assertFalse($this->entity1->hasRelationship($this->entity2->guid, 'test_relationship'));
		$this->assertFalse($this->entity1->hasRelationship($this->entity3->guid, 'test_relationship'));

		$this->assertTrue($this->entity3->hasRelationship($this->entity1->guid, 'test_relationship'));
		$this->assertTrue($this->entity1->removeAllRelationships('test_relationship', true));
		// inverse relationships should be gone too
		$this->assertFalse($this->entity3->hasRelationship($this->entity1->guid, 'test_relationship'));
	}

	public function testEntityMethodRemoveAllRelationships() {
		$this->assertTrue($this->entity1->addRelationship($this->entity2->guid, 'test_relationship'));
		$this->assertTrue($this->entity1->hasRelationship($this->entity2->guid, 'test_relationship'));

		$this->assertTrue($this->entity1->addRelationship($this->entity3->guid, 'test_relationship'));
		$this->assertTrue($this->entity1->hasRelationship($this->entity3->guid, 'test_relationship'));

		$this->assertTrue($this->entity3->addRelationship($this->entity1->guid, 'test_relationship'));
		$this->assertTrue($this->entity3->hasRelationship($this->entity1->guid, 'test_relationship'));

		$this->assertTrue($this->entity1->addRelationship($this->entity2->guid, 'test_relationship2'));
		$this->assertTrue($this->entity1->hasRelationship($this->entity2->guid, 'test_relationship2'));

		$this->assertTrue($this->entity1->addRelationship($this->entity3->guid, 'test_relationship2'));
		$this->assertTrue($this->entity1->hasRelationship($this->entity3->guid, 'test_relationship2'));

		// remove all relationships
		$this->assertTrue($this->entity1->removeAllRelationships());
		$this->assertTrue($this->entity1->removeAllRelationships('', true));
		$this->assertFalse($this->entity1->hasRelationship($this->entity2->guid, 'test_relationship'));
		$this->assertFalse($this->entity1->hasRelationship($this->entity3->guid, 'test_relationship'));
		$this->assertFalse($this->entity1->hasRelationship($this->entity2->guid, 'test_relationship2'));
		$this->assertFalse($this->entity1->hasRelationship($this->entity3->guid, 'test_relationship2'));

		// inverse relationships should be gone too
		$this->assertFalse($this->entity3->hasRelationship($this->entity1->guid, 'test_relationship'));
	}
}
