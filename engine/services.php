<?php
/**
 * Note: We use PhpDiResolver instead of Closures so they can be cached
 */

use Elgg\Application\Database;
use Elgg\Config;
use Elgg\Di\PhpDiResolver;
use Elgg\EventsService;
use Elgg\Gatekeeper;
use Elgg\I18n\Translator;
use Elgg\Logger;
use Elgg\Menu\Service as MenuService;
use Elgg\PluginHooksService;
use Elgg\Security\Csrf;
use Elgg\SystemMessagesService;
use Elgg\Users\Accounts;
use Elgg\Views\HtmlFormatter;
use Elgg\Views\TableColumn\ColumnFactory;
use Elgg\I18n\LocaleService;

return [
	'accounts' => new PhpDiResolver(Accounts::class, 'accounts'),
	'config' => new PhpDiResolver(Config::class, 'config'),
	'csrf' => new PhpDiResolver(Csrf::class, 'csrf'),
	'db' => new PhpDiResolver(Database::class, 'publicDb'),
	'events' => new PhpDiResolver(EventsService::class, 'events'),
	'gatekeeper' => new PhpDiResolver(Gatekeeper::class, 'gatekeeper'),
	'group_tools' => new PhpDiResolver(\Elgg\Groups\Tools::class, 'group_tools'),
	'html_formatter' => new PhpDiResolver(HtmlFormatter::class, 'html_formatter'),
	'hooks' => new PhpDiResolver(PluginHooksService::class, 'hooks'),
	'locale' => new PhpDiResolver(LocaleService::class, 'localeService'),
	'logger' => new PhpDiResolver(Logger::class, 'logger'),
	'menus' => new PhpDiResolver(MenuService::class, 'menus'),
	'session' => new PhpDiResolver(ElggSession::class, 'session'),
	'system_messages' => new PhpDiResolver(SystemMessagesService::class, 'systemMessages'),
	'table_columns' => new PhpDiResolver(ColumnFactory::class, 'table_columns'),
	'translator' => new PhpDiResolver(Translator::class, 'translator'),
];
