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

		_elgg_invalidate_cache_for_entity($entity->guid);
		_elgg_invalidate_memcache_for_entity($entity->guid);

		$entity = get_entity($entity->guid);

		$this->assertTrue(elgg_instanceof($entity, $type, $subtype));
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
