<?php

class Elgg_ServiceProviderTest extends PHPUnit_Framework_TestCase {

	public function testPropertiesReturnCorrectClassNames() {
		$mgr = $this->getMock('Elgg_AutoloadManager', array(), array(), '', false);

		$sp = new Elgg_ServiceProvider($mgr);

		$svcClasses = array(
			'actions' => 'Elgg_ActionsService',
			
			// requires _elgg_get_simplecache_root() to be defined
			//'amdConfig' => 'Elgg_AmdConfig',
			
			'autoP' => 'ElggAutoP',
			'autoloadManager' => 'Elgg_AutoloadManager',
			'db' => 'Elgg_Database',
			'events' => 'Elgg_EventService',
			'hooks' => 'Elgg_PluginHookService',
			'logger' => 'ElggLogger',
			'metadataCache' => 'ElggVolatileMetadataCache',
			'request' => 'Elgg_Request',
			'router' => 'Elgg_Router',
			
			// Will this start session?
			//'session' => 'ElggSession'
			
			'views' => 'Elgg_ViewService',
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
