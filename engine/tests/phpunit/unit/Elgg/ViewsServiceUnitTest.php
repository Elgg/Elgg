<?php

namespace Elgg;

use Elgg\Project\Paths;

class ViewsServiceUnitTest extends \Elgg\UnitTestCase {

	/**
	 * @var EventsService
	 */
	protected $events;

	/**
	 * @var ViewsService
	 */
	protected $views;
	protected $viewsDir;

	public function up() {
		$this->viewsDir = $this->normalizeTestFilePath("views");

		$this->events = new EventsService(_elgg_services()->handlers);
		$logger = $this->createMock('\Elgg\Logger', array(), array(), '', false);

		$this->views = new ViewsService($this->events, _elgg_services()->request, _elgg_services()->config, _elgg_services()->serverCache);
		$this->views->setLogger($logger);
		$this->views->autoregisterViews('', "{$this->viewsDir}/default", 'default');
		$this->views->autoregisterViews('', "{$this->viewsDir}/json", 'json');
		$this->views->setViewtype('');
	}
	
	public function down() {
		set_input('view', '');
		elgg_set_config('view', null);
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

	public function testRegistersPhpFilesAsViews() {
		$this->assertTrue($this->views->viewExists('js/interpreted.js'));
	}

	public function testRegistersStaticFilesAsViews() {
		$this->assertTrue($this->views->viewExists('js/static.js'));
	}

	public function testUsesPhpToRenderNonStaticViews() {
		$this->assertEquals("// PHPin", $this->views->renderView('js/interpreted.js', ['in' => 'in']));
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

		$this->assertEquals("// PHPin", $this->views->renderView('hello/world.js', ['in' => 'in']));
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

	public function testCanAlterViewInput() {
		$this->events->registerHandler('view_vars', 'js/interpreted.js', function (\Elgg\Event $event) {
			$vars = $event->getValue();
			$vars['in'] = 'out';
			return $vars;
		});

		$this->assertEquals("// PHPout", $this->views->renderView('js/interpreted.js'));
	}

	public function testCanAlterViewOutput() {
		$this->events->registerHandler('view', 'js/interpreted.js', function (\Elgg\Event $event) {
			return '// Hello';
		});

		$this->assertEquals("// Hello", $this->views->renderView('js/interpreted.js'));
	}

	public function testCanReplaceViews() {
		$this->events->registerHandler('view_vars', 'js/interpreted.js', function (\Elgg\Event $event) {
			return ['__view_output' => 123];
		});

		$this->events->registerHandler('view', 'js/interpreted.js', function (\Elgg\Event $event) {
			$this->fail('view event was called though __view_output was set.');
		});

		$this->assertSame("123", $this->views->renderView('js/interpreted.js'));
	}

	public function testCanListViews() {
		$views = $this->views->listViews('default');
		$this->assertTrue(in_array('js/interpreted.js', $views));
		$this->assertTrue(in_array('js/static.js', $views));

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
	
	public function testSetViewtype() {
		$this->assertTrue($this->views->setViewtype('test'));
		$this->assertEquals('test', $this->views->getViewtype());
	}
	
	public function testDefaultViewtype() {
		$this->assertEquals('default', $this->views->getViewtype());
	}
	
	public function testInputSetsInitialViewtype() {
		set_input('view', 'json');
		$this->assertEquals('json', $this->views->getViewtype());
	}
	
	public function testConfigSetsInitialViewtype() {
		elgg_set_config('view', 'json');
		
		$this->assertEquals('json', $this->views->getViewtype());
	}
	
	public function testSettingInputDoesNotChangeViewtype() {
		$this->assertEquals('default', $this->views->getViewtype());
		
		set_input('view', 'json');
		$this->assertEquals('default', $this->views->getViewtype());
	}
	
	public function testSettingConfigDoesNotChangeViewtype() {
		$this->assertEquals('default', $this->views->getViewtype());
		
		elgg_set_config('view', 'json');
		$this->assertEquals('default', $this->views->getViewtype());
	}
	
	public function testIsValidViewtype() {
		$this->assertTrue($this->views->isValidViewtype('valid'));
		$this->assertTrue($this->views->isValidViewtype('valid_viewtype'));
		$this->assertTrue($this->views->isValidViewtype('0'));
		$this->assertTrue($this->views->isValidViewtype(123)); // will be autocasted to string
		
		$this->assertFalse($this->views->isValidViewtype('a;b'));
		$this->assertFalse($this->views->isValidViewtype('invalid-viewtype'));
		$this->assertFalse($this->views->isValidViewtype(''));
	}
}
