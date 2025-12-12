<?php

namespace Elgg\Integration;

use Elgg\IntegrationTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class ElggCoreAnnotationAPITest extends IntegrationTestCase {

	/**
	 * @var \ElggObject
	 */
	protected $object;

	public function up() {
		_elgg_services()->session_manager->setLoggedInUser($this->getAdmin());

		$this->object = $this->createObject();
	}

	public function testElggGetAnnotationsCount() {
		$this->object->title = 'Annotation Unit Test';
		$this->object->save();

		$guid = $this->object->getGUID();
		$this->object->annotate('tested', 'tested1', ACCESS_PUBLIC, 0, 'text');
		$this->object->annotate('tested', 'tested2', ACCESS_PUBLIC, 0, 'text');

		$count = (int) elgg_get_annotations([
			'annotation_names' => ['tested'],
			'guid' => $guid,
			'count' => true,
		]);

		$this->assertEquals(2, $count);

		$this->object->delete();
	}

	public function testElggDeleteAnnotations() {
		$e = $this->createObject();

		for ($i = 0; $i < 30; $i++) {
			$e->annotate('test_annotation', rand(0, 10000));
		}

		$options = [
			'guid' => $e->getGUID(),
			'limit' => false,
		];

		$annotations = elgg_get_annotations($options);
		$this->assertEquals(30, count($annotations));

		$this->assertTrue(elgg_delete_annotations($options));

		$annotations = elgg_get_annotations($options);
		$this->assertTrue(empty($annotations));

		// nothing to delete so true returned
		$this->assertTrue(elgg_delete_annotations($options));
	}

	public function testElggAnnotationExists() {
		$e = $this->createObject();
		$guid = $e->getGUID();

		$this->assertFalse(elgg_annotation_exists($guid, 'test_annotation'));

		$id = $e->annotate('test_annotation', rand(0, 10000));
		$this->assertNotFalse($id);

		$this->assertTrue(elgg_annotation_exists($guid, 'test_annotation'));
		// this metastring should always exist but an annotation of this name should not
		$this->assertFalse(elgg_annotation_exists($guid, 'email'));

		$this->assertTrue($e->delete());
		$this->assertFalse(elgg_annotation_exists($guid, 'test_annotation'));
	}

	#[DataProvider('booleanPairsProvider')]
	public function testElggGetEntitiesFromBooleanAnnotation($value, $query, $type) {

		$object = $this->createObject();
		$this->assertNotEmpty($object->annotate('annotation', $value));

		$result = elgg_get_entities([
			'type' => 'object',
			'subtype' => $object->subtype,
			'annotation_name_value_pairs' => [
				[
					'name' => 'annotation',
					'value' => $query,
					'operand' => '=',
					'type' => $type,
				]
			],
			'count' => true,
		]);

		$this->assertEquals(1, $result);
	}

	public static function booleanPairsProvider() {
		return [
			[true, true, null],
			[true, 1, null],
			[true, '1', ELGG_VALUE_INTEGER],
			[false, false, null],
			[false, 0, null],
			[false, '0', ELGG_VALUE_INTEGER],
			[1, true, null],
			[0, false, null],
		];
	}

	public function testCanDeleteAnnotationById() {

		$entity = $this->createObject();
		$this->assertNotEmpty($entity->annotate('foo', 'bar'));
		$this->assertNotEmpty($entity->annotate('bar', 'baz'));

		$annotations = elgg_get_annotations([
			'guid' => $entity->guid,
			'annotation_names' => ['foo', 'bar'],
		]);

		foreach ($annotations as $annotation) {
			$this->assertTrue(elgg_delete_annotation_by_id($annotation->id));
		}

		$this->assertEmpty($entity->getAnnotations([
			'annotation_names' => ['foo', 'bar'],
		]));
	}
	
	public function testCanPreloadAnnotationOwners() {
		$entity = $this->createObject();
		$owner1 = $this->createUser();
		$owner2 = $this->createUser();
		
		$entity->annotate('foo', 'bar', ACCESS_PUBLIC, $owner1->guid);
		$entity->annotate('foo', 'baz', ACCESS_PUBLIC, $owner2->guid);
		
		elgg_get_annotations([
			'guid' => $entity->guid,
			'annotation_name' => 'foo',
		]);
		
		$this->assertNull(_elgg_services()->entityCache->load($owner1->guid));
		$this->assertNull(_elgg_services()->entityCache->load($owner2->guid));
		
		elgg_get_annotations([
			'guid' => $entity->guid,
			'annotation_name' => 'foo',
			'preload_owners' => true,
		]);
		
		$this->assertNotNull(_elgg_services()->entityCache->load($owner1->guid));
		$this->assertNotNull(_elgg_services()->entityCache->load($owner2->guid));
	}
}
