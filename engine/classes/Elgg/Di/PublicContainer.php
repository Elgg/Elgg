<?php

namespace Elgg\Di;

use DI\Container;
use Elgg\Application\Database;
use Elgg\Gatekeeper;
use Elgg\Menu\Service;
use Elgg\Views\TableColumn\ColumnFactory;
use ElggSession;

/**
 * Public service container
 *
 * @property-read Database      $db            Public database
 * @property-read Gatekeeper    $gatekeeper    Gatekeeper
 * @property-read Service       $menus         Menus
 * @property-read ElggSession  $session       Session
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
