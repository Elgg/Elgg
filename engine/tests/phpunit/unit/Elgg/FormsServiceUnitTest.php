<?php

namespace Elgg;

use Elgg\Controllers\EntityEditAction;
use Elgg\Exceptions\DomainException;
use Elgg\Exceptions\InvalidArgumentException;
use Elgg\Exceptions\LogicException;

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
		$this->assertStringContainsString('name="__elgg_sticky_form_name"', $actual);
		$this->assertStringContainsString('value="foo/bar"', $actual);
		$this->assertStringContainsString('name="__elgg_sticky_ignored_fields"', $actual);
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
	
	public function testGetFormViewTypeSubtype() {
		$forms = _elgg_services()->forms;
		
		$this->assertEquals('object/bar/edit', $this->invokeInaccessableMethod($forms, 'getFormView', 'object', 'bar'));
	}
	
	public function testGetFormViewSubtype() {
		$forms = _elgg_services()->forms;
		
		$this->assertEquals('foo/edit', $this->invokeInaccessableMethod($forms, 'getFormView', 'object', 'foo'));
	}
	
	public function testGetFormViewGeneric() {
		$forms = _elgg_services()->forms;
		
		$this->assertEquals('entity/edit', $this->invokeInaccessableMethod($forms, 'getFormView', 'object', 'bar2'));
	}
	
	public function testGetFormActionTypeSubtype() {
		elgg_register_action('object/bar/edit', EntityEditAction::class);
		$forms = _elgg_services()->forms;
		
		$this->assertStringContainsString('object/bar/edit', $this->invokeInaccessableMethod($forms, 'getFormAction', 'object', 'bar'));
	}
	
	public function testGetFormActionSubtype() {
		elgg_register_action('bar/edit', EntityEditAction::class);
		$forms = _elgg_services()->forms;
		
		$this->assertStringContainsString('bar/edit', $this->invokeInaccessableMethod($forms, 'getFormAction', 'object', 'bar'));
	}
	
	public function testGetFormActionUnknown() {
		$forms = _elgg_services()->forms;
		
		$this->expectException(DomainException::class);
		$this->invokeInaccessableMethod($forms, 'getFormAction', 'object', 'bar');
	}
	
	public function testRenderEntityWithoutEntity() {
		elgg_register_action('bar/edit', EntityEditAction::class);
		$forms = _elgg_services()->forms;
		
		$form = $forms->renderEntity('object', 'bar');
		$this->assertNotEmpty($form);
		$this->assertIsString($form);
	}
	
	public function testRenderEntityWithEntity() {
		elgg_register_action('bar/edit', EntityEditAction::class);
		$forms = _elgg_services()->forms;
		
		$object = $this->createObject([
			'subtype' => 'bar',
		]);
		
		$form = $forms->renderEntity('object', 'bar', $object);
		$this->assertNotEmpty($form);
		$this->assertIsString($form);
	}
	
	public function testRenderEntityWithWrongEntity() {
		elgg_register_action('bar/edit', EntityEditAction::class);
		$forms = _elgg_services()->forms;
		
		$object = $this->createObject([
			'subtype' => 'foo',
		]);
		
		$this->expectException(InvalidArgumentException::class);
		$forms->renderEntity('object', 'bar', $object);
	}
}
