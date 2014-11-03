<?php
namespace Elgg\Di;


class ServiceProviderTest extends \PHPUnit_Framework_TestCase {

	public function testPropertiesReturnCorrectClassNames() {
		$mgr = $this->getMock('\Elgg\AutoloadManager', array(), array(), '', false);

		$sp = new \Elgg\Di\ServiceProvider($mgr);

		$svcClasses = array(
			'actions' => '\Elgg\ActionsService',
			'adminNotices' => '\Elgg\Database\AdminNotices',

			// requires _elgg_get_simplecache_root() to be defined
			//'amdConfig' => '\Elgg\Amd\Config',
			
			'annotations' => '\Elgg\Database\Annotations',
			'autoP' => '\ElggAutoP',
			'autoloadManager' => '\Elgg\AutoloadManager',
			'config' => '\Elgg\Config',
			'configTable' => '\Elgg\Database\ConfigTable',
			'datalist' => '\Elgg\Database\Datalist',
			'db' => '\Elgg\Database',
			'entityTable' => '\Elgg\Database\EntityTable',
			'events' => '\Elgg\EventsService',
			'externalFiles' => '\Elgg\Assets\ExternalFiles',
			'hooks' => '\Elgg\PluginHooksService',
			'input' => '\Elgg\Http\Input',
			'logger' => '\Elgg\Logger',
			'metadataCache' => '\ElggVolatileMetadataCache',
			'request' => '\Elgg\Http\Request',
			'router' => '\Elgg\Router',
			
			// Will this start session?
			//'session' => '\ElggSession'
			'stickyForms' => '\Elgg\Forms\StickyForms',
			
			'simpleCache' => '\Elgg\Cache\SimpleCache',
			'subtypeTable' => '\Elgg\Database\SubtypeTable',
			'systemCache' => '\Elgg\Cache\SystemCache',
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

