<?php

namespace Elgg\Di;

use DI\Container;
use Elgg\Application\Database;
use Elgg\Config;
use Elgg\EventsService;
use Elgg\Filesystem\MimeTypeService;
use Elgg\Forms\FieldsService;
use Elgg\Gatekeeper;
use Elgg\Groups\Tools;
use Elgg\I18n\LocaleService;
use Elgg\I18n\Translator;
use Elgg\Logger;
use Elgg\Menu\Service;
use Elgg\PluginHooksService;
use Elgg\Security\Csrf;
use Elgg\SystemMessagesService;
use Elgg\Users\Accounts;
use Elgg\Views\HtmlFormatter;
use Elgg\Views\TableColumn\ColumnFactory;
use ElggSession;

/**
 * Public service container
 *
 * @property-read Accounts              $accounts        User accounts service
 * @property-read Config                $config          Config
 * @property-read Csrf                  $csrf            CSRF protection
 * @property-read Database              $db              Public database
 * @property-read EventsService         $events          Event service
 * @property-read FieldsService         $fields          Fields service
 * @property-read Gatekeeper            $gatekeeper      Gatekeeper
 * @property-read Tools                 $group_tools     Group Tools
 * @property-read HtmlFormatter         $html_formatter  HTML formatter
 * @property-read PluginHooksService    $hooks           Hooks service
 * @property-read LocaleService         $locale          LocaleService
 * @property-read Logger                $logger          Logger
 * @property-read Service               $menus           Menus
 * @property-read MimeTypeService       $mimetype        MIME type detection
 * @property-read ElggSession           $session         Session
 * @property-read SystemMessagesService $system_messages System messages
 * @property-read ColumnFactory         $table_columns   Table columns
 * @property-read Translator            $translator      Translator
 *
 * @method string echo (string $message_key, array $args = [], string $language = null) Outputs a translated string
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
