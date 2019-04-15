<?php

namespace Elgg\Di;

use ConfigurationException;
use DI\ContainerBuilder;
use Elgg\Application;
use Elgg\Assets\CssCompiler;
use Elgg\Cache\CompositeCache;
use Elgg\Cache\DataCache;
use Elgg\Cache\SessionCache;
use Elgg\Cli\Progress;
use Elgg\Config;
use Elgg\Cron;
use Elgg\Database\DbConfig;
use Elgg\Database\SiteSecret;
use Elgg\Groups\Tools;
use Elgg\Invoker;
use Elgg\Logger;
use Elgg\Project\Paths;
use Elgg\Router\RouteRegistrationService;
use Elgg\Security\Csrf;
use Zend\Mail\Transport\TransportInterface as Mailer;
use Elgg\I18n\LocaleService;

/**
 * Provides common Elgg services.
 *
 * We extend the container because it allows us to document properties in the PhpDoc, which assists
 * IDEs to auto-complete properties and understand the types returned. Extension allows us to keep
 * the container generic.
 *
 * @property-read \Elgg\Database\AccessCollections                $accessCollections
 * @property-read \ElggCache                                      $accessCache
 * @property-read \Elgg\ActionsService                            $actions
 * @property-read \Elgg\Users\Accounts                            $accounts
 * @property-read \Elgg\Database\AdminNotices                     $adminNotices
 * @property-read \Elgg\Ajax\Service                              $ajax
 * @property-read \Elgg\Amd\Config                                $amdConfig
 * @property-read \Elgg\Database\AnnotationsTable                 $annotationsTable
 * @property-read \ElggAutoP                                      $autoP
 * @property-read \Elgg\AutoloadManager                           $autoloadManager
 * @property-read \Elgg\BootService                               $boot
 * @property-read \Elgg\Application\CacheHandler                  $cacheHandler
 * @property-read \Elgg\Assets\CssCompiler                        $cssCompiler
 * @property-read \Elgg\Security\Csrf                             $csrf
 * @property-read \Elgg\ClassLoader                               $classLoader
 * @property-read \Elgg\Cli                                       $cli
 * @property-read \Symfony\Component\Console\Input\InputInterface $cli_input
 * @property-read \Symfony\Component\Console\Output\OutputInterface $cli_output
 * @property-read \Elgg\Cli\Progress                              $cli_progress
 * @property-read \Elgg\Cron                                      $cron
 * @property-read \ElggCrypto                                     $crypto
 * @property-read \Elgg\Config                                    $config
 * @property-read \Elgg\Database\ConfigTable                      $configTable
 * @property-read \Elgg\Cache\DataCache                           $dataCache
 * @property-read \Elgg\Database                                  $db
 * @property-read \Elgg\Database\DbConfig                         $dbConfig
 * @property-read \Elgg\DeprecationService                        $deprecation
 * @property-read \Elgg\DI\PublicContainer                        $dic
 * @property-read \Di\ContainerBuilder                            $dic_builder
 * @property-read \Elgg\Di\DefinitionCache                        $dic_cache
 * @property-read \Elgg\Di\DefinitionLoader                       $dic_loader
 * @property-read \Elgg\EmailService                              $emails
 * @property-read \Elgg\Cache\EntityCache                         $entityCache
 * @property-read \Elgg\EntityPreloader                           $entityPreloader
 * @property-read \Elgg\Database\EntityTable                      $entityTable
 * @property-read \Elgg\EventsService                             $events
 * @property-read \Elgg\Assets\ExternalFiles                      $externalFiles
 * @property-read \ElggCache                                      $fileCache
 * @property-read \ElggDiskFilestore                              $filestore
 * @property-read \Elgg\FormsService                              $forms
 * @property-read \Elgg\Gatekeeper                                $gatekeeper
 * @property-read \Elgg\Groups\Tools                              $group_tools
 * @property-read \Elgg\HandlersService                           $handlers
 * @property-read \Elgg\Security\HmacFactory                      $hmac
 * @property-read \Elgg\Views\HtmlFormatter                       $html_formatter
 * @property-read \Elgg\PluginHooksService                        $hooks
 * @property-read \Elgg\EntityIconService                         $iconService
 * @property-read \Elgg\ImageService                              $imageService
 * @property-read \Elgg\Invoker                                   $invoker
 * @property-read \Elgg\I18n\LocaleService                        $localeService
 * @property-read \Elgg\Logger                                    $logger
 * @property-read Mailer                                          $mailer
 * @property-read \Elgg\Menu\Service                              $menus
 * @property-read \Elgg\Cache\MetadataCache                       $metadataCache
 * @property-read \Elgg\Database\MetadataTable                    $metadataTable
 * @property-read \Elgg\Database\Mutex                            $mutex
 * @property-read \Elgg\Notifications\NotificationsService        $notifications
 * @property-read \Elgg\PasswordService                           $passwords
 * @property-read \Elgg\PersistentLoginService                    $persistentLogin
 * @property-read \Elgg\Database\Plugins                          $plugins
 * @property-read \Elgg\Cache\PrivateSettingsCache                $privateSettingsCache
 * @property-read \Elgg\Database\PrivateSettingsTable             $privateSettings
 * @property-read \Elgg\Application\Database                      $publicDb
 * @property-read \Elgg\Database\QueryCounter                     $queryCounter
 * @property-read \Elgg\RedirectService                           $redirects
 * @property-read \Elgg\Http\Request                              $request
 * @property-read \Elgg\Router\RequestContext                     $requestContext
 * @property-read \Elgg\Http\ResponseFactory                      $responseFactory
 * @property-read \Elgg\Database\RelationshipsTable               $relationshipsTable
 * @property-read \Elgg\Router\RouteCollection                    $routeCollection
 * @property-read \Elgg\Router\RouteRegistrationService           $routes
 * @property-read \Elgg\Router                                    $router
 * @property-read \Elgg\Database\Seeder                           $seeder
 * @property-read \Elgg\Application\ServeFileHandler              $serveFileHandler
 * @property-read \ElggSession                                    $session
 * @property-read \Elgg\Cache\SessionCache                        $sessionCache
 * @property-read \Elgg\Search\SearchService                      $search
 * @property-read \Elgg\Cache\SimpleCache                         $simpleCache
 * @property-read \Elgg\Database\SiteSecret                       $siteSecret
 * @property-read \Elgg\Forms\StickyForms                         $stickyForms
 * @property-read \Elgg\Cache\SystemCache                         $systemCache
 * @property-read \Elgg\SystemMessagesService                     $systemMessages
 * @property-read \Elgg\Views\TableColumn\ColumnFactory           $table_columns
 * @property-read \ElggTempDiskFilestore                          $temp_filestore
 * @property-read \Elgg\Timer                                     $timer
 * @property-read \Elgg\I18n\Translator                           $translator
 * @property-read \Elgg\Security\UrlSigner                        $urlSigner
 * @property-read \Elgg\UpgradeService                            $upgrades
 * @property-read \Elgg\Upgrade\Locator                           $upgradeLocator
 * @property-read \Elgg\Router\UrlGenerator                       $urlGenerator
 * @property-read \Elgg\Router\UrlMatcher                         $urlMatcher
 * @property-read \Elgg\UploadService                             $uploads
 * @property-read \Elgg\UserCapabilities                          $userCapabilities
 * @property-read \Elgg\Database\UsersTable                       $usersTable
 * @property-read \Elgg\ViewsService                              $views
 * @property-read \Elgg\Cache\ViewCacher                          $viewCacher
 * @property-read \Elgg\WidgetsService                            $widgets
 *
 * @access private
 */
class ServiceProvider extends DiContainer {

	/**
	 * Constructor
	 *
	 * @param Config $config Elgg Config service
	 * @throws ConfigurationException
	 */
	public function __construct(Config $config) {

		$this->setFactory('autoloadManager', function(ServiceProvider $c) {
			$manager = new \Elgg\AutoloadManager($c->classLoader);

			if (!$c->config->AutoloaderManager_skip_storage) {
				$manager->setCache($c->fileCache);
				$manager->loadCache();
			}

			return $manager;
		});

		$this->setFactory('accessCache', function(ServiceProvider $c) {
			return $this->sessionCache->access;
		});

		$this->setFactory('accessCollections', function(ServiceProvider $c) {
			return new \Elgg\Database\AccessCollections(
				$c->config,
				$c->db,
				$c->entityTable,
				$c->userCapabilities,
				$c->accessCache,
				$c->hooks,
				$c->session,
				$c->translator
			);
		});

		$this->setFactory('actions', function(ServiceProvider $c) {
			return new \Elgg\ActionsService($c->routes, $c->handlers);
		});

		$this->setFactory('accounts', function(ServiceProvider $c) {
			return new \Elgg\Users\Accounts($c->config, $c->translator, $c->passwords, $c->usersTable, $c->hooks);
		});

		$this->setClassName('adminNotices', \Elgg\Database\AdminNotices::class);

		$this->setFactory('ajax', function(ServiceProvider $c) {
			return new \Elgg\Ajax\Service($c->hooks, $c->systemMessages, $c->request, $c->amdConfig);
		});

		$this->setFactory('amdConfig', function(ServiceProvider $c) {
			$obj = new \Elgg\Amd\Config($c->hooks);
			$obj->setBaseUrl($c->simpleCache->getRoot());
			return $obj;
		});

		$this->setFactory('annotationsTable', function(ServiceProvider $c) {
			return new \Elgg\Database\AnnotationsTable($c->db, $c->events);
		});

		$this->setClassName('autoP', \ElggAutoP::class);

		$this->setFactory('boot', function (ServiceProvider $c) {
			$flags = ELGG_CACHE_PERSISTENT | ELGG_CACHE_FILESYSTEM | ELGG_CACHE_RUNTIME;
			$cache = new CompositeCache("elgg_boot", $c->config, $flags);
			$boot = new \Elgg\BootService($cache);
			if ($c->config->enable_profiling) {
				$boot->setTimer($c->timer);
			}
			return $boot;
		});

		$this->setFactory('cacheHandler', function(ServiceProvider $c) {
			$simplecache_enabled = $c->config->simplecache_enabled;
			if ($simplecache_enabled === null) {
				$simplecache_enabled = $c->configTable->get('simplecache_enabled');
			}
			return new \Elgg\Application\CacheHandler($c->config, $c->request, $simplecache_enabled);
		});

		$this->setFactory('cssCompiler', function(ServiceProvider $c) {
			return new CssCompiler($c->config, $c->hooks);
		});

		$this->setFactory('csrf', function(ServiceProvider $c) {
			return new Csrf(
				$c->config,
				$c->session,
				$c->crypto,
				$c->hmac
			);
		});

		$this->setFactory('classLoader', function(ServiceProvider $c) {
			$loader = new \Elgg\ClassLoader(new \Elgg\ClassMap());
			$loader->register();
			return $loader;
		});

		$this->setFactory('cli', function(ServiceProvider $c) {
			$version = elgg_get_version(true);

			$console = new \Elgg\Cli\Application('Elgg', $version);
			$console->setup($c->cli_input, $c->cli_output);

			return new \Elgg\Cli(
				$console,
				$c->hooks,
				$c->cli_input,
				$c->cli_output
			);
		});

		$this->setFactory('cli_input', function(ServiceProvider $c) {
			return Application::getStdIn();
		});

		$this->setFactory('cli_output', function(ServiceProvider $c) {
			return Application::getStdOut();
		});

		$this->setFactory('cli_progress', function(ServiceProvider $c) {
			return new Progress($c->cli_output);
		});

		$this->setFactory('config', function (ServiceProvider $sp) use ($config) {
			$this->initConfig($config, $sp);
			return $config;
		});

		$this->setFactory('configTable', function(ServiceProvider $c) {
			return new \Elgg\Database\ConfigTable($c->db, $c->boot, $c->logger);
		});

		$this->setFactory('cron', function(ServiceProvider $c) {
			return new Cron($c->hooks, $c->logger, $c->events);
		});

		$this->setClassName('crypto', \ElggCrypto::class);

		$this->setFactory('dataCache', function (ServiceProvider $c) {
			return new DataCache($c->config);
		});

		$this->setFactory('db', function (ServiceProvider $c) {
			$db = new \Elgg\Database($c->dbConfig);
			$db->setLogger($c->logger);

			if ($c->config->profiling_sql) {
				$db->setTimer($c->timer);
			}

			return $db;
		});

		$this->setFactory('dbConfig', function(ServiceProvider $c) {
			$config = $c->config;
			$db_config = \Elgg\Database\DbConfig::fromElggConfig($config);

			// get this stuff out of config!
			unset($config->db);
			unset($config->dbname);
			unset($config->dbhost);
			unset($config->dbuser);
			unset($config->dbpass);

			return $db_config;
		});

		$this->setFactory('deprecation', function(ServiceProvider $c) {
			return new \Elgg\DeprecationService($c->logger);
		});

		$this->setFactory('dic', function (ServiceProvider $c) {
			$definitions = $c->dic_loader->getDefinitions();
			foreach ($definitions as $definition) {
				$c->dic_builder->addDefinitions($definition);
			}
			return $c->dic_builder->build();
		});

		$this->setFactory('dic_builder', function(ServiceProvider $c) {
			$dic_builder = new ContainerBuilder(PublicContainer::class);
			$dic_builder->useAnnotations(false);
			$dic_builder->setDefinitionCache($c->dic_cache);

			return $dic_builder;
		});

		$this->setFactory('dic_cache', function (ServiceProvider $c) {
			$cache = new CompositeCache(
				'dic',
				$c->config,
				ELGG_CACHE_APC |
				ELGG_CACHE_PERSISTENT |
				ELGG_CACHE_FILESYSTEM |
				ELGG_CACHE_RUNTIME
			);

			return new \Elgg\Di\DefinitionCache($cache);
		});

		$this->setFactory('dic_loader', function(ServiceProvider $c) {
			return new \Elgg\Di\DefinitionLoader($c->plugins);
		});

		$this->setFactory('emails', function(ServiceProvider $c) {
			return new \Elgg\EmailService($c->config, $c->hooks, $c->mailer, $c->logger);
		});

		$this->setFactory('entityCache', function(ServiceProvider $c) {
			return new \Elgg\Cache\EntityCache($c->session, $c->sessionCache->entities);
		});

		$this->setFactory('entityPreloader', function(ServiceProvider $c) {
			return new \Elgg\EntityPreloader($c->entityTable);
		});

		$this->setFactory('entityTable', function(ServiceProvider $c) {
			return new \Elgg\Database\EntityTable(
				$c->config,
				$c->db,
				$c->entityCache,
				$c->metadataCache,
				$c->privateSettingsCache,
				$c->events,
				$c->session,
				$c->translator,
				$c->logger
			);
		});

		$this->setFactory('events', function(ServiceProvider $c) {
			$events = new \Elgg\EventsService($c->handlers);
			if ($c->config->enable_profiling) {
				$events->setTimer($c->timer);
			}

			return $events;
		});

		$this->setClassName('externalFiles', \Elgg\Assets\ExternalFiles::class);

		$this->setFactory('fileCache', function(ServiceProvider $c) {
			$flags = ELGG_CACHE_PERSISTENT | ELGG_CACHE_FILESYSTEM | ELGG_CACHE_RUNTIME;
			return new CompositeCache("elgg_system_cache", $c->config, $flags);
		});

		$this->setFactory('filestore', function(ServiceProvider $c) {
			return new \ElggDiskFilestore($c->config->dataroot);
		});

		$this->setFactory('forms', function(ServiceProvider $c) {
			return new \Elgg\FormsService($c->views, $c->logger);
		});

		$this->setFactory('gatekeeper', function(ServiceProvider $c) {
			return new \Elgg\Gatekeeper(
				$c->session,
				$c->request,
				$c->redirects,
				$c->entityTable,
				$c->accessCollections,
				$c->translator
			);
		});

		$this->setFactory('group_tools', function(ServiceProvider $c) {
			return new Tools($c->hooks);
		});
		
		$this->setClassName('handlers', \Elgg\HandlersService::class);

		$this->setFactory('hmac', function(ServiceProvider $c) {
			return new \Elgg\Security\HmacFactory($c->siteSecret, $c->crypto);
		});

		$this->setFactory('html_formatter', function(ServiceProvider $c) {
			return new \Elgg\Views\HtmlFormatter(
				$c->logger,
				$c->views,
				$c->hooks,
				$c->autoP
			);
		});

		$this->setFactory('hooks', function(ServiceProvider $c) {
			return new \Elgg\PluginHooksService($c->events);
		});

		$this->setFactory('iconService', function(ServiceProvider $c) {
			return new \Elgg\EntityIconService($c->config, $c->hooks, $c->request, $c->logger, $c->entityTable, $c->uploads, $c->imageService);
		});

		$this->setFactory('imageService', function(ServiceProvider $c) {
			switch ($c->config->image_processor) {
				case 'imagick':
					if (extension_loaded('imagick')) {
						$imagine = new \Imagine\Imagick\Imagine();
						break;
					}
				default:
					// default use GD
					$imagine = new \Imagine\Gd\Imagine();
					break;
			}

			return new \Elgg\ImageService($imagine, $c->config);
		});

		$this->setFactory('invoker', function(ServiceProvider $c) {
			return new Invoker($c->session, $c->dic);
		});
		
		$this->setFactory('localeService', function(ServiceProvider $c) {
			return new LocaleService($c->config);
		});

		$this->setFactory('logger', function (ServiceProvider $c) {
			$logger = Logger::factory($c->cli_input, $c->cli_output);

			$logger->setLevel($c->config->debug);
			$logger->setHooks($c->hooks);

			return $logger;
		});

		$this->setClassName('mailer', 'Zend\Mail\Transport\Sendmail');

		$this->setFactory('menus', function(ServiceProvider $c) {
			return new \Elgg\Menu\Service($c->hooks, $c->config);
		});

		$this->setFactory('metadataCache', function (ServiceProvider $c) {
			$cache = $c->dataCache->metadata;
			return new \Elgg\Cache\MetadataCache($cache);
		});

		$this->setFactory('metadataTable', function(ServiceProvider $c) {
			// TODO(ewinslow): Use Pool instead of MetadataCache for caching
			return new \Elgg\Database\MetadataTable($c->metadataCache, $c->db, $c->events);
		});

		$this->setFactory('mutex', function(ServiceProvider $c) {
			return new \Elgg\Database\Mutex(
				$c->db,
				$c->logger
			);
		});

		$this->setFactory('notifications', function(ServiceProvider $c) {
			// @todo move queue in service provider
			$queue_name = \Elgg\Notifications\NotificationsService::QUEUE_NAME;
			$queue = new \Elgg\Queue\DatabaseQueue($queue_name, $c->db);
			$sub = new \Elgg\Notifications\SubscriptionsService($c->db);
			return new \Elgg\Notifications\NotificationsService($sub, $queue, $c->hooks, $c->session, $c->translator, $c->entityTable, $c->logger);
		});

		$this->setFactory('persistentLogin', function(ServiceProvider $c) {
			$global_cookies_config = $c->config->getCookieConfig();
			$cookie_config = $global_cookies_config['remember_me'];
			$cookie_name = $cookie_config['name'];
			$cookie_token = $c->request->cookies->get($cookie_name, '');
			return new \Elgg\PersistentLoginService(
				$c->db, $c->session, $c->crypto, $cookie_config, $cookie_token);
		});

		$this->setClassName('passwords', \Elgg\PasswordService::class);

		$this->setFactory('plugins', function(ServiceProvider $c) {
			$cache = new CompositeCache('plugins', $c->config, ELGG_CACHE_RUNTIME);
			$plugins = new \Elgg\Database\Plugins(
				$cache,
				$c->db,
				$c->session,
				$c->events,
				$c->translator,
				$c->views,
				$c->privateSettingsCache,
				$c->config,
				$c->systemMessages,
				$c->request->getContextStack()
			);
			if ($c->config->enable_profiling) {
				$plugins->setTimer($c->timer);
			}
			return $plugins;
		});

		$this->setFactory('privateSettingsCache', function(ServiceProvider $c) {
			$cache = $c->dataCache->private_settings;
			return new \Elgg\Cache\PrivateSettingsCache($cache);
		});

		$this->setFactory('privateSettings', function (ServiceProvider $c) {
			return new \Elgg\Database\PrivateSettingsTable($c->db, $c->entityTable, $c->privateSettingsCache);
		});

		$this->setFactory('publicDb', function(ServiceProvider $c) {
			return new \Elgg\Application\Database($c->db);
		});

		$this->setFactory('queryCounter', function(ServiceProvider $c) {
			return new \Elgg\Database\QueryCounter($c->db);
		}, false);

		$this->setFactory('redirects', function(ServiceProvider $c) {
			$url = current_page_url();
			$is_xhr = $c->request->isXmlHttpRequest();
			return new \Elgg\RedirectService($c->session, $is_xhr, $c->config->wwwroot, $url);
		});

		$this->setFactory('relationshipsTable', function(ServiceProvider $c) {
			return new \Elgg\Database\RelationshipsTable($c->db, $c->entityTable, $c->metadataTable, $c->events);
		});

		$this->setFactory('request', [\Elgg\Http\Request::class, 'createFromGlobals']);

		$this->setFactory('requestContext', function(ServiceProvider $c) {
			$context = new \Elgg\Router\RequestContext();
			$context->fromRequest($c->request);
			return $context;
		});

		$this->setFactory('responseFactory', function(ServiceProvider $c) {
			$transport = Application::getResponseTransport();
			return new \Elgg\Http\ResponseFactory($c->request, $c->hooks, $c->ajax, $transport, $c->events);
		});

		$this->setFactory('routeCollection', function(ServiceProvider $c) {
			return new \Elgg\Router\RouteCollection();
		});

		$this->setFactory('routes', function(ServiceProvider $c) {
			return new RouteRegistrationService(
				$c->hooks,
				$c->logger,
				$c->routeCollection,
				$c->urlGenerator
			);
		});

		$this->setFactory('router', function (ServiceProvider $c) {
			$router = new \Elgg\Router(
				$c->hooks,
				$c->routeCollection,
				$c->urlMatcher,
				$c->handlers,
				$c->responseFactory
			);
			if ($c->config->enable_profiling) {
				$router->setTimer($c->timer);
			}

			return $router;
		});

		$this->setFactory('search', function(ServiceProvider $c) {
			return new \Elgg\Search\SearchService($c->config, $c->hooks, $c->db);
		});

		$this->setFactory('seeder', function(ServiceProvider $c) {
			return new \Elgg\Database\Seeder($c->hooks, $c->cli_progress);
		});

		$this->setFactory('serveFileHandler', function(ServiceProvider $c) {
			return new \Elgg\Application\ServeFileHandler($c->hmac, $c->config);
		});

		$this->setFactory('session', function(ServiceProvider $c) {
			return \ElggSession::fromDatabase($c->config, $c->db);
		});

		$this->setFactory('sessionCache', function (ServiceProvider $c) {
			return new SessionCache($c->config);
		});

		$this->initSiteSecret($config);

		$this->setFactory('simpleCache', function(ServiceProvider $c) {
			return new \Elgg\Cache\SimpleCache($c->config, $c->views);
		});

		$this->setClassName('stickyForms', \Elgg\Forms\StickyForms::class);

		$this->setFactory('systemCache', function (ServiceProvider $c) {
			$cache = new \Elgg\Cache\SystemCache($c->fileCache, $c->config);
			if ($c->config->enable_profiling) {
				$cache->setTimer($c->timer);
			}
			return $cache;
		});

		$this->setFactory('systemMessages', function(ServiceProvider $c) {
			return new \Elgg\SystemMessagesService($c->session);
		});

		$this->setClassName('table_columns', \Elgg\Views\TableColumn\ColumnFactory::class);

		$this->setClassName('temp_filestore',  \ElggTempDiskFilestore::class);

		$this->setClassName('timer', \Elgg\Timer::class);

		$this->setFactory('translator', function(ServiceProvider $c) {
			return new \Elgg\I18n\Translator($c->config, $c->localeService);
		});

		$this->setFactory('uploads', function(ServiceProvider $c) {
			return new \Elgg\UploadService($c->request);
		});

		$this->setFactory('upgrades', function(ServiceProvider $c) {
			return new \Elgg\UpgradeService(
				$c->upgradeLocator,
				$c->translator,
				$c->events,
				$c->config,
				$c->logger,
				$c->mutex,
				$c->systemMessages,
				$c->cli_progress
			);
		});

		$this->setFactory('urlGenerator', function(ServiceProvider $c) {
			return new \Elgg\Router\UrlGenerator(
				$c->routeCollection,
				$c->requestContext
			);
		});

		$this->setFactory('urlMatcher', function(ServiceProvider $c) {
			return new \Elgg\Router\UrlMatcher(
				$c->routeCollection,
				$c->requestContext
			);
		});

		$this->setClassName('urlSigner', \Elgg\Security\UrlSigner::class);

		$this->setFactory('userCapabilities', function(ServiceProvider $c) {
			return new \Elgg\UserCapabilities($c->hooks, $c->entityTable, $c->session);
		});

		$this->setFactory('usersTable', function(ServiceProvider $c) {
			return new \Elgg\Database\UsersTable(
				$c->config,
				$c->db,
				$c->metadataTable
			);
		});

		$this->setFactory('upgradeLocator', function(ServiceProvider $c) {
			return new \Elgg\Upgrade\Locator(
				$c->plugins,
				$c->logger
			);
		});

		$this->setFactory('views', function(ServiceProvider $c) {
			return new \Elgg\ViewsService($c->hooks, $c->logger, $c->request);
		});

		$this->setFactory('viewCacher', function(ServiceProvider $c) {
			return new \Elgg\Cache\ViewCacher($c->views, $c->config);
		});

		$this->setClassName('widgets', \Elgg\WidgetsService::class);
	}

	/**
	 * Extract the site secret from config or set up its factory
	 *
	 * @param Config $config Elgg Config
	 * @return void
	 */
	protected function initSiteSecret(Config $config) {
		// Try the config, because if it's there we want to remove it to isolate who can see it.
		$secret = SiteSecret::fromConfig($config);
		if ($secret) {
			$this->setValue('siteSecret', $secret);
			$config->elgg_config_set_secret = true;
			return;
		}

		$this->setFactory('siteSecret', function (ServiceProvider $c) {
			return SiteSecret::fromDatabase($c->configTable);
		});
	}

	/**
	 * Validate, normalize, fill in missing values, and lock some
	 *
	 * @param Config          $config Config
	 * @param ServiceProvider $sp     Service Provider
	 *
	 * @return void
	 * @throws ConfigurationException
	 */
	public function initConfig(Config $config, ServiceProvider $sp) {
		if ($config->elgg_config_locks === null) {
			$config->elgg_config_locks = true;
		}

		if ($config->elgg_config_locks) {
			$lock = function ($name) use ($config) {
				$config->lock($name);
			};
		} else {
			// the installer needs to build an application with defaults then update
			// them after they're validated, so we don't want to lock them.
			$lock = function () {
			};
		}

		$sp->timer->begin([]);

		if ($config->dataroot) {
			$config->dataroot = Paths::sanitize($config->dataroot);
		} else {
			if (!$config->installer_running) {
				throw new ConfigurationException('Config value "dataroot" is required.');
			}
		}
		$lock('dataroot');

		if ($config->cacheroot) {
			$config->cacheroot = Paths::sanitize($config->cacheroot);
		} else {
			$config->cacheroot = Paths::sanitize($config->dataroot . 'caches');
		}
		$lock('cacheroot');

		if ($config->assetroot) {
			$config->assetroot = Paths::sanitize($config->assetroot);
		} else {
			$config->assetroot = Paths::sanitize($config->cacheroot . 'views_simplecache');
		}
		$lock('assetroot');
		
		if ($config->wwwroot) {
			$config->wwwroot = rtrim($config->wwwroot, '/') . '/';
		} else {
			$config->wwwroot = $sp->request->sniffElggUrl();
		}
		$lock('wwwroot');

		if (!$config->language) {
			$config->language = Application::DEFAULT_LANG;
		}

		if ($config->default_limit) {
			$lock('default_limit');
		} else {
			$config->default_limit = Application::DEFAULT_LIMIT;
		}

		if ($config->plugins_path) {
			$plugins_path = rtrim($config->plugins_path, '/') . '/';
		} else {
			$plugins_path = Paths::project() . 'mod/';
		}

		$locked_props = [
			'site_guid' => 1,
			'path' => Paths::project(),
			'plugins_path' => $plugins_path,
			'pluginspath' => $plugins_path,
			'url' => $config->wwwroot,
		];
		foreach ($locked_props as $name => $value) {
			$config->$name = $value;
			$lock($name);
		}

		// move sensitive credentials into isolated services
		$sp->setValue('dbConfig', DbConfig::fromElggConfig($config));

		// get this stuff out of config!
		unset($config->db);
		unset($config->dbname);
		unset($config->dbhost);
		unset($config->dbuser);
		unset($config->dbpass);

		$config->boot_complete = false;
	}
}
