<?php

/**
 * @group UnitTests
 * @group Breadcrumbs
 */
class ElggBreadcrumbsUnitTest extends \Elgg\IntegratedUnitTestCase {

	public function up() {
		elgg_set_config('breadcrumbs', []);
	}

	public function down() {

	}

	public function testCrumbsCanBePushed() {
		elgg_push_breadcrumb('title 1');

		elgg_push_breadcrumb('title 2', 'path2');

		$this->assertEquals([
			['text' => 'title 1', 'href' => false, 'name' => 0],
			['text' => 'title 2', 'href' => 'path2', 'name' => 1],
		], elgg_get_breadcrumbs());
	}

	public function testCrumbsCanBePopped() {
		// disable the default behavior that auto-removes the final non-link in get
		_elgg_services()->hooks->backup();

		elgg_push_breadcrumb('title 1');

		elgg_push_breadcrumb('title 2', 'path2');

		$this->assertEquals(['text' => 'title 2', 'href' => 'path2'], elgg_pop_breadcrumb());

		$this->assertEquals([
			['text' => 'title 1', 'href' => false, 'name' => 0],
		], elgg_get_breadcrumbs());

		$this->assertEquals(['text' => 'title 1', 'href' => false], elgg_pop_breadcrumb());

		$this->assertEquals([], elgg_get_breadcrumbs());

		_elgg_services()->hooks->restore();
	}

	public function testCanAlterCrumbsViaHook() {
		elgg_push_breadcrumb(str_repeat('abcd ', 100));

		elgg_unregister_plugin_hook_handler('prepare', 'breadcrumbs', \Elgg\Page\PrepareBreadcrumbsHandler::class);

		$this->assertEquals([
			[
				'text' => str_repeat('abcd ', 100),
				'href' => false,
				'name' => 0,
			],
		], elgg_get_breadcrumbs());
	}

	public function testCrumbsAreExcerpted() {
		elgg_push_breadcrumb(str_repeat('abcd ', 100));

		$this->assertEquals([
			[
				'text' => str_repeat('abcd ', 100),
				'href' => false,
				'name' => 0,
			]
		], elgg_get_breadcrumbs());
	}

	public function testTrailingNonLinkIsRemoved() {
		elgg_register_plugin_hook_handler('prepare', 'breadcrumbs', \Elgg\Page\PrepareBreadcrumbsHandler::class);

		elgg_push_breadcrumb('Foo', 'foo');
		elgg_push_breadcrumb('Bar');

		$html = elgg_view('navigation/breadcrumbs');
		$this->assertFalse(strpos($html, 'Bar'));
	}

}
