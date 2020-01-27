<?php

namespace Elgg\Integration;

use Elgg\IntegrationTestCase;

/**
 * @group EntityClasses
 */
class ElggEntityClassResolutionTest extends IntegrationTestCase {

	public function up() {

	}

	public function down() {

	}

	/**
	 * @dataProvider entityClasses
	 */
	public function testCoreEntityClassRegistrations($type, $subtype, $class) {

		$entity = $this->createOne($type, [
			'subtype' => $subtype,
		]);

		$entity->invalidateCache();

		$entity = get_entity($entity->guid);
		
		$this->assertEquals($type, $entity->getType());
		$this->assertEquals($subtype, $entity->getSubtype());
		$this->assertInstanceOf($class, $entity);
	}

	public function entityClasses() {
		return [
			['user', 'user', \ElggUser::class],
			['group', 'group', \ElggGroup::class],
			//['object', 'plugin', \ElggPlugin::class],
			['object', 'file', \ElggFile::class],
			//['object', 'widget', \ElggWidget::class],
			['object', 'comment', \ElggComment::class],
			//['object', 'elgg_upgrade', \ElggUpgrade::class],
		];
	}
}
