<?php

namespace Elgg\Integration;

use ElggGroup;
use Elgg\IntegrationTestCase;

/**
 * @group Groups
 * @group GroupTools
 */
class ElggCoreGroupToolTest extends IntegrationTestCase {
	/**
	 * @var ElggGroup
	 */
	protected $group;

	/**
	 * @var \ElggUser
	 */
	protected $user;

	public function up() {
		$this->group = $this->createGroup();
	}

	public function down() {
		$this->group->delete();
	}
	
	public function testToolRegistration() {
		elgg()->group_tools->register('test_option');
		
		$this->assertArrayHasKey('test_option', elgg()->group_tools->all());
		
		elgg()->group_tools->unregister('test_option');
		
		$this->assertArrayNotHasKey('test_option', elgg()->group_tools->all());
	}
	
	public function testCanSaveGroupToolAvailability() {
		$this->assertNull($this->group->test_option_enable);
		$this->assertFalse($this->group->enableTool('test_option'));
		$this->assertFalse($this->group->disableTool('test_option'));
		
		elgg()->group_tools->register('test_option');
		
		$this->assertTrue($this->group->enableTool('test_option'));
		$this->assertEquals('yes', $this->group->test_option_enable);
		
		$this->assertTrue($this->group->disableTool('test_option'));
		$this->assertEquals('no', $this->group->test_option_enable);
	}
	
	public function testCanCheckGroupToolAvailability() {
		$this->assertFalse($this->group->isToolEnabled(''));
		$this->assertFalse($this->group->isToolEnabled('test_option2'));
		
		elgg()->group_tools->register('test_option2');
		$this->assertTrue($this->group->isToolEnabled('test_option2'));
		
		$this->assertTrue($this->group->disableTool('test_option2'));
		$this->assertFalse($this->group->isToolEnabled('test_option2'));
		
		$this->assertTrue($this->group->enableTool('test_option2'));
		$this->assertTrue($this->group->isToolEnabled('test_option2'));
	}
}
