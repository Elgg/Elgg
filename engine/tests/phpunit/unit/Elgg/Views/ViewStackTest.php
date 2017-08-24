<?php

namespace Elgg\Views;

/**
 * @group ViewsService
 */
class ViewStackTest extends \Elgg\UnitTestCase {

	public function up() {
		elgg_views_boot();
	}

	public function down() {

	}

	public function viewsProvider() {

		self::createApplication();

		elgg_views_boot();

		$provides = [];

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

		$this->assertTrue(is_readable($path));

		// check file for syntax errors
		if (substr($path, -4) === '.php') {
			exec("php -l $path", $output, $return_var);
			if ($return_var === 0) {
				// no syntax errors
			}else{
				// syntax errors detected
				throw new \Exception(implode(PHP_EOL, $output));
			}
		}

		$this->assertTrue(_elgg_services()->views->isValidViewtype($viewtype));

		$this->assertEquals($path, _elgg_services()->views->findViewFile($view, $viewtype));
		$this->assertTrue(_elgg_services()->views->viewExists($view, $viewtype));

		$view_list = _elgg_services()->views->getViewList($view);
		$this->assertNotEmpty($view_list);
		$this->assertEquals(count($view_list) > 1, _elgg_services()->views->viewIsExtended($view));

		$this->assertEquals($is_simplecache_view, _elgg_services()->views->isCacheableView($view));
	}
}