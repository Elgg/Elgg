<?php

namespace Elgg;

use Elgg\Project\Paths;

/**
 * @group UnitTests
 */
class ViewsServiceUnitTest extends \Elgg\UnitTestCase {

	/**
	 * @var PluginHooksService
	 */
	protected $hooks;

	/**
	 * @var ViewsService
	 */
	protected $views;
	protected $viewsDir;

	public function up() {
		$this->viewsDir = $this->normalizeTestFilePath("views");

		$this->hooks = new PluginHooksService(_elgg_services()->events);
		$logger = $this->createMock('\Elgg\Logger', array(), array(), '', false);

		$this->views = new ViewsService($this->hooks, $logger);
		$this->views->autoregisterViews('', "$this->viewsDir/default", 'default');

		// supports deprecation wrapper for $vars['user']
		_elgg_services()->setValue('session', \ElggSession::getMock());
	}

	public function down() {

	}

	public function testCanExtendViews() {
		$this->views->extendView('foo', 'bar');

		// Unextending valid extension succeeds.
		$this->assertTrue($this->views->unextendView('foo', 'bar'));

		// Unextending non-existent extension "fails."
		$this->assertFalse($this->views->unextendView('foo', 'bar'));
	}

	public function testCanExtendExtensionsViews() {
		$this->views->extendView('output/1', 'output/2');
		$this->views->extendView('output/2', 'output/3');
		$this->views->extendView('output/3', 'output/4');

		$this->assertEquals('1234', $this->views->renderView('output/1'));
	}

	public function testPreventViewExtensionsRecursion() {
		
		$this->views->extendView('output/1', 'output/2');
		$this->views->extendView('output/2', 'output/3');
		$this->views->extendView('output/3', 'output/4');
		$this->views->extendView('output/4', 'output/1'); // should be prevented
		
		$this->assertEquals('1234', $this->views->renderView('output/1'));
	}

	public function testExtensionsBranches() {
		
		$this->views->extendView('output/1', 'output/4', 100);
		$this->views->extendView('output/1', 'output/2');
		$this->views->extendView('output/2', 'output/3');
		$this->views->extendView('output/3', 'output/4');
		
		$this->assertEquals('41234', $this->views->renderView('output/1'));
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
		
		$relative_prefix = '';
		if (Paths::elgg() !== Paths::project()) {
			// Elgg installed as composer dependency
			$relative_prefix = 'vendor/elgg/elgg/';
		}
		
		$this->views->mergeViewsSpec([
			'default' => [
				'hello.js' => $this->normalizeTestFilePath('views/default/js/static.js'),
				'hello/world.js' => [$relative_prefix . 'engine/tests/test_files/views/default/js/interpreted.js.php'],
			],
		]);

		$expected = file_get_contents("$this->viewsDir/default/js/static.js");
		$this->assertEquals($expected, $this->views->renderView('hello.js'));

		$this->assertEquals("// PHPin", $this->views->renderView('hello/world.js', array(
					'in' => 'in',
		)));
	}

	public function testCanSetViewsDirs() {
		$this->views->setViewDir('static.css', $this->normalizeTestFilePath('views2/'));
		$this->assertEquals('body{}', $this->views->renderView('static.css'));
	}

	public function testViewtypesCanFallBack() {
		$this->views->registerViewtypeFallback('mobile');
		$this->assertTrue($this->views->doesViewtypeFallBack('mobile'));
	}

	public function testViewsCanExistBasedOnViewtypeFallback() {
		$this->views->registerViewtypeFallback('mobile');
		$this->assertTrue($this->views->viewExists('js/interpreted.js', 'mobile'));
		$this->assertEquals('// PHP', $this->views->renderView('js/interpreted.js', array(), 'mobile'));
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

	public function testCanReplaceViews() {
		$this->hooks->registerHandler('view_vars', 'js/interpreted.js', function ($h, $t, $v, $p) {
			return ['__view_output' => 123];
		});

		$this->hooks->registerHandler('view', 'js/interpreted.js', function ($h, $t, $v, $p) {
			$this->fail('view hook was called though __view_output was set.');
		});

		$this->assertSame("123", $this->views->renderView('js/interpreted.js'));
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
		$this->assertEquals($canonical, ViewsService::canonicalizeViewName($alias));
	}

	public function testCanListViews() {
		$views = $this->views->listViews('default');
		$this->assertTrue(in_array('interpreted.js', $views));
		$this->assertTrue(in_array('static.js', $views));

		$this->assertEmpty($this->views->listViews('fake_viewtype'));
	}

	public function testCanGetViewRenderingList() {
		$list = $this->views->getViewList('foo');
		$this->assertEquals([
			500 => 'foo',
				], $list);

		$this->views->extendView('foo', 'bar');
		$this->views->extendView('foo', 'bing', 499);

		$list = $this->views->getViewList('foo');
		$this->assertEquals([
			499 => 'bing',
			500 => 'foo',
			501 => 'bar',
				], $list);
	}
	
	public function testPreventExtensionOnSelf() {
		
		$this->views->extendView('output/1', 'output/1');

		$list = $this->views->getViewList('output/1');
		$this->assertEquals([
			500 => 'output/1',
		], $list);
	}
	
	public function testPreventUnExtendOfSelf() {
		
		$this->views->extendView('output/1', 'output/2'); // force existence of extension list
		$this->views->unextendView('output/1', 'output/1');

		$list = $this->views->getViewList('output/1');
		$this->assertEquals([
			500 => 'output/1',
			501 => 'output/2',
		], $list);
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
