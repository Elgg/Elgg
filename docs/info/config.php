<?php
/**
 * Stub info for $CONFIG global options.
 *
 * @tip Plugins should never use the $CONFIG array directly.
 *
 * @package    Elgg.Core
 * @subpackage Configuration
 */

/**
 * Paths to scan for autoloading languages.
 *
 * Languages are automatically loaded for the site or
 * user's default language.  Plugins can extend or override strings.
 * language_paths is an array of paths to scan for PHP files matching
 * the default language.  The order of paths is determined by the plugin load order,
 * with later entries overriding earlier.  Language files within these paths are
 * named as the two-letter ISO 639-1 country codes for the language they represent.
 *
 * Language paths are stored as array keys in the format:
 * <code>
 * $CONFIG->language_paths[str $language_path] = true
 * </code>
 *
 * @link http://en.wikipedia.org/wiki/ISO_639-1
 * @see register_language()
 * @global array $CONFIG->language_paths
 */
$CONFIG->language_paths;


/**
 * String translations for the current language.
 *
 * Elgg uses a key-based system for string internationalization, which
 * is accessed with {@link elgg_echo()}.
 *
 * Translations are stored as an array in the following format:
 * <code>
 * $CONFIG->translations[str $language_code][str $string_key] = str 'Translated Language String';
 * </code>
 *
 * @see register_translation()
 * @see elgg_echo()
 * @global array $CONFIG->translations
 */
$CONFIG->translations;

/**
 * An array of metadata names to be used as tags.
 *
 * Because tags are simply names of meatdata, This is used
 * in search to prevent data exposure by searching on
 * arbitrary metadata.
 *
 * @global array $CONFIG->registered_tag_metadata_names
 */
$CONFIG->registered_tag_metadata_names;

/**
 * The full path where Elgg is installed.
 *
 * @global string $CONFIG->path;
 */
$CONFIG->path;

/**
 * The full path for core views.
 *
 * @global string $CONFIG->viewpath
 */
$CONFIG->viewpath;

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
 * The guid of the current site object.
 *
 * @global int $CONFIG->site_guid
 */
$CONFIG->site_guid;

/**
 * Copy of $CONFIG->site_guid
 *
 * @global int $CONFIG->site_id
 */
$CONFIG->site_id;

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
 * @global string $CONFIG->simplecache_enabled
 */
$CONFIG->simplecache_enabled;

/**
 * Is the system cache enabled
 *
 * @global string $CONFIG->system_cache_enabled
 */
$CONFIG->system_cache_enabled;

/**
 * The site description from the current site object.
 *
 * @global string $CONFIG->sitedescription
 */
$CONFIG->sitedescription;

/**
 * The site email from the current site object.
 *
 * @global string $CONFIG->siteemail
 */
$CONFIG->siteemail;

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
 * Holds information about views.
 *
 * @global object $CONFIG->views
 */
$CONFIG->views;

/**
 * A list of views to cache in the simple cache.
 *
 * @global object $CONFIG->views->simplecache
 */
$CONFIG->views->simplecache;

/**
 * A list of views and the top level views directory to search for the view in.
 *
 * @note Views are stored as the key and the top level view location is stored as the value.
 * The current viewtype {@link $CONFIG->view} is used to determin which directory under the entry
 * in $CONFIG->views->location to search.  View names are automatically appened a '.php' extension.
 *
 * @global object $CONFIG->views->locations
 */
$CONFIG->views->locations;


/**
 * A list of valid view types as discovered.
 *
 * @global array $CONFIG->view_types
 */
$CONFIG->view_types;

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
 * A list of entity types and subtypes that have metadata whose access permission
 * can be changed independently of the main object.  {@link register_metadata_as_indepenent()}
 *
 * @global string $CONFIG->independents
 */
$CONFIG->independents;

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
 * A list of stop works for search.  Not currently used.
 *
 * @global array $CONFIG->wordblacklist
 * @todo currently unused.
 */
$CONFIG->wordblacklist;

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
 * Holds available group tools options.  Added with {@link add_group_tool_option()}
 *
 * @global array $CONFIG->group_tool_options
 */
$CONFIG->group_tool_options;

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