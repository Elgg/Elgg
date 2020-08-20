<?php

/**
 * @group Access
 * @group UnitTests
 * @group ElggData
 */
class ElggAccessCollectionIntegrationTest extends \Elgg\IntegrationTestCase {

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

		_elgg_services()->hooks->registerHandler('access_collection:url', 'access_collection', function (\Elgg\Hook $hook) use ($acl) {
			$hook_acl = $hook->getParam('access_collection');
			$this->assertEquals($acl, $hook_acl);
			if ($hook_acl->getSubtype() === 'foo') {
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
}
