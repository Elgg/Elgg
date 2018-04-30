<?php

/**
 * @group Access
 * @group UnitTests
 * @group ElggData
 */
class ElggAccessCollectionUnitTest extends \Elgg\IntegrationTestCase {

	public function up() {

	}

	public function down() {

	}

	public function createCollection() {
		$owner = $this->createUser();

		$name = 'test';

		$id = create_access_collection($name, $owner->guid, 'foo');
		$acl = get_access_collection($id);

		$this->assertInstanceOf(\ElggAccessCollection::class, $acl);

		return $acl;
	}

	public function testCanSetAccessCollectionUrl() {

		$acl = $this->createCollection();

		_elgg_services()->hooks->backup();

		_elgg_services()->hooks->registerHandler('access_collection:url', 'access_collection', function ($hook, $type, $return, $params) use ($acl) {
			$this->assertEquals($acl, $params['access_collection']);
			if ($params['access_collection']->getSubtype() == 'foo') {
				return 'bar';
			}
		});

		$this->assertEquals(elgg_normalize_url('bar'), $acl->getURL());

		_elgg_services()->hooks->restore();
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

	public function testCanResolvePublicAcl() {

		$collection = get_access_collection(ACCESS_PUBLIC);

		$this->assertEquals('globe', $collection->getIconName());
		$this->assertEquals(elgg_echo('access:label:public'), $collection->getDisplayName());
	}

	public function testCanResolveLoggedInAcl() {

		$collection = get_access_collection(ACCESS_LOGGED_IN);

		$this->assertEquals('globe', $collection->getIconName());
		$this->assertEquals(elgg_echo('access:label:logged_in'), $collection->getDisplayName());
	}

	public function testCanResolvePrivateAcl() {

		$collection = get_access_collection(ACCESS_PRIVATE);

		$this->assertEquals('lock', $collection->getIconName());
		$this->assertEquals(elgg_echo('access:label:private'), $collection->getDisplayName());
	}

	public function testCanResolveFriendsCollection() {
		$user = $this->createUser();

		$id = create_access_collection('friends', $user->guid, ElggAccessCollection::FRIENDS);
		$collection = get_access_collection($id);

		$this->assertEquals('user', $collection->getIconName());
		$this->assertEquals(elgg_echo('access:label:friends'), $collection->getDisplayName());
	}

	public function testCanResolveGroupCollectionIcon() {
		$group = $this->createGroup();

		$id = create_access_collection($group->getDisplayName(), $group->guid, ElggAccessCollection::GROUP_MEMBERS);
		$collection = get_access_collection($id);

		$this->assertEquals('users', $collection->getIconName());
	}
}
