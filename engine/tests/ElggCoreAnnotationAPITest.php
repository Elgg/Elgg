<?php
/**
 * Elgg Test annotation api
 *
 * @package Elgg
 * @subpackage Test
 */
class ElggCoreAnnotationAPITest extends ElggCoreUnitTest {
	protected $metastrings;

	/**
	 * Called before each test method.
	 */
	public function setUp() {
		$this->object = new ElggObject();
	}

	/**
	 * Called after each test method.
	 */
	public function tearDown() {
		// do not allow SimpleTest to interpret Elgg notices as exceptions
		$this->swallowErrors();

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
		$e = new ElggObject();
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

		$this->assertTrue($e->delete());
	}
}
