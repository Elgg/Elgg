<?php

class ElggAccessCollectionIntegrationTest extends \Elgg\IntegrationTestCase {

	public function up() {
		_elgg_services()->events->backup();
		
		// make sure our testing ACL subtype can be retrieved
		elgg_register_event_handler('access:collections:write:subtypes', 'user', function(\Elgg\Event $event) {
			$value = $event->getValue();
			$value[] = 'foo';
			
			return $value;
		});
	}
	
	public function down() {
		_elgg_services()->events->restore();
	}
	
	protected function createCollection(): \ElggAccessCollection {
		$owner = $this->createUser();
		
		$acl = elgg_create_access_collection('test_' . microtime(true), $owner->guid, 'foo');
		$this->assertInstanceOf(\ElggAccessCollection::class, $acl);

		return $acl;
	}

	public function testCreateGetDeleteACL() {
		$owner = $this->createUser();
		$acl_name = 'test access collection';
		
		$acl = elgg_create_access_collection($acl_name, $owner->guid, 'foo');
		
		$this->assertInstanceOf(\ElggAccessCollection::class, $acl);
		$this->assertEquals($acl_name, $acl->name);
		$this->assertEquals($owner->guid, $acl->owner_guid);
		$this->assertEquals('foo', $acl->getSubtype());
		
		$this->assertTrue($acl->delete());
		
		$this->assertEmpty(elgg_get_access_collection($acl->id));
	}
	
	public function testCanSetAccessCollectionUrl() {
		$acl = $this->createCollection();

		_elgg_services()->events->registerHandler('access_collection:url', 'access_collection', function (\Elgg\Event $event) use ($acl) {
			$event_acl = $event->getParam('access_collection');
			$this->assertEquals($acl, $event_acl);
			if ($event_acl->getSubtype() === 'foo') {
				return 'bar';
			}
		});

		$this->assertEquals(elgg_normalize_url('bar'), $acl->getURL());
	}

	public function testCanExport() {
		$acl = $this->createCollection();

		$export = $acl->toObject();

		$this->assertEquals($acl->id, $export->id);
		$this->assertEquals($acl->owner_guid, $export->owner_guid);
		$this->assertEquals($acl->name, $export->name);
		$this->assertEquals($acl->getType(), $export->type);
		$this->assertEquals($acl->getSubtype(), $export->subtype);
		$this->assertEquals($acl->name, $export->name);
	}

	public function testCanSerialize() {
		$acl = $this->createCollection();

		$data = serialize($acl);

		$unserialized = unserialize($data);

		$this->assertEquals($acl, $unserialized);
	}

	public function testCanArrayAccessAttributes() {
		$acl = $this->createCollection();

		$this->assertEquals($acl->id, $acl['id']);

		foreach ($acl as $attr => $value) {
			$this->assertEquals($acl->$attr, $acl[$attr]);
		}

		unset($acl['type']);
	}

	public function testIsLoggable() {
		$acl = $this->createCollection();

		$this->assertEquals($acl->id, $acl->getSystemLogID());
		$this->assertEquals($acl, $acl->getObjectFromID($acl->id));
	}
	
	public function testCanEditACL() {
		$acl = $this->createCollection();
		
		// should be true since it's the owner
		$this->assertTrue($acl->canEdit($acl->owner_guid));
		
		// should be false as no logged in user
		$this->assertFalse($acl->canEdit());
		
		$new_user = $this->createUser();
		elgg()->session_manager->setLoggedInUser($new_user);
		
		// should be true since IA is on.
		elgg_call(ELGG_IGNORE_ACCESS, function() use ($acl) {
			$this->assertTrue($acl->canEdit());
		});
		
		elgg_call(ELGG_ENFORCE_ACCESS, function() use ($acl) {
			// still checking the owner, but different user is logged in
			$this->assertTrue($acl->canEdit(($acl->owner_guid)));
			// should be false as not the owner
			$this->assertFalse($acl->canEdit());
		});
		
		elgg()->session_manager->removeLoggedInUser();
	}
	
	public function testCanEditACLEvent() {
		$acl = $this->createCollection();
		$user = $this->createUser();
		
		$handler = function(\Elgg\Event $event) use ($acl, $user) {
			$value = $event->getValue();
			
			if ($event->getParam('user_id') == $user->guid) {
				$value[$acl->id] = $acl->name;
			}
			
			return $value;
		};
		
		$this->assertFalse($acl->canEdit($user->guid));
		
		elgg_register_event_handler('access:collections:write', 'all', $handler, 600);
		
		$this->assertTrue($acl->canEdit($user->guid));
		
		elgg_unregister_event_handler('access:collections:write', 'all', $handler);
	}
	
	public function testMemberCantEditACL() {
		$acl = $this->createCollection();
		$member = $this->createUser();
		
		$this->assertTrue($acl->addMember($member->guid));
		
		elgg_call(ELGG_ENFORCE_ACCESS, function() use ($acl, $member) {
			$this->assertTrue($acl->canEdit($acl->owner_guid));
			$this->assertFalse($acl->canEdit($member->guid));
		});
	}
	
	public function testAddRemoveUserToACL() {
		$acl = $this->createCollection();
		$member = $this->createUser();
		
		$this->assertTrue($acl->addMember($member->guid));
		$this->assertTrue($acl->removeMember($member->guid));
	}
	
	public function testAddNonUserToACL() {
		$acl = $this->createCollection();
		$object = $this->createObject();
		
		$this->assertFalse($acl->addMember($object->guid));
	}
	
	public function testAddMemberToACLRemoveMember() {
		$acl = $this->createCollection();
		$member = $this->createUser();
		
		$this->assertTrue($acl->addMember($member->guid));
		$this->assertNotEmpty($acl->getMembers());
		
		// now remove the user, this should remove him from the ACL
		elgg_call(ELGG_IGNORE_ACCESS, function() use ($member) {
			$this->assertTrue($member->delete());
		});
			
		$this->assertEmpty($acl->getMembers());;
	}
}
