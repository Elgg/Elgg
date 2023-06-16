<?php

use Elgg\IntegrationTestCase;

/**
 * Elgg Test \ElggWidget
 */
class ElggWidgetIntegrationTest extends IntegrationTestCase {
	/**
	 * @var ElggWidget
	 */
	protected $widget;

	/**
	 * @var ElggUser
	 */
	protected $user;

	public function up() {
		$this->user = $this->createUser();
		_elgg_services()->session_manager->setLoggedInUser($this->user);
		
		$this->widget = $this->createObject([
			'subtype' => 'widget',
			'tags' => 'tag',
		]);
		_elgg_services()->session_manager->removeLoggedInUser();
		
		elgg_register_widget_type(['id' => 'widget_move_handler', 'context' => ['move_widget_context']]);
	}

	public function down() {
		elgg_unregister_widget_type('widget_move_handler');
	}

	public function testSettingAllowedMetadata() {
		$this->widget->title = 'test_title';
		$this->assertEquals('test_title', $this->widget->title);
		$this->assertEquals('test_title', $this->widget->getMetadata('title'));
		$this->widget->description = 'test_description';
		$this->assertEquals('test_description', $this->widget->description);
		$this->assertEquals('test_description', $this->widget->getMetadata('description'));
	}
	
	public function testSettingGuidIsNotAllowed() {
		$current_guid = $this->widget->guid;
		$this->widget->guid = 12345;
		$this->assertEquals($current_guid, $this->widget->guid);
	}
		
	public function testUnsettingMetadata() {
		$this->widget->title = 'testing';
		$this->assertEquals('testing', $this->widget->title);
		$this->assertTrue(isset($this->widget->title));
		unset($this->widget->title);
		$this->assertEmpty($this->widget->title);
		$this->assertFalse(isset($this->widget->title));
	}
		
	public function testGetTitle() {
		$this->widget->title = 'get_title';
		$this->widget->handler = 'widget_handler';
		$this->widget->context = 'widget_context';
		$this->assertEquals('get_title', $this->widget->getDisplayName());
		
		unset($this->widget->title);
		
		elgg_register_widget_type([
			'id' => 'widget_handler',
			'name' => 'title_from_definition',
			'description' => 'widget_description',
			'context' => ['widget_context'],
		]);
		$this->assertEquals('title_from_definition', $this->widget->getDisplayName());
	}
		
	public function testMovingWidgetsWithinSameColumnToLastPosition() {
		$widget_one = $this->createObject([
			'subtype' => 'widget',
			'handler' => 'widget_move_handler',
			'context' => 'move_widget_context',
			'container_guid' => $this->user->guid,
			'column' => 1,
			'order' => 1,
		]);
		
		$widget_two = $this->createObject([
			'subtype' => 'widget',
			'handler' => 'widget_move_handler',
			'context' => 'move_widget_context',
			'container_guid' => $this->user->guid,
			'column' => 1,
			'order' => 2,
		]);
		
		$widget_three = $this->createObject([
			'subtype' => 'widget',
			'handler' => 'widget_move_handler',
			'context' => 'move_widget_context',
			'container_guid' => $this->user->guid,
			'column' => 1,
			'order' => 3,
		]);
		
		// move first to bottom
		$widget_one->move(1, -1); // expected result: two (2), three(3), one(13)
		
		$widget_one->invalidateCache();
		$widget_two->invalidateCache();
		$widget_three->invalidateCache();
		
		$this->assertEquals(2, $widget_two->order);
		$this->assertEquals(3, $widget_three->order);
		$this->assertEquals(13, $widget_one->order);
		
		// move second to bottom
		$widget_three->move(1, -1); // expected result: two (2), one(13), three(23)
		
		$widget_one->invalidateCache();
		$widget_two->invalidateCache();
		$widget_three->invalidateCache();
		
		$this->assertEquals(2, $widget_two->order);
		$this->assertEquals(13, $widget_one->order);
		$this->assertEquals(23, $widget_three->order);
		
		// move last to bottom
		$widget_three->move(1, -1); // expected result: two (2), one(13), three(33)
		
		$widget_one->invalidateCache();
		$widget_two->invalidateCache();
		$widget_three->invalidateCache();
		
		$this->assertEquals(2, $widget_two->order);
		$this->assertEquals(13, $widget_one->order);
		$this->assertEquals(33, $widget_three->order);
	}
		
	public function testMovingWidgetsWithinSameColumnToFirstPosition() {
		$widget_one = $this->createObject([
			'subtype' => 'widget',
			'handler' => 'widget_move_handler',
			'context' => 'move_widget_context',
			'container_guid' => $this->user->guid,
			'column' => 1,
			'order' => 1,
		]);
		
		$widget_two = $this->createObject([
			'subtype' => 'widget',
			'handler' => 'widget_move_handler',
			'context' => 'move_widget_context',
			'container_guid' => $this->user->guid,
			'column' => 1,
			'order' => 2,
		]);
		
		$widget_three = $this->createObject([
			'subtype' => 'widget',
			'handler' => 'widget_move_handler',
			'context' => 'move_widget_context',
			'container_guid' => $this->user->guid,
			'column' => 1,
			'order' => 3,
		]);
		
		// move last to top
		$widget_three->move(1, 0); // expected result: three(-9), one(1), two (2)
		
		$widget_one->invalidateCache();
		$widget_two->invalidateCache();
		$widget_three->invalidateCache();
		
		$this->assertEquals(-9, $widget_three->order);
		$this->assertEquals(1, $widget_one->order);
		$this->assertEquals(2, $widget_two->order);
		
		// move second to top
		$widget_one->move(1, 0); // expected result: one(-19), three(-9), two (2)
		
		$widget_one->invalidateCache();
		$widget_two->invalidateCache();
		$widget_three->invalidateCache();
		
		$this->assertEquals(-19, $widget_one->order);
		$this->assertEquals(-9, $widget_three->order);
		$this->assertEquals(2, $widget_two->order);
		
		// move first to top
		$widget_one->move(1, 0); // expected result: one(-29), three(-9), two (2)
		
		$widget_one->invalidateCache();
		$widget_two->invalidateCache();
		$widget_three->invalidateCache();
		
		$this->assertEquals(-29, $widget_one->order);
		$this->assertEquals(-9, $widget_three->order);
		$this->assertEquals(2, $widget_two->order);
	}
		
	public function testMovingWidgetsWithinSameColumnToPositionInBetweenOtherWidgets() {
		$widget_one = $this->createObject([
			'subtype' => 'widget',
			'handler' => 'widget_move_handler',
			'context' => 'move_widget_context',
			'container_guid' => $this->user->guid,
			'column' => 1,
			'order' => 1,
		]);
		
		$widget_two = $this->createObject([
			'subtype' => 'widget',
			'handler' => 'widget_move_handler',
			'context' => 'move_widget_context',
			'container_guid' => $this->user->guid,
			'column' => 1,
			'order' => 2,
		]);
		
		$widget_three = $this->createObject([
			'subtype' => 'widget',
			'handler' => 'widget_move_handler',
			'context' => 'move_widget_context',
			'container_guid' => $this->user->guid,
			'column' => 1,
			'order' => 3,
		]);
		
		$widget_four = $this->createObject([
			'subtype' => 'widget',
			'handler' => 'widget_move_handler',
			'context' => 'move_widget_context',
			'container_guid' => $this->user->guid,
			'column' => 1,
			'order' => 4,
		]);
		
		// move last to rank 1
		$widget_four->move(1, 1); // expected result: one(0), four(10), two (20), three(30)
		
		$widget_one->invalidateCache();
		$widget_two->invalidateCache();
		$widget_three->invalidateCache();
		$widget_four->invalidateCache();
		
		$this->assertEquals(0, $widget_one->order);
		$this->assertEquals(10, $widget_four->order);
		$this->assertEquals(20, $widget_two->order);
		$this->assertEquals(30, $widget_three->order);
		
		// move rank 1 to rank 2
		$widget_four->move(1, 2); // expected result: one(0), two (10), four(20), three(30)
		
		$widget_one->invalidateCache();
		$widget_two->invalidateCache();
		$widget_three->invalidateCache();
		$widget_four->invalidateCache();
		
		$this->assertEquals(0, $widget_one->order);
		$this->assertEquals(10, $widget_two->order);
		$this->assertEquals(20, $widget_four->order);
		$this->assertEquals(30, $widget_three->order);
		
		// move to current rank
		$widget_four->move(1, 2); // expected result: one(0), two (10), four(20), three(30)
		
		$widget_one->invalidateCache();
		$widget_two->invalidateCache();
		$widget_three->invalidateCache();
		$widget_four->invalidateCache();
		
		$this->assertEquals(0, $widget_one->order);
		$this->assertEquals(10, $widget_two->order);
		$this->assertEquals(20, $widget_four->order);
		$this->assertEquals(30, $widget_three->order);
		
		// move to very high rank
		$widget_four->move(1, 200); // expected result: one(0), two (10), three(20), four(30)
		
		$widget_one->invalidateCache();
		$widget_two->invalidateCache();
		$widget_three->invalidateCache();
		$widget_four->invalidateCache();
		
		$this->assertEquals(0, $widget_one->order);
		$this->assertEquals(10, $widget_two->order);
		$this->assertEquals(20, $widget_three->order);
		$this->assertEquals(30, $widget_four->order);
		
		// move to very negative rank
		$widget_four->move(1, -200); // expected result: four(0), one(10), two (20), three(30)
		
		$widget_one->invalidateCache();
		$widget_two->invalidateCache();
		$widget_three->invalidateCache();
		$widget_four->invalidateCache();
		
		$this->assertEquals(0, $widget_four->order);
		$this->assertEquals(10, $widget_one->order);
		$this->assertEquals(20, $widget_two->order);
		$this->assertEquals(30, $widget_three->order);
	}
		
	public function testMovingWidgetsToEmptyColumn() {
		$widget_one = $this->createObject([
			'subtype' => 'widget',
			'handler' => 'widget_move_handler',
			'context' => 'move_widget_context',
			'container_guid' => $this->user->guid,
			'column' => 1,
			'order' => 1,
		]);
		
		// move to rank 1 in column 2
		$widget_one->move(2, 1); // expected result: one(0), four(10), two (20), three(30)
		
		$widget_one->invalidateCache();
		
		$this->assertEquals(2, $widget_one->column);
		$this->assertEquals(0, $widget_one->order);
	}
		
	public function testMovingWidgetsToBottomOfOtherColumn() {
		$widget_one = $this->createObject([
			'subtype' => 'widget',
			'handler' => 'widget_move_handler',
			'context' => 'move_widget_context',
			'container_guid' => $this->user->guid,
			'column' => 1,
			'order' => 1,
		]);
		
		$widget_two = $this->createObject([
			'subtype' => 'widget',
			'handler' => 'widget_move_handler',
			'context' => 'move_widget_context',
			'container_guid' => $this->user->guid,
			'column' => 2,
			'order' => 1,
		]);
		
		// move to bottom in column 2
		$widget_one->move(2, -1); // expected result: two(1), one(11)
		
		$widget_one->invalidateCache();
		$widget_two->invalidateCache();
		
		$this->assertEquals(2, $widget_one->column);
		
		$this->assertEquals(1, $widget_two->order);
		$this->assertEquals(11, $widget_one->order);
	}
		
	public function testMovingWidgetsToTopOfOtherColumn() {
		$widget_one = $this->createObject([
			'subtype' => 'widget',
			'handler' => 'widget_move_handler',
			'context' => 'move_widget_context',
			'container_guid' => $this->user->guid,
			'column' => 1,
			'order' => 1,
		]);
		
		$widget_two = $this->createObject([
			'subtype' => 'widget',
			'handler' => 'widget_move_handler',
			'context' => 'move_widget_context',
			'container_guid' => $this->user->guid,
			'column' => 2,
			'order' => 1,
		]);
		
		// move to top in column 2
		$widget_one->move(2, 0); // expected result: one(-9), two(1)
		
		$widget_one->invalidateCache();
		$widget_two->invalidateCache();
		
		$this->assertEquals(2, $widget_one->column);
		
		$this->assertEquals(-9, $widget_one->order);
		$this->assertEquals(1, $widget_two->order);
	}
		
	public function testMovingWidgetsBetweenOtherWidgetsInOtherColumn() {
		$widget_one = $this->createObject([
			'subtype' => 'widget',
			'handler' => 'widget_move_handler',
			'context' => 'move_widget_context',
			'container_guid' => $this->user->guid,
			'column' => 1,
			'order' => 1,
		]);
		
		$widget_two = $this->createObject([
			'subtype' => 'widget',
			'handler' => 'widget_move_handler',
			'context' => 'move_widget_context',
			'container_guid' => $this->user->guid,
			'column' => 2,
			'order' => 1,
		]);

		$widget_three = $this->createObject([
			'subtype' => 'widget',
			'handler' => 'widget_move_handler',
			'context' => 'move_widget_context',
			'container_guid' => $this->user->guid,
			'column' => 2,
			'order' => 2,
		]);
		
		// move to top in column 2
		$widget_one->move(2, 1); // expected result: two(0), one(10), three(20)
		
		$widget_one->invalidateCache();
		$widget_two->invalidateCache();
		$widget_three->invalidateCache();
		
		$this->assertEquals(2, $widget_one->column);
		
		$this->assertEquals(0, $widget_two->order);
		$this->assertEquals(10, $widget_one->order);
		$this->assertEquals(20, $widget_three->order);
	}
}
