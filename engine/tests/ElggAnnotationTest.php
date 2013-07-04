<?php
/**
 * test ElggAnnotation
 *
 * @package Elgg
 * @subpackage Test
 */
class ElggAnnotationTest extends ElggCoreUnitTest {

	/**
	 * @var ElggEntity
	 */
	protected $entity;

	/**
	 * Called before each test method.
	 */
	public function setUp() {
		$this->original_hooks = _elgg_services()->hooks;
		_elgg_services()->hooks = new Elgg_PluginHooksService();

		$this->entity = new ElggObject();
		$this->entity->subtype = 'elgg_annotation_test';
		$this->entity->access_id = ACCESS_PUBLIC;
		$this->entity->save();
	}

	/**
	 * Called after each test method.
	 */
	public function tearDown() {
		$this->entity->delete();
		remove_subtype('object', 'elgg_annotation_test');

		_elgg_services()->hooks = $this->original_hooks;
	}

	public function testCanEdit() {
		$user = new ElggUser();
		$user->save();

		$id = $this->entity->annotate('test', 'foo', ACCESS_LOGGED_IN, elgg_get_logged_in_user_guid());
		$a = elgg_get_annotation_from_id($id);
		$this->assertTrue($a->canEdit());
		$this->assertFalse($a->canEdit($user->guid));

		$id = $this->entity->annotate('test', 'foo2', ACCESS_LOGGED_IN, $user->guid);
		$a = elgg_get_annotation_from_id($id);
		$this->assertTrue($a->canEdit());
		$this->assertTrue($a->canEdit($user->guid));

		$user->delete();
	}

}
