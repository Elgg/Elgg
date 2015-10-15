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

	protected $viewsDir;

	public function setUp() {
		$this->viewsDir = dirname(dirname(__FILE__)) . "/test_files/views";
		
		$this->hooks = new \Elgg\PluginHooksService();
		$this->logger = $this->getMock('\Elgg\Logger', array(), array(), '', false);
		
		$this->views = new \Elgg\ViewsService($this->hooks, $this->logger);
		$this->views->autoregisterViews('', "$this->viewsDir/default", 'default');

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

	public function testCanSetViewPathsViaSpec() {
		$this->views->mergeViewsSpec([
			'default' => [
				'hello.js' => __DIR__ . '/../test_files/views/default/js/static.js',
				'hello/world.js' => 'engine/tests/phpunit/test_files/views/default/js/interpreted.js.php',
			],
		]);

		$expected = file_get_contents("$this->viewsDir/default/js/static.js");
		$this->assertEquals($expected, $this->views->renderView('hello.js'));

		$this->assertEquals("// PHPin", $this->views->renderView('hello/world.js', array(
			'in' => 'in',
		)));
	}

	public function testCanSetViewsDirs() {
		$this->views->setViewDir('static.css', __DIR__ . '/../test_files/views2/');
		$this->assertEquals('body{}', $this->views->renderView('static.css'));
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
	
	public function testThrowsOnCircularAliases() {
		$this->markTestIncomplete();
	}
	
	public function testEmitsDeprecationWarningWhenOldViewNameIsReferenced() {
		$this->markTestIncomplete();
		// elgg_view
		// elgg_extend_view
		// elgg_unextend_view
		// views/*
		// engine/views.php
		// elgg_get_simplecache_url
		// elgg_set_view_location
		// elgg_get_view_location
	}
	
	/**
	 * @dataProvider getExampleNormalizedViews
	 */
	public function testDefaultNormalizeBehavior($canonical, $alias) {
		$this->assertEquals($canonical, $this->views->canonicalizeViewName($alias));
	}

	public function testCanListViews() {
		$views = $this->views->listViews('default');
		$this->assertTrue(in_array('interpreted.js', $views));
		$this->assertTrue(in_array('static.js', $views));

		$this->assertEmpty($this->views->listViews('fake_viewtype'));
	}
	
	public function getExampleNormalizedViews() {
		return [
			// [canonical, alias]
			
			// js namespace should be removed and .js added to all JS views
			['view.js', 'js/view'],
			['view.js', 'js/view.js'],
			['view.css', 'js/view.css'],
			['view.png', 'js/view.png'],
			
			// ".form" in this case is not an extension, just a delimiter. Ignore.
			['jquery.form.js', 'js/jquery.form'],
			
			// css namespace should be removed and .css added to all CSS views
			['view.css', 'css/view'],
			['view.css', 'css/view.css'],
			['view.png', 'css/view.png'],
			['view.jpg', 'css/view.jpg'],
		];
	}
}

