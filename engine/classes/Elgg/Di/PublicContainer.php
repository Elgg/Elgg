<?php

namespace Elgg\Di;

/**
 * Public service container
 *
 * @property-read \Elgg\Users\Accounts                  $accounts         User accounts service
 * @property-read \Elgg\Comments\ThreadPreloaderService $thread_preloader Comments thread preloader
 * @property-read \Elgg\Config                          $config           Config
 * @property-read \Elgg\Security\Csrf                   $csrf             CSRF protection
 * @property-read \Elgg\Application\Database            $db               Public database
 * @property-read \Elgg\EventsService                   $events           Event service
 * @property-read \Elgg\Forms\FieldsService             $fields           Fields service
 * @property-read \Elgg\Gatekeeper                      $gatekeeper       Gatekeeper
 * @property-read \Elgg\Groups\Tools                    $group_tools      Group Tools
 * @property-read \Elgg\Views\HtmlFormatter             $html_formatter   HTML formatter
 * @property-read \Elgg\I18n\LocaleService              $locale           LocaleService
 * @property-read \Elgg\Logger                          $logger           Logger
 * @property-read \Elgg\Menu\Service                    $menus            Menus
 * @property-read \Elgg\Filesystem\MimeTypeService      $mimetype         MIME type detection
 * @property-read \ElggSession                          $session          Session
 * @property-read \Elgg\SessionManagerService           $session_manager  Session manager
 * @property-read \Elgg\SystemMessagesService           $system_messages  System messages
 * @property-read \Elgg\Views\TableColumn\ColumnFactory $table_columns    Table columns
 * @property-read \Elgg\I18n\Translator                 $translator       Translator
 */
class PublicContainer extends DiContainer {
	
	/**
	 * {@inheritDoc}
	 */
	public static function getDefinitionSources(): array {
		return [\Elgg\Project\Paths::elgg() . 'engine/public_services.php'];
	}
}
