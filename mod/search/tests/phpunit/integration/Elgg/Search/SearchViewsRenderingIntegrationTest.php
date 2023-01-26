<?php

namespace Elgg\Search;

use Elgg\Views\ViewRenderingIntegrationTestCase;

/**
 * @group ViewRendering
 * @group ViewsService
 * @group Search
 * @group SearchPlugin
 */
class SearchViewsRenderingIntegrationTest extends ViewRenderingIntegrationTestCase {

	public function up() {
		parent::up();
		
		$this->startPlugin();
	}

	public function getViewNames() {
		return [
			'forms/search',
			'resources/search/index',
			'search/entity',
			'search/list',
			'search/search.css',
			'search/search_box',
		];
	}

	public function getDefaultViewVars() {
		$entity = $this->createObject(['subtype' => 'commentable']);
		$comment = $this->createObject([
			'subtype' => 'comment',
			'container_guid' => $entity->guid,
		]);

		return [
			'results' => [
				'entities' => [
					$entity,
					$comment,
				],
				'count' => 2,
			],
			'entity' => $entity,
			'guid' => $entity->guid,
			'params' => [
				'type' => 'object',
				'subtype' => $entity->getSubtype(),
				'search_type' => 'custom',
				'query' => 'lorem ipsum',
			],
		];
	}
}
