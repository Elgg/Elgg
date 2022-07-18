<?php

namespace Elgg\Views;

class ElggIconMappingTest extends ViewRenderingTestCase {

	public function getViewNames() {
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
		$view = elgg_view_icon('mail', 'custom-class');

		$this->assertXmlStringEqualsXmlString($view, '<span class="elgg-icon elgg-icon-mail custom-class far fa-envelope"></span>');
	}

	public function testCanRenderIconMarkup() {
		$view = elgg_view_icon('abcdefg', ['data-icon' => 'foo']);

		$this->assertXmlStringEqualsXmlString($view, '<span class="elgg-icon elgg-icon-abcdefg fas fa-abcdefg" data-icon="foo"/>');
	}

	public function testCanAlterIconClasses() {

		$hook = $this->registerTestingHook('view_vars', 'output/icon', function(\Elgg\Hook $hook) {
			$vars = $hook->getValue();
			$vars['class'] = ['override'];
			return $vars;
		});

		$view = elgg_view_icon('abcdefg', ['data-icon' => 'foo']);

		$this->assertXmlStringEqualsXmlString($view, '<span class="elgg-icon override" data-icon="foo"/>');

		$hook->unregister();
	}
}
