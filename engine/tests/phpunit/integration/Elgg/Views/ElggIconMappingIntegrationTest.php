<?php

namespace Elgg\Views;

class ElggIconMappingIntegrationTest extends ViewRenderingIntegrationTestCase {

	public static function getViewNames() {
		return [
			'output/icon',
		];
	}

	public function getDefaultViewVars() {
		return [
			'class' => 'elgg-icon-wizard-of-oz',
		];
	}

	public function testCanViewIcon() {
		$view = elgg_view_icon('mail', ['class' => 'custom-class']);

		$this->assertXmlStringEqualsXmlString($view, '<span class="elgg-icon elgg-icon-mail custom-class far fa-envelope"></span>');
	}

	public function testCanRenderIconMarkup() {
		$view = elgg_view_icon('abcdefg', ['data-icon' => 'foo']);

		$this->assertXmlStringEqualsXmlString($view, '<span class="elgg-icon elgg-icon-abcdefg fas fa-abcdefg" data-icon="foo"/>');
	}

	public function testCanAlterIconClasses() {

		$event = $this->registerTestingEvent('view_vars', 'output/icon', function(\Elgg\Event $event) {
			$vars = $event->getValue();
			$vars['class'] = ['override'];
			return $vars;
		});

		$view = elgg_view_icon('abcdefg', ['data-icon' => 'foo']);

		$this->assertXmlStringEqualsXmlString($view, '<span class="elgg-icon override" data-icon="foo"/>');

		$event->unregister();
	}
}
