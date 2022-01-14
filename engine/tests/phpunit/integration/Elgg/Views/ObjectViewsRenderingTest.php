<?php

namespace Elgg\Views;

/**
 * @group ViewRendering
 * @group ViewsService
 */
class ObjectViewsRenderingTest extends ViewRenderingTestCase {

	public function getViewNames() {
		return [
			'object/default',
			'object/elements/access',
			'object/elements/byline',
			'object/elements/full',
			'object/elements/imprint',
			'object/elements/imprint_contents',
			'object/elements/summary',
			'object/elements/time',
			'object/elements/full/attachments',
			'object/elements/full/body',
			'object/elements/full/header',
			'object/elements/full/navigation',
			'object/elements/full/responses',
			'object/elements/summary/content',
			'object/elements/summary/metadata',
			'object/elements/summary/subtitle',
			'object/elements/summary/title',
			'icon/default',
		];
	}

	public function getDefaultViewVars() {
		$object = $this->createObject();
		return [
			'item' => $object,
			'entity' => $object,
			'guid' => $object->guid,
		];
	}
}
