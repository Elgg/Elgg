<?php

namespace Elgg\Groups;

use Elgg\UnitTestCase;

/**
 * @group Groups
 * @group GroupTools
 */
class ToolsUnitTest extends UnitTestCase {

	public function up() {
		elgg()->group_tools->all()->fill([]);
	}

	public function down() {

	}

	public function testCanRegisterTools() {

		elgg()->group_tools->register('my-tool', [
			'default_on' => false,
			'priority' => 300,
		]);

		$tool = elgg()->group_tools->get('my-tool');

		$this->assertInstanceOf(Tool::class, $tool);
		$this->assertEquals(elgg_echo("groups:tool:my-tool"), $tool->label);
		$this->assertEquals($tool->label, $tool->getLabel());
		$this->assertEquals('my-tool', $tool->name);
		$this->assertEquals($tool->name, $tool->getID());
		$this->assertFalse($tool->default_on);
		$this->assertFalse($tool->isEnabledByDefault());
		$this->assertEquals('my-tool_enable', $tool->mapMetadataName());
		$this->assertEquals('no', $tool->mapMetadataValue());
		$this->assertEquals('yes', $tool->mapMetadataValue('yes'));
		$this->assertEquals('yes', $tool->mapMetadataValue(true));
		$this->assertEquals('no', $tool->mapMetadataValue(false));
		$this->assertEquals(300, $tool->priority);

		elgg()->group_tools->unregister('my-tool');

		$tool = elgg()->group_tools->get('my-tool');
		$this->assertNull($tool);
	}

	public function testCanMutateToolAtRuntime() {
		elgg()->group_tools->register('my-tool');

		$tool = elgg()->group_tools->get('my-tool');

		$this->assertEquals(elgg_echo("groups:tool:my-tool"), $tool->label);
		$this->assertTrue($tool->isEnabledByDefault());
		$this->assertEquals(500, $tool->priority);

		elgg()->group_tools->get('my-tool')->label = 'edited';
		elgg()->group_tools->get('my-tool')->default_on = false;
		elgg()->group_tools->get('my-tool')->priority = 300;

		$this->assertEquals('edited', $tool->label);
		$this->assertFalse($tool->isEnabledByDefault());
		$this->assertEquals(300, $tool->priority);
	}
}