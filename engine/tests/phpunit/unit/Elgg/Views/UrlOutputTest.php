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
	
	/**
	 * @dataProvider anchorTextProvider
	 */
	public function testCanRenderAnchorWithText($text_input, $text_output) {
		$href = 'http://example.com';

		$output = elgg_format_element('a', [
			'class' => 'elgg-anchor',
			'href' => $href,
			'rel' => 'nofollow',
		], $text_output);

		$this->assertViewOutput($output, 'output/url', [
			'text' => $text_input,
			'href' => $href,
		]);
	}
	
	public function anchorTextProvider() {
		
		return [
			[
				'text_input' => 'sample text',
				'text_output' => elgg_format_element('span', ['class' => 'elgg-anchor-label',], 'sample text'),
			],
			[
				'text_input' => '0',
				'text_output' => elgg_format_element('span', ['class' => 'elgg-anchor-label',], '0'),
			],
			[
				'text_input' => 0,
				'text_output' => elgg_format_element('span', ['class' => 'elgg-anchor-label',], 0),
			],
			[
				'text_input' => '',
				'text_output' => '',
			],
			[
				'text_input' => false,
				'text_output' => '',
			],
		];
	}
}
