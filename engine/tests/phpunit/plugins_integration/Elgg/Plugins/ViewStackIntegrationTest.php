<?php

namespace Elgg\Plugins;

use Elgg\PluginsIntegrationTestCase;
use Elgg\ViewsService;

class ViewStackIntegrationTest extends PluginsIntegrationTestCase {
	
	/**
	 * @var ViewsService
	 */
	protected $views;
	
	public function up() {
		parent::up();
		
		_elgg_services()->reset('views');
		
		$this->views = _elgg_services()->views;
	}
	
	/**
	 * Get all the views per plugin
	 *
	 * @return array
	 */
	public static function viewsProvider(): array {
		self::createApplication([
			'isolate' => true,
		]);
		
		$result = [];
		
		$plugins = elgg_get_plugins();
		foreach ($plugins as $plugin) {
			if (!is_dir($plugin->getPath() . 'views/')) {
				continue;
			}
			
			_elgg_services()->reset('views');
			
			// can not use ->startPlugin() as it needs to be static
			$plugin->register();
			$plugin->boot();
			$plugin->init();
			
			$data = _elgg_services()->views->getInspectorData();
			foreach ($data['locations'] as $viewtype => $views) {
				foreach ($views as $view => $path) {
					$result[] = [
						$plugin,
						$view,
						$viewtype,
						$path,
						elgg_extract($view, $data['simplecache'], false),
					];
				}
			}
		}
		
		return $result;
	}
	
	/**
	 * @dataProvider viewsProvider
	 */
	public function testViewStackRegistrations(\ElggPlugin $plugin, $view, $viewtype, $path, $is_simplecache_view) {
		$this->startPlugin($plugin->getID(), false);
		
		$this->assertFileExists($path);
		$this->assertFileIsReadable($path);
		
		// check file for syntax errors
		if (str_ends_with($path, '.php')) {
			$output = [];
			$return_var = null;
			exec("php -l {$path}", $output, $return_var);
			if ($return_var !== 0) {
				// syntax errors detected
				$this->fail("Syntax error detected in {$view}: " . implode(PHP_EOL, $output));
			}
		}
		
		$this->assertTrue($this->views->isValidViewtype($viewtype));
		
		$this->assertEquals($path, $this->views->findViewFile($view, $viewtype));
		$this->assertTrue($this->views->viewExists($view, $viewtype));
		
		$view_list = $this->views->getViewList($view);
		$this->assertNotEmpty($view_list);
		$this->assertEquals(count($view_list) > 1, !empty(elgg_get_view_extensions($view)));
		
		$this->assertEquals($is_simplecache_view, $this->views->isCacheableView($view));
	}
}
