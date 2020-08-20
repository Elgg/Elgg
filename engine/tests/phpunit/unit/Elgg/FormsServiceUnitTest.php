<?php

namespace Elgg;

use Psr\Log\LogLevel;

/**
 * @group FormsService
 * @group UnitTests
 */
class FormsServiceUnitTest extends \Elgg\UnitTestCase {

	public function up() {
		$views_dir = $this->normalizeTestFilePath('views');
		_elgg_services()->views->autoregisterViews('', "$views_dir/default", 'default');
	}

	public function down() {

	}

	public function testCanRenderForm() {

		$expected = elgg_view('forms/foo/bar.html');
		$actual = elgg_view_form('foo/bar', [
			'class' => 'foo-bar',
		], [
			'baz2' => 'bar2',
		]);

		$this->assertNotEmpty($expected);
		$this->assertNotEmpty($actual);
		$normalize = function ($html) {
			return preg_replace('~>\s+~', ">", $html);
		};
		$this->assertEquals($normalize($expected), $normalize($actual));
	}

	public function testCanNotSetFooterOutsideFormView() {
		_elgg_services()->logger->disable();

		$this->assertFalse(_elgg_services()->forms->setFooter('footer'));
		$logs = _elgg_services()->logger->enable();
		$expected = [
			[
				'message' => 'Form footer can only be set and retrieved during form rendering, anywhere in elgg_view_form() call stack (e.g. form view, extending views, or view hooks)',
				'level' => LogLevel::ERROR,
			],
		];
		$this->assertEquals($expected, $logs);
	}

	public function testCanNotGetFooterOutsideFormView() {
		_elgg_services()->logger->disable();
		$this->assertFalse(_elgg_services()->forms->getFooter());

		$expected = [
			[
				'message' => 'Form footer can only be set and retrieved during form rendering, anywhere in elgg_view_form() call stack (e.g. form view, extending views, or view hooks)',
				'level' => LogLevel::ERROR,
			]
		];
		$logs = _elgg_services()->logger->enable();
		$this->assertEquals($expected, $logs);
	}
}
