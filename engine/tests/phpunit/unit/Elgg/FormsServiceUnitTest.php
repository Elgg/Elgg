<?php

namespace Elgg;

use Elgg\Exceptions\LogicException;

/**
 * @group FormsService
 * @group UnitTests
 */
class FormsServiceUnitTest extends \Elgg\UnitTestCase {

	/**
	 * @var TestableEvent
	 */
	protected $event;
	
	public function up() {
		$views_dir = $this->normalizeTestFilePath('views');
		_elgg_services()->views->autoregisterViews('', "{$views_dir}/default", 'default');
	}
	
	public function down() {
		if ($this->event instanceof TestableEvent) {
			$this->event->unregister();
		}
	}

	public function testCanRenderForm() {
		$this->event = $this->registerTestingEvent('form:prepare:fields', 'foo/bar', function(\Elgg\Event $incoming_event) {
			$vars = $incoming_event->getValue();
			
			$vars['foo'] = 'bar';
			
			return $vars;
		});
		
		$expected = elgg_view('forms/foo/bar.html');
		$actual = elgg_view_form('foo/bar', [
			'class' => 'foo-bar',
		], [
			'baz2' => 'bar2',
		]);

		// check form contents
		$this->assertNotEmpty($expected);
		$this->assertNotEmpty($actual);
		$normalize = function ($html) {
			return preg_replace('~>\s+~', ">", $html);
		};
		$this->assertEquals($normalize($expected), $normalize($actual));
		
		// check for triggered event
		$this->event->assertNumberOfCalls(1);
		$this->event->assertValueBefore(['baz2' => 'bar2']);
		$this->event->assertValueAfter(['baz2' => 'bar2', 'foo' => 'bar']);
	}
	
	public function testCanRenderFormWithStickySupport() {
		$actual = elgg_view_form('foo/bar', [
			'class' => 'foo-bar',
			'sticky_enabled' => true,
			'sticky_ignored_fields' => [
				'password',
				'some-field',
			],
		], [
			'baz2' => 'bar2',
		]);

		// check form contents
		$this->assertNotEmpty($actual);
		$this->assertStringContainsString('name="_elgg_sticky_form_name"', $actual);
		$this->assertStringContainsString('value="foo/bar"', $actual);
		$this->assertStringContainsString('name="_elgg_sticky_ignored_fields"', $actual);
		$this->assertStringContainsString('value="password,some-field"', $actual);
	}

	public function testCanNotSetFooterOutsideFormView() {
		$this->expectException(LogicException::class);
		$this->expectExceptionMessage('Form footer can only be set and retrieved during form rendering, anywhere in elgg_view_form() call stack (e.g. form view, extending views, or view events)');
		
		_elgg_services()->forms->setFooter('footer');
	}

	public function testCanNotGetFooterOutsideFormView() {
		$this->expectException(LogicException::class);
		$this->expectExceptionMessage('Form footer can only be set and retrieved during form rendering, anywhere in elgg_view_form() call stack (e.g. form view, extending views, or view events)');
		
		_elgg_services()->forms->getFooter();
	}
}
