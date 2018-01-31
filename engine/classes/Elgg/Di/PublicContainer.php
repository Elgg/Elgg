<?php

namespace Elgg\Di;

use DI\Container;
use Elgg\Application\Database;
use Elgg\Menu\Service;
use Elgg\Views\TableColumn\ColumnFactory;

/**
 * Public service container
 *
 * @property-read Database      $db            Public database
 * @property-read Service       $menus         Menus
 * @property-read ColumnFactory $table_columns Table columns
 */
class PublicContainer extends Container {

	/**
	 * {@inheritdoc}
	 */
	public function __get($name) {
		return $this->get($name);
	}
}
