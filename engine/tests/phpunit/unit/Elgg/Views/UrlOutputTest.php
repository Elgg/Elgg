<?php

namespace Elgg\Views;

/**
 * @group ViewRendering
 * @group ViewsService
 */
class UrlOutputTest extends ViewRenderingTestCase {

	public function getViewNames() {
		return ['output/url'];
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

		$this->assertViewOutput($output, 'output/url', [
			'href' => $href,
		]);
	}
}
