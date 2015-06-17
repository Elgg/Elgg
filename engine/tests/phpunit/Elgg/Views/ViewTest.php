<?php

namespace Elgg\Views;

use Elgg\Filesystem\FlyDirectory;
use Elgg\Filesystem\Directory;
use Elgg\PluginHooksService as Hooks;
use Elgg\EventsService as Events;
use Elgg\Http\Input;
use PHPUnit_Framework_TestCase as TestCase;
use Elgg\Structs\EntryCollectionMap;

class ViewTest extends TestCase {
	
	private function createPlayground(Directory $viewsDir = null) {
		if (!$viewsDir) {
			global $test_files;
			$viewsDir = $test_files->chroot('/views/');
		}
		
		$config = new \stdClass;
		$input = new Input();
		$viewtypes = new ViewtypeRegistry($config, $input);

		$events = new Events();
		$hooks = new Hooks();
		$logger = $this->getMock('\Elgg\Logger', array(), array(), '', false);
		
		// Make this as similar to core's setup as possible
		// by adding all the decorators...
		$viewFiles = new DirectoryPathRegistry($viewsDir, $viewtypes);
		$viewFiles = FallbackPathRegistry::fromArray([$viewFiles], $viewtypes);
		$viewFiles = new ViewtypeFallbackPathRegistry($viewFiles);
		
		$views = new ViewRegistry($config, $events, $hooks, $logger, $viewtypes, $viewFiles);
		
		return (object)[
			'views' => $views,
			'viewsDir' => $viewsDir,
			'viewtypes' => $viewtypes,
		];
	}
	
	public function setUp() {
		$app = $this->createPlayground();
		
		$this->views = $app->views;
		$this->viewsDir = $app->viewsDir;
		$this->viewtypes = $app->viewtypes;
		
		_elgg_services()->setValue('views', $this->views);
	}
	
	private function createView(Viewtype $viewtype, $path, $content) {
		$file = FlyDirectory::createInMemoryFile($path, $content);

		$view = new View('name', EntryCollectionMap::fromArray([
			[$viewtype, $file],
		]));
		
		return $view;
	}
	
	public function testCanExtendViews() {
		elgg_extend_view('foo', 'bar');
		
		// Unextending valid extension succeeds.
		$this->assertTrue(elgg_unextend_view('foo', 'bar'));

		// Unextending non-existent extension "fails."
		$this->assertFalse(elgg_unextend_view('foo', 'bar'));
	}
	
	public function testViewsCanExistBasedOnViewtypeFallback() {
		$viewsDir = FlyDirectory::createInMemory([
			'/default/view.php' => '',
		]);

		$app = $this->createPlayground($viewsDir);
		$mobile = $app->viewtypes->get('mobile');
		$mobile->setFallback($app->viewtypes->get('default'));

		$this->assertTrue($app->views->get('view')->exists($mobile));
	}
	
	public function testSpecifiedVarsAreAvailableToViewsInTheVarsArray() {
		$view = $this->views->get('vars_example');
		
		$hello = $view->render(['title' => "Hello"], $this->viewtypes->get('default'));
		$world = $view->render(['title' => "World"], $this->viewtypes->get('default'));
		
		$this->assertTrue(strpos($hello, 'Hello') !== false);
		$this->assertTrue(strpos($world, 'World') !== false);
		$this->assertNotEquals($hello, $world);
	}
	
	public function testViewsCanExistBasedOnExtension() {
		$default = Viewtype::create('default');
		
		$view = new View('name', EntryCollectionMap::fromArray([]));
		
		$this->assertFalse($view->exists($default));
		
		$extension = $this->createView($default, 'extension.php', 'extension');
		
		$view->append($extension, 500);

		$this->assertTrue($extension->exists($default));
		$this->assertTrue($view->exists($default));
		$this->assertFalse($view->exists($default, false));
	}
	
	public function testCanRegisterViewsAsCacheable() {
		$view = $this->createView(Viewtype::create('default'), 'view.php', '');
		
		$this->assertFalse($view->isCacheable());
		
		$view->setCacheable(true);
		
		$this->assertTrue($view->isCacheable());
	}
	
	public function testStaticViewsAreAlwaysCacheable() {
		$view = $this->createView(Viewtype::create('default'), 'js/static.js', 'define({})');
		
		$this->assertTrue($view->isCacheable());
	}
	
	public function testUsesPhpToRenderNonStaticViews() {
		$app = $this->createPlayground();
		$this->assertEquals("// PHP", $app->views->render('js/interpreted.js'));
	}
	
	public function testDoesNotUsePhpToRenderStaticViews() {
		$app = $this->createPlayground();
		$expected = $app->viewsDir->getContents("/default/js/static.js");
		$this->assertEquals($expected, $app->views->render('js/static.js'));
	}
	
	public function testExtendingAViewWithPriorityLessThan500PrependsExtensionContent() {
		elgg_extend_view('foo', 'bar', 499);
		
		$this->assertEquals("bar\nfoo\n", elgg_view('foo'));
	}

	public function testExtendingAViewWithPriorityGreaterThanOrEqualTo500AppendsExtensionContent() {
		elgg_extend_view('foo', 'bar', 501);
		
		$this->assertEquals("foo\nbar\n", elgg_view('foo'));
	}

	public function testUnextendingAViewRemovesTheEffectOfAPreviousExtension() {
		elgg_extend_view('foo', 'bar', 499);
		elgg_unextend_view('foo', 'bar');
		
		$this->assertEquals("foo\n", elgg_view('foo'));
	}

	public function testUnextendingAViewHasNoEffectOnSubsequentExtensions() {
		elgg_unextend_view('foo', 'bar');
		elgg_extend_view('foo', 'bar', 501);
		
		$this->assertEquals("foo\nbar\n", elgg_view('foo'));
	}

	public function testViewsCanBeExtendedTwiceByTheSameView() {
		elgg_extend_view('foo', 'bar', 499);
		elgg_extend_view('foo', 'bar', 501);
		
		$this->assertEquals("bar\nfoo\nbar\n", elgg_view('foo'));
	}

	public function testViewsExtendedTwiceByTheSameViewNeedToBeUnextendedTwiceToRemoveThoseExtensions() {
		elgg_extend_view('foo', 'bar', 499);
		elgg_extend_view('foo', 'bar', 501);
		
		elgg_unextend_view('foo', 'bar');
		
		$this->assertNotEquals("foo\n", elgg_view('foo'));
		
		elgg_unextend_view('foo', 'bar');
		
		$this->assertEquals("foo\n", elgg_view('foo'));
	}
}