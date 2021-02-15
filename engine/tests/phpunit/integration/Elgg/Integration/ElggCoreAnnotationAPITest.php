<?php

namespace Elgg\Integration;

use Elgg\IntegrationTestCase;

/**
 * Elgg Test annotation api
 *
 * @group IntegrationTests
 * @group EntityAnnotations
 * @group Annotations
 */
class ElggCoreAnnotationAPITest extends IntegrationTestCase {

	/**
	 * @var \ElggObject
	 */
	protected $object;

	public function up() {
		_elgg_services()->session->setLoggedInUser($this->getAdmin());

		$this->object = new \ElggObject();
		$this->object->setSubtype($this->getRandomSubtype());
	}

	public function down() {
		if ($this->object instanceof \ElggEntity) {
			$this->object->delete();
		}

		_elgg_services()->session->removeLoggedInUser();
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
		$e = new \ElggObject();
		$e->setSubtype($this->getRandomSubtype());
		$e->save();

		for ($i = 0; $i < 30; $i++) {
			$e->annotate('test_annotation', rand(0, 10000));
		}

		$options = [
			'guid' => $e->getGUID(),
			'limit' => 0
		];

		$annotations = elgg_get_annotations($options);
		$this->assertEquals(30, count($annotations));

		$this->assertTrue(elgg_delete_annotations($options));

		$annotations = elgg_get_annotations($options);
		$this->assertTrue(empty($annotations));

		// nothing to delete so null returned
		$this->assertNull(elgg_delete_annotations($options));

		$this->assertTrue($e->delete());
	}

	public function testElggDisableAnnotations() {
		$e = new \ElggObject();
		$e->setSubtype($this->getRandomSubtype());
		$e->save();

		for ($i = 0; $i < 30; $i++) {
			$e->annotate('test_annotation', rand(0, 10000));
		}

		$options = [
			'guid' => $e->getGUID(),
			'limit' => 0
		];

		$this->assertTrue(elgg_disable_annotations($options));

		$annotations = elgg_get_annotations($options);
		$this->assertTrue(empty($annotations));

		$annotations = elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function() use ($options) {
			return elgg_get_annotations($options);
		});
		$this->assertEquals(30, count($annotations));

		$this->assertTrue($e->delete());
	}

	public function testElggEnableAnnotations() {
		$e = new \ElggObject();
		$e->setSubtype($this->getRandomSubtype());
		$e->save();

		for ($i = 0; $i < 30; $i++) {
			$e->annotate('test_annotation', rand(0, 10000));
		}

		$options = [
			'guid' => $e->getGUID(),
			'limit' => 0
		];

		$this->assertTrue(elgg_disable_annotations($options));

		// cannot see any annotations so returns null
		$this->assertNull(elgg_enable_annotations($options));

		elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function() use ($options) {
			$this->assertTrue(elgg_enable_annotations($options));
		});

		$annotations = elgg_get_annotations($options);
		$this->assertEquals(30, count($annotations));

		$this->assertTrue($e->delete());
	}

	public function testElggAnnotationExists() {
		$e = new \ElggObject();
		$e->setSubtype($this->getRandomSubtype());
		$e->save();
		$guid = $e->getGUID();

		$this->assertFalse(elgg_annotation_exists($guid, 'test_annotation'));

		$id = $e->annotate('test_annotation', rand(0, 10000));
		$this->assertNotFalse($id);

		$this->assertTrue(elgg_annotation_exists($guid, 'test_annotation'));
		// this metastring should always exist but an annotation of this name should not
		$this->assertFalse(elgg_annotation_exists($guid, 'email'));

		$options = [
			'guid' => $guid,
			'limit' => 0
		];
		$this->assertTrue(elgg_disable_annotations($options));
		$this->assertTrue(elgg_annotation_exists($guid, 'test_annotation'));

		$this->assertTrue($e->delete());
		$this->assertFalse(elgg_annotation_exists($guid, 'test_annotation'));
	}

	/**
	 * @dataProvider booleanPairsProvider
	 */
	public function testElggGetEntitiesFromBooleanAnnotation($value, $query, $type) {

		$object = $this->createObject();
		$this->assertNotEmpty($object->annotate('annotation', $value));

		$options = [
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
		];

		$result = elgg_get_entities($options);

		$this->assertEquals(1, $result);

		$object->delete();
	}

	public function booleanPairsProvider() {
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

}
