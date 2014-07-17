<?php
namespace Elgg\Views;

use ElggSession as Session;
use Elgg\EventsService as Events;
use Elgg\Filesystem\Filesystem;
use Elgg\Http\Input;
use Elgg\PluginHooksService as Hooks;
use PHPUnit_Framework_TestCase as TestCase;

class RegistryTest extends TestCase {
	
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
		$this->dir = $test_files->chroot("/views/default");
		$this->views->registerViews('', $this->dir, 'default');

		// supports deprecation wrapper for $vars['user'] 
		_elgg_services()->setValue('session', Session::getMock());
		_elgg_services()->setValue('views', $this->views);
	}
	
	public function testSetViewtypeOverridesAnyCurrentViewtypeSetting() {
		$this->assertTrue(elgg_set_viewtype('test'));
		$this->assertEquals('test', elgg_get_viewtype());
	}

	public function testCurrentViewtypeCanBeDeterminedByARequestVariable() {
		$this->assertEquals('default', elgg_get_viewtype());

		set_input('view', 'foo');
		$this->assertEquals('foo', elgg_get_viewtype());

		set_input('view', 'a;b');
		$this->assertEquals('default', elgg_get_viewtype());
	}
	
	public function testGetOrCreateViewtypeAlwaysReturnsTheSameInstanceForTheSameString() {
		$default1 = $this->views->getOrCreateViewtype('default');
		$default2 = $this->views->getOrCreateViewtype('default');
		
		$this->assertEquals($default1, $default2);
	}

	public function testRegistersPhpFilesAsViews() {
		$viewtype = $this->views->getOrCreateViewtype('default');
		$view = $this->views->getView('js/interpreted.js');
		
		$this->assertTrue($view->exists($viewtype));
	}
	
	public function testRegistersStaticFilesAsViews() {
		$default = $this->views->getOrCreateViewtype('default');
		$this->assertTrue($this->views->getView('js/static.js')->exists($default));
	}
	
	public function testCurrentViewtypeIsTheExplicitlySetViewtypeIfProvided() {
		// setViewtype('foo')
		// check that current viewtype is 'foo'
		$this->markTestIncomplete();
	}

	public function testCurrentViewtypeFallsBackToTheViewInputParameterIfAnExplicitViewtypeWasNotSet() {
		// set view query param to "foo"
		// check that current viewtype is "foo"
		$this->markTestIncomplete();
	}

	public function testCurrentViewtypeFallsBackToTheDbConfiguredDefaultIfAnInputParameterWasNotProvided() {
		// set db default view to "foo"
		// check that current viewtype is "foo"
		$this->markTestIncomplete();
	}

	public function testCurrentViewtypeFallsBackToDefaultIfNoOtherValueWasProvided() {
		// check that current viewtype is "foo"
		$this->markTestIncomplete();
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

	public function testGlobalConfigBeingAvailableViaTheVarsArrayIsDeprecated() {
		// render a view that uses the global config
		// check that a deprecation notice is triggered
		$this->markTestIncomplete();
	}

	public function testCurrentUserBeingAvailableViaTheVarsArrayIsDeprecated() {
		// render a view that uses the current user from $vars
		// check that a deprecation notice is triggered
		$this->markTestIncomplete();
	}

	public function testSiteUrlBeingAvailableViaTheVarsArrayIsDeprecated() {
		// render a view that uses the current site url from $vars
		// check that a deprecation notice is triggered
		$this->markTestIncomplete();
	}

	public function testElggGetViewLocationReturnsPathToViewtypeFolder() {
		$location = elgg_get_view_location('foo');
		
		$this->assertEquals("$this->dir/", $location);
	}
}

