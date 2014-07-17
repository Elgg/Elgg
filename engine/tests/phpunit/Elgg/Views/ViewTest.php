<?php

namespace Elgg\Views;

use ElggSession as Session;
use Elgg\PluginHooksService as Hooks;
use Elgg\EventsService as Events;
use Elgg\Http\Input;
use Elgg\Filesystem\GaufretteDirectory;
use PHPUnit_Framework_TestCase as TestCase;

class ViewTest extends TestCase {
	
	public function createViewsRegistry() {
		$hooks = new Hooks();
		$events = new Events();
		$logger = $this->getMock('\Elgg\Logger', array(), array(), '', false);
		$input = new Input();
		$config = new \stdClass;
		
		return new Registry($config, $events, $hooks, $input, $logger);
	}
	
	public function setUp() {
		$this->views = $this->createViewsRegistry();

		global $test_files;
		$this->dir = $test_files->chroot('/views/default');
		$this->views->registerViews('', $this->dir, 'default');
		
		// supports deprecation wrapper for $vars['user'] 
		_elgg_services()->setValue('session', Session::getMock());
		_elgg_services()->setValue('views', $this->views);
	}
	
	public function createView(Viewtype $viewtype, $content = '', $path = 'view.php') {
		$fs = GaufretteDirectory::createInMemory();
		$file = $fs->getFile($path);
		$file->putContents($content);

		$view = new View();
		$view->setLocation($viewtype, $file);
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
		$default = Viewtype::create('default');
		$mobile = Viewtype::create('mobile');
		$mobile->setFallback($default);
		
		$view = $this->createView($default);

		$this->assertTrue($view->exists($mobile));
	}
	
	public function testSpecifiedVarsAreAvailableToViewsInTheVarsArray() {
		$view = $this->views->getView('vars_example');
		
		$hello = $view->render(['title' => "Hello"], $this->views->getOrCreateViewtype('default'));
		$world = $view->render(['title' => "World"], $this->views->getOrCreateViewtype('default'));
		
		$this->assertTrue(strpos($hello, 'Hello') !== false);
		$this->assertTrue(strpos($world, 'World') !== false);
		$this->assertNotEquals($hello, $world);
	}
	

	
	public function testViewsCanExistBasedOnExtension() {
		$default = Viewtype::create('default');
		
		$view = new View();
		
		$this->assertFalse($view->exists($default));
		
		$extension = $this->createView($default, 'extension', 'extension.php');
		
		$view->append($extension, 500);

		$this->assertTrue($extension->exists($default));
		$this->assertTrue($view->exists($default));
		$this->assertFalse($view->exists($default, false));
	}
	
	public function testCanRegisterViewsAsCacheable() {
		$default = Viewtype::create('default');
		$view = $this->createView($default, '', 'view.php');
		
		$this->assertFalse($view->isCacheable());
		
		$view->setCacheable(true);
		
		$this->assertTrue($view->isCacheable());
	}
	
	public function testStaticViewsAreAlwaysCacheable() {
		$default = Viewtype::create('default');
		
	    $view = $this->createView($default, 'define({})', 'js/static.js');
	    
		$this->assertTrue($view->isCacheable());
	}
	
		public function testUsesPhpToRenderNonStaticViews() {
		$this->assertEquals("// PHP", $this->views->renderView('js/interpreted.js'));
	}
	
	public function testDoesNotUsePhpToRenderStaticViews() {
		$expected = $this->dir->getFile("/js/static.js")->getContents();
		$this->assertEquals($expected, elgg_view('js/static.js'));
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