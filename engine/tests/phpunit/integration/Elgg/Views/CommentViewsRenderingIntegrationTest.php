<?php

namespace Elgg\Views;

/**
 * @group ViewRendering
 * @group ViewsService
 * @group Comments
 */
class CommentViewsRenderingIntegrationTest extends ViewRenderingIntegrationTestCase {

	public function getViewNames() {
		return [
			'object/comment',
			'forms/comment/save',
		];
	}

	public function getDefaultViewVars() {
		// make sure we have a commentable container
		$container = $this->createObject();
		elgg_entity_enable_capability($container->getType(), $container->getSubtype(), 'commentable');
		
		$comment = $this->createObject([
			'subtype' => 'comment',
			'container_guid' => $container->guid,
		]);
		return [
			'entity' => $comment,
		];
	}
}
