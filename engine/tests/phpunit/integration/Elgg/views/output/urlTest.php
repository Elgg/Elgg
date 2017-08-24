<?php

namespace Elgg\views\output;

use Elgg\ViewRenderingTestCase;

/**
 * @group ViewRendering
 */
class urlTest extends ViewRenderingTestCase {

	public function getViewName() {
		return 'output/url';
	}

	public function getDefaultViewVars() {
		return [
			'href' => 'http://example.com',
			'text' => 'Click here',
		];
	}

	public function testCanRenderAnchorWithoutText() {
		$href = 'http://example.com';

		$output = elgg_format_element('a', [
			'class' => 'elgg-anchor',
			'href' => $href,
			'rel' => 'nofollow',
		], elgg_format_element('span', [
			'class' => 'elgg-anchor-label',
		], $href));

		$this->assertViewOutput($output, [
			'href' => $href,
		]);
	}
}