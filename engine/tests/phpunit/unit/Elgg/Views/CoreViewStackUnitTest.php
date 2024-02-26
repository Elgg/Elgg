<?php

namespace Elgg\Views;

use Elgg\Project\Paths;
use Elgg\UnitTestCase;

class CoreViewStackUnitTest extends UnitTestCase {

	public function up() {
		_elgg_services()->views->registerViewsFromPath(Paths::elgg());
	}
	
	public static function viewsProvider() {

		self::createApplication();

		$provides = [];

		_elgg_services()->views->registerViewsFromPath(Paths::elgg());
		$data = _elgg_services()->views->getInspectorData();

		foreach ($data['locations'] as $viewtype => $views) {
			foreach ($views as $view => $path) {
				$provides[] = [
					$view,
					$viewtype,
					$path,
					elgg_extract($view, $data['simplecache'], false),
				];
			}
		}

		return $provides;
	}

	/**
	 * @dataProvider viewsProvider
	 */
	public function testViewStackRegistrations($view, $viewtype, $path, $is_simplecache_view) {

		$this->assertTrue(is_file($path));
		$this->assertTrue(is_readable($path));

		// check file for syntax errors
		if (substr($path, -4) === '.php') {
			$output = [];
			$return_var = null;
			exec("php -l {$path}", $output, $return_var);
			if ($return_var !== 0) {
				// syntax errors detected
				$this->fail("Syntax error detected in {$view}: " . implode(PHP_EOL, $output));
			}
		}

		$this->assertTrue(_elgg_services()->views->isValidViewtype($viewtype));

		$this->assertEquals($path, _elgg_services()->views->findViewFile($view, $viewtype));
		$this->assertTrue(_elgg_services()->views->viewExists($view, $viewtype));

		$view_list = _elgg_services()->views->getViewList($view);
		$this->assertNotEmpty($view_list);
		$this->assertEquals(count($view_list) > 1, !empty(elgg_get_view_extensions($view)));

		$this->assertEquals($is_simplecache_view, _elgg_services()->simpleCache->isCacheableView($view));
	}
}
