# v1.9.0-dev (2014-01-20)

## Contributing Developers
* Aday Talavera
* Andras Szepeshazi
* Ben Werdmuller
* Brett Profitt
* Cash Costello
* Evan Winslow
* Facyla
* Hayden Shaw
* Hellekin Wolf
* Ismayil Khayredinov
* Janek Lasocki-Biczysko
* Jeff Tilson
* Jeroen Dalsem
* Jerome Bakker
* Juho Jaakkola
* Marcus Povey
* Matt Beckett
* Paweł Sroka
* Per Jensen
* Rasmus Lerdorf
* RiverVanRain
* Sem
* Steve Clay
* Tantek Çelik
* Team Webgalli

## Performance
* Using dataroot and simplecache_enabled if set in settings.php
* Changes simplecache caching so that it is performed on demand
* Adds support for simplecache minification of CSS and JavaScript
* Adds ability to enable the query cache after being disabled
* Don't call getter after a previous count call returned 0 items
* Make sure Apache2 is configured so .ico can be cached
* Adds deflate Apache filter to SVG images
* Log display no longer emit deprecation warnings and uses fewer queries
* speeds up user location upgrade
* Progress toward HHVM compatibility

## UI changes
* Lots of spit and polish
* New responsive theme - aalborg_theme
* Drops support for IE6
* Adds image uploading from editor
* Replaces fancybox lightbox with colorbox
* Replaces Tinymce editor with CKEditor
* Liking and friending use ajax
* Removes topbar Elgg logo and made "powered by" themable
* Allows keeping group content limited to the group
* Site notifications moved into separate plugin from messages
* Shows owner block when viewing own content
* Focus styles for accessible keyboard navigation
* Improved theme sandbox
* Session expired message
* Ajaxified the discussion reply edit form.
* Alphabetize friends/friends-of, group notifications, group owned/member lists
* Added support for greying out the label of disabled input
* Added more microformats to the profile page
* Automatically configure autocorrect and autocapitalize for input views
* Using unified language strings for several plugins
* Adds focus outlines to all focusable elements

## Admin changes
* Adds new notification system
* Makes the wire message length configurable
* Changes user directories use GUIDs rather than join date
* Adds banned user widget
* Adds legacy_url plugin for supporting legacy URLs
* Adds robots.txt configuration
* Adds maintenance mode
* Added automatic configuration of RewriteBase during fresh install.

## New developer features
* HTML5
* New mysql-based async queue
* AMD modules using require.js
* New notification system
* New class loader that is PSR-0 compliant
* Improves control over cookies
* Adds plugin manifest fields (id, php_version, contributors)
* Static files recognized as views
* Adds support for multi-select
* JSON rendered through views system rather than using global
* Links in login box use menu system
* Upgrades jQuery and includes the jquery migrate plugin
* Widgets can set their titles
* New JavaScript unit test library
* Front page and actions go through page handling system
* Group edit form easier to extend
* More specific list item classes
* Page layouts more standardized with same elements
* Allows customizing colorbox instances
* Views system recognizes static files as views in addition to PHP files
* Adds ability to turn off query cache
* Can change time_created if set explicitly
* Allows update event to alter attributes and checks canEdit() on DB copy
* add more specific list item classes
* moved elgg_view_icon html to own view for more flexibility
* Allow body attributes
* Eases extending the input/view view
* Split group edit form into seperate parts
* Moved group_activity widget from dashboard to groups plugin
* Adds warnings for uncallable handlers in hooks/events.
* Members list pages (tabs/content/titles) can now be extended via plugins
* Adds configuration support for remember me cookie

## API changes
* Comments and discussion replies are entities
* New notification system
* Changes elgg_register_widget_type() to expect contexts to be an array
* New session API accessible via elgg_get_session()
* Moves many functions into methods on ElggEntity and related classes
* Adds support for returning translations as arrays from language files
* Adds ElggEntity::getDisplayName()
* Adds ElggEntity::toObject()
* Adds target_guid to the river
* Adds elgg_get_entities_from_attributes()
* Adds ElggMenuItem::addItemClass()
* Adds elgg_get_menu_item()
* Adds elgg_format_element() for creating HTML elements
* ElggFile::getSize() replaces ElggFile::size()
* Defaults to full_view = false in elgg_list_entities* functions
* Allows passing $params to event handlers
* Allows views to be accessed via URL and cacheable
* Columns added to entity query functions are available in returned entities
* Separates some events into :before/:after
* Adds elgg_entity_gatekeeper()
* get_online_users() and find_active_users() now use $options arrays
* Adds default option to elgg_get_plugin_setting
* namespaced the gatekeeper functions (but made it optional)
* Added URL fragment (#anchors) support to elgg_http_build_url
* made elgg_unregister_menu_item() more useful

## New hooks/events
* plugin hook: simple_type, file
* plugin hook: default, access
* plugin hook: login:forward, user
* plugin hook: layout, page
* plugin hook: shell, page
* plugin hook: head, page
* plugin hook: get_sql, access
* plugin hook: maintenance:allow, url
* notifications plugin hooks
* event: init:cookie, name

## Deprecated functionality
* calendar library (was not maintained)
* web services library (now plugin distributed with Elgg)
* export, import, and opendd libraries (see ElggEntity:toObject())
* location library
* xml library
* Split logout event to before/after events
* Split login event to before/after events
* Added a deprecate notice to the elgg_view_icon use of a boolean
* Deprecated get_annotation_url() in favor of ElggAnnotation::getURL()
* Deprecated full_url() in favor of current_page_url()
* Deprecated "class" in ElggMenuItem::factory in favor of "link_class"
* Deprecated passing null to ElggRelationship constructor
* Deprecated .elgg-autofocus in favor of HTML5 autofocus
* Deprecated ElggUser::countObjects (part of Friendable interface)
* Deprecated favicon view in favor of head, page plugin hook
* Deprecated analytics view in favor of page/elements/foot
* Deprecated availability of $vars keys (url, config) and $CONFIG
* Deprecated ElggEntity::get()/set() in favor of property access
* Deprecated cron, reboot event
* Deprecated add_to_river() in favor of elgg_create_river_item()
* Renames many functions to begin with "elgg_" (with deprecated versions)

## Removed functionality
* xml-rpc library (now plugin: https://github.com/Elgg/xml-rpc)
* xml, php, and ical views (now plugin: https://github.com/Elgg/data_views)
* foaf views (now plugin: https://github.com/Elgg/semantic_web)
* Default entity page handler

## Documentation
* Shiny new rST docs (hosted at http://learn.elgg.org)
* Various improvements to source code comments

## Security Enhancements
* Using SSL for setting password when https login enabled
* Make several views files non-executable

## Bugfixes
* HTMLawed Strips html comments and CDATA from input
* Hundreds of miscellaneous fixes
* users can edit metadata that they created by default
* removes special check to allow access override
* if no container, default to false for writing to container
* fixes default user access
* returning false to create events forces delete regardless of access
* Fix json and xml views broken by wrap view of developer tools
* Do not use link with file icon when using full_view.
* made page shells consistent for $vars parameters
* show owner block also if looking at owned pages
* Pagination uses HTTP referrer as default base_url for Ajax requests
* Added several missing translation strings
* standardizes layouts so that they all have title buttons and the same basic sections
* entity list limit respects passed limits and just provides defaults
* fixes setting page owner due to routing change
* Fixed batch install usage of createHtaccess
* fixed typo that prevented context for front page from being set
* Make sure empty string return is interpreted as "handling" the list hook
* replaced double search box with a single box and a single searchhook
* Login, user event code can use elgg_get_logged_in_user_*()
* Make sure user has access to both river object and target
* Uses correct default value for find_active_users 'seconds' parameter
* Added jquery map file and unminified version to make Chrome dev tools happy and not throw 404 error
* Corrects container write permissions bug
* Sends correct Content-Length with profile icon
* Getting correct client IP behind proxy.
* Fixed old function name for batch metastring operations
* allow full access to the metadata API through setMetadata() rather than requiring use of create_metadata()
* catching when the base entity is not created due to permissions override
* message if no results found
* all link should reset entity type/subtype
* forces lastcache to be an int
* Many more miscellaneous improvements...


# v1.8.19 (March 12, 2014)

## Contributing Developers
* Brett Profitt
* Centillien
* Evan Winslow
* Ismayil Khayredinov
* Jerome Bakker
* Juho Jaakkola
* Matt Beckett
* RiverVanRain
* Sem
* Steve Clay
* pattyland

## Security enhancements
* Implements stronger remember me cookie strategy to prevent brute force attacks.

## Bugfixes
* Fixed numerous PHP warnings.
* Groups: Corrected breadcrumb for group discussion pages.
* Fixed RSS validation for the River RSS feed.

## Improvements
* Moved Site Secret update to configure -> advanced.
* Added more comprehensive tests for HTMLAwed.

## Documentation
* Added better deprecation warnings for use of certain attributes in views.


# v1.8.18 (January 11, 2014)

## Contributing Developers
* Juho Jaakkola
* Steve Clay

## Bugfixes
* Fixes notify_user() broken in 1.8.17


# v1.8.17 (January 1, 2014)

## Contributing Developers
* Brett Profitt
* Cash Costello
* Ed Lyons
* Evan Winslow
* Jeroen Dalsem
* Jerome Bakker
* Juho Jaakkola
* Matt Beckett
* Paweł Sroka
* Sem
* Steve Clay

## Security Fixes
* Specially-crafted request could return the contents of sensitive files.
* Reflected XSS attack was possible against 1.8 systems.
* The cryptographic key used for various purposes may have been generated with weak entropy, particularly on Windows.

## Bugfixes
* URLs with non-ASCII usernames again work
* Floated images are now properly cleared in content areas
* The activity page title now matches the document title
* Search again supports multiple comments on the same entity
* Blog archive sidebar now reverse chronological
* URLs with matching parens can now be auto-linked
* Log browser links for users now work
* Disabling over 50 objects should no longer result in an infinite loop
* Radio/checkbox inputs no longer have border radius (for IE10)
* User picker: the Only Friends checkbox again works
* Group bookmarklet no longer shown to non-members
* Widget reordering fixed when moving across columns
* Refuse to deactivate plugins needed as dependencies

## Enhancements
* Group member listings are ordered by name
* The system_log table can now store IPv6 addresses
* Web services auth_gettoken() now accepts email address
* List functions: no need to specify pagination for unlimited queries
* Htmlawed was upgraded to 1.1.16


# v1.8.16 (June 25, 2013)

## Contributing Developers
* Brett Profitt
* Cash Costello
* Jeff Tilson
* Jerome Bakker
* Paweł Sroka
* Steve Clay

## Security Fixes
* Fixed avatar removal bug (thanks to Jerome Bakker for the first report of this)

## Bugfixes
* Fixed infinite loop when deleting/disabling an entity with > 50 annotations
* Fixed deleting log tables in log rotate plugin
* Added full text index for groups if missing
* Added workaround for IE8 and jumping user avatar
* Fixed pagination for members pages
* Fixed several internal cache issues
* Plus many more bug fixes


# v1.8.15 (April 23, 2013)

## Contributing Developers
* Cash Costello
* Ismayil Khayredinov
* Jeff Tilson
* Juho Jaakkola
* Matt Beckett
* Paweł Sroka
* Sem
* Steve Clay
* Tom Voorneveld

## Bugfixes
* Not displaying http:// on profiles when website isn't set
* Fixed pagination display issue for small screens
* Not hiding subpages of top level pages that have been deleted
* Stop corrupting JavaScript views with elgg deprecation messages
* Fixed out of memory error due to query cache
* Fixed bug preventing users authorizing Twitter account access
* Fixed friends access level for editing pages
* Fixed uploading files within the embed dialog

## Enhancements
* Added browser caching of language JS files
* Adding nofollow on user posted URLs for spam deterrence (thanks to Hellekin)
* Auto-registering views for simplecache when their URL is requested
* Display helpful message for those who have site URL configuration issues
* Can revert to a previous revision with pages plugin
* Site owners can turn off posting wire messages to Twitter
* Search results are sorted by relevance

## Removed Functionality
* Twitter widget due to changes in Twitter API and terms of service
* OAuth API plugin due to conflicts with the Twitter API plugin


# v1.8.14 (March 12, 2013)

## Contributing Developers
* Aday Talavera
* Brett Profitt
* Cash Costello
* Ed Lyons
* German Bortoli
* Hellekin Wolf
* iionly
* Jerome Bakker
* Luciano Lima
* Matt Beckett
* Paweł Sroka
* Sem
* Steve Clay

## Security Fixes
* Fixed a XSS vulnerability when accepting URLs on user profiles
* Fixed bug that exposed subject lines of messages in inbox
* Added requirement for CSRF token for login

## Bugfixes
* Strip html tags from tag input
* Fixed several display issues for IE7
* Fixed several issues with blog drafts
* Fixed repeated token timeout errors
* Fixed JavaScript localization for non-English languages

## Enhancements
* Web services fall back to json if the viewtype is invalid


# v1.8.13 (January 29, 2013)

## Contributing Developers
* Cash Costello
* Juho Jaakkola
* Kevin Jardine
* Krzysztof Różalski
* Steve Clay

## Security Fixes
* Added validation of Twitter usernames in Twitter widget

## Bugfixes
* CLI usages with walled garden fixed
* Upgrading from < 1.8 to 1.8 fixed
* Default widgets fixed
* Quotes in object titles no longer result in "qout" in URLs
* List of my groups is ordered now
* Language string river:comment:object:default is defined now
* Added language string for comments: generic_comment:on

## Enhancements
* Added confirm dialog for resetting profile fields (adds language string profile:resetdefault:confirm)


# v1.8.12 (January 4th, 2013)

## Contributing Developers
* Brett Profitt
* Cash Costello
* Jerome Bakker
* Matt Beckett
* Paweł Sroka
* Sem
* Steve Clay

## Bugfixes
* Added an AJAX workaround for the rewrite test.
* Code cleanup to prevent some notices and warnings.
* Removed "original_order" in menu item anchor tags.
* Site menu's selected item correctly persists through content pages.
* Static caches rewritten and improved to prevent stale data being returned.
* Installation: Invalid characters in admin username are handled correctly.
* Messages: Fixed inbox link in email notifications.
* The Wire: Fixed objects not displaying correctly when upgrading from 1.7.

## Enhancements
* Performance improvements and improved caching in entity loading.
* Added upgrade locking to prevent concurrent upgrade attempts.
* Replaced xml_to_object() and autop() with GPL / MIT-compatible code.
* Error messages (register_error()) only fade after being clicked.
* Groups: Added a sidebar entry to display membership status and a link to
 group notification settings.
* Groups: Added pending membership and invitation requests to the sidebar.
* Groups: Better redirection for invisible and closed groups.
* Search: User profile fields are searched.
* Pages: Subpages can be reassigned to new parent pages.
* Twitter: Login with twitter supports persistent login and correctly forwards
 after login.


# v1.8.11 (December 5th, 2012)

## Bugfixes
* Fixed fatal error in group creation form


# v1.8.10 (December 4th, 2012)

## Contributing Developers
* Krzysztof Różalski
* Lars Hærvig
* Paweł Sroka
* RiverVanRain
* Sem
* Steve Clay

## Security Enhancements
* Cached metadata respects access restrictions to fix problems with profile
 field display.
* Group RSS feeds are restricted to valid entities

## Enhancements
* UX: Added a list of Administrators in the admin area
* UX: Limiting message board activity stream entries to excerpts
* Performance: Prefetching river entries
* Performance: Plugin entities are cached

## Bugfixes
* Removed superfluous commas in JS files to fix IE compatibility.
* API: Fixed Twitter API.
* Performance: Outputting valid ETags and expires headers.


# v1.8.9 (November 11, 2012)

## Contributing Developers
* Brett Profitt
* Cash Costello
* Evan Winslow
* Jeroen Dalsem
* Jerome Bakker
* Matt Beckett
* Paweł Sroka
* Sem
* Steve Clay

## Security Enhancements
* Sample CLI installer cannot break site
* Removed XSS vulnerabilities in titles and user profiles

## Enhancements
* UX: A group's owner can transfer ownership to another member
* UX: Search queries persist in the search box
* Several (X)HTML validation improvements
* Improved performance via more aggressive entity and metadata caching
* BC: 1.7 group profile URLs forward correctly

## Bugfixes
* UX: Titles containing HTML tokens are never mangled
* UX: Empty user profile values saved properly
* UX: Blog creator always mentioned in activity stream (not user who published it)
* UI: Fixed ordering of registered menu items in some cases
* UI: Embed dialog does not break file inputs
* UI: Datepicker now respects language
* UI: More reliable display of access input in widgets
* UI: Group edit form is sticky
* UI: Site categories are sticky in forms
* API: Language fallback works in Javascript
* API: Fallback to default viewtype if invalid one given
* API: Notices reported for missing language keys
* Memcache now safe to use; never bypasses access control
* BC: upgrade shows comments consistently in activity stream


# v1.8.8 (July 11, 2012)

## Contributing Developers
* Cash Costello
* Miguel Rodriguez
* Sem

## Enhancements
* Added a delete button on river items for admins

## Bugfixes
* Fixed the significant bug with htmlawed plugin that caused duplicate tags


# v1.8.7 (July 10, 2012)

## Contributing Developers
* Cash Costello
* Evan Winslow
* Ismayil Khayredinov
* Jeroen Dalsem
* Jerome Bakker
* Matt Beckett
* Miguel Rodriguez
* Paweł Sroka
* Sem
* Steve Clay

## Enhancements
* Better support for search engine friendly URLs
* Upgraded htmlawed (XSS filtering)
* Internationalization support for TinyMCE
* Public access not available for walled gardens
* Better forwarding and messages when they cannot view content because logged out

## Bugfixes
* Fatal errors due to type hints downgraded to warnings
* Group discussion reply notifications work again
* Sending user to inbox when deleting a message
* Fixed location profile information when it is an array
* Over 30 other bug fixes.


# v1.8.6 (June 18, 2012)

## Contributing Developers
* Cash Costello
* Evan Winslow
* Ismayil Khayredinov
* Jeff Tilson
* Jerome Bakker
* Paweł Sroka
* Sem
* Steve Clay

## Enhancements
* New ajax spinner
* Detecting docx, xlsx, and pptx files in file plugin
* Showing ajax spinner when uploading file with embed plugin

## Bugfixes
* Fixed some language caching issues.
* Users can add sub-pages to another user's page in a group.
* Over 30 other bug fixes.


# v1.8.5 (May 17, 2012)

## Contributing Developers
* Brett Profitt
* Evan Winslow
* Sem
* Steve Clay
* Jeroen Dalsem
* Jerome Bakker

## Security Enhancements
* Fixed possible XSS vulnerability if using a crafted URL.
* Fixed exploit to bypass new user validation if using a crafted form.
* Fixed incorrect caching of access lists that could allow plugins
to show private entities to non-admin and non-owning users. (Non-exploitable)

## Bugfixes
* Twitter API: New users are forwarded to the correct page after creating
             an account with Twitter.
* Files: PDF files are downloaded as "inline" to display in the browser.
* Fixed possible duplication errors when writing metadata with multiple values.
* Fixed possible upgrade issue if using a plugin uses the system_log hooks.
* Fixed problems when enabling more than 50 metadata or annotations.

## API
* River entries' timestamps use elgg_view_friendly_time() and can be
 overridden with the friendly time output view.


# v1.8.4 (April 24, 2012)

## Contributing Developers
* Adayth Talavera
* Brett Profitt
* Cash Costello
* Evan Winslow
* Ismayil Khayredinov
* Janek Lasocki-Biczysko
* Jerome Baker
* Sem
* Steve Clay
* Webgalli

## Security Enhancements
* Fixed an issue in the web services auth.get_token endpoint that
would give valid auth tokens to invalid credentials. Thanks to
Christian for reporting this!
* Fixed an that could show which plugins are loaded on a site.

## Enhancements
* UI: All bundled plugins' list pages display a no content message if there is nothing to list.
* UI: Site default access is limited to core access levels.
* UI: Showing a system message to the admin if plugins are disabled with the "disabled"
magic file.
* UI: Added transparent backgrounds for files and pages icons.
* External (Site) Pages: If in Wall Garden mode, Site Pages use the Walled Garden
theme when logged out.
* UI: Database errors only show the query to admin users.
* UI: Cannot set the data path to a relative path in installation or site settings.
* UI: Cleaned up notifications for bundled plugins.
* UI: Hiding crop button if no avatar is uploaded.
* UI: Bundled plugins are displayed with a gold border in the plugin admin area.
* UI: Can see all the categories a plugin belongs to.
* Web Services: Multiple tokens allowed for users.
* API: More efficient entity loading.
* API: Added IP address to system log.
* API: Languages are cached.
* API: ElggBatch supports disabling offsets for callbacks that delete entities.
* API: Cleaned up the boot process.
* API: Fixed situation in which the cache isn't properly cleared if a file can't be unlinked.

## Bugfixes
* UI: Tags display in the case they were saved.
* UI: Friendly titles keep -s.
* UI: Removed pagination in friends widget.
* UI: Profile settings actions correctly displays error messages as errors.
* UI: Tag search works for tags with spaces.
* UI: Fixed river display for friending that happens during registration.
* Groups: Link for managing join requests is restored in the sidebar.
* Walled Garden: Cron and web services endpoints are exposed as public sites.
* The Wire: UTF usernames are correctly linked with @ syntax.
* The Wire: No longer selecting the "Mine" tab for users who aren't you.
* Blogs: Notifications restored.
* Message Board: Fixed delete.
* Groups: Forwarding to correct page if trying to access closed group.
* API: entities loaded via elgg_get_entities_from_relationship() have the correct time_created.
* API: Deleting entities recursively works when code is logged out.
* API: Fixed multiple uses of deprecated functions.


# v1.8.3 (January 12, 2012)

## Enhancements
* Adds a white list for ajax views
* Improved navigation tab options
* Added group specific search
* Added button for reverting avatar
* Improved documentation for core class attributes
* Adds a server info page under administer -> statistics
* Improving caching of icons and js/css
* Deprecation notices not displayed to non-admin users

## Bugfixes
* Fixed upgrade scripts for blog posts and groups forum posts
* Can now delete invitations to invisible groups
* Fixed several widget bugs
* Fixed access level on add to group river item
* Fixed recursive entity enabling
* Fixed limit on pages in sidebar navigation
* Fixed deletion of large numbers of annotations


# v1.8.2 (December 21, 2011)

## Enhancements
* Added a 404 page
* Widgets controls now using the menu system
* Admins can edit users' account information
* Embed uploader supports uploading into groups
* Add a control panel widget for easy access to cache flushing and upgrading
* Comments now have a unqiue URL with fragment identifier
* JavaScript language files are cacheable
* jQuery form script only loaded when required

## Bugfixes
* Fixed default widgets
* Fixed activity filtering
* Embedding an image now inserts a medium sized image
* Search plugin only uses mbstring extension if enabled
* Site pages links returned to footer
* Fixed URL creation for users with non-ASCII characters in username
* The wire username parsing supports periods in usernames
* Returned the posting area to the main wire page
* Fixed layout issue on pages with a fragment identifier in URL
* Added support for call elgg_load_js() in header and footer
* Fixed user picker
* Fixed uservalidationbyemail plugin ignoring the actions of other plugins
* Fixed bug preventing the creation of admin users
* Fixed deleting a widget with JavaScript disabled
* Fixed many bugs in the unit/integration tests


# v1.8.1 (November 16, 2011)

## Enhancements
* Completed styling of user validation admin page
* Adding rel=nofollow for non-trusted links
* Added direct icon loading for profile avatars in profile plugin
* Improved the structure of content views to make styling easier
* Updated version of jQuery to 1.6.4
* Added basic support for icon size customization
* Added a toggle for gallery/list view in file plugin
* Added support for passing CSS classes to icon views
* Added support for non http URLs to Elgg's normalize functions
* Added better support for the 404 forward if a page handler does handle a request

## Bugfixes
* Fixed autocomplete and userpicker
* Fixed RSS and web service-related view types
* Fixed walled garden display issues
* Added work around for IE/TinyMCE/embed insert problem
* Implemented ElggUser.isAdmin() JavaScript method
* Fixed the date views and JavaScript datepicker
* Fixed horizontal radio buttons styling
* Modules only display header if there is content for it


# v1.8.1b (October 11, 2011)

## Enhancements
* New group activity widget for user dashboard.
* Added more sprites.
* version.php information cached instead of loaded 100s of times.
* Added class elgg-autofocus to add focus on inputs when the page loads.
* Admins can edit user avatars again.
* Added a filter for non-bundled plugins in plugin admin.
* Improvements to admin area theme.

## Bugfixes
* Fixed site dropdown menu for IE.
* ElggEntity->deleteMetadata() no longer deletes all metadata ever if
called on an unsaved entity.
* Fixed Embed plugin.
* Fixed activate and deactivate all plugins.
* Fixed URL for group membership request in notification email.
* Fixed log browser plugin's admin area display.
* Fixed RSS icon not showing up on some pages.
* Fixed river entries for forum posts that were lost if upgrading from 1.7.
* Better displaying of errors when activating, deactivating, or
reordering plugins.
* Fixed Developer Plugin's inspection tool.
* Fixed avatar cropping on IE 7.
* Bookmarks plugin accepts URLs with dashes.
* "More" menu item on site menu hidden if items are manually specified.
* Fixed hover menu floating if unrestrained.
* JS init, system fired when DOM and languages are read.
* Fixed the date picker input view.
* Fixed stack overflow when calling elgg_view() from a pagesetup
event.
* Menu links no longer have empty titles and confirm attributes.
* Fixed crash when attempting to change password to an invalid value.
* Fixed "More groups" link for groups widget.
* Fixed output/confirmlink to use a default question if not specified.
* Added missing language strings. Also added "new", "add", and "create".
* Registered security token refresh page as external to avoid token refresh
problems on Walled Garden sites.
* Displaying more accurate message if uploading an avatar fails.
* "Leave group" button doesn't display for group owners.
* Request group membership button displays only when logged in.
* Fixed the number of displayed items for Bookmarks widget.
* Fixed fallback to deprecated views for widgets.

## API Changes
* Menus names must be unique to the entire menu, not just section.
* Input views that encode text use the option 'encode_text'.
* Added ElggPlugin->getFriendlyName().
* elgg_view_icon() accepts a class.
* Added hook output:before, page.
* Added hook output:before, layout.
* elgg_get_entities() and related functions return false if passed
valid options with invalid values.
* Can disable the user hover menu by passing hover => false to
elgg_view_icon(). Previously it was override => true.
* Embed plugin uses menu system. See readme for embed plugin.
* Manifest attributes are no longer translated via elgg_echo().
* Fixed livesearch ajax endpoint.
* Fixed site unit test.
* Unit tests tidy up after themselves better.
* forward() throws an exception if headers are already sent.
* Better errors if adding a user through admin area fails.
* Localized profile fields.
* Added 'is_trusted' parameter output/url to avoid escaping and filtering.
Defaults to false.
* Added elgg_unregister_action()
* Fixed ElggPriorityList::rewind().
* Fixed forwarding after login for login-protected pages.
* get_site_by_url() respects class inheritance for subclassing ElggSite.

## Internal changes
* Updated deprecated uses of internalname/id.
* Using wwwroot instead of www_root because of inconsistencies.


# v1.8.0 (Jackie) (September 5th, 2011)

## Notes
Elgg 1.8 contains the most changes in Elgg since the transition from Elgg
0.9 to Elgg 1.0. The core team tried to make the transition as smooth as
possible, but in the interest of following standards and simplifying the
development process for core and third party developers, we have made
changes that will require updating plugins. We believe these changes
will help Elgg development be easier for everyone.

It is unreasonable and unhelpful to list the full details of all changes in
this file. Instead, we will list the high level, overarching changes to
systems. If you are interested in the specifics, Elgg 1.8's source code is
highly documented and serves as a good guide and the git commit log can
provide excruciating details of what has changed between 1.7 and 1.8.

Please post your feedback, questions, and comments to the community site
at http://community.elgg.org. As always, thank you for using Elgg!

--The Elgg Core Development Team

A tip about updating plugins:

It's not difficult to update 1.7 plugins for 1.8. There is a detailed
document outlining this process on the wiki:
http://docs.elgg.org/wiki/Updating_plugins_for_Elgg_1.8

The basic process is:

1. Clean up the plugin to make sure it conforms to coding standards,
 official structure, and best practices.
2. Update any uses of deprecated functions. Functions deprecated in 1.7 will
 produce visible notices in 1.8!
3. Use the new manifest format.
4. Use the new menu functions.
5. Use the new JS features.
6. Update the views to use core CSS helper functions and classes instead of
 writing your own.

The documentation directory and the wiki has more information.

## User-visible changes
* New default theme.
* New installation.
* Separate and updated admin interface.
* Updated plugin themes.

## Generic API changes
* Improved the markup and CSS.
* Restructured and simplified the views layouts.
* Added a new menu system.
* Added new CSS and JS file registration functions.
* Added a JS engine.
* Added a breadcrumb system.
* Added a sticky forms system.

## New plugins
* Dashboard - The activity stream is now the default index page. A 1.7-style
dashboard is provided through the dashboard plugin.
* Developers Plugins - Developer tools.
* Likes - Allows users to "like" other users' content.
* oAuth API - A generic, reusable oAuth library.
* Tag Cloud - A widget-based tag cloud generator.
* Twitter API - A generic Twitter library that allows signin with Twitter
and pushing content to tweets. Replaces twitter_service.

## Deprecated plugins
* captcha - Captchas have long since stopped being useful as a deterrent
against spam.
* crontrigger - Real cron should be used.
* default_widgets - This functionality is now part of core.
* friends - This functionality is now part of core.
* riverdashboard - Displaying the river (activity stream) is default in
core. The original dashboard can be restored by the new Dashboard plugin.
* twitter_service - Replaced by Twitter API.

Elgg 1.8.0.1 was released immediately after 1.8.0 to correct a problem in
installation.
