<?php

use Elgg\IntegrationTestCase;

/**
 * @group IntegrationTests
 * @group ElggObject
 */
class ElggObjectIntegrationTest extends IntegrationTestCase {
	
	/**
	 * {@inheritDoc}
	 * @see \Elgg\BaseTestCase::up()
	 */
	public function up() {
		_elgg_services()->hooks->backup();
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Elgg\BaseTestCase::down()
	 */
	public function down() {
		_elgg_services()->hooks->restore();
	}
	
	public function testCantCommentLoggedOut() {
		
		$object = $this->createObject();
		
		$this->assertFalse($object->canComment());
	}
	
	public function testCanCommentLoggedIn() {
		
		$user = $this->createUser();
		
		$session = elgg_get_session();
		$session->setLoggedInUser($user);
		
		$object = $this->createObject();
				
		$this->assertTrue($object->canComment());
		
		$session->removeLoggedInUser();
	}
	
	public function testCanCommentOnGroupContent() {
		$user = $this->createUser();
		$user2 = $this->createUser();
		
		// make sure hook is registered
		_elgg_services()->hooks->registerHandler('permissions_check:comment', 'object', \Elgg\Comments\GroupMemberPermissionsHandler::class, 999);
		
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
		
		$session->removeLoggedInUser();
	}
}
