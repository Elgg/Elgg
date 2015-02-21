<?php
namespace Elgg\Di;


class ServiceProviderTest extends \PHPUnit_Framework_TestCase {

	public function testPropertiesReturnCorrectClassNames() {
		$mgr = $this->getMock('\Elgg\AutoloadManager', array(), array(), '', false);

		$sp = new \Elgg\Di\ServiceProvider($mgr);
		$sp->setValue('session', \ElggSession::getMock());
		
		$svcClasses = array(
			'accessCache' => '\ElggStaticVariableCache',
			'accessCollections' => '\Elgg\Database\AccessCollections',
			'actions' => '\Elgg\ActionsService',
			'adminNotices' => '\Elgg\Database\AdminNotices',

			// requires _elgg_get_simplecache_root() to be defined
			//'amdConfig' => '\Elgg\Amd\Config',
			
			'annotations' => '\Elgg\Database\Annotations',
			'autoP' => '\ElggAutoP',
			'autoloadManager' => '\Elgg\AutoloadManager',
			'config' => '\Elgg\Config',
			'configTable' => '\Elgg\Database\ConfigTable',
			'context' => '\Elgg\Context',
			'datalist' => '\Elgg\Database\Datalist',
			'db' => '\Elgg\Database',
			'entityTable' => '\Elgg\Database\EntityTable',
			'events' => '\Elgg\EventsService',
			'externalFiles' => '\Elgg\Assets\ExternalFiles',
			'hooks' => '\Elgg\PluginHooksService',
			'input' => '\Elgg\Http\Input',
			'logger' => '\Elgg\Logger',
			'metadataCache' => '\Elgg\Cache\MetadataCache',
			'metadataTable' => '\Elgg\Database\MetadataTable',
			'metastringsTable' => '\Elgg\Database\MetastringsTable',
			'passwords' => '\Elgg\PasswordService',
			'plugins' => '\Elgg\Database\Plugins',
			'request' => '\Elgg\Http\Request',
			'relationshipsTable' => '\Elgg\Database\RelationshipsTable',
			'router' => '\Elgg\Router',
			'session' => '\ElggSession',
			'simpleCache' => '\Elgg\Cache\SimpleCache',
			'siteSecret' => '\Elgg\Database\SiteSecret',
			'stickyForms' => '\Elgg\Forms\StickyForms',
			'subtypeTable' => '\Elgg\Database\SubtypeTable',
			'systemCache' => '\Elgg\Cache\SystemCache',
			'translator' => '\Elgg\I18n\Translator',
			'usersTable' => '\Elgg\Database\UsersTable',
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

