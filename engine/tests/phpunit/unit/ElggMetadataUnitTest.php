<?php

use Elgg\UnitTestCase;

/**
 * Metadata operations are mocked in \Elgg\Mocks\Database\MetadataTable table
 * Any changes to the SQL queries in the metadata table/API should be reflected there
 * For elgg_get_metadata_from_id() to work with the mocks, the SQL query must be
 * an exact match, so any new commas, brackets and clauses need to be reflected in the
 * mock class.
 *
 * @group ElggMetadata
 * @group UnitTests
 * @group ElggData
 */
class ElggMetadataUnitTest extends UnitTestCase {

	public function up() {
		_elgg_services()->metadataTable->setCurrentTime();
	}

	public function down() {

	}

	public function testExtenderConstructor() {

		$owner = $this->createUser();
		$object = $this->createObject([
			'owner_guid' => $owner->guid,
		]);

		$name = 'test_metadata_' . rand();

		$metadata = new ElggMetadata();
		$metadata->entity_guid = $object->guid;
		$metadata->name = $name;
		$metadata->value = 'test_value_' . rand();
		$id = $metadata->save();

		$metadata = elgg_get_metadata_from_id($id);

		$this->assertInstanceOf(\ElggMetadata::class, $metadata);
		
		$this->assertEquals('metadata', $metadata->getType());
		$this->assertEquals($name, $metadata->getSubtype());
		$this->assertInstanceOf(\Elgg\Export\Data::class, $metadata->toObject());
		$this->assertEquals($object, $metadata->getEntity());
		$this->assertEquals($metadata->id, $metadata->getSystemLogID());

		$metadata->setValue(25);
		$this->assertEquals('integer', $metadata->value_type);

		$metadata->setValue('foo');
		$this->assertEquals('text', $metadata->value_type);

		$metadata->setValue(25, 'text');
		$this->assertEquals('text', $metadata->value_type);
	}

	public function testCanSetMetadataUrl() {

		$owner = $this->createUser();
		$object = $this->createObject([
			'owner_guid' => $owner->guid,
		]);

		$name = 'test_metadata_' . rand();
		$metadata = new ElggMetadata();
		$metadata->entity_guid = $object->guid;
		$metadata->name = $name;
		$metadata->value = 'test_value_' . rand();
		$id = $metadata->save();

		$metadata = elgg_get_metadata_from_id($id);

		$this->assertInstanceOf(\ElggMetadata::class, $metadata);
		
		_elgg_services()->hooks->backup();

		_elgg_services()->hooks->registerHandler('extender:url', 'metadata', function($hook, $type, $return, $params) use ($metadata, $name) {
			$this->assertEquals($metadata, $params['extender']);
			if ($params['extender']->getSubtype() == $name) {
				return 'foo';
			}
		});

		$this->assertEquals(elgg_normalize_url('foo'), $metadata->getURL());

		_elgg_services()->hooks->restore();
	}
	
	public function testCanEditMetadata() {

		$owner = $this->createUser();
		$other = $this->createUser();

		$object = $this->createObject([
			'owner_guid' => $owner->guid,
		]);

		$metadata = new ElggMetadata();
		$metadata->entity_guid = $object->guid;
		$metadata->name = 'test_metadata_' . rand();
		$metadata->value = 'test_value_' . rand();
		$id = $metadata->save();

		$metadata = elgg_get_metadata_from_id($id);

		$this->assertInstanceOf(\ElggMetadata::class, $metadata);
		
		// Default access level is private
		$this->assertTrue($metadata->canEdit($other->guid));
	}

	public function testCanSaveMetadata() {

		$owner = $this->createUser();
		_elgg_services()->session->setLoggedInUser($owner);

		$object = $this->createObject([
			'owner_guid' => $owner->guid,
		]);

		$metadata = new ElggMetadata();
		$metadata->entity_guid = $object->guid;
		$metadata->name = 'foo';
		$metadata->value = 'bar';
		$metadata->time_created = _elgg_services()->metadataTable->getCurrentTime()->getTimestamp();

		$id = \Elgg\Mocks\Database\MetadataTable::$iterator + 1;

		// Insert
		$dbprefix = _elgg_config()->dbprefix;
		$sql = "INSERT INTO {$dbprefix}metadata
				(entity_guid, name, value, value_type, time_created)
				VALUES (:entity_guid, :name, :value, :value_type, :time_created)";

		_elgg_services()->db->addQuerySpec([
			'sql' => $sql,
			'params' => [
				':entity_guid' => $metadata->entity_guid,
				':name' => 'foo',
				':value' => 'bar',
				':value_type' => 'text',
				':time_created' => $metadata->time_created,
			],
			'insert_id' => $id,
		]);

		$this->assertEquals($id, $metadata->save());
		
		_elgg_services()->session->removeLoggedInUser();
	}

	public function testCanDeleteMetadata() {

		$owner = $this->createUser();
		_elgg_services()->session->setLoggedInUser($owner);

		$object = $this->createObject([
			'owner_guid' => $owner->guid,
		]);

		$metadata = new ElggMetadata();
		$metadata->entity_guid = $object->guid;
		$metadata->name = 'test_metadata_' . rand();
		$metadata->value = 'test_value_' . rand();
		$id = $metadata->save();

		$metadata = elgg_get_metadata_from_id($id);

		$this->assertInstanceOf(\ElggMetadata::class, $metadata);
		
		$dbprefix = _elgg_config()->dbprefix;
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

	public function testCanExport() {

		$object = $this->createObject();
		$metadata = new ElggMetadata();
		$metadata->entity_guid = $object->guid;
		$metadata->name = 'foo';
		$metadata->value = 'bar';
		$metadata->time_created = _elgg_services()->metadataTable->getCurrentTime()->getTimestamp();
		$metadata->save();

		$export = $metadata->toObject();

		$this->assertEquals($metadata->id, $export->id);
		$this->assertEquals($metadata->owner_guid, $export->owner_guid);
		$this->assertEquals($metadata->entity_guid, $export->entity_guid);
		$this->assertEquals($metadata->time_created, $export->getTimeCreated()->getTimestamp());
		$this->assertEquals($metadata->name, $export->name);
		$this->assertEquals($metadata->value, $export->value);
	}

	public function testCanSerialize() {
		$object = $this->createObject();
		$metadata = new ElggMetadata();
		$metadata->entity_guid = $object->guid;
		$metadata->name = 'foo';
		$metadata->value = 'bar';
		$metadata->time_created = _elgg_services()->metadataTable->getCurrentTime()->getTimestamp();
		$metadata->save();

		$data = serialize($metadata);

		$unserialized = unserialize($data);

		$this->assertEquals($metadata, $unserialized);
	}

	public function testCanArrayAccessAttributes() {
		$object = $this->createObject();
		$metadata = new ElggMetadata();
		$metadata->entity_guid = $object->guid;
		$metadata->name = 'foo';
		$metadata->value = 'bar';
		$metadata->time_created = _elgg_services()->metadataTable->getCurrentTime()->getTimestamp();
		$metadata->save();

		$this->assertEquals($metadata->id, $metadata['id']);

		foreach ($metadata as $attr => $value) {
			$this->assertEquals($metadata->$attr, $metadata[$attr]);
		}

		unset($metadata['access_id']);
	}

	public function testIsLoggable() {
		$object = $this->createObject();
		$metadata = new ElggMetadata();
		$metadata->entity_guid = $object->guid;
		$metadata->name = 'foo';
		$metadata->value = 'bar';
		$metadata->time_created = _elgg_services()->metadataTable->getCurrentTime()->getTimestamp();
		$metadata->save();

		$this->assertEquals($metadata->id, $metadata->getSystemLogID());
		$this->assertEquals($metadata, $metadata->getObjectFromID($metadata->id));
	}
}
