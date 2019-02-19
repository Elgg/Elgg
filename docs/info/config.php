<?php
/**
 * Legacy documentation for the old $CONFIG object. In Elgg 3.0 this is a reference to
 * the Config service.
 *
 * @warning DO NOT USE $CONFIG except in a legacy settings.php file. Note that it will be unset
 *          by the boot process after reading.
 */

/**
 * The full path where Elgg is installed.
 *
 * This is set in \Elgg\Config::__construct
 *
 * @global string $CONFIG->path;
 */
$CONFIG->path;

/**
 * The full path where plugins are stored.
 *
 * @global string $CONFIG->pluginspath
 */
$CONFIG->pluginspath;

/**
 * The full URL where Elgg is installed
 *
 * @global string $CONFIG->wwwroot
 */
$CONFIG->wwwroot;

/**
 * The full URL where Elgg is installed
 *
 * @global string $CONFIG->wwwroot
 */
$CONFIG->url;

/**
 * The name of the site as defined in the config table.
 *
 * @global string $CONFIG->sitename
 */
$CONFIG->sitename;

/**
 * The current language for either the site or the user.
 *
 * @global $CONFIG->language
 */
$CONFIG->language;

/**
 * Is the site fully installed
 *
 * @global bool $CONFIG->installed
 */
$CONFIG->installed;

/**
 * The guid of the site object (1)
 *
 * @global int $CONFIG->site_guid
 */
$CONFIG->site_guid;

/**
 * The current site object.
 *
 * @global ElggSite $CONFIG->site
 */
$CONFIG->site;

/**
 * The full path to the data directory.
 *
 * @global string $CONFIG->dataroot
 */
$CONFIG->dataroot;

/**
 * Is simplecache enabled?
 *
 * @see elgg_is_simplecache_enabled() Use this instead of reading the value.
 *
 * @global string $CONFIG->simplecache_enabled
 */
$CONFIG->simplecache_enabled;

/**
 * Is the system cache enabled
 *
 * @see elgg_is_system_cache_enabled() Use this instead of reading the value.
 *
 * @global string $CONFIG->system_cache_enabled
 */
$CONFIG->system_cache_enabled;

/**
 * Are unbootable plugins automatically disabled
 *
 * @see \Elgg\Database\Plugins->load
 *
 * @global string $CONFIG->auto_disable_plugins
 */
$CONFIG->auto_disable_plugins;

/**
 * The site description from the current site object.
 *
 * @global string $CONFIG->sitedescription
 */
$CONFIG->sitedescription;

/**
 * The default "limit" used in site queries.
 *
 * @global int $CONFIG->default_limit
 */
$CONFIG->default_limit;

/**
 * The current view type
 *
 * View types determin the location of view files that are used to draw pages.
 * They are set system-wide by the $_REQUEST['view'].  If a view type is manually
 * set in settings.php or through a function hooking to the {@elgg_hook
 *
 * @warning This is the current view type used to determine where to load views.
 * Don't confuse this with the current view.
 *
 * @global string $CONFIG->view
 */
$CONFIG->view;

/**
 * Default access as defined in the config table for the current site.
 *
 * @global string $CONFIG->default_access
 */
$CONFIG->default_access;

/**
 * Is registration enabled?
 *
 * @global bool $CONFIG->allow_registration
 */
$CONFIG->allow_registration;

/**
 * Is current site in walled garden mode?
 *
 * @global bool $CONFIG->walled_garden
 */
$CONFIG->walled_garden;

/**
 * Are users allow to enter their own default access levels
 *
 * @global bool $CONFIG->allow_user_default_access
 */
$CONFIG->allow_user_default_access;

/**
 * A list of feature URLs for the main site menu.
 *
 * These links are added via the admin interface.
 *
 * @global string $CONFIG->menu_items_featured_urls
 */
$CONFIG->menu_items_featured_urls;

/**
 * The custom menu items entered in the administration.
 *
 * @global string $CONFIG->menu_items_custom_items
 */
$CONFIG->menu_items_custom_items;

/**
 * A list of plugins and their load order
 *
 * @global string $CONFIG->pluginlistcache
 */
$CONFIG->pluginlistcache;

/**
 * A list of registered entities and subtypes.  Used in search.
 *
 * @global array $CONFIG->registered_entities
 */
$CONFIG->registered_entities;

/**
 * Holds items for all submenus.
 *
 * @global string $CONFIG->submenu_items
 */
$CONFIG->submenu_items;

/**
 * Holds the service handlers as registered by {@register_service_handler()}
 *
 * @global array $CONFIG->servicehandler
 */
$CONFIG->servicehandler;

/**
 * A list of menu contexts for menus registered with {@link add_menu()}.  Not currently used.
 *
 * @global array $CONFIG->menucontexts
 */
$CONFIG->menucontexts;

/**
 * A list of registers and their children added via {@add_to_register()}.  Used only for menus.
 *
 * @global string $CONFIG->registers
 */
$CONFIG->registers;

/**
 * A list of objects that can emit notifications.  {@link register_notification_object()}
 *
 * @global array $CONFIG->register_objects
 */
$CONFIG->register_objects;

/**
 * The last cache time for the current viewtype.  Used in the generation of CSS and JS links.
 *
 * @global string $CONFIG->lastcache
 */
$CONFIG->lastcache;

/**
 * This is an optional script used to override Elgg's default handling of
 * uncaught exceptions.
 *
 * This should be an absolute file path to a php script that will be called
 * any time an uncaught exception is thrown.
 *
 * The script will have access to the following variables as part of the scope
 * global $CONFIG
 * $exception - the unhandled exception
 *
 * @warning - the database may not be available
 *
 * @global string $CONFIG->exception_include
 */
$CONFIG->exception_include = '';

/**
 * Maximum php execution time for actions (in seconds)
 *
 * This setting can be used to set a custom php execution time specifically for Elgg actions.
 * Note that if some actions set their own execution time limit, this setting will no affect those actions.
 *
 * @global int $CONFIG->action_time_limit
 */
$CONFIG->action_time_limit;

/**
 * Allow access to PHPInfo
 *
 * This setting can be used to allow site administrators access to the PHPInfo page.
 * By default this is not allowed.
 *
 * @global bool $CONFIG->allow_phpinfo
 */
$CONFIG->allow_phpinfo = false;

/**
 * Plugins with more than the configured number of plugin settings won't be loaded into
 * bootdata cache. This is done to prevent memory issues.
 *
 * If set to < 1 all plugins will be loaded into the bootdata cache
 *
 * Default: 40
 *
 * @global int $CONFIG->bootdata_plugin_settings_limit
 */
$CONFIG->bootdata_plugin_settings_limit;

/**
 * Language to locale mapping
 *
 * Some features support mapping a language to a locale setting (for example date presentations). In this setting
 * the mapping between language (key) and locale setting (values) can be configured.
 *
 * For example if you wish to present English dates in USA format make the mapping 'en' => ['en_US'], or if you
 * wish to use UK format 'en' => ['en_UK'].
 *
 * It's possible to configure the locale mapping for mulitple languages, for example:
 * [
 * 	'en' => ['en_US', 'en_UK'],
 * 	'nl' => ['nl_NL'],
 * ]
 *
 * It's also possible to add new languages to the supported languages:
 * [
 * 	'my_language' => [], // no locale mapping
 * 	'my_language2' => ['en_US'], // using USA locale mapping
 * ]
 *
 * @see https://secure.php.net/manual/en/function.setlocale.php
 *
 * @global array $CONFIG->language_to_locale_mapping
 */
$CONFIG->language_to_locale_mapping;
