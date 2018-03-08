<?php

namespace Elgg\Search;

use Elgg\UnitTestCase;

/**
 * @group Search
 * @group Hooks
 */
class FormatCommentEntityHookTest extends UnitTestCase {

	public function up() {
		$this->startPlugin();
		_elgg_services()->hooks->backup();
	}

	public function down() {
		_elgg_services()->hooks->restore();
	}

	public function testCommentVolatileDataIsPopulated() {
		$hook = $this->registerTestingHook('search:format', 'entity', FormatComentEntityHook::class);

		$object = $this->createObject([], [
			'title' => 'Container',
		]);
		$comment = $this->createObject([
			'subtype' => 'comment',
			'owner_guid' => 0,
			'container_guid' => $object->guid,
		], [
			'title' => 'Comment',
		]);

		$search = new Search([
			'query' => 'comment',
			'search_type' => 'entities',
			'entity_type' => 'object',
			'entity_subtype' => 'comment',
		]);

		$formatter = new Formatter($comment, $search);
		$formatter->format();

		$hook->assertNumberOfCalls(1);

		$comment = $hook->getResult();

		$this->assertEmpty($comment->getVolatileData('search_matched_icon'));

		$this->assertEquals(
			elgg_echo('search:comment_on', ['Container']),
			$comment->getVolatileData('search_matched_title')
		);

		$hook->unregister();

	}
}