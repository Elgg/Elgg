<?php

namespace Elgg\Views;

use Elgg\ViewRenderingTestCase;

/**
 * @group ViewRendering
 * @group ViewsService
 * @group Comments
 */
class CommentViewsRenderingTest extends ViewRenderingTestCase {

	public function getViewNames() {
		return [
			'object/comment',
			'forms/comment/save',
		];
	}

	public function getDefaultViewVars() {
		$comment = $this->createOne('object', [
			'subtype' => 'comment',
		]);
		return [
			'entity' => $comment,
		];
	}
}