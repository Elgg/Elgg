<?php
namespace Elgg\Di;

use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session\Session as SymfonySession;
use Zend\Mail\Transport\TransportInterface as Mailer;

/**
 * Provides common Elgg services.
 *
 * We extend the container because it allows us to document properties in the PhpDoc, which assists
 * IDEs to auto-complete properties and understand the types returned. Extension allows us to keep
 * the container generic.
 *
 * @property-read \Elgg\Database\AccessCollections         $accessCollections
 * @property-read \ElggStaticVariableCache                 $accessCache
 * @property-read \Elgg\ActionsService                     $actions
 * @property-read \Elgg\Database\AdminNotices              $adminNotices
 * @property-read \Elgg\AjaxApi\Service                    $ajaxApi
 * @property-read \Elgg\Amd\Config                         $amdConfig
 * @property-read \Elgg\Database\Annotations               $annotations
 * @property-read \ElggAutoP                               $autoP
 * @property-read \Elgg\ClassLoader                        $classLoader
 * @property-read \Elgg\AutoloadManager                    $autoloadManager
 * @property-read \ElggCrypto                              $crypto
 * @property-read \Elgg\Config                             $config
 * @property-read \Elgg\Database\ConfigTable               $configTable
 * @property-read \Elgg\Context                            $context
 * @property-read \Elgg\Database\Datalist                  $datalist
 * @property-read \Elgg\Database                           $db
 * @property-read \Elgg\DeprecationService                 $deprecation
 * @property-read \Elgg\EntityPreloader                    $entityPreloader
 * @property-read \Elgg\Database\EntityTable               $entityTable
 * @property-read \Elgg\EventsService                      $events
 * @property-read \Elgg\Assets\ExternalFiles               $externalFiles
 * @property-read \Elgg\PluginHooksService                 $hooks
 * @property-read \Elgg\Http\Input                         $input
 * @property-read \Elgg\Logger                             $logger
 * @property-read Mailer                                   $mailer
 * @property-read \Elgg\Cache\MetadataCache                $metadataCache
 * @property-read \Elgg\Database\MetadataTable             $metadataTable
 * @property-read \Elgg\Database\MetastringsTable          $metastringsTable
 * @property-read \Elgg\Notifications\NotificationsService $notifications
 * @property-read \Elgg\PasswordService                    $passwords
 * @property-read \Elgg\PersistentLoginService             $persistentLogin
 * @property-read \Elgg\Database\Plugins                   $plugins
 * @property-read \Elgg\Database\QueryCounter              $queryCounter
 * @property-read \Elgg\Http\Request                       $request
 * @property-read \Elgg\Database\RelationshipsTable        $relationshipsTable
 * @property-read \Elgg\Router                             $router
 * @property-read \ElggSession                             $session
 * @property-read \Elgg\Cache\SimpleCache                  $simpleCache
 * @property-read \Elgg\Database\SiteSecret                $siteSecret
 * @property-read \Elgg\Forms\StickyForms                  $stickyForms
 * @property-read \Elgg\Database\SubtypeTable              $subtypeTable
 * @property-read \Elgg\Cache\SystemCache                  $systemCache
 * @property-read \Elgg\SystemMessagesService              $systemMessages
 * @property-read \Elgg\I18n\Translator                    $translator
 * @property-read \Elgg\Database\UsersTable                $usersTable
 * @property-read \Elgg\ViewsService                       $views
 * @property-read \Elgg\WidgetsService                     $widgets
 * 
 * @package Elgg.Core
 * @access private
 */
class ServiceProvider extends \Elgg\Di\DiContainer {

	/**
	 * Constructor
	 *
	 * @param \Elgg\Config $config Elgg Config service
	 */
	public function __construct(\Elgg\Config $config) {

		$this->setFactory('classLoader', function(ServiceProvider $c) {
			$loader = new \Elgg\ClassLoader(new \Elgg\ClassMap());
			$loader->register();
			return $loader;
		});

		$this->setFactory('autoloadManager', function(ServiceProvider $c) {
			$manager = new \Elgg\AutoloadManager($c->classLoader);
			if (!$c->config->get('AutoloaderManager_skip_storage')) {
				$manager->setStorage($c->systemCache->getFileCache());
				$manager->loadCache();
			}
			return $manager;
		});

		$this->setFactory('accessCache', function(ServiceProvider $c) {
			return new \ElggStaticVariableCache('access');
		});

		$this->setFactory('accessCollections', function(ServiceProvider $c) {
			return new \Elgg\Database\AccessCollections($c->config->get('site_guid'));
		});

		$this->setClassName('actions', \Elgg\ActionsService::class);

		$this->setFactory('ajaxApi', function(ServiceProvider $c) {
			return new \Elgg\AjaxApi\Service($c->hooks, $c->systemMessages, $c->input);
		});

		$this->setClassName('adminNotices', \Elgg\Database\AdminNotices::class);

		$this->setFactory('amdConfig', function(ServiceProvider $c) {
			$obj = new \Elgg\Amd\Config($c->hooks);
			$obj->setBaseUrl($c->simpleCache->getRoot() . "js/");
			return $obj;
		});

		$this->setClassName('annotations', \Elgg\Database\Annotations::class);

		$this->setClassName('autoP', \ElggAutoP::class);

		$this->setValue('config', $config);

		$this->setClassName('configTable', \Elgg\Database\ConfigTable::class);

		$this->setClassName('context', \Elgg\Context::class);

		$this->setClassName('crypto', \ElggCrypto::class);

		$this->setFactory('datalist', function(ServiceProvider $c) {
			// TODO(ewinslow): Add back memcached support
			$db = $c->db;
			$dbprefix = $db->getTablePrefix();
			$pool = new \Elgg\Cache\MemoryPool();
			return new \Elgg\Database\Datalist($pool, $db, $c->logger, "{$dbprefix}datalists");
		});

		$this->setFactory('db', function(ServiceProvider $c) {
			$db_config = new \Elgg\Database\Config($c->config->getStorageObject());

			// we inject the logger in _elgg_engine_boot()
			return new \Elgg\Database($db_config);
		});

		$this->setFactory('deprecation', function(ServiceProvider $c) {
			return new \Elgg\DeprecationService($c->session, $c->logger);
		});

		$this->setClassName('entityPreloader', \Elgg\EntityPreloader::class);

		$this->setClassName('entityTable', \Elgg\Database\EntityTable::class);

		$this->setFactory('events', function(ServiceProvider $c) {
			return $this->resolveLoggerDependencies('events');
		});

		$this->setFactory('externalFiles', function(ServiceProvider $c) {
			return new \Elgg\Assets\ExternalFiles($c->config->getStorageObject());
		});

		$this->setFactory('hooks', function(ServiceProvider $c) {
			return $this->resolveLoggerDependencies('hooks');
		});

		$this->setClassName('input', \Elgg\Http\Input::class);

		$this->setFactory('logger', function(ServiceProvider $c) {
			return $this->resolveLoggerDependencies('logger');
		});
		
		// TODO(evan): Support configurable transports...
		$this->setClassName('mailer', 'Zend\Mail\Transport\Sendmail');

		$this->setFactory('metadataCache', function (ServiceProvider $c) {
			return new \Elgg\Cache\MetadataCache($c->session);
		});

		$this->setFactory('metadataTable', function(ServiceProvider $c) {
			// TODO(ewinslow): Use Elgg\Cache\Pool instead of MetadataCache
			return new \Elgg\Database\MetadataTable(
				$c->metadataCache, $c->db, $c->entityTable, $c->events, $c->metastringsTable, $c->session);
		});

		$this->setFactory('metastringsTable', function(ServiceProvider $c) {
			// TODO(ewinslow): Use memcache-based Pool if available...
			$pool = new \Elgg\Cache\MemoryPool();
			return new \Elgg\Database\MetastringsTable($pool, $c->db);
		});

		$this->setFactory('notifications', function(ServiceProvider $c) {
			// @todo move queue in service provider
			$queue_name = \Elgg\Notifications\NotificationsService::QUEUE_NAME;
			$queue = new \Elgg\Queue\DatabaseQueue($queue_name, $c->db);
			$sub = new \Elgg\Notifications\SubscriptionsService($c->db);
			return new \Elgg\Notifications\NotificationsService($sub, $queue, $c->hooks, $c->session);
		});

		$this->setFactory('persistentLogin', function(ServiceProvider $c) {
			$global_cookies_config = $c->config->get('cookies');
			$cookie_config = $global_cookies_config['remember_me'];
			$cookie_name = $cookie_config['name'];
			$cookie_token = $c->request->cookies->get($cookie_name, '');
			return new \Elgg\PersistentLoginService(
				$c->db, $c->session, $c->crypto, $cookie_config, $cookie_token);
		});

		$this->setFactory('passwords', function (ServiceProvider $c) {
			if (!function_exists('password_hash')) {
				$root = $c->config->getRootPath();
				require "{$root}vendor/ircmaxell/password-compat/lib/password.php";
			}
			return new \Elgg\PasswordService();
		});

		$this->setFactory('plugins', function(ServiceProvider $c) {
			return new \Elgg\Database\Plugins($c->events, new \Elgg\Cache\MemoryPool());
		});

		$this->setFactory('queryCounter', function(ServiceProvider $c) {
			return new \Elgg\Database\QueryCounter($c->db);
		}, false);

		$this->setClassName('relationshipsTable', \Elgg\Database\RelationshipsTable::class);

		$this->setFactory('request', [\Elgg\Http\Request::class, 'createFromGlobals']);

		$this->setFactory('router', function(ServiceProvider $c) {
			// TODO(evan): Init routes from plugins or cache
			return new \Elgg\Router($c->hooks);
		});

		$this->setFactory('session', function(ServiceProvider $c) {
			$params = $c->config->get('cookies')['session'];
			$options = [
				// session.cache_limiter is unfortunately set to "" by the NativeSessionStorage
				// constructor, so we must capture and inject it directly.
				'cache_limiter' => session_cache_limiter(),

				'name' => $params['name'],
				'cookie_path' => $params['path'],
				'cookie_domain' => $params['domain'],
				'cookie_secure' => $params['secure'],
				'cookie_httponly' => $params['httponly'],
				'cookie_lifetime' => $params['lifetime'],
			];

			$handler = new \Elgg\Http\DatabaseSessionHandler($c->db);
			$storage = new NativeSessionStorage($options, $handler);
			$session = new SymfonySession($storage);
			return new \ElggSession($session);
		});

		$this->setClassName('simpleCache', \Elgg\Cache\SimpleCache::class);

		$this->setClassName('siteSecret', \Elgg\Database\SiteSecret::class);

		$this->setClassName('stickyForms', \Elgg\Forms\StickyForms::class);

		$this->setClassName('subtypeTable', \Elgg\Database\SubtypeTable::class);

		$this->setClassName('systemCache', \Elgg\Cache\SystemCache::class);

		$this->setFactory('systemMessages', function(ServiceProvider $c) {
			return new \Elgg\SystemMessagesService($c->session);
		});

		$this->setClassName('translator', \Elgg\I18n\Translator::class);

		$this->setClassName('usersTable', \Elgg\Database\UsersTable::class);

		$this->setFactory('views', function(ServiceProvider $c) {
			return new \Elgg\ViewsService($c->hooks, $c->logger);
		});

		$this->setClassName('widgets', \Elgg\WidgetsService::class);

	}

	/**
	 * Returns the first requested service of the logger, events, and hooks. It sets the
	 * hooks and events up in the right order to prevent circular dependency.
	 *
	 * @param string $service_needed The service requested first
	 * @return mixed
	 */
	protected function resolveLoggerDependencies($service_needed) {
		$svcs['hooks'] = new \Elgg\PluginHooksService();
		$svcs['logger'] = new \Elgg\Logger($svcs['hooks'], $this->config, $this->context);
		$svcs['hooks']->setLogger($svcs['logger']);
		$svcs['events'] = new \Elgg\EventsService();
		$svcs['events']->setLogger($svcs['logger']);

		foreach ($svcs as $key => $service) {
			$this->setValue($key, $service);
		}
		return $svcs[$service_needed];
	}
}
