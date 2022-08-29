<?php

use Psr\Container\ContainerInterface;

return [
	'autoloadManager' => DI\autowire(\Elgg\AutoloadManager::class)->constructorParameter('cache', DI\get('localFileCache')),
	'accessCache' => DI\factory(function (ContainerInterface $c) {
		return $c->sessionCache->access;
    }),
	'accessCollections' => DI\autowire(\Elgg\Database\AccessCollections::class)->constructorParameter('cache', DI\get('accessCache')),
	'actions' => DI\autowire(\Elgg\ActionsService::class),
	'accounts' => DI\autowire(\Elgg\Users\Accounts::class),
	'adminNotices' => DI\autowire(\Elgg\Database\AdminNotices::class),
	'ajax' => DI\autowire(\Elgg\Ajax\Service::class),
	'amdConfig' => DI\factory(function (ContainerInterface $c) {
		$obj = new \Elgg\Amd\Config($c->hooks);
		$obj->setBaseUrl($c->simpleCache->getRoot());
		return $obj;
	}),
	'annotationsTable' => DI\autowire(\Elgg\Database\AnnotationsTable::class),
	'apiUsersTable' => DI\autowire(\Elgg\Database\ApiUsersTable::class),
	'authentication' => DI\autowire(\Elgg\AuthenticationService::class),
	'autoP' => DI\autowire(\ElggAutoP::class),
	'boot' => DI\autowire(\Elgg\BootService::class)->constructorParameter('cache', DI\get('bootCache')),
	'bootCache' => DI\factory(function (ContainerInterface $c) {
		$flags = ELGG_CACHE_PERSISTENT | ELGG_CACHE_FILESYSTEM | ELGG_CACHE_RUNTIME;
		return new \Elgg\Cache\CompositeCache('elgg_boot', $c->config, $flags);
    }),
	'cacheHandler' => DI\autowire(\Elgg\Application\CacheHandler::class),
	'cssCompiler' => DI\autowire(\Elgg\Assets\CssCompiler::class),
	'csrf' => DI\autowire(\Elgg\Security\Csrf::class),
	'classLoader' => DI\autowire(\Elgg\ClassLoader::class),
	'cli' => DI\autowire(\Elgg\Cli::class)->constructorParameter('input', DI\get('cli_input'))->constructorParameter('output', DI\get('cli_output')),
	'cli_input' => DI\factory(function (ContainerInterface $c) {
		return \Elgg\Application::getStdIn();
    }),
	'cli_output' => DI\factory(function (ContainerInterface $c) {
		return \Elgg\Application::getStdOut();
    }),
	'cli_progress' => DI\autowire(\Elgg\Cli\Progress::class)->constructorParameter('output', DI\get('cli_output')),
	// the 'config' service is available but is set as part of the construction of the application
	'configTable' => DI\autowire(\Elgg\Database\ConfigTable::class),
	'cron' => DI\autowire(\Elgg\Cron::class),
	'crypto' => DI\autowire(\Elgg\Security\Crypto::class),
	'dataCache' => DI\autowire(\Elgg\Cache\DataCache::class),
	'db' => DI\autowire(\Elgg\Database::class),
	// the 'dbConfig' service is available but is set as part of the construction of the application
	'delayedEmailQueueTable' => DI\autowire(\Elgg\Database\DelayedEmailQueueTable::class),
	'delayedEmailService' => DI\autowire(\Elgg\Email\DelayedEmailService::class),
	'emails' => DI\autowire(\Elgg\EmailService::class)->constructorParameter('mailer', DI\get('mailer')),
	'entityCache' => DI\factory(function (ContainerInterface $c) {
		return new \Elgg\Cache\EntityCache($c->session, $c->sessionCache->entities);
    }),
	'entity_capabilities' => DI\autowire(\Elgg\EntityCapabilitiesService::class),
	'entityPreloader' => DI\autowire(\Elgg\EntityPreloader::class),
	'entityTable' => DI\autowire(\Elgg\Database\EntityTable::class),
	'events' => DI\autowire(\Elgg\EventsService::class),
	'externalFiles' => DI\autowire(\Elgg\Assets\ExternalFiles::class)->constructorParameter('serverCache', DI\get('serverCache')),
	'fields' => DI\autowire(\Elgg\Forms\FieldsService::class),
	'forms' => DI\autowire(\Elgg\FormsService::class),
	'fileCache' => DI\factory(function (ContainerInterface $c) {
		$flags = ELGG_CACHE_PERSISTENT | ELGG_CACHE_FILESYSTEM | ELGG_CACHE_RUNTIME;
		return new \Elgg\Cache\CompositeCache('elgg_system_cache', $c->config, $flags);
    }),
	'filestore' => DI\factory(function (ContainerInterface $c) {
		return new \ElggDiskFilestore($c->config->dataroot);
    }),
	'gatekeeper' => DI\autowire(\Elgg\Gatekeeper::class),
	'group_tools' => DI\autowire(\Elgg\Groups\Tools::class),
	'handlers' => DI\autowire(\Elgg\HandlersService::class),
	'hmac' => DI\autowire(\Elgg\Security\HmacFactory::class),
	'hmacCacheTable' => DI\autowire(\Elgg\Database\HMACCacheTable::class),
	'html_formatter' => DI\autowire(\Elgg\Views\HtmlFormatter::class),
	'hooks' => DI\autowire(\Elgg\PluginHooksService::class),
	'iconService' => DI\autowire(\Elgg\EntityIconService::class),
	'imageFetcher' => DI\autowire(\Elgg\Assets\ImageFetcherService::class),
	'imageService' => DI\autowire(\Elgg\ImageService::class),
	'invoker' => DI\factory(function (ContainerInterface $c) {
		return new \Elgg\Invoker($c->session, elgg());
    }),
	'locale' => DI\autowire(\Elgg\I18n\LocaleService::class),
	'localFileCache' => DI\factory(function (ContainerInterface $c) {
		$flags = ELGG_CACHE_LOCALFILESYSTEM | ELGG_CACHE_RUNTIME;
		return new \Elgg\Cache\CompositeCache('elgg_local_system_cache', $c->config, $flags);
    }),
	'logger' => DI\factory(function (ContainerInterface $c) {
		$logger = \Elgg\Logger::factory($c->cli_input, $c->cli_output);

		$logger->setLevel($c->config->debug);

		return $logger;
    }),
	'mailer' => DI\factory(function (ContainerInterface $c) {
		switch ($c->config->emailer_transport) {
			case 'smtp':
				$transport = new \Laminas\Mail\Transport\Smtp();
				$transportOptions = new \Laminas\Mail\Transport\SmtpOptions(
					$c->config->emailer_smtp_settings
				);
				$transport->setOptions($transportOptions);
				return $transport;
			default:
				return new \Laminas\Mail\Transport\Sendmail($c->config->emailer_sendmail_settings);
		}
    }),
	'menus' => DI\autowire(\Elgg\Menu\Service::class),
	'metadataCache' => DI\factory(function (ContainerInterface $c) {
		$cache = $c->dataCache->metadata;
		return new \Elgg\Cache\MetadataCache($cache);
    }),
	'metadataTable' => DI\autowire(\Elgg\Database\MetadataTable::class),
	'mimetype' => DI\autowire(\Elgg\Filesystem\MimeTypeService::class),
	'mutex' => DI\autowire(\Elgg\Database\Mutex::class),
	'notifications' => DI\autowire(\Elgg\Notifications\NotificationsService::class)->constructorParameter('queue', DI\get('notificationsQueue')),
	'notificationsQueue' => DI\autowire(\Elgg\Notifications\NotificationsQueue::class)->constructorParameter('name', \Elgg\Notifications\NotificationsQueue::QUEUE_NAME),
	'pageOwner' => DI\autowire(\Elgg\Page\PageOwnerService::class),
	'passwords' => DI\autowire(\Elgg\PasswordService::class),
	'passwordGenerator' => DI\autowire(\Elgg\Security\PasswordGeneratorService::class),
	'persistentLogin' => DI\autowire(\Elgg\PersistentLoginService::class),
	'plugins' => DI\autowire(\Elgg\Database\Plugins::class)->constructorParameter('cache', DI\get('pluginsCache')),
	'pluginsCache' => DI\factory(function (ContainerInterface $c) {
		return new \Elgg\Cache\CompositeCache('plugins', $c->config, ELGG_CACHE_RUNTIME);
    }),
	'publicDb' => DI\autowire(\Elgg\Application\Database::class),
	'queryCache' => DI\factory(function (ContainerInterface $c) {
		$config_disabled = $c->config->db_disable_query_cache === true;
		return new \Elgg\Cache\QueryCache($c->config->db_query_cache_limit, $config_disabled);
    }),
	'redirects' => DI\autowire(\Elgg\RedirectService::class),
	'relationshipsTable' => DI\autowire(\Elgg\Database\RelationshipsTable::class),
	'request' => DI\factory(function (ContainerInterface $c) {
		global $CONFIG;
		$request = \Elgg\Http\Request::createFromGlobals();
			
		// not using config service because of deadloop issues (request is used during config construction)
		// global $CONFIG is not set during installer
		if ($CONFIG instanceof \Elgg\Config) {
			$request->initializeTrustedProxyConfiguration($CONFIG);
			$request->correctBaseURL($CONFIG);
		}
		
		return $request;
    }),
	'requestContext' => DI\factory(function (ContainerInterface $c) {
		$context = new \Elgg\Router\RequestContext();
		return $context->fromRequest($c->request);
    }),
	'responseFactory' => DI\autowire(\Elgg\Http\ResponseFactory::class),
	'riverTable' => DI\autowire(\Elgg\Database\RiverTable::class),
	'routeCollection' => DI\autowire(\Elgg\Router\RouteCollection::class),
	'routes' => DI\autowire(\Elgg\Router\RouteRegistrationService::class),
	'router' => DI\autowire(\Elgg\Router::class),
	'search' => DI\autowire(\Elgg\Search\SearchService::class),
	'seeder' => DI\autowire(\Elgg\Database\Seeder::class),
	'serveFileHandler' => DI\autowire(\Elgg\Application\ServeFileHandler::class),
	'session' => DI\factory(function (ContainerInterface $c) {
        return \ElggSession::fromDatabase($c->config, $c->db);
    }),
	'sessionCache' => DI\autowire(\Elgg\Cache\SessionCache::class),
	'simpleCache' => DI\autowire(\Elgg\Cache\SimpleCache::class),
	// siteSecret definition is created during initConfig of \Elgg\Di\InternalContainer
	'stickyForms' => DI\autowire(\Elgg\Forms\StickyForms::class),
	'systemCache' => DI\autowire(\Elgg\Cache\SystemCache::class)->constructorParameter('cache', DI\get('fileCache')),
	'serverCache' => DI\autowire(\Elgg\Cache\SystemCache::class)->constructorParameter('cache', DI\get('localFileCache')),
	'subscriptions' => DI\autowire(\Elgg\Notifications\SubscriptionsService::class),
	'system_messages' => DI\autowire(\Elgg\SystemMessagesService::class),
	'table_columns' => DI\autowire(\Elgg\Views\TableColumn\ColumnFactory::class),
	'temp_filestore' => DI\autowire(\ElggTempDiskFilestore::class),
	'timer' => DI\autowire(\Elgg\Timer::class),
	'translator' => DI\autowire(\Elgg\I18n\Translator::class),
	'uploads' => DI\autowire(\Elgg\UploadService::class),
	'upgrades' => DI\autowire(\Elgg\UpgradeService::class),
	'urlGenerator' => DI\factory(function (ContainerInterface $c) {
        return new \Elgg\Router\UrlGenerator($c->routeCollection, $c->requestContext);
    }),
	'urlMatcher' => DI\factory(function (ContainerInterface $c) {
        return new \Elgg\Router\UrlMatcher($c->routeCollection, $c->requestContext);
    }),
	'urlSigner' => DI\autowire(\Elgg\Security\UrlSigner::class),
	'urls' => DI\autowire(\Elgg\Http\Urls::class),
	'userCapabilities' => DI\autowire(\Elgg\UserCapabilities::class),
	'usersApiSessionsTable' => DI\autowire(\Elgg\Database\UsersApiSessionsTable::class),
	'users_remember_me_cookies_table' => DI\autowire(\Elgg\Database\UsersRememberMeCookiesTable::class),
	'usersTable' => DI\autowire(\Elgg\Database\UsersTable::class),
	'upgradeLocator' => DI\autowire(\Elgg\Upgrade\Locator::class),
	'views' => DI\autowire(\Elgg\ViewsService::class),
	'viewCacher' => DI\autowire(\Elgg\Cache\ViewCacher::class),
	'widgets' => DI\autowire(\Elgg\WidgetsService::class),
	
	// map classes to alias to allow autowiring
	\ElggAutoP::class => DI\get('autoP'),
	\ElggDiskFilestore::class => DI\get('filestore'),
	\ElggSession::class => DI\get('session'),
	\ElggTempDiskFilestore::class => DI\get('temp_filestore'),
	\Elgg\ActionsService::class => DI\get('actions'),
	\Elgg\Ajax\Service::class => DI\get('ajax'),
	\Elgg\Amd\Config::class => DI\get('amdConfig'),
	\Elgg\Application\CacheHandler::class => DI\get('cacheHandler'),
	\Elgg\Application\Database::class => DI\get('publicDb'),
	\Elgg\Application\ServeFileHandler::class => DI\get('serveFileHandler'),
	\Elgg\Assets\CssCompiler::class => DI\get('cssCompiler'),
	\Elgg\Assets\ExternalFiles::class => DI\get('externalFiles'),
	\Elgg\Assets\ImageFetcherService::class => DI\get('imageFetcher'),
	\Elgg\AuthenticationService::class => DI\get('authentication'),
	\Elgg\AutoloadManager::class => DI\get('autoloadManager'),
	\Elgg\BootService::class => DI\get('boot'),
	\Elgg\Cache\DataCache::class => DI\get('dataCache'),
	\Elgg\Cache\EntityCache::class => DI\get('entityCache'),
	\Elgg\Cache\MetadataCache::class => DI\get('metadataCache'),
	\Elgg\Cache\QueryCache::class => DI\get('queryCache'),
	\Elgg\Cache\SessionCache::class => DI\get('sessionCache'),
	\Elgg\Cache\SimpleCache::class => DI\get('simpleCache'),
	\Elgg\Cache\SystemCache::class => DI\get('systemCache'),
	\Elgg\Cache\ViewCacher::class => DI\get('viewCacher'),
	\Elgg\ClassLoader::class => DI\get('classLoader'),
	\Elgg\Cli::class => DI\get('cli'),
	\Elgg\Cli\Progress::class => DI\get('cli_progress'),
	\Elgg\Config::class => DI\get('config'),
	\Elgg\Cron::class => DI\get('cron'),
	\Elgg\Database::class => DI\get('db'),
	\Elgg\Database\AccessCollections::class => DI\get('accessCollections'),
	\Elgg\Database\AdminNotices::class => DI\get('adminNotices'),
	\Elgg\Database\AnnotationsTable::class => DI\get('annotationsTable'),
	\Elgg\Database\ApiUsersTable::class => DI\get('apiUsersTable'),
	\Elgg\Database\ConfigTable::class => DI\get('configTable'),
	\Elgg\Database\DbConfig::class => DI\get('dbConfig'),
	\Elgg\Database\DelayedEmailQueueTable::class => DI\get('delayedEmailQueueTable'),
	\Elgg\Database\EntityTable::class => DI\get('entityTable'),
	\Elgg\Database\HMACCacheTable::class => DI\get('hmacCacheTable'),
	\Elgg\Database\MetadataTable::class => DI\get('metadataTable'),
	\Elgg\Database\Mutex::class => DI\get('mutex'),
	\Elgg\Database\Plugins::class => DI\get('plugins'),
	\Elgg\Database\RelationshipsTable::class => DI\get('relationshipsTable'),
	\Elgg\Database\RiverTable::class => DI\get('riverTable'),
	\Elgg\Database\Seeder::class => DI\get('seeder'),
	\Elgg\Database\SiteSecret::class => DI\get('siteSecret'),
	\Elgg\Database\UsersApiSessionsTable::class => DI\get('usersApiSessionsTable'),
	\Elgg\Database\UsersRememberMeCookiesTable::class => DI\get('users_remember_me_cookies_table'),
	\Elgg\Database\UsersTable::class => DI\get('usersTable'),
	\Elgg\EntityPreloader::class => DI\get('entityPreloader'),
	\Elgg\EmailService::class => DI\get('emails'),
	\Elgg\Email\DelayedEmailService::class => DI\get('delayedEmailService'),
	\Elgg\EntityIconService::class => DI\get('iconService'),
	\Elgg\EventsService::class => DI\get('events'),
	\Elgg\Filesystem\MimeTypeService::class => DI\get('mimetype'),
	\Elgg\FormsService::class => DI\get('forms'),
	\Elgg\Forms\FieldsService::class => DI\get('fields'),
	\Elgg\Forms\StickyForms::class => DI\get('stickyForms'),
	\Elgg\Gatekeeper::class => DI\get('gatekeeper'),
	\Elgg\Groups\Tools::class => DI\get('group_tools'),
	\Elgg\HandlersService::class => DI\get('handlers'),
	\Elgg\Http\Request::class => DI\get('request'),
	\Elgg\Http\ResponseFactory::class => DI\get('responseFactory'),
	\Elgg\Http\Urls::class => DI\get('urls'),
	\Elgg\I18n\LocaleService::class => DI\get('locale'),
	\Elgg\I18n\Translator::class => DI\get('translator'),
	\Elgg\ImageService::class => DI\get('imageService'),
	\Elgg\Invoker::class => DI\get('invoker'),
	\Elgg\Logger::class => DI\get('logger'),
	\Elgg\Menu\Service::class => DI\get('menus'),
	\Elgg\Notifications\NotificationsService::class => DI\get('notifications'),
	\Elgg\Notifications\NotificationsQueue::class => DI\get('notificationsQueue'),
	\Elgg\Notifications\SubscriptionsService::class => DI\get('subscriptions'),
	\Elgg\Page\PageOwnerService::class => DI\get('pageOwner'),
	\Elgg\PasswordService::class => DI\get('passwords'),
	\Elgg\PersistentLoginService::class => DI\get('persistentLogin'),
	\Elgg\PluginHooksService::class => DI\get('hooks'),
	\Elgg\RedirectService::class => DI\get('redirects'),
	\Elgg\Router::class => DI\get('router'),
	\Elgg\Router\RequestContext::class => DI\get('requestContext'),
	\Elgg\Router\RouteCollection::class => DI\get('routeCollection'),
	\Elgg\Router\RouteRegistrationService::class => DI\get('routes'),
	\Elgg\Router\UrlGenerator::class => DI\get('urlGenerator'),
	\Elgg\Router\UrlMatcher::class => DI\get('urlMatcher'),
	\Elgg\Search\SearchService::class => DI\get('search'),
	\Elgg\Security\Crypto::class => DI\get('crypto'),
	\Elgg\Security\Csrf::class => DI\get('csrf'),
	\Elgg\Security\HmacFactory::class => DI\get('hmac'),
	\Elgg\Security\PasswordGeneratorService::class => DI\get('passwordGenerator'),
	\Elgg\Security\UrlSigner::class => DI\get('urlSigner'),
	\Elgg\SystemMessagesService::class => DI\get('system_messages'),
	\Elgg\Timer::class => DI\get('timer'),
	\Elgg\UpgradeService::class => DI\get('upgrades'),
	\Elgg\Upgrade\Locator::class => DI\get('upgradeLocator'),
	\Elgg\UploadService::class => DI\get('uploads'),
	\Elgg\UserCapabilities::class => DI\get('userCapabilities'),
	\Elgg\Users\Accounts::class => DI\get('accounts'),
	\Elgg\ViewsService::class => DI\get('views'),
	\Elgg\Views\HtmlFormatter::class => DI\get('html_formatter'),
	\Elgg\Views\TableColumn\ColumnFactory::class => DI\get('table_columns'),
	\Elgg\WidgetsService::class => DI\get('widgets'),
];
