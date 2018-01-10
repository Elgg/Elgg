<?php
/**
 * Note: We use PhpDiResolver instead of Closures so they can be cached
 */

use Elgg\Di\PhpDiResolver;
use Elgg\Menu\Service as MenuService;
use Elgg\Views\TableColumn\ColumnFactory;

return [
	MenuService::class => new PhpDiResolver(MenuService::class, 'menus'),
	ColumnFactory::class => new PhpDiResolver(ColumnFactory::class, 'table_columns'),
];
