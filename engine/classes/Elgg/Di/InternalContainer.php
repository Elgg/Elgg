<?php

namespace Elgg\Di;

use Elgg\Application;
use Elgg\Config;
use Elgg\Database\DbConfig;
use Elgg\Exceptions\ConfigurationException;
use Elgg\Project\Paths;
use Psr\Container\ContainerInterface;

/**
 * Internal service container
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
 * @property-read \Elgg\AuthenticationService                     $authentication
 * @property-read \ElggAutoP                                      $autoP
 * @property-read \Elgg\AutoloadManager                           $autoloadManager
 * @property-read \Elgg\BootService                               $boot
 * @property-read \ElggCache                                      $bootCache
 * @property-read \Elgg\Application\CacheHandler                  $cacheHandler
 * @property-read \Elgg\Assets\CssCompiler                        $cssCompiler
 * @property-read \Elgg\Security\Csrf                             $csrf
 * @property-read \Elgg\ClassLoader                               $classLoader
 * @property-read \Elgg\Cli                                       $cli
 * @property-read \Symfony\Component\Console\Input\InputInterface $cli_input
 * @property-read \Symfony\Component\Console\Output\OutputInterface $cli_output
 * @property-read \Elgg\Cli\Progress                              $cli_progress
 * @property-read \Elgg\Cron                                      $cron
 * @property-read \Elgg\Security\Crypto                           $crypto
 * @property-read \Elgg\Config                                    $config
 * @property-read \Elgg\Database\ConfigTable                      $configTable
 * @property-read \Elgg\Cache\DataCache                           $dataCache
 * @property-read \Elgg\Database                                  $db
 * @property-read \Elgg\Database\DbConfig                         $dbConfig
 * @property-read \Elgg\Database\DelayedEmailQueueTable           $delayedEmailQueueTable
 * @property-read \Elgg\Email\DelayedEmailService                 $delayedEmailService
 * @property-read \Elgg\EmailService                              $emails
 * @property-read \Elgg\Cache\EntityCache                         $entityCache
 * @property-read \Elgg\EntityCapabilitiesService                 $entity_capabilities
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
 * @property-read \Elgg\I18n\LocaleService                        $locale
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
 * @property-read \ElggCache                                      $pluginsCache
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
 * @property-read \Elgg\SystemMessagesService                     $system_messages
 * @property-read \Elgg\Views\TableColumn\ColumnFactory           $table_columns
 * @property-read \ElggTempDiskFilestore                          $temp_filestore
 * @property-read \Elgg\Timer                                     $timer
 * @property-read \Elgg\I18n\Translator                           $translator
 * @property-read \Elgg\Security\UrlSigner                        $urlSigner
 * @property-read \Elgg\UpgradeService                            $upgrades
 * @property-read \Elgg\Upgrade\Locator                           $upgradeLocator
 * @property-read \Elgg\Router\UrlGenerator                       $urlGenerator
 * @property-read \Elgg\Router\UrlMatcher                         $urlMatcher
 * @property-read \Elgg\Http\Urls                                 $urls
 * @property-read \Elgg\UploadService                             $uploads
 * @property-read \Elgg\UserCapabilities                          $userCapabilities
 * @property-read \Elgg\Database\UsersApiSessionsTable            $usersApiSessionsTable
 * @property-read \Elgg\Database\UsersRememberMeCookiesTable      $users_remember_me_cookies_table
 * @property-read \Elgg\Database\UsersTable                       $usersTable
 * @property-read \Elgg\ViewsService                              $views
 * @property-read \Elgg\Cache\ViewCacher                          $viewCacher
 * @property-read \Elgg\WidgetsService                            $widgets
 *
 * @internal
 */
class InternalContainer extends DiContainer {
	
	/**
	 * Validate, normalize, fill in missing values, and lock some
	 *
	 * @param Config $config Config
	 *
	 * @return Config
	 *
	 * @throws ConfigurationException
	 */
	public function initConfig(Config $config): Config {
		$this->timer->begin([]);

		if (!$config->dataroot && !$config->installer_running) {
			throw new ConfigurationException('Config value "dataroot" is required.');
		}

		if (!$config->cacheroot) {
			$config->cacheroot = $config->dataroot . 'caches';
		}

		if (!$config->assetroot) {
			$config->assetroot = $config->cacheroot . 'views_simplecache';
		}
		
		if (!$config->wwwroot) {
			$config->wwwroot = $this->request->sniffElggUrl();
		}

		if (!$config->plugins_path) {
			$config->plugins_path = Paths::project() . 'mod/';
		}

		$config->path = Paths::project(); // deprecated
		$config->pluginspath = $config->plugins_path; // deprecated alias
		$config->url = $config->wwwroot; // deprecated alias

		// move sensitive credentials into isolated services
		$this->set('dbConfig', DbConfig::fromElggConfig($config));
		$secret = \Elgg\Database\SiteSecret::fromConfig($config);
		if ($secret) {
			$this->set('siteSecret', $secret);
		} else {
			$this->set('siteSecret', function(ContainerInterface $c) {
				return \Elgg\Database\SiteSecret::fromDatabase($c->configTable);
			});
		}

		// get this stuff out of config!
		foreach ($config::SENSITIVE_PROPERTIES as $name) {
			unset($config->{$name});
		}

		return $config;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public static function factory(array $options = []) {
		$container = parent::factory($options);
		
		if (isset($options['config'])) {
			$config = $options['config'];
			$container->set('config', function(ContainerInterface $c) use ($config) {
				return $c->initConfig($config);
			});
		}
		
		return $container;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public static function getDefinitionSources(): array {
		return [\Elgg\Project\Paths::elgg() . 'engine/internal_services.php'];
	}
}
