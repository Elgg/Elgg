<?php

namespace Elgg\Views;

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
		$comment = $this->createObject([
			'subtype' => 'comment',
		]);
		return [
			'entity' => $comment,
		];
	}
}
