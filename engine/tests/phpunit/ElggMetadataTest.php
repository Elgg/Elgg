<?php

use Elgg\TestCase;

/**
 * @group ElggMetadata
 */
class ElggMetadataTest extends TestCase {

	public function setUp() {
		$this->setupMockServices();
		_elgg_services()->metadataTable->setCurrentTime();
	}

	public function testExtenderConstructor() {

		$owner = $this->mocks()->getUser();
		$object = $this->mocks()->getObject([
			'owner_guid' => $owner->guid,
		]);

		$id = create_metadata($object->guid, 'foo', 'bar', '', $owner->guid);
		$metadata = elgg_get_metadata_from_id($id);

		$this->assertEquals('metadata', $metadata->getType());
		$this->assertEquals('foo', $metadata->getSubtype());
		$this->assertInstanceOf(stdClass::class, $metadata->toObject());
		$this->assertEquals($object, $metadata->getEntity());
		$this->assertEquals($owner, $metadata->getOwnerEntity());
		$this->assertEquals($owner->guid, $metadata->getOwnerGUID());
		$this->assertEquals($metadata->id, $metadata->getSystemLogID());

		$metadata->setValue(25);
		$this->assertEquals('integer', $metadata->value_type);

		$metadata->setValue('foo');
		$this->assertEquals('text', $metadata->value_type);

		$metadata->setValue(25, 'text');
		$this->assertEquals('text', $metadata->value_type);
	}

	public function testCanSetMetadataUrl() {

		$owner = $this->mocks()->getUser();
		$object = $this->mocks()->getObject([
			'owner_guid' => $owner->guid,
		]);

		$id = create_metadata($object->guid, 'foo', 'bar', '', $owner->guid);
		$metadata = elgg_get_metadata_from_id($id);

		_elgg_services()->hooks->backup();

		_elgg_services()->hooks->registerHandler('extender:url', 'metadata', function($hook, $type, $return, $params) use ($metadata) {
			$this->assertEquals($metadata, $params['extender']);
			if ($params['extender']->getSubtype() == 'foo') {
				return 'foo';
			}
		});

		$this->assertEquals(elgg_normalize_url('foo'), $metadata->getURL());

		_elgg_services()->hooks->restore();
	}
	
	public function testCanEditMetadata() {

		$owner = $this->mocks()->getUser();
		$other = $this->mocks()->getUser();

		$object = $this->mocks()->getObject([
			'owner_guid' => $owner->guid,
		]);

		$id = create_metadata($object->guid, 'foo', 'bar', '', $owner->guid);
		$metadata = elgg_get_metadata_from_id($id);

		// Default access level is private
		$this->assertEquals(ACCESS_PRIVATE, $metadata->access_id);
		
		$this->assertTrue($metadata->canEdit($owner->guid));
		$this->assertFalse($metadata->canEdit($other->guid));
	}

	public function testCanSaveMetadata() {

		$owner = $this->mocks()->getUser();
		_elgg_services()->session->setLoggedInUser($owner);

		$object = $this->mocks()->getObject([
			'owner_guid' => $owner->guid,
		]);

		$metadata = new ElggMetadata();
		$metadata->entity_guid = $object->guid;
		$metadata->name = 'foo';
		$metadata->value = 'bar';
		$metadata->time_created = _elgg_services()->metadataTable->getCurrentTime()->getTimestamp();

		$id = _elgg_services()->metadataTable->iterator + 1;

		// Insert
		$dbprefix = elgg_get_config('dbprefix');
		$sql = "INSERT INTO {$dbprefix}metadata
				(entity_guid, name_id, value_id, value_type, owner_guid, time_created, access_id)
				VALUES (:entity_guid, :name_id, :value_id, :value_type, :owner_guid, :time_created, :access_id)";

		_elgg_services()->db->addQuerySpec([
			'sql' => $sql,
			'params' => [
				':entity_guid' => $metadata->entity_guid,
				':name_id' => elgg_get_metastring_id('foo'),
				':value_id' => elgg_get_metastring_id('bar'),
				':value_type' => 'text',
				':owner_guid' => $metadata->owner_guid,
				':time_created' => $metadata->time_created,
				':access_id' => $metadata->access_id,
			],
			'insert_id' => $id,
		]);

		$this->assertEquals($id, $metadata->save());
		
		_elgg_services()->session->removeLoggedInUser();
	}

	public function testCanDeleteMetadata() {

		$owner = $this->mocks()->getUser();
		_elgg_services()->session->setLoggedInUser($owner);

		$object = $this->mocks()->getObject([
			'owner_guid' => $owner->guid,
		]);

		$id = create_metadata($object->guid, 'foo', 'bar', '', $owner->guid);
		$metadata = elgg_get_metadata_from_id($id);

		$dbprefix = elgg_get_config('dbprefix');
		_elgg_services()->db->addQuerySpec([
			'sql' => "DELETE FROM {$dbprefix}metadata WHERE id = :id",
			'params' => [
				':id' => $id,
			],
			'row_count' => 1,
			'times' => 1,
		]);

		$this->assertTrue($metadata->delete());

		_elgg_services()->session->removeLoggedInUser();
	}

	public function testCanDisableMetadata() {

		$owner = $this->mocks()->getUser();
		_elgg_services()->session->setLoggedInUser($owner);

		$object = $this->mocks()->getObject([
			'owner_guid' => $owner->guid,
		]);

		$id = create_metadata($object->guid, 'foo', 'bar', '', $owner->guid);
		$metadata = elgg_get_metadata_from_id($id);

		$this->assertTrue($metadata->disable());

		$this->assertEquals('no', $metadata->enabled);

		$this->assertTrue($metadata->enable());

		$this->assertEquals('yes', $metadata->enabled);

		_elgg_services()->session->removeLoggedInUser();
	}

}
