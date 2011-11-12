<?php
/**
 * Stub info for $CONFIG global options.
 *
 * @tip Plugins should never use the $CONFIG array directly.
 *
 * @package Elgg.Core
 * @subpackage Configuration
 */

/**
 * Event information for the events subsystem.
 *
 * Events are added with {@link elgg_register_event_handler()} and
 * can be removed in >= 1.8 with {@link elgg_unregister_event_handler()}.
 *
 * Events are stored as a multidimensional array in the format:
 * <code>
 * $CONFIG->events[str $event_name][str $event_type][int priority] = str callback_function
 * </code>
 *
 * @global array $CONFIG->events
 * @name $CONFIG->events
 * @see events()
 * @see elgg_register_event_handler()
 * @see elgg_unregister_event_handler()
 * @see elgg_trigger_event()
 */
$CONFIG->events;

/**
 * Plugin Hook information for the plugin hooks subsystem.
 *
 * Hooks are added with {@link elgg_register_plugin_hook_handler()} and
 * can be removed in >= 1.8 with {@link elgg_unregister_plugin_hook_handler()}.
 *
 * Hooks are stored as a multidimensional array in the format:
 * <code>
 * $CONFIG->hooks[str $hook_name][str $hook_type][int priority] = str callback_function
 * </code>
 *
 * @global array $CONFIG->hooks
 * @see elgg_register_plugin_hook_handler()
 * @see elgg_unregister_plugin_hook_handler()
 * @see elgg_trigger_plugin_hook()
 */
$CONFIG->hooks;

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
 * Stores input used by {@link set_input()} and {@link get_input()}.
 *
 * @global array $CONFIG->input
 */
$CONFIG->input;

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
 * An associative array of page handlers and their function names.
 *
 * Page handlers must be registered by {@link elgg_register_page_handler()} and
 * will be dispatched by {@link engine/handlers/pagehandler.php} to the
 * proper function.
 *
 *  @global array $CONFIG->pagehandler
 */
$CONFIG->pagehandler;

/**
 * An object holding valid widgets and their configurations.
 *
 * This object stores the valid context for widgets, and the handlers
 * registered, as well as a description of the widget.
 *
 * Widgets are added with {@link add_widget_type()}.
 *
 *  @global stdClass $CONFIG->widgets
 */
$CONFIG->widgets;

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
 * @global int $CONFIG->site_id
 */
$CONFIG->site_id;

/**
 * The guid of the current site object.
 *
 * @global int $CONFIG->site_id
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
 * @global string $CONFIG->simplecache_enabled
 */
$CONFIG->simplecache_enabled;

/**
 * Is view paths cache enabled
 *
 * @global string $CONFIG->viewpath_cache_enabled
 */
$CONFIG->viewpath_cache_enabled;

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
 * A list of registered actions, their file locations, and access permissions.
 *
 * @global array $CONFIG->actions
 */
$CONFIG->actions;

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
 * Holds URL handler information for ElggExtender objects.
 *
 * @global array $CONFIG->extender_url_handler
 */
$CONFIG->extender_url_handler;

/**
 * A list of registered entities and subtypes.  Used in search.
 *
 * @global array $CONFIG->registered_entities
 */
$CONFIG->registered_entities;

/**
 * A list of URL handlers for {@link ElggEntity::getURL()}
 *
 * @global array $CONFIG->entity_url_handler
 */
$CONFIG->entity_url_handler;

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