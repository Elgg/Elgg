<?php

namespace Elgg\Views;

/**
 * @group Glyphs
 * @group Views
 */
class ElggIconMappingTest extends ViewRenderingTestCase {

	public function up() {
		parent::up();
	}

	public function down() {
		parent::down();
	}

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

	public function testCanMapIcon() {
		$classes = _elgg_map_icon_glyph_class(['elgg-icon-mail']);

		$this->assertContains('far', $classes);
		$this->assertContains('fa-envelope', $classes);
		$this->assertContains('elgg-icon-mail', $classes);
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

		$hook = $this->registerTestingHook('classes', 'icon', function() {
			return ['override'];
		});

		$view = elgg_view_icon('abcdefg', ['data-icon' => 'foo']);

		$this->assertXmlStringEqualsXmlString($view, '<span class="override" data-icon="foo"/>');

		$hook->unregister();
	}
}