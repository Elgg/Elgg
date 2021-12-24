<?php

use Elgg\IntegrationTestCase;

/**
 * @group IntegrationTests
 * @group ElggObject
 */
class ElggObjectIntegrationTest extends IntegrationTestCase {
	
	public function testCantCommentLoggedOut() {
		$user = $this->createUser();
		$object = $this->createObject();
		$object2 = $this->createObject();
		
		$this->assertFalse($object->canComment());
		$this->assertTrue($object->canComment($user->guid));
		$this->assertFalse($object->canComment(-1));
		$this->assertFalse($object->canComment($object2->guid));
	}
	
	public function testCanCommentLoggedIn() {
		
		$user = $this->createUser();
		$other_user = $this->createUser();
		
		$session = elgg_get_session();
		$session->setLoggedInUser($user);
		
		$object = $this->createObject();
		$object2 = $this->createObject();
				
		$this->assertTrue($object->canComment());
		$this->assertTrue($object->canComment($other_user->guid));
		$this->assertFalse($object->canComment(-1));
		$this->assertFalse($object->canComment($object2->guid));
	}
	
	public function testCanCommentOnGroupContent() {
		$user = $this->createUser();
		$user2 = $this->createUser();
		
		$session = elgg_get_session();
		$session->setLoggedInUser($user);
		
		$group = $this->createGroup([
			'owner_guid' => $user2->guid,
		]);
		
		$object = $this->createObject([
			'owner_guid' => $user2->guid,
			'container_guid' => $group->guid,
		]);
		
		// non group member shouldn't be allowed
		$this->assertFalse($object->canComment());
		
		// join group
		$this->assertTrue($group->join($user));
		
		// now comment is allowed
		$this->assertTrue($object->canComment());
	}
	
	public function testEntityHasCapability() {
		$object = $this->createObject();
		$this->assertFalse($object->hasCapability('foo'));
		
		elgg_entity_enable_capability($object->getType(), $object->getSubtype(), 'foo');
		$this->assertTrue($object->hasCapability('foo'));
	}
}
