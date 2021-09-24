<?php

namespace Elgg\Di;

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
use Elgg\Exceptions\ConfigurationException;
use Elgg\Groups\Tools;
use Elgg\Invoker;
use Elgg\Logger;
use Elgg\Project\Paths;
use Elgg\Security\Csrf;
use Laminas\Mail\Transport\TransportInterface as Mailer;
use Elgg\I18n\LocaleService;
use Elgg\Security\PasswordGeneratorService;

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
 * @property-read \Elgg\Database\ApiUsersTable                    $apiUsersTable
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
 * @property-read \Elgg\Database\DelayedEmailQueueTable           $delayedEmailQueueTable
 * @property-read \Elgg\Email\DelayedEmailService                 $delayedEmailService
 * @property-read \Elgg\DI\PublicContainer                        $dic
 * @property-read \Di\ContainerBuilder                            $dic_builder
 * @property-read \Elgg\Di\DefinitionLoader                       $dic_loader
 * @property-read \Elgg\EmailService                              $emails
 * @property-read \Elgg\Cache\EntityCache                         $entityCache
 * @property-read \Elgg\EntityPreloader                           $entityPreloader
 * @property-read \Elgg\Database\EntityTable                      $entityTable
 * @property-read \Elgg\EventsService                             $events
 * @property-read \Elgg\Assets\ExternalFiles                      $externalFiles
 * @property-read \Elgg\Forms\FieldsService                       $fields
 * @property-read \ElggCache                                      $fileCache
 * @property-read \ElggDiskFilestore                              $filestore
 * @property-read \Elgg\FormsService                              $forms
 * @property-read \Elgg\Gatekeeper                                $gatekeeper
 * @property-read \Elgg\Groups\Tools                              $group_tools
 * @property-read \Elgg\HandlersService                           $handlers
 * @property-read \Elgg\Security\HmacFactory                      $hmac
 * @property-read \Elgg\Database\HMACCacheTable                   $hmacCacheTable
 * @property-read \Elgg\Views\HtmlFormatter                       $html_formatter
 * @property-read \Elgg\PluginHooksService                        $hooks
 * @property-read \Elgg\EntityIconService                         $iconService
 * @property-read \Elgg\Assets\ImageFetcherService                $imageFetcher
 * @property-read \Elgg\ImageService                              $imageService
 * @property-read \Elgg\Invoker                                   $invoker
 * @property-read \Elgg\I18n\LocaleService                        $localeService
 * @property-read \ElggCache                                      $localFileCache
 * @property-read \Elgg\Logger                                    $logger
 * @property-read Mailer                                          $mailer
 * @property-read \Elgg\Menu\Service                              $menus
 * @property-read \Elgg\Cache\MetadataCache                       $metadataCache
 * @property-read \Elgg\Database\MetadataTable                    $metadataTable
 * @property-read \Elgg\Filesystem\MimeTypeService                $mimetype
 * @property-read \Elgg\Database\Mutex                            $mutex
 * @property-read \Elgg\Notifications\NotificationsService        $notifications
 * @property-read \Elgg\Notifications\NotificationsQueue          $notificationsQueue
 * @property-read \Elgg\Page\PageOwnerService                     $pageOwner
 * @property-read \Elgg\PasswordService                           $passwords
 * @property-read \Elgg\Security\PasswordGeneratorService         $passwordGenerator
 * @property-read \Elgg\PersistentLoginService                    $persistentLogin
 * @property-read \Elgg\Database\Plugins                          $plugins
 * @property-read \Elgg\Cache\PrivateSettingsCache                $privateSettingsCache
 * @property-read \Elgg\Database\PrivateSettingsTable             $privateSettings
 * @property-read \Elgg\Application\Database                      $publicDb
 * @property-read \Elgg\Cache\QueryCache                          $queryCache
 * @property-read \Elgg\RedirectService                           $redirects
 * @property-read \Elgg\Http\Request                              $request
 * @property-read \Elgg\Router\RequestContext                     $requestContext
 * @property-read \Elgg\Http\ResponseFactory                      $responseFactory
 * @property-read \Elgg\Database\RelationshipsTable               $relationshipsTable
 * @property-read \Elgg\Database\RiverTable                       $riverTable
 * @property-read \Elgg\Router\RouteCollection                    $routeCollection
 * @property-read \Elgg\Router\RouteRegistrationService           $routes
 * @property-read \Elgg\Router                                    $router
 * @property-read \Elgg\Database\Seeder                           $seeder
 * @property-read \Elgg\Application\ServeFileHandler              $serveFileHandler
 * @property-read \Elgg\Cache\SystemCache                         $serverCache
 * @property-read \ElggSession                                    $session
 * @property-read \Elgg\Cache\SessionCache                        $sessionCache
 * @property-read \Elgg\Search\SearchService                      $search
 * @property-read \Elgg\Cache\SimpleCache                         $simpleCache
 * @property-read \Elgg\Database\SiteSecret                       $siteSecret
 * @property-read \Elgg\Forms\StickyForms                         $stickyForms
 * @property-read \Elgg\Notifications\SubscriptionsService        $subscriptions
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
 * @property-read \Elgg\Database\UsersApiSessionsTable            $usersApiSessionsTable
 * @property-read \Elgg\Database\UsersTable                       $usersTable
 * @property-read \Elgg\ViewsService                              $views
 * @property-read \Elgg\Cache\ViewCacher                          $viewCacher
 * @property-read \Elgg\WidgetsService                            $widgets
 *
 * @internal
 */
class ServiceProvider extends DiContainer {

	/**
	 * Constructor
	 *
	 * @param Config $config Elgg Config service
	 */
	public function __construct(Config $config) {

		$this->setFactory('autoloadManager', function(ServiceProvider $sp) {
			$manager = new \Elgg\AutoloadManager($sp->classLoader);

			if (!$sp->config->AutoloaderManager_skip_storage) {
				$manager->setCache($sp->localFileCache);
				$manager->loadCache();
			}

			return $manager;
		});

		$this->setFactory('accessCache', function(ServiceProvider $sp) {
			return $this->sessionCache->access;
		});

		$this->setFactory('accessCollections', function(ServiceProvider $sp) {
			return new \Elgg\Database\AccessCollections(
				$sp->config,
				$sp->db,
				$sp->entityTable,
				$sp->userCapabilities,
				$sp->accessCache,
				$sp->hooks,
				$sp->session,
				$sp->translator
			);
		});

		$this->setFactory('actions', function(ServiceProvider $sp) {
			return new \Elgg\ActionsService($sp->routes, $sp->handlers);
		});

		$this->setFactory('accounts', function(ServiceProvider $sp) {
			return new \Elgg\Users\Accounts(
				$sp->config,
				$sp->translator,
				$sp->passwords,
				$sp->usersTable,
				$sp->hooks,
				$sp->emails,
				$sp->passwordGenerator
			);
		});

		$this->setClassName('adminNotices', \Elgg\Database\AdminNotices::class);

		$this->setFactory('ajax', function(ServiceProvider $sp) {
			return new \Elgg\Ajax\Service($sp->hooks, $sp->systemMessages, $sp->request, $sp->amdConfig);
		});

		$this->setFactory('amdConfig', function(ServiceProvider $sp) {
			$obj = new \Elgg\Amd\Config($sp->hooks);
			$obj->setBaseUrl($sp->simpleCache->getRoot());
			return $obj;
		});

		$this->setFactory('annotationsTable', function(ServiceProvider $sp) {
			return new \Elgg\Database\AnnotationsTable($sp->db, $sp->events);
		});

		$this->setFactory('apiUsersTable', function(ServiceProvider $sp) {
			return new \Elgg\Database\ApiUsersTable($sp->db, $sp->crypto);
		});
		
		$this->setClassName('autoP', \ElggAutoP::class);

		$this->setFactory('boot', function (ServiceProvider $sp) {
			$flags = ELGG_CACHE_PERSISTENT | ELGG_CACHE_FILESYSTEM | ELGG_CACHE_RUNTIME;
			$cache = new CompositeCache("elgg_boot", $sp->config, $flags);
			$boot = new \Elgg\BootService($cache);
			if ($sp->config->enable_profiling) {
				$boot->setTimer($sp->timer);
			}
			return $boot;
		});

		$this->setFactory('cacheHandler', function(ServiceProvider $sp) {
			if ($sp->config->hasInitialValue('simplecache_enabled')) {
				// check for setting in settings.php
				$simplecache_enabled = $sp->config->getInitialValue('simplecache_enabled');
			} else {
				// need to retrieve setting from db
				$simplecache_enabled = $sp->configTable->get('simplecache_enabled') ?? $sp->config->simplecache_enabled;
			}
			
			return new \Elgg\Application\CacheHandler($sp->config, $sp->request, $simplecache_enabled);
		});

		$this->setFactory('cssCompiler', function(ServiceProvider $sp) {
			return new CssCompiler($sp->config, $sp->hooks);
		});

		$this->setFactory('csrf', function(ServiceProvider $sp) {
			return new Csrf(
				$sp->config,
				$sp->session,
				$sp->crypto,
				$sp->hmac
			);
		});

		$this->setFactory('classLoader', function(ServiceProvider $sp) {
			$loader = new \Elgg\ClassLoader(new \Elgg\ClassMap());
			$loader->register();
			return $loader;
		});

		$this->setFactory('cli', function(ServiceProvider $sp) {
			$version = elgg_get_version(true);

			$console = new \Elgg\Cli\Application('Elgg', $version);
			$console->setup($sp->cli_input, $sp->cli_output);

			return new \Elgg\Cli(
				$console,
				$sp->hooks,
				$sp->cli_input,
				$sp->cli_output
			);
		});

		$this->setFactory('cli_input', function(ServiceProvider $sp) {
			return Application::getStdIn();
		});

		$this->setFactory('cli_output', function(ServiceProvider $sp) {
			return Application::getStdOut();
		});

		$this->setFactory('cli_progress', function(ServiceProvider $sp) {
			return new Progress($sp->cli_output);
		});

		$this->setFactory('config', function (ServiceProvider $sp) use ($config) {
			$this->initConfig($config, $sp);
			return $config;
		});

		$this->setFactory('configTable', function(ServiceProvider $sp) {
			return new \Elgg\Database\ConfigTable($sp->db, $sp->boot);
		});

		$this->setFactory('cron', function(ServiceProvider $sp) {
			return new Cron($sp->hooks, $sp->events);
		});

		$this->setClassName('crypto', \ElggCrypto::class);

		$this->setFactory('dataCache', function (ServiceProvider $sp) {
			return new DataCache($sp->config);
		});

		$this->setFactory('db', function (ServiceProvider $sp) {
			$db = new \Elgg\Database($sp->dbConfig, $sp->queryCache);

			if ($sp->config->profiling_sql) {
				$db->setTimer($sp->timer);
			}

			return $db;
		});

		$this->setFactory('dbConfig', function(ServiceProvider $sp) {
			$config = $sp->config;
			$db_config = \Elgg\Database\DbConfig::fromElggConfig($config);

			// get this stuff out of config!
			unset($config->db);
			unset($config->dbname);
			unset($config->dbhost);
			unset($config->dbport);
			unset($config->dbuser);
			unset($config->dbpass);

			return $db_config;
		});
		
		$this->setFactory('delayedEmailQueueTable', function (ServiceProvider $sp) {
			return new \Elgg\Database\DelayedEmailQueueTable($sp->db);
		});
		
		$this->setFactory('delayedEmailService', function (ServiceProvider $sp) {
			return new \Elgg\Email\DelayedEmailService($sp->delayedEmailQueueTable, $sp->emails, $sp->views, $sp->translator, $sp->invoker);
		});

		$this->setFactory('dic', function (ServiceProvider $sp) {
			$definitions = $sp->dic_loader->getDefinitions();
			foreach ($definitions as $definition) {
				$sp->dic_builder->addDefinitions($definition);
			}
			return $sp->dic_builder->build();
		});

		$this->setFactory('dic_builder', function(ServiceProvider $sp) {
			$dic_builder = new ContainerBuilder(PublicContainer::class);
			$dic_builder->useAnnotations(false);
			
			return $dic_builder;
		});

		$this->setFactory('dic_loader', function(ServiceProvider $sp) {
			return new \Elgg\Di\DefinitionLoader($sp->plugins);
		});

		$this->setFactory('emails', function(ServiceProvider $sp) {
			return new \Elgg\EmailService(
				$sp->config,
				$sp->hooks,
				$sp->mailer,
				$sp->html_formatter,
				$sp->views,
				$sp->imageFetcher,
				$sp->cssCompiler
			);
		});

		$this->setFactory('entityCache', function(ServiceProvider $sp) {
			return new \Elgg\Cache\EntityCache($sp->session, $sp->sessionCache->entities);
		});

		$this->setFactory('entityPreloader', function(ServiceProvider $sp) {
			return new \Elgg\EntityPreloader($sp->entityTable);
		});

		$this->setFactory('entityTable', function(ServiceProvider $sp) {
			return new \Elgg\Database\EntityTable(
				$sp->config,
				$sp->db,
				$sp->entityCache,
				$sp->metadataCache,
				$sp->privateSettingsCache,
				$sp->events,
				$sp->session,
				$sp->translator
			);
		});

		$this->setFactory('events', function(ServiceProvider $sp) {
			$events = new \Elgg\EventsService($sp->handlers);
			if ($sp->config->enable_profiling) {
				$events->setTimer($sp->timer);
			}

			return $events;
		});

		$this->setClassName('externalFiles', \Elgg\Assets\ExternalFiles::class);

		$this->setFactory('fields', function(ServiceProvider $sp) {
			return new \Elgg\Forms\FieldsService($sp->hooks, $sp->translator);
		});
		
		$this->setFactory('fileCache', function(ServiceProvider $sp) {
			$flags = ELGG_CACHE_PERSISTENT | ELGG_CACHE_FILESYSTEM | ELGG_CACHE_RUNTIME;
			return new CompositeCache("elgg_system_cache", $sp->config, $flags);
		});

		$this->setFactory('filestore', function(ServiceProvider $sp) {
			return new \ElggDiskFilestore($sp->config->dataroot);
		});

		$this->setFactory('forms', function(ServiceProvider $sp) {
			return new \Elgg\FormsService($sp->views);
		});

		$this->setFactory('gatekeeper', function(ServiceProvider $sp) {
			return new \Elgg\Gatekeeper(
				$sp->session,
				$sp->request,
				$sp->redirects,
				$sp->entityTable,
				$sp->accessCollections,
				$sp->translator
			);
		});

		$this->setFactory('group_tools', function(ServiceProvider $sp) {
			return new Tools($sp->hooks);
		});
		
		$this->setClassName('handlers', \Elgg\HandlersService::class);

		$this->setFactory('hmac', function(ServiceProvider $sp) {
			return new \Elgg\Security\HmacFactory($sp->siteSecret, $sp->crypto);
		});
		
		$this->setFactory('hmacCacheTable', function(ServiceProvider $sp) {
			$hmac = new \Elgg\Database\HMACCacheTable($sp->db);
			// HMAC lifetime is 25 hours (this should be related to the time drift allowed in header validation)
			$hmac->setTTL(90000);
			
			return $hmac;
		});

		$this->setFactory('html_formatter', function(ServiceProvider $sp) {
			return new \Elgg\Views\HtmlFormatter(
				$sp->views,
				$sp->hooks,
				$sp->autoP
			);
		});

		$this->setFactory('hooks', function(ServiceProvider $sp) {
			return new \Elgg\PluginHooksService($sp->events);
		});

		$this->setFactory('iconService', function(ServiceProvider $sp) {
			return new \Elgg\EntityIconService(
				$sp->config,
				$sp->hooks,
				$sp->entityTable,
				$sp->uploads,
				$sp->imageService,
				$sp->mimetype
			);
		});

		$this->setFactory('imageFetcher', function(ServiceProvider $sp) {
			return new \Elgg\Assets\ImageFetcherService($sp->config, $sp->systemCache, $sp->session);
		});
		
		$this->setFactory('imageService', function(ServiceProvider $sp) {
			switch ($sp->config->image_processor) {
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

			return new \Elgg\ImageService($imagine, $sp->config, $sp->mimetype);
		});

		$this->setFactory('invoker', function(ServiceProvider $sp) {
			return new Invoker($sp->session, $sp->dic);
		});
		
		$this->setFactory('localeService', function(ServiceProvider $sp) {
			return new LocaleService($sp->config);
		});
		
		$this->setFactory('localFileCache', function(ServiceProvider $sp) {
			$flags = ELGG_CACHE_LOCALFILESYSTEM | ELGG_CACHE_RUNTIME;
			return new CompositeCache('elgg_local_system_cache', $sp->config, $flags);
		});

		$this->setFactory('logger', function (ServiceProvider $sp) {
			$logger = Logger::factory($sp->cli_input, $sp->cli_output);

			$logger->setLevel($sp->config->debug);
			$logger->setHooks($sp->hooks);

			return $logger;
		});

		$this->setFactory('mailer', function(ServiceProvider $sp) {
			switch ($sp->config->emailer_transport) {
				case 'smtp':
					$transport = new \Laminas\Mail\Transport\Smtp();
					$transportOptions = new \Laminas\Mail\Transport\SmtpOptions(
						$sp->config->emailer_smtp_settings
					);
					$transport->setOptions($transportOptions);
					return $transport;
				default:
					return new \Laminas\Mail\Transport\Sendmail($sp->config->emailer_sendmail_settings);
			}
		});

		$this->setFactory('menus', function(ServiceProvider $sp) {
			return new \Elgg\Menu\Service($sp->hooks, $sp->config);
		});

		$this->setFactory('metadataCache', function (ServiceProvider $sp) {
			$cache = $sp->dataCache->metadata;
			return new \Elgg\Cache\MetadataCache($cache);
		});

		$this->setFactory('metadataTable', function(ServiceProvider $sp) {
			return new \Elgg\Database\MetadataTable($sp->metadataCache, $sp->db, $sp->events, $sp->entityTable);
		});

		$this->setFactory('mimetype', function(ServiceProvider $sp) {
			return new \Elgg\Filesystem\MimeTypeService(
				$sp->hooks
			);
		});
		
		$this->setFactory('mutex', function(ServiceProvider $sp) {
			return new \Elgg\Database\Mutex(
				$sp->db
			);
		});

		$this->setFactory('notifications', function(ServiceProvider $sp) {
			return new \Elgg\Notifications\NotificationsService($sp->notificationsQueue, $sp->hooks, $sp->session);
		});
		
		$this->setFactory('notificationsQueue', function(ServiceProvider $sp) {
			$queue_name = \Elgg\Notifications\NotificationsQueue::QUEUE_NAME;
			return new \Elgg\Notifications\NotificationsQueue($queue_name, $sp->db, $sp->config);
		});

		$this->setFactory('pageOwner', function(ServiceProvider $sp) {
			return new \Elgg\Page\PageOwnerService(
				$sp->request,
				$sp->entityTable,
				$sp->hooks,
				$sp->usersTable,
				$sp->invoker
			);
		});
		
		$this->setClassName('passwords', \Elgg\PasswordService::class);
		
		$this->setFactory('passwordGenerator', function(ServiceProvider $sp) {
			return new PasswordGeneratorService(
				$sp->config,
				$sp->translator,
				$sp->hooks
			);
		});
		
		$this->setFactory('persistentLogin', function(ServiceProvider $sp) {
			$global_cookies_config = $sp->config->getCookieConfig();
			$cookie_config = $global_cookies_config['remember_me'];
			$cookie_name = $cookie_config['name'];
			$cookie_token = $sp->request->cookies->get($cookie_name, '');
			return new \Elgg\PersistentLoginService(
				$sp->db,
				$sp->session,
				$sp->crypto,
				$cookie_config,
				$cookie_token
			);
		});
		
		$this->setFactory('plugins', function(ServiceProvider $sp) {
			$cache = new CompositeCache('plugins', $sp->config, ELGG_CACHE_RUNTIME);
			$plugins = new \Elgg\Database\Plugins(
				$cache,
				$sp->db,
				$sp->session,
				$sp->events,
				$sp->translator,
				$sp->views,
				$sp->privateSettingsCache,
				$sp->config,
				$sp->systemMessages,
				$sp->request->getContextStack()
			);
			if ($sp->config->enable_profiling) {
				$plugins->setTimer($sp->timer);
			}
			return $plugins;
		});

		$this->setFactory('privateSettingsCache', function(ServiceProvider $sp) {
			$cache = $sp->dataCache->private_settings;
			return new \Elgg\Cache\PrivateSettingsCache($cache);
		});

		$this->setFactory('privateSettings', function (ServiceProvider $sp) {
			return new \Elgg\Database\PrivateSettingsTable($sp->db, $sp->entityTable, $sp->privateSettingsCache);
		});

		$this->setFactory('publicDb', function(ServiceProvider $sp) {
			return new \Elgg\Application\Database($sp->db);
		});

		$this->setFactory('queryCache', function(ServiceProvider $sp) {
			// @todo maybe make this a configurable value
			$cache_size = 50;
			
			$config_disabled = $sp->config->db_disable_query_cache === true;
						
			$cache = new \Elgg\Cache\QueryCache($cache_size, $config_disabled);
			
			return $cache;
		});

		$this->setFactory('redirects', function(ServiceProvider $sp) {
			$url = current_page_url();
			$is_xhr = $sp->request->isXmlHttpRequest();
			return new \Elgg\RedirectService($sp->session, $is_xhr, $sp->config->wwwroot, $url);
		});

		$this->setFactory('relationshipsTable', function(ServiceProvider $sp) {
			return new \Elgg\Database\RelationshipsTable($sp->db, $sp->entityTable, $sp->metadataTable, $sp->events);
		});

		$this->setFactory('request', function(ServiceProvider $sp) use ($config) {
			$request = \Elgg\Http\Request::createFromGlobals();
			
			// not using ServiceProvider->config because of deadloop issues
			// using (the same) $config as the ServiceProvider was constructed with)
			$request->initializeTrustedProxyConfiguration($config);
			
			return $request;
		});

		$this->setFactory('requestContext', function(ServiceProvider $sp) {
			$context = new \Elgg\Router\RequestContext();
			$context->fromRequest($sp->request);
			return $context;
		});

		$this->setFactory('responseFactory', function(ServiceProvider $sp) {
			$transport = Application::getResponseTransport();
			return new \Elgg\Http\ResponseFactory($sp->request, $sp->hooks, $sp->ajax, $transport, $sp->events);
		});

		$this->setFactory('riverTable', function (ServiceProvider $sp) {
			return new \Elgg\Database\RiverTable(
				$sp->db,
				$sp->annotationsTable,
				$sp->entityTable,
				$sp->events,
				$sp->views
			);
		});

		$this->setFactory('routeCollection', function(ServiceProvider $sp) {
			return new \Elgg\Router\RouteCollection();
		});

		$this->setFactory('routes', function(ServiceProvider $sp) {
			return new \Elgg\Router\RouteRegistrationService(
				$sp->hooks,
				$sp->routeCollection,
				$sp->urlGenerator
			);
		});

		$this->setFactory('router', function (ServiceProvider $sp) {
			$router = new \Elgg\Router(
				$sp->hooks,
				$sp->routeCollection,
				$sp->urlMatcher,
				$sp->handlers,
				$sp->responseFactory,
				$sp->plugins
			);
			if ($sp->config->enable_profiling) {
				$router->setTimer($sp->timer);
			}

			return $router;
		});

		$this->setFactory('search', function(ServiceProvider $sp) {
			return new \Elgg\Search\SearchService($sp->config, $sp->hooks, $sp->db);
		});

		$this->setFactory('seeder', function(ServiceProvider $sp) {
			return new \Elgg\Database\Seeder($sp->hooks, $sp->cli_progress, $sp->invoker);
		});

		$this->setFactory('serveFileHandler', function(ServiceProvider $sp) {
			return new \Elgg\Application\ServeFileHandler($sp->hmac, $sp->config, $sp->mimetype);
		});

		$this->setFactory('session', function(ServiceProvider $sp) {
			return \ElggSession::fromDatabase($sp->config, $sp->db);
		});

		$this->setFactory('sessionCache', function (ServiceProvider $sp) {
			return new SessionCache($sp->config);
		});

		$this->initSiteSecret($config);

		$this->setFactory('simpleCache', function(ServiceProvider $sp) {
			return new \Elgg\Cache\SimpleCache($sp->config, $sp->views);
		});

		$this->setClassName('stickyForms', \Elgg\Forms\StickyForms::class);

		$this->setFactory('systemCache', function (ServiceProvider $sp) {
			$cache = new \Elgg\Cache\SystemCache($sp->fileCache, $sp->config);
			if ($sp->config->enable_profiling) {
				$cache->setTimer($sp->timer);
			}
			return $cache;
		});

		$this->setFactory('serverCache', function (ServiceProvider $sp) {
			$cache = new \Elgg\Cache\SystemCache($sp->localFileCache, $sp->config);
			if ($sp->config->enable_profiling) {
				$cache->setTimer($sp->timer);
			}
			return $cache;
		});
		
		$this->setFactory('subscriptions', function (ServiceProvider $sp) {
			return new \Elgg\Notifications\SubscriptionsService($sp->db, $sp->relationshipsTable, $sp->hooks);
		});
		
		$this->setFactory('systemMessages', function(ServiceProvider $sp) {
			return new \Elgg\SystemMessagesService($sp->session);
		});

		$this->setClassName('table_columns', \Elgg\Views\TableColumn\ColumnFactory::class);

		$this->setClassName('temp_filestore',  \ElggTempDiskFilestore::class);

		$this->setClassName('timer', \Elgg\Timer::class);

		$this->setFactory('translator', function(ServiceProvider $sp) {
			return new \Elgg\I18n\Translator($sp->config, $sp->localeService);
		});

		$this->setFactory('uploads', function(ServiceProvider $sp) {
			return new \Elgg\UploadService($sp->request);
		});

		$this->setFactory('upgrades', function(ServiceProvider $sp) {
			return new \Elgg\UpgradeService(
				$sp->upgradeLocator,
				$sp->translator,
				$sp->events,
				$sp->config,
				$sp->mutex,
				$sp->systemMessages,
				$sp->cli_progress
			);
		});

		$this->setFactory('urlGenerator', function(ServiceProvider $sp) {
			return new \Elgg\Router\UrlGenerator(
				$sp->routeCollection,
				$sp->requestContext
			);
		});

		$this->setFactory('urlMatcher', function(ServiceProvider $sp) {
			return new \Elgg\Router\UrlMatcher(
				$sp->routeCollection,
				$sp->requestContext
			);
		});

		$this->setClassName('urlSigner', \Elgg\Security\UrlSigner::class);

		$this->setFactory('userCapabilities', function(ServiceProvider $sp) {
			return new \Elgg\UserCapabilities($sp->hooks, $sp->entityTable, $sp->session);
		});

		$this->setFactory('usersApiSessionsTable', function(ServiceProvider $sp) {
			return new \Elgg\Database\UsersApiSessionsTable($sp->db, $sp->crypto);
		});
		
		$this->setFactory('usersTable', function(ServiceProvider $sp) {
			return new \Elgg\Database\UsersTable(
				$sp->config,
				$sp->db,
				$sp->metadataTable
			);
		});

		$this->setFactory('upgradeLocator', function(ServiceProvider $sp) {
			return new \Elgg\Upgrade\Locator(
				$sp->plugins
			);
		});

		$this->setFactory('views', function(ServiceProvider $sp) {
			return new \Elgg\ViewsService($sp->hooks, $sp->request);
		});

		$this->setFactory('viewCacher', function(ServiceProvider $sp) {
			return new \Elgg\Cache\ViewCacher($sp->views, $sp->config);
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

		$this->setFactory('siteSecret', function (ServiceProvider $sp) {
			return SiteSecret::fromDatabase($sp->configTable);
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
		unset($config->dbport);
		unset($config->dbuser);
		unset($config->dbpass);

		$config->boot_complete = false;
	}
}
