<?php
namespace Elgg\Di;


class ServiceProviderTest extends \PHPUnit_Framework_TestCase {

	public function testPropertiesReturnCorrectClassNames() {
		$mgr = $this->getMock('\Elgg\AutoloadManager', array(), array(), '', false);

		$sp = new \Elgg\Di\ServiceProvider($mgr);

		$svcClasses = array(
			'actions' => '\Elgg\ActionsService',
			
			// requires _elgg_get_simplecache_root() to be defined
			//'amdConfig' => '\Elgg\Amd\Config',
			
			'autoP' => '\ElggAutoP',
			'autoloadManager' => '\Elgg\AutoloadManager',
			'db' => '\Elgg\Database',
			'events' => '\Elgg\EventsService',
			'hooks' => '\Elgg\PluginHooksService',
			'logger' => '\Elgg\Logger',
			'metadataCache' => '\ElggVolatileMetadataCache',
			'request' => '\Elgg\Http\Request',
			'router' => '\Elgg\Router',
			
			// Will this start session?
			//'session' => '\ElggSession'
			
			'views' => '\Elgg\ViewsService',
			'widgets' => '\Elgg\WidgetsService',
		);

		foreach ($svcClasses as $key => $class) {
			$obj1 = $sp->{$key};
			$obj2 = $sp->{$key};
			$this->assertInstanceOf($class, $obj1);
			$this->assertSame($obj1, $obj2);
		}
	}
}

