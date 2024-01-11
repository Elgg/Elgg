<?php

use Elgg\IntegrationTestCase;

class ElggRiverItemIntegrationTest extends IntegrationTestCase {
	
	/**
	 * @var \ElggUser
	 */
	protected $subject;
	
	/**
	 * @var \ElggObject
	 */
	protected $object;
	
	/**
	 * @var \ElggGroup
	 */
	protected $target;
	
	/**
	 * @var \ElggAnnotation
	 */
	protected $annotation;
	
	/**
	 * Get an ElggRiverItem saved to the database
	 *
	 * @return \ElggRiverItem
	 */
	protected function getRiverItem(): \ElggRiverItem {
		$this->subject = $this->createUser();
		$this->object = $this->createObject();
		$this->target = $this->createGroup();
		
		$annotation_id = $this->object->annotate('foo', 'bar', ACCESS_PUBLIC);
		$this->assertIsInt($annotation_id);
		$this->annotation = elgg_get_annotation_from_id($annotation_id);
		
		return elgg_create_river_item([
			'view' => 'river/object/create',
			'action_type' => 'create',
			'object_guid' => $this->object->guid,
			'subject_guid' => $this->subject->guid,
			'target_guid' => $this->target->guid,
			'annotation_id' => $annotation_id,
			'posted' => time(),
		]);
	}
	
	public function up() {
		$this->createApplication([
			'isolate' => true,
		]);
	}
	
	public function testGetTypeSubtype() {
		$item = $this->getRiverItem();
		
		$this->assertEquals($this->object->getType(), $item->type);
		$this->assertEquals($this->object->getSubtype(), $item->subtype);
	}
	
	public function testGetSubjectEntity() {
		$item = $this->getRiverItem();
		
		$subject = $item->getSubjectEntity();
		$this->assertInstanceOf(\ElggEntity::class, $subject);
		$this->assertElggDataEquals($this->subject, $subject);
	}
	
	public function testGetTargetEntity() {
		$item = $this->getRiverItem();
		
		$target = $item->getTargetEntity();
		$this->assertInstanceOf(\ElggEntity::class, $target);
		$this->assertElggDataEquals($this->target, $target);
	}
	
	public function testGetObjectEntity() {
		$item = $this->getRiverItem();
		
		$object = $item->getObjectEntity();
		$this->assertInstanceOf(\ElggEntity::class, $object);
		$this->assertElggDataEquals($this->object, $object);
	}
	
	public function testGetAnnotation() {
		$item = $this->getRiverItem();
		
		$annotation = $item->getAnnotation();
		$this->assertInstanceOf(\ElggAnnotation::class, $annotation);
		$this->assertEquals($this->annotation, $annotation);
	}
	
	public function testGetView() {
		$item = $this->getRiverItem();
		
		$this->assertEquals('river/object/create', $item->getView());
	}
	
	public function testGetTimePosted() {
		$item = $this->getRiverItem();
		
		$this->assertIsInt($item->getTimePosted());
		$this->assertGreaterThan(time() - 5, $item->getTimePosted());
		$this->assertLessThanOrEqual(time(), $item->getTimePosted());
	}
	
	public function testLastActionOnCreate() {
		$item = $this->getRiverItem();
		
		$this->assertIsInt($item->last_action);
		$this->assertEquals($item->getTimePosted(), $item->last_action);
	}
	
	public function testUpdateLastAction() {
		$item = $this->getRiverItem();
		
		$new_last_action = $item->last_action + 100;
		$item->updateLastAction($new_last_action);
		$this->assertEquals($new_last_action, $item->last_action);
		
		$loaded_item = elgg_get_river_item_from_id($item->id);
		$this->assertEquals($new_last_action, $loaded_item->last_action);
	}
	
	public function testCanDelete() {
		$item = $this->getRiverItem();
		
		$this->assertFalse($item->canDelete()); // no logged in user
		$this->assertFalse($item->canDelete($this->subject->guid));
		
		$admin = $this->getAdmin();
		$this->assertTrue($item->canDelete($admin->guid));
		
		elgg_call(ELGG_IGNORE_ACCESS, function() use ($item) {
			$this->assertTrue($item->canDelete());
			$this->assertTrue($item->canDelete($this->subject->guid));
		});
	}
	
	public function testUpdateNotPersistent() {
		$item = $this->getRiverItem();
		
		$item->view = 'river/object/comment/create';
		$this->assertEquals('river/object/comment/create', $item->view);
		
		$this->assertTrue($item->save());
		
		$original = elgg_get_river_item_from_id($item->id);
		$this->assertNotEquals($original->view, $item->view);
	}
}
