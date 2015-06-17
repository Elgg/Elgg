<?php
namespace Elgg\Views;

use ElggSession as Session;
use Elgg\EventsService as Events;
use Elgg\Filesystem\Filesystem;
use Elgg\Http\Input;
use Elgg\PluginHooksService as Hooks;
use PHPUnit_Framework_TestCase as TestCase;

class ViewRegistryTest extends TestCase {
	
	private function createPlayground() {
		global $test_files;
		
		$config = new \stdClass;
		$events = new Events();
		$hooks = new Hooks();
		$input = new Input();
		$logger = $this->getMock('\Elgg\Logger', array(), array(), '', false);
		$viewtypes = new ViewtypeRegistry($config, $input);
		$viewsDir = $test_files->chroot("views");
		$viewPaths = new DirectoryPathRegistry($viewsDir, $viewtypes);
		$views = new ViewRegistry($config, $events, $hooks, $logger, $viewtypes, $viewPaths);

		// supports deprecation wrapper for $vars['user'] 
		_elgg_services()->setValue('session', Session::getMock());
		_elgg_services()->setValue('views', $views);
		_elgg_services()->setValue('input', $input);
		
		return (object)[
			'config' => $config,
			'input' => $input,
			'views' => $views,
			'viewsDir' => $viewsDir,
			'viewtypes' => $viewtypes,
		];
	}
	
	public function testRegistersPhpFilesAsViews() {
		$app = $this->createPlayground();
		
		$viewtype = $app->viewtypes->get('default');
		$view = $app->views->get('js/interpreted.js');
		
		$this->assertTrue($view->exists($viewtype));
	}
	
	public function testRegistersStaticFilesAsViews() {
		$app = $this->createPlayground();
		
		$default = $app->viewtypes->get('default');
		$this->assertTrue($app->views->get('js/static.js')->exists($default));
	}
	
	public function testCurrentViewtypeIsTheExplicitlySetViewtypeIfProvided() {
		$app = $this->createPlayground();
		
		$foo = $app->viewtypes->get('foo');
		$app->viewtypes->setCurrent($foo);
		
		$this->assertEquals($foo, $app->viewtypes->getCurrent());
	}

	public function testCurrentViewtypeFallsBackToTheViewInputParameterIfAnExplicitViewtypeWasNotSet() {
		$app = $this->createPlayground();

		$app->input->set('view', 'foo');

		$this->assertEquals($app->viewtypes->get('foo'), $app->viewtypes->getCurrent());
	}

	public function testCurrentViewtypeFallsBackToTheDbConfiguredDefaultIfAnInputParameterWasNotProvided() {
		$app = $this->createPlayground();
		
		$app->config->view = 'foo';
		
		$this->assertEquals($app->viewtypes->get('foo'), $app->viewtypes->getCurrent());
	}

	public function testCurrentViewtypeFallsBackToDefaultIfNoOtherValueWasProvided() {
		$this->assertEquals('default', elgg_get_viewtype());
	}

	public function testViewsAreRenderedWithTheCurrentViewtypeByDefault() {
		// render the "foo" view
		// check that output is "foo"
		// setViewtype('rss');
		// check that output of "foo" view changes accordingly
		$this->markTestIncomplete();
	}

	public function testViewsAreRenderedWithTheSpecifiedViewtypeAsLongAsItIsValid() {
		// set viewtype to "foo"
		// check that view is rendered
		// set viewtype to ";"
		// check that view is not rendered
		$this->markTestIncomplete();
	}

	public function testViewsAreNotRenderedRecursivelyWithASpecifiedViewtypeIfThatViewtypeIsNotTheCurrentOne() {
		// assume current viewtype is "default"
		// have a view "stream" that renders a "post" view as "rss" viewtype
		// The "post" view should render a "content" view in the default format
		// check that the output of the "content" view is default format, not rss
		$this->markTestIncomplete();
	}
}

