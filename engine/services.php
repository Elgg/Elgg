<?php
/**
 * Note: We use PhpDiResolver instead of Closures so they can be cached
 */

use Elgg\Application\Database;
use Elgg\Di\PhpDiResolver;
use Elgg\EventsService;
use Elgg\Gatekeeper;
use Elgg\I18n\Translator;
use Elgg\Menu\Service as MenuService;
use Elgg\PluginHooksService;
use Elgg\Views\TableColumn\ColumnFactory;

return [
	'db' => new PhpDiResolver(Database::class, 'publicDb'),
	'events' => new PhpDiResolver(EventsService::class, 'events'),
	'gatekeeper' => new PhpDiResolver(Gatekeeper::class, 'gatekeeper'),
	'hooks' => new PhpDiResolver(PluginHooksService::class, 'hooks'),
	'menus' => new PhpDiResolver(MenuService::class, 'menus'),
	'session' => new PhpDiResolver(ElggSession::class, 'session'),
	'table_columns' => new PhpDiResolver(ColumnFactory::class, 'table_columns'),
	'translator' => new PhpDiResolver(Translator::class, 'translator'),
];
