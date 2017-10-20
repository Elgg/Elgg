<?php

namespace Elgg\views\object\elements;

use Elgg\ViewRenderingTestCase;

/**
 * @group ViewRendering
 */
class summaryTest extends ViewRenderingTestCase {

	public function getViewName() {
		return 'object/elements/summary';
	}

	public function getDefaultViewVars() {
		return [
			'entity' => $this->createOne('object'),
		];
	}
}