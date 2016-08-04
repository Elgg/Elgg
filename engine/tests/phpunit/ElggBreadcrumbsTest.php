<?php

class ElggBreadcrumbsTest extends \PHPUnit_Framework_TestCase {

//	public function setUp() {
//		// TODO run each test in better isolation
//		static $have_run;
//		if (!$have_run) {
//			_elgg_nav_init();
//			$have_run = true;
//		}
//	}

	public function testCrumbsCanBePushed() {
		elgg_push_breadcrumb('title 1');

		elgg_push_breadcrumb('title 2', 'path2');

		$this->assertEquals(array(
			array('title' => 'title 1', 'link' => null),
			array('title' => 'title 2', 'link' => 'path2')
		), elgg_get_breadcrumbs());
	}

	public function testCrumbsCanBePopped() {
		elgg_push_breadcrumb('title 1');

		elgg_push_breadcrumb('title 2', 'path2');

		$this->assertEquals(array('title' => 'title 2', 'link' => 'path2'), elgg_pop_breadcrumb());

		$this->assertEquals(array(
			array('title' => 'title 1', 'link' => null),
		), elgg_get_breadcrumbs());

		$this->assertEquals(array('title' => 'title 1', 'link' => null), elgg_pop_breadcrumb());

		$this->assertEquals(array(), elgg_get_breadcrumbs());
	}

	public function testCanAlterCrumbsViaHook() {
		elgg_push_breadcrumb(str_repeat('abcd ', 100));

		elgg_unregister_plugin_hook_handler('prepare', 'breadcrumbs', 'elgg_prepare_breadcrumbs');

		$this->assertEquals(array(
			array(
				'title' => str_repeat('abcd ', 100),
				'link' => null,
			),
		), elgg_get_breadcrumbs());
	}

	public function testCrumbsAreExcerpted() {
		$this->markTestIncomplete('Needs DB');

		elgg_push_breadcrumb(str_repeat('abcd ', 100));

		$this->assertEquals(array(
			array(
				'title' => elgg_get_excerpt(str_repeat('abcd ', 100), 100),
				'link' => null,
			),
		), elgg_get_breadcrumbs());
	}

	public function testCrumbTitlesAreEscaped() {
		$this->markTestIncomplete('Needs DB');

		// TODO make this unnecessary
		elgg_set_view_location('output/url', __DIR__ . '/../../../views/');
		elgg_set_view_location('navigation/breadcrumbs', __DIR__ . '/../../../views/');

		elgg_push_breadcrumb('Me < &amp; you');
		$escaped = 'Me &lt; &amp; you';
		$html = elgg_view('navigation/breadcrumbs');
		$this->assertNotFalse(strpos($html, $escaped));

		// links uses different view
		elgg_pop_breadcrumb();
		elgg_push_breadcrumb('Me < &amp; you', 'link');

		$html = elgg_view('navigation/breadcrumbs');
		$this->assertNotFalse(strpos($html, $escaped));
	}

	public function testCrumbLinksAreNormalized() {
		$this->markTestIncomplete('Needs DB');

		// TODO make this unnecessary
		elgg_set_view_location('output/url', __DIR__ . '/../../../views/');
		elgg_set_view_location('navigation/breadcrumbs', __DIR__ . '/../../../views/');

		elgg_push_breadcrumb('test', 'link');
		$html = elgg_view('navigation/breadcrumbs');
		$this->assertNotFalse(strpos($html, '"http://localhost/link"'));
	}
}
