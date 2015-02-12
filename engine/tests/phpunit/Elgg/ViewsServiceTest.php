<?php
namespace Elgg;


class ViewsServiceTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var \Elgg\PluginHooksService
	 */
	protected $hooks;

	/**
	 * @var \Elgg\ViewsService
	 */
	protected $views;

	public function setUp() {
		$this->viewsDir = dirname(dirname(__FILE__)) . "/test_files/views";
		
		$this->hooks = new \Elgg\PluginHooksService();
		$this->logger = $this->getMock('\Elgg\Logger', array(), array(), '', false);
		
		$this->views = new \Elgg\ViewsService($this->hooks, $this->logger);
		$this->views->autoregisterViews('', "$this->viewsDir/default", "$this->viewsDir/", 'default');

		// supports deprecation wrapper for $vars['user'] 
		_elgg_services()->setValue('session', \ElggSession::getMock());
	}
	
	public function testCanExtendViews() {
		$this->views->extendView('foo', 'bar');
		
		// Unextending valid extension succeeds.
		$this->assertTrue($this->views->unextendView('foo', 'bar'));

		// Unextending non-existent extension "fails."
		$this->assertFalse($this->views->unextendView('foo', 'bar'));
	}

	public function testViewCanOnlyExistIfString() {
		$this->assertFalse($this->views->viewExists(1));
		$this->assertFalse($this->views->viewExists(new \stdClass));
	}

	public function testRegistersPhpFilesAsViews() {
		$this->assertTrue($this->views->viewExists('js/interpreted.js'));
	}
	
	public function testRegistersStaticFilesAsViews() {
		$this->assertTrue($this->views->viewExists('js/static.js'));
	}
	
	public function testUsesPhpToRenderNonStaticViews() {
		$this->assertEquals("// PHPin", $this->views->renderView('js/interpreted.js', array(
			'in' => 'in',
		)));
	}
	
	public function testDoesNotUsePhpToRenderStaticViews() {
		$expected = file_get_contents("$this->viewsDir/default/js/static.js");
		$this->assertEquals($expected, $this->views->renderView('js/static.js'));
	}
		
	public function testStoresDirectoryForViewLocation() {
		$this->assertEquals("$this->viewsDir/", $this->views->getViewLocation('js/interpreted.js', 'default'));
	}
	
	public function testViewtypesCanFallBack() {
		$this->views->registerViewtypeFallback('mobile');
		$this->assertTrue($this->views->doesViewtypeFallBack('mobile'));
	}
	
	public function testViewsCanExistBasedOnViewtypeFallback() {
		$this->views->registerViewtypeFallback('mobile');
		$this->assertTrue($this->views->viewExists('js/interpreted.js', 'mobile'));
		$this->assertEquals('// PHP', $this->views->renderView('js/interpreted.js', array(), false, 'mobile'));
	}
	
	public function testCanRegisterViewsAsCacheable() {
		$this->assertFalse($this->views->isCacheableView('js/interpreted.js'));
		
		$this->views->registerCacheableView('js/interpreted.js');
		
		$this->assertTrue($this->views->isCacheableView('js/interpreted.js'));
	}
	
	public function testStaticViewsAreAlwaysCacheable() {
		$this->assertTrue($this->views->isCacheableView('js/static.js'));
	}

	public function testCanAlterViewInput() {
		$this->hooks->registerHandler('view_vars', 'js/interpreted.js', function ($h, $t, $v, $p) {
			$v['in'] = 'out';
			return $v;
		});

		$this->assertEquals("// PHPout", $this->views->renderView('js/interpreted.js'));
	}

	public function testCanAlterViewOutput() {
		$this->hooks->registerHandler('view', 'js/interpreted.js', function ($h, $t, $v, $p) {
			return '// Hello';
		});

		$this->assertEquals("// Hello", $this->views->renderView('js/interpreted.js'));
	}
}

