<?php

namespace Elgg;

use Elgg\Exceptions\LogicException;

/**
 * @group FormsService
 * @group UnitTests
 */
class FormsServiceUnitTest extends \Elgg\UnitTestCase {

	public function up() {
		$views_dir = $this->normalizeTestFilePath('views');
		_elgg_services()->views->autoregisterViews('', "{$views_dir}/default", 'default');
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
		$this->expectException(LogicException::class);
		$this->expectExceptionMessage('Form footer can only be set and retrieved during form rendering, anywhere in elgg_view_form() call stack (e.g. form view, extending views, or view hooks)');
		
		_elgg_services()->forms->setFooter('footer');
	}

	public function testCanNotGetFooterOutsideFormView() {
		$this->expectException(LogicException::class);
		$this->expectExceptionMessage('Form footer can only be set and retrieved during form rendering, anywhere in elgg_view_form() call stack (e.g. form view, extending views, or view hooks)');
		
		_elgg_services()->forms->getFooter();
	}
}
