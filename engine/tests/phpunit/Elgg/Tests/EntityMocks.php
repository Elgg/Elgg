<?php

namespace Elgg\Tests;

use Elgg\Database\Annotations;
use Elgg\Database\EntityTable;
use Elgg\Database\MetadataTable;
use Elgg\Database\RelationshipsTable;
use Elgg\TestCase;
use ElggAnnotation;
use ElggEntity;
use ElggGroup;
use ElggMetadata;
use ElggObject;
use ElggRelationship;
use ElggUser;
use InvalidArgumentException;

/**
 * Create test doubles for Elgg entities
 * @access private
 * @since 2.2
 */
class EntityMocks {

	/**
	 * @var TestCase
	 */
	private $test;

	/**
	 * @var ElggEntity[]
	 */
	private $mocks = [];

	/**
	 * @var ElggMetadata[]
	 */
	private $metadata_mocks = [];

	/**
	 * @var ElggAnnotation[]
	 */
	private $annotation_mocks = [];

	/**
	 * @var ElggRelationship[]
	 */
	private $relationship_mocks = [];

	/**
	 * @var int
	 */
	private $iterator;

	/**
	 * Constructor
	 *
	 * @param TestCase $test Test case
	 */
	public function __construct(TestCase $test) {
		$this->test = $test;
		$this->iterator = 100; // some random offset
	}

	/**
	 * Return callback for mocking \Elgg\Database\EntityTable
	 * 
	 * @param int    $guid GUID of the mock entity
	 * @param string $type Type the mock entity
	 * @return \ElggEntity|boolean
	 */
	public function get($guid, $type = '') {
		if (empty($this->mocks[$guid])) {
			return false;
		}
		$entity = $this->mocks[$guid];
		if (!$type) {
			return $entity;
		}
		if ($type && $entity && $entity->getType() == $type) {
			return $entity;
		}
		return false;
	}

	/**
	 * Return callback for mocking \Elgg\Database\MetadataTable
	 *
	 * @param int $id Metadata id
	 * @return ElggMetadata|false
	 */
	public function getMetadata($id) {
		if (empty($this->metadata_mocks[$id])) {
			return false;
		}
		return $this->metadata_mocks[$id];
	}

	/**
	 * Return callback for mocking \Elgg\Database\MetadataTable
	 *
	 * @param int    $entity_guid
	 * @param string $name
	 * @param mixed  $value
	 * @param string $value_type     'text', 'integer', or '' for automatic detection
	 * @param int    $owner_guid     GUID of entity that owns the metadata. Default is logged in user.
	 * @param int    $access_id      Default is ACCESS_PRIVATE
	 * @param bool   $allow_multiple Allow multiple values for one key. Default is false
	 * @return boolean
	 */
	public function createMetadata($entity_guid, $name, $value, $value_type = '', $owner_guid = 0, $access_id = ACCESS_PRIVATE, $allow_multiple = false) {
		$entity = $this->get((int) $entity_guid);
		if (!$entity) {
			return false;
		}
		$metadata = $entity->setMetadata($name, $value);
		return $metadata->id;
	}

	/**
	 * Return callback for mocking \Elgg\Database\MetadataTable
	 *
	 * @param int $id Metadata id
	 * @return boolean
	 */
	public function deleteMetadata($id) {
		unset($this->metadata_mocks[$id]);
		return true;
	}

	/**
	 * Return callback for mocking \Elgg\Database\AnnotationTable
	 *
	 * @param int $id Annotation id
	 * @return ElggAnnotation|false
	 */
	public function getAnnotation($id) {
		if (empty($this->annotation_mocks[$id])) {
			return false;
		}
		return $this->annotation_mocks[$id];
	}

	/**
	 * Create a new annotation.
	 *
	 * @param int    $entity_guid GUID of entity to be annotated
	 * @param string $name        Name of annotation
	 * @param string $value       Value of annotation
	 * @param string $value_type  Type of value (default is auto detection)
	 * @param int    $owner_guid  Owner of annotation (default is logged in user)
	 * @param int    $access_id   Access level of annotation
	 *
	 * @return int|bool id on success or false on failure
	 */
	function createAnnotation($entity_guid, $name, $value, $value_type = '', $owner_guid = 0, $access_id = ACCESS_PRIVATE) {
		$entity = $this->get((int) $entity_guid);
		if (!$entity) {
			return false;
		}
		return $entity->annotate($name, $value, $value_type, $owner_guid, $access_id);
	}

	/**
	 * Return callback for mocking \Elgg\Database\AnnotationTable
	 *
	 * @param int $id Annotation id
	 * @return boolean
	 */
	public function deleteAnnotation($id) {
		unset($this->annotation_mocks[$id]);
		return true;
	}

	/**
	 * Get a relationship by its ID
	 *
	 * @param int $id The relationship ID
	 *
	 * @return ElggRelationship|false False if not found
	 */
	public function getRelationship($id) {
		if (empty($this->relationship_mocks[$id])) {
			return false;
		}
		return $this->relationship_mocks[$id];
	}

	/**
	 * Delete a relationship by its ID
	 *
	 * @param int  $id         Relationship ID
	 * @param bool $call_event Call the delete event before deleting
	 *
	 * @return bool
	 */
	public function deleteRelationship($id, $call_event = true) {
		unset($this->relationship_mocks[$id]);
		return true;
	}

	/**
	 * Create a relationship between two entities. E.g. friendship, group membership, site membership.
	 *
	 * This function lets you make the statement "$guid_one is a $relationship of $guid_two". In the statement,
	 * $guid_one is the subject of the relationship, $guid_two is the target, and $relationship is the type.
	 *
	 * @param int    $guid_one     GUID of the subject entity of the relationship
	 * @param string $relationship Type of the relationship
	 * @param int    $guid_two     GUID of the target entity of the relationship
	 *
	 * @return bool
	 * @throws InvalidArgumentException
	 */
	public function addRelationship($guid_one, $relationship, $guid_two) {
		$rel = $this->checkRelationship($guid_one, $relationship, $guid_two);
		if ($rel) {
			return false;
		}
		
		$this->iterator++;
		$id = $this->iterator;

		if (!$this->get($guid_one) || !$this->get($guid_two) || !$relationship) {
			return false;
		}

		$rel = new ElggRelationship((object) [
			'id' => $id,
			'guid_one' => $guid_one,
			'guid_two' => $guid_two,
			'relationship' => $relationship,
			'time_created' => time(),
		]);

		$this->relationship_mocks[$id] = $rel;
		return true;
	}

	/**
	 * Check if a relationship exists between two entities. If so, the relationship object is returned.
	 *
	 * This function lets you ask "Is $guid_one a $relationship of $guid_two?"
	 *
	 * @param int    $guid_one     GUID of the subject entity of the relationship
	 * @param string $relationship Type of the relationship
	 * @param int    $guid_two     GUID of the target entity of the relationship
	 *
	 * @return ElggRelationship|false Depending on success
	 */
	public function checkRelationship($guid_one, $relationship, $guid_two) {
		foreach ($this->relationship_mocks as $rel) {
			if ($rel->guid_one != $guid_one) {
				continue;
			}
			if ($rel->guid_two != $guid_two) {
				continue;
			}
			if ($rel->relationship != $relationship) {
				continue;
			}
			return $rel;
		}
		return false;
	}

	/**
	 * Return callback for mocking \Elgg\Database\EntityTable
	 *
	 * @param int    $guid GUID of the mock entity
	 * @return boolean
	 */
	public function exists($guid) {
		return $guid && array_key_exists($guid, $this->mocks);
	}

	/**
	 * Setup a mock entity
	 *
	 * @param int    $guid       GUID of the mock entity
	 * @param string $type       Type of the mock entity
	 * @param string $subtype    Subtype of the mock entity
	 * @param array  $attributes Attributes of the mock entity
	 * @return ElggEntity
	 */
	protected function setup($guid, $type, $subtype, array $attributes = []) {

		$methods = [
			'getGUID',
			'getType',
			'getSubtype',
			'__get',
			'__set',
			'__unset',
			'getOwnerEntity',
			'getContainerEntity',
			'getMetadata',
			'setMetadata',
			'deleteMetadata',
			'annotate',
		];

		switch ($type) {
			case 'object' :
				$class = ElggObject::class;
				$external_attributes = [
					'title' => null,
					'description' => null,
				];
				break;
			case 'user' :
				$class = ElggUser::class;
				$external_attributes = [
					'name' => "John Doe $guid",
					'username' => "john_doe_$guid",
					'password' => null,
					'salt' => null,
					'password_hash' => null,
					'email' => "john_doe_$guid@example.com",
					'language' => 'en',
					'banned' => "no",
					'admin' => 'no',
					'prev_last_action' => null,
					'last_login' => null,
					'prev_last_login' => null,
				];
				$methods[] = 'isAdmin';
				break;
			case 'group' :
				$class = ElggGroup::class;
				$external_attributes = [
					'name' => null,
					'description' => null,
				];
				break;
		}

		$entity = $this->test->getMockBuilder($class)
				->setMethods($methods)
				->disableOriginalConstructor()
				->getMock();

		$entity->expects($this->test->any())
				->method('getGUID')
				->will($this->test->returnValue($guid));
		$attributes['guid'] = $guid;

		$entity->expects($this->test->any())
				->method('getType')
				->will($this->test->returnValue($type));
		$attributes['type'] = $type;


		$entity->expects($this->test->any())
				->method('getSubtype')
				->will($this->test->returnValue($subtype));
		$attributes['subtype'] = $subtype;

		if (!isset($attributes['owner_guid'])) {
			switch ($type) {
				case 'user' :
					$attributes['owner_guid'] = 0;
					break;
				case 'group' :
				case 'object' :
					$owner = $this->getUser();
					$attributes['owner_guid'] = $owner->guid;
					break;
			}
		}

		if (!isset($attributes['container_guid'])) {
			switch ($type) {
				case 'user' :
					$attributes['container_guid'] = 0;
					break;
				case 'group' :
				case 'object' :
					$attributes['container_guid'] = $attributes['owner_guid'];
					break;
			}
		}

		$entity->expects($this->test->any())
				->method('getOwnerEntity')
				->will($this->test->returnValue($this->get($attributes['owner_guid'])));

		$entity->expects($this->test->any())
				->method('getContainerEntity')
				->will($this->test->returnValue($this->get($attributes['container_guid'])));

		$map = array_merge($external_attributes, $attributes);

		$entity->expects($this->test->any())
				->method('__get')
				->will($this->test->returnCallback(function($name) use ($entity, &$map) {
							if (isset($map[$name])) {
								return $map[$name];
							}
							return $entity->getMetadata($name);
						}));

		$entity->expects($this->test->any())
				->method('__set')
				->will($this->test->returnCallback(function($name, $value) use ($entity, &$map) {
							$map[$name] = $value;
							$entity->setMetadata($name, $value);
						}));

		$entity->expects($this->test->any())
				->method('__set')
				->will($this->test->returnCallback(function($name, $value) use ($entity, &$map) {
							$entity->deleteMetadata($name);
						}));

		$entity->expects($this->test->any())
				->method('setMetadata')
				->will($this->test->returnCallback(function($name, $value) use ($entity) {
							$this->iterator++;
							$id = $this->iterator;
							$metadata = new ElggMetadata((object) [
										'type' => 'metadata',
										'id' => $id,
										'entity_guid' => $entity->guid,
										'owner_guid' => $entity->owner_guid,
										'name' => $name,
										'value' => $value,
										'time_created' => time(),
										'access_id' => $entity->guid,
							]);
							$this->metadata_mocks[$id] = $metadata;
							return $metadata;
						}));

		$entity->expects($this->test->any())
				->method('getMetadata')
				->will($this->test->returnCallback(function($name) use ($entity) {
							foreach ($this->metadata_mocks as $md) {
								if ($md->entity_guid == $entity->guid && $md->name == $name) {
									return $md->value;
								}
							}
						}));

		$entity->expects($this->test->any())
				->method('deleteMetadata')
				->will($this->test->returnCallback(function($name) use ($entity, &$map) {
							foreach ($this->metadata_mocks as $id => $md) {
								if ($md->entity_guid == $entity->guid && $md->name == $name) {
									unset($this->metadata_mocks[$id]);
								}
								unset($map[$name]);
							}
						}));

		$entity->expects($this->test->any())
				->method('annotate')
				->will($this->test->returnCallback(function($name, $value, $value_type, $owner_guid, $access_id) use ($entity, &$map) {
							$this->iterator++;
							$id = $this->iterator;
							$annotation = new ElggAnnotation((object) [
										'type' => 'annotation',
										'id' => $id,
										'entity_guid' => $entity->guid,
										'owner_guid' => $owner_guid ? : $entity->owner_guid,
										'name' => $name,
										'value' => $value,
										'value_type' => $value_type,
										'time_created' => time(),
										'access_id' => $access_id,
							]);
							$this->annotation_mocks[$id] = $annotation;
							return $id;
						}));

		if (in_array('isAdmin', $methods)) {
			$entity->expects($this->test->any())
					->method('isAdmin')
					->will($this->test->returnValue(isset($map['admin']) && $map['admin'] == 'yes'));
		}

		$this->mocks[$guid] = $entity;

		return $entity;
	}

	/**
	 * Setup a mock user
	 *
	 * @param array $attributes An array of attributes
	 * @return ElggUser
	 */
	public function getUser(array $attributes = array()) {
		$this->iterator++;
		unset($attributes['type']);
		unset($attributes['guid']);
		$subtype = isset($attributes['subtype']) ? $attributes['subtype'] : 'foo_user';
		return $this->setup($this->iterator, 'user', $subtype, $attributes);
	}

	/**
	 * Setup a mock object
	 *
	 * @param array $attributes An array of attributes
	 * @return ElggObject
	 */
	public function getObject(array $attributes = array()) {
		$this->iterator++;
		unset($attributes['type']);
		unset($attributes['guid']);
		$subtype = isset($attributes['subtype']) ? $attributes['subtype'] : 'foo_object';
		return $this->setup($this->iterator, 'object', $subtype, $attributes);
	}

	/**
	 * Setup a mock object
	 *
	 * @param array $attributes An array of attributes
	 * @return ElggGroup
	 */
	public function getGroup(array $attributes = array()) {
		$this->iterator++;
		unset($attributes['type']);
		unset($attributes['guid']);
		$subtype = isset($attributes['subtype']) ? $attributes['subtype'] : 'foo_group';
		return $this->setup($this->iterator, 'group', $subtype, $attributes);
	}

	/**
	 * Setup entity table mock
	 * @return EntityTable
	 */
	public function getEntityTableMock() {
		$mock = $this->test->getMockBuilder(EntityTable::class)
				->setMethods(['get', 'exists'])
				->disableOriginalConstructor()
				->getMock();

		$mock->expects($this->test->any())
				->method('get')
				->will($this->test->returnCallback([$this, 'get']));

		$mock->expects($this->test->any())
				->method('exists')
				->will($this->test->returnCallback([$this, 'exists']));

		return $mock;
	}

	/**
	 * Setup metadata table mock
	 * @return MetadataTable
	 */
	public function getMetadataTableMock() {
		$mock = $this->test->getMockBuilder(MetadataTable::class)
				->setMethods(['get', 'create', 'delete'])
				->disableOriginalConstructor()
				->getMock();

		$mock->expects($this->test->any())
				->method('get')
				->will($this->test->returnCallback([$this, 'getMetadata']));

		$mock->expects($this->test->any())
				->method('create')
				->will($this->test->returnCallback([$this, 'createMetadata']));

		$mock->expects($this->test->any())
				->method('delete')
				->will($this->test->returnCallback([$this, 'deleteMetadata']));

		return $mock;
	}

	/**
	 * Setup annotation table mock
	 * @return Annotations
	 */
	public function getAnnotationsTableMock() {
		$mock = $this->test->getMockBuilder(\Elgg\Database\AnnotationTable::class)
				->setMethods(['get', 'create', 'delete'])
				->disableOriginalConstructor()
				->getMock();

		$mock->expects($this->test->any())
				->method('get')
				->will($this->test->returnCallback([$this, 'getAnnotation']));

		$mock->expects($this->test->any())
				->method('create')
				->will($this->test->returnCallback([$this, 'createAnnotation']));

		$mock->expects($this->test->any())
				->method('delete')
				->will($this->test->returnCallback([$this, 'deleteAnnotation']));

		return $mock;
	}

	/**
	 * Setup relationship table mock
	 * @return RelationshipsTable
	 */
	public function getRelationshipsTableMock() {
		$mock = $this->test->getMockBuilder(\Elgg\Database\AnnotationTable::class)
				->setMethods(['get', 'add', 'remove', 'delete', 'check'])
				->disableOriginalConstructor()
				->getMock();

		$mock->expects($this->test->any())
				->method('get')
				->will($this->test->returnCallback([$this, 'getRelationship']));

		$mock->expects($this->test->any())
				->method('add')
				->will($this->test->returnCallback([$this, 'addRelationship']));

		$mock->expects($this->test->any())
				->method('delete')
				->will($this->test->returnCallback([$this, 'deleteRelationship']));

		$mock->expects($this->test->any())
				->method('remove')
				->will($this->test->returnCallback([$this, 'deleteRelationship']));

		$mock->expects($this->test->any())
				->method('check')
				->will($this->test->returnCallback([$this, 'checkRelationship']));

		return $mock;
	}

}
