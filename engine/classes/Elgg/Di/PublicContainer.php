<?php

namespace Elgg\Di;

use DI\Container;
use Elgg\Application\Database;
use Elgg\Gatekeeper;
use Elgg\I18n\Translator;
use Elgg\Menu\Service;
use Elgg\Views\TableColumn\ColumnFactory;
use ElggSession;

/**
 * Public service container
 *
 * @property-read Database      $db            Public database
 * @property-read Gatekeeper    $gatekeeper    Gatekeeper
 * @property-read Service       $menus         Menus
 * @property-read ElggSession   $session       Session
 * @property-read ColumnFactory $table_columns Table columns
 * @property-read Translator    $translator    Translator
 *
 * @method string echo(string $message_key, array $args, string $language) Outputs a translated string
 */
class PublicContainer extends Container {

	/**
	 * {@inheritdoc}
	 */
	public function __get($name) {
		return $this->get($name);
	}

	/**
	 * {@inheritdoc}
	 */
	public function __call($name, $arguments) {
		$proxies = [
			'echo' => ['translator', 'translate'],
		];

		if (!empty($proxies[$name])) {
			$svc = $proxies[$name][0];
			$method = $proxies[$name][1];
			return call_user_func_array([$this->$svc, $method], $arguments);
		}
	}
}
