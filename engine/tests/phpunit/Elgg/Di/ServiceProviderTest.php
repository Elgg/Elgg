<?php

class Elgg_Di_ServiceProviderTest extends PHPUnit_Framework_TestCase {

	public function testPropertiesReturnCorrectClassNames() {
		$mgr = $this->getMock('Elgg_AutoloadManager', array(), array(), '', false);

		$sp = new Elgg_Di_ServiceProvider($mgr);

		$svcClasses = array(
			'actions' => 'Elgg_ActionsService',

			// requires _elgg_get_simplecache_root() to be defined
			//'amdConfig' => 'Elgg_Amd_Config',

			'autoP' => 'ElggAutoP',
			'autoloadManager' => 'Elgg_AutoloadManager',
			'db' => 'Elgg_Database',
			'events' => 'Elgg_EventsService',
			'hooks' => 'Elgg_PluginHooksService',
			'logger' => 'Elgg_Logger',
			'metadataCache' => 'ElggVolatileMetadataCache',
			'request' => 'Elgg_Http_Request',
			'router' => 'Elgg_Router',

			// Will this start session?
			//'session' => 'ElggSession'

			'views' => 'Elgg_ViewsService',
			'widgets' => 'Elgg_WidgetsService',
		);

		foreach ($svcClasses as $key => $class) {
			$obj1 = $sp->{$key};
			$obj2 = $sp->{$key};
			$this->assertInstanceOf($class, $obj1);
			$this->assertSame($obj1, $obj2);
		}
	}
}
