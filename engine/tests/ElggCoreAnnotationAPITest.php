<?php
/**
 * Elgg Test annotation api
 *
 * @package Elgg
 * @subpackage Test
 */
class ElggCoreAnnotationAPITest extends \ElggCoreUnitTest {
	protected $metastrings;

	/**
	 * Called before each test method.
	 */
	public function setUp() {
		$this->object = new \ElggObject();
	}

	/**
	 * Called after each test method.
	 */
	public function tearDown() {

		unset($this->object);
	}

	public function testElggGetAnnotationsCount() {
		$this->object->title = 'Annotation Unit Test';
		$this->object->save();

		$guid = $this->object->getGUID();
		create_annotation($guid, 'tested', 'tested1', 'text', 0, ACCESS_PUBLIC);
		create_annotation($guid, 'tested', 'tested2', 'text', 0, ACCESS_PUBLIC);

		$count = (int)elgg_get_annotations(array(
			'annotation_names' => array('tested'),
			'guid' => $guid,
			'count' => true,
		));

		$this->assertIdentical($count, 2);

		$this->object->delete();
	}

	public function testElggDeleteAnnotations() {
		$e = new \ElggObject();
		$e->save();

		for ($i=0; $i<30; $i++) {
			$e->annotate('test_annotation', rand(0,10000));
		}

		$options = array(
			'guid' => $e->getGUID(),
			'limit' => 0
		);

		$annotations = elgg_get_annotations($options);
		$this->assertIdentical(30, count($annotations));

		$this->assertTrue(elgg_delete_annotations($options));

		$annotations = elgg_get_annotations($options);
		$this->assertTrue(empty($annotations));

		// nothing to delete so null returned
		$this->assertNull(elgg_delete_annotations($options));

		$this->assertTrue($e->delete());
	}

	public function testElggDisableAnnotations() {
		$e = new \ElggObject();
		$e->save();

		for ($i=0; $i<30; $i++) {
			$e->annotate('test_annotation', rand(0,10000));
		}

		$options = array(
			'guid' => $e->getGUID(),
			'limit' => 0
		);

		$this->assertTrue(elgg_disable_annotations($options));

		$annotations = elgg_get_annotations($options);
		$this->assertTrue(empty($annotations));

		access_show_hidden_entities(true);
		$annotations = elgg_get_annotations($options);
		$this->assertIdentical(30, count($annotations));
		access_show_hidden_entities(false);

		$this->assertTrue($e->delete());
	}

	public function testElggEnableAnnotations() {
		$e = new \ElggObject();
		$e->save();

		for ($i=0; $i<30; $i++) {
			$e->annotate('test_annotation', rand(0,10000));
		}

		$options = array(
			'guid' => $e->getGUID(),
			'limit' => 0
		);

		$this->assertTrue(elgg_disable_annotations($options));

		// cannot see any annotations so returns null
		$this->assertNull(elgg_enable_annotations($options));

		access_show_hidden_entities(true);
		$this->assertTrue(elgg_enable_annotations($options));
		access_show_hidden_entities(false);

		$annotations = elgg_get_annotations($options);
		$this->assertIdentical(30, count($annotations));

		$this->assertTrue($e->delete());
	}

	public function testElggAnnotationExists() {
		$e = new \ElggObject();
		$e->save();
		$guid = $e->getGUID();

		$this->assertFalse(elgg_annotation_exists($guid, 'test_annotation'));

		$e->annotate('test_annotation', rand(0, 10000));
		$this->assertTrue(elgg_annotation_exists($guid, 'test_annotation'));
		// this metastring should always exist but an annotation of this name should not
		$this->assertFalse(elgg_annotation_exists($guid, 'email'));

		$options = array(
			'guid' => $guid,
			'limit' => 0
		);
		$this->assertTrue(elgg_disable_annotations($options));
		$this->assertTrue(elgg_annotation_exists($guid, 'test_annotation'));

		$this->assertTrue($e->delete());
		$this->assertFalse(elgg_annotation_exists($guid, 'test_annotation'));
	}
}
