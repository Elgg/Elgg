<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
/**
 * Sites
 */

	'item:site:site' => 'Site',
	'collection:site:site' => 'Sites',
	'index:content' => '<p>Welcome to your Elgg site.</p><p><strong>Tip:</strong> Many sites use the <code>activity</code> plugin to place a site activity stream on this page.</p>',

/**
 * Sessions
 */

	'login' => "Log in",
	'loginok' => "You have been logged in.",
	'login:empty' => "Username/email and password are required.",
	'login:baduser' => "Unable to load your user account.",
	'auth:nopams' => "Internal error. No user authentication method installed.",

	'logout' => "Log out",
	'logoutok' => "You have been logged out.",
	'logouterror' => "We couldn't log you out. Please try again.",
	'session_expired' => "Your session has expired. Please <a href='javascript:location.reload(true)'>reload</a> the page to log in.",
	'session_changed_user' => "You have been logged in as another user. You should <a href='javascript:location.reload(true)'>reload</a> the page.",

	'loggedinrequired' => "You must be logged in to view the requested page.",
	'loggedoutrequired' => "You must be logged out to view the requested page.",
	'adminrequired' => "You must be an administrator to view the requested page.",
	'membershiprequired' => "You must be a member of this group to view the requested page.",
	'limited_access' => "You do not have permission to view the requested page.",
	'invalid_request_signature' => "The URL of the page you are trying to access is invalid or has expired",

/**
 * Errors
 */

	'exception:title' => "Fatal Error.",
	'exception:contact_admin' => 'An unrecoverable error has occurred and has been logged. Contact the site administrator with the following information:',

	'actionnotfound' => "The action file for %s was not found.",
	'actionunauthorized' => 'You are unauthorized to perform this action',

	'ajax:error' => 'Unexpected error while performing an AJAX call. Maybe the connection to the server is lost.',
	'ajax:not_is_xhr' => 'You cannot access AJAX views directly',
	'ajax:pagination:no_data' => 'No new page data found',
	'ajax:pagination:load_more' => 'Load more',

	'ElggEntity:Error:SetSubtype' => 'Use %s instead of the magic setter for "subtype"',
	'ElggEntity:Error:SetEnabled' => 'Use %s instead of the magic setter for "enabled"',
	'ElggUser:Error:SetAdmin' => 'Use %s instead of the magic setter for "admin"',
	'ElggUser:Error:SetBanned' => 'Use %s instead of the magic setter for "banned"',

	'PluginException:CannotStart' => '%s (guid: %s) cannot start and has been deactivated.  Reason: %s',
	'PluginException:InvalidID' => "%s is an invalid plugin ID.",
	'PluginException:InvalidPath' => "%s is an invalid plugin path.",
	'PluginException:PluginMustBeActive' => "Requires plugin '%s' to be active.",
	'PluginException:PluginMustBeAfter' => "Requires to be positioned after plugin '%s'.",
	'PluginException:PluginMustBeBefore' => "Requires to be positioned before plugin '%s'.",
	'ElggPlugin:MissingID' => 'Missing plugin ID (guid %s)',
	'ElggPlugin:NoPluginComposer' => 'Missing composer.json for plugin ID %s (guid %s)',
	'ElggPlugin:StartFound' => 'For plugin ID %s a start.php was found. This could indicate a unsupported plugin version.',
	'ElggPlugin:IdMismatch' => 'This plugin\'s directory must be renamed to "%s" to match the projectname set in the plugin composer.json.',
	'ElggPlugin:Error' => 'Plugin error',
	'ElggPlugin:Exception:CannotIncludeFile' => 'Cannot include %s for plugin %s (guid: %s) at %s.',
	'ElggPlugin:Exception:IncludeFileThrew' => 'Threw exception including %s for plugin %s (guid: %s) at %s.',
	'ElggPlugin:Exception:CannotRegisterViews' => 'Cannot open views dir for plugin %s (guid: %s) at %s.',
	'ElggPlugin:InvalidAndDeactivated' => '%s is an invalid plugin and has been deactivated.',
	'ElggPlugin:activate:BadConfigFormat' => 'Plugin file "elgg-plugin.php" did not return a serializable array.',
	'ElggPlugin:activate:ConfigSentOutput' => 'Plugin file "elgg-plugin.php" sent output.',

	'ElggPlugin:Dependencies:ActiveDependent' => 'There are other plugins that list %s as a dependency.  You must disable the following plugins before disabling this one: %s',
	'ElggPlugin:Dependencies:MustBeActive' => 'Must be active',
	'ElggPlugin:Dependencies:Position' => 'Position',

	'ElggMenuBuilder:Trees:NoParents' => 'Menu items found without parents to link them to',
	'ElggMenuBuilder:Trees:OrphanedChild' => 'Menu item [%s] found with a missing parent[%s]',
	'ElggMenuBuilder:Trees:DuplicateChild' => 'Duplicate registration found for menu item [%s]',

	'RegistrationException:EmptyPassword' => 'The password fields cannot be empty',
	'RegistrationException:PasswordMismatch' => 'Passwords must match',
	'LoginException:BannedUser' => 'You have been banned from this site and cannot log in',
	'LoginException:UsernameFailure' => 'We could not log you in. Please check your username/email and password.',
	'LoginException:PasswordFailure' => 'We could not log you in. Please check your username/email and password.',
	'LoginException:AccountLocked' => 'Your account has been locked for too many log in failures.',
	'LoginException:ChangePasswordFailure' => 'Failed current password check.',
	'LoginException:Unknown' => 'We could not log you in due to an unknown error.',
	'LoginException:AdminValidationPending' => "Your account needs to be validated by a site administrator before you can use it. You'll be notified when your account is validated.",
	'LoginException:DisabledUser' => "Your account has been disabled. You're not allowed to login.",

	'UserFetchFailureException' => 'Cannot check permission for user_guid [%s] as the user does not exist.',

	'PageNotFoundException' => 'The page you are trying to view does not exist or you do not have permissions to view it',
	'EntityNotFoundException' => 'The content you were trying to access has been removed or you do not have permissions to access it.',
	'EntityPermissionsException' => 'You do not have sufficient permissions for this action.',
	'GatekeeperException' => 'You do not have permissions to view the page you are trying to access',
	'BadRequestException' => 'Bad request',
	'ValidationException' => 'Submitted data did not meet the requirements, please check your input.',
	'LogicException:InterfaceNotImplemented' => '%s must implement %s',
	
	'Security:InvalidPasswordCharacterRequirementsException' => "The provided password is doesn't meet the character requirements",
	'Security:InvalidPasswordLengthException' => "The provided password doesn't meet the minimal length requirement of %s characters",
	
	'Entity:Subscriptions:InvalidMethodsException' => '%s requires $methods to be a string or an array of strings',

	'viewfailure' => 'There was an internal failure in the view %s',
	'changebookmark' => 'Please change your bookmark for this page',
	'error:missing_data' => 'There was some data missing in your request',
	'save:fail' => 'There was a failure saving your data',
	'save:success' => 'Your data was saved',

	'error:default:title' => 'Oops...',
	'error:default:content' => 'Oops... something went wrong.',
	'error:400:title' => 'Bad request',
	'error:400:content' => 'Sorry. The request is invalid or incomplete.',
	'error:403:title' => 'Forbidden',
	'error:403:content' => 'Sorry. You are not allowed to access the requested page.',
	'error:404:title' => 'Page not found',
	'error:404:content' => 'Sorry. We could not find the page that you requested.',

	'upload:error:ini_size' => 'The file you tried to upload is too large.',
	'upload:error:form_size' => 'The file you tried to upload is too large.',
	'upload:error:partial' => 'The file upload did not complete.',
	'upload:error:no_file' => 'No file was selected.',
	'upload:error:no_tmp_dir' => 'Cannot save the uploaded file.',
	'upload:error:cant_write' => 'Cannot save the uploaded file.',
	'upload:error:extension' => 'Cannot save the uploaded file.',
	'upload:error:unknown' => 'The file upload failed.',

/**
 * Table columns
 */
	'table_columns:fromView:admin' => 'Admin',
	'table_columns:fromView:banned' => 'Banned',
	'table_columns:fromView:container' => 'Container',
	'table_columns:fromView:excerpt' => 'Description',
	'table_columns:fromView:link' => 'Name/Title',
	'table_columns:fromView:icon' => 'Icon',
	'table_columns:fromView:item' => 'Item',
	'table_columns:fromView:language' => 'Language',
	'table_columns:fromView:owner' => 'Owner',
	'table_columns:fromView:time_created' => 'Time Created',
	'table_columns:fromView:time_updated' => 'Time Updated',
	'table_columns:fromView:user' => 'User',

	'table_columns:fromProperty:description' => 'Description',
	'table_columns:fromProperty:email' => 'Email',
	'table_columns:fromProperty:name' => 'Name',
	'table_columns:fromProperty:type' => 'Type',
	'table_columns:fromProperty:username' => 'Username',

	'table_columns:fromMethod:getSubtype' => 'Subtype',
	'table_columns:fromMethod:getDisplayName' => 'Name/Title',
	'table_columns:fromMethod:getMimeType' => 'MIME Type',
	'table_columns:fromMethod:getSimpleType' => 'Type',

/**
 * User details
 */

	'name' => "Display name",
	'email' => "Email address",
	'username' => "Username",
	'loginusername' => "Username or email",
	'password' => "Password",
	'passwordagain' => "Password (again for verification)",
	'admin_option' => "Make this user an admin?",
	'autogen_password_option' => "Automatically generate a secure password?",

/**
 * Access
 */

	'access:label:private' => "Private",
	'access:label:logged_in' => "Logged in users",
	'access:label:public' => "Public",
	'access:label:logged_out' => "Logged out users",
	'access:label:friends' => "Friends",
	'access' => "Who can see this",
	'access:limited:label' => "Limited",
	'access:help' => "The access level",
	'access:read' => "Read access",
	'access:write' => "Write access",
	'access:admin_only' => "Administrators only",
	
/**
 * Dashboard and widgets
 */

	'dashboard' => "Dashboard",
	'dashboard:nowidgets' => "Your dashboard lets you track the activity and content on this site that matters to you.",

	'widgets:add' => 'Add widgets',
	'widgets:add:description' => "Click on any widget button below to add it to the page.",
	'widget:unavailable' => 'You have already added this widget',
	'widget:numbertodisplay' => 'Number of items to display',

	'widget:delete' => 'Remove %s',
	'widget:edit' => 'Customize this widget',

	'widgets' => "Widgets",
	'widget' => "Widget",
	'item:object:widget' => "Widget",
	'collection:object:widget' => 'Widgets',
	'widgets:save:success' => "The widget was successfully saved.",
	'widgets:save:failure' => "We could not save your widget.",
	'widgets:add:success' => "The widget was successfully added.",
	'widgets:add:failure' => "We could not add your widget.",
	'widgets:move:failure' => "We could not store the new widget position.",
	'widgets:remove:failure' => "Unable to remove this widget",
	'widgets:not_configured' => "This widget is not yet configured",
	
/**
 * Groups
 */

	'group' => "Group",
	'item:group' => "Group",
	'collection:group' => 'Groups',
	'item:group:group' => "Group",
	'collection:group:group' => 'Groups',
	'groups:tool_gatekeeper' => "The requested functionality is currently not enabled in this group",

/**
 * Users
 */

	'user' => "User",
	'item:user' => "User",
	'collection:user' => 'Users',
	'item:user:user' => 'User',
	'collection:user:user' => 'Users',
	'notification:user:user:make_admin' => "Send a notification when a user receives admin rights",
	'notification:user:user:remove_admin' => "Send a notification when the admin rights of a user are revoked",
	'notification:user:user:unban' => "Send a notification when a user is unbanned",

	'friends' => "Friends",
	'collection:friends' => 'Friends\' %s',

	'avatar' => 'Avatar',
	'avatar:noaccess' => "You're not allowed to edit this user's avatar",
	'avatar:create' => 'Create your avatar',
	'avatar:edit' => 'Edit avatar',
	'avatar:upload' => 'Upload a new avatar',
	'avatar:current' => 'Current avatar',
	'avatar:remove' => 'Remove your avatar and set the default icon',
	'avatar:crop:title' => 'Avatar cropping tool',
	'avatar:upload:instructions' => "Your avatar is displayed throughout the site. You can change it as often as you'd like. (File formats accepted: GIF, JPG or PNG)",
	'avatar:create:instructions' => 'Click and drag a square below to match how you want your avatar cropped. A preview will appear in the box on the right. When you are happy with the preview, click \'Create your avatar\'. This cropped version will be used throughout the site as your avatar.',
	'avatar:upload:success' => 'Avatar successfully uploaded',
	'avatar:upload:fail' => 'Avatar upload failed',
	'avatar:resize:fail' => 'Resize of the avatar failed',
	'avatar:crop:success' => 'Cropping the avatar succeeded',
	'avatar:crop:fail' => 'Avatar cropping failed',
	'avatar:remove:success' => 'Removing the avatar succeeded',
	'avatar:remove:fail' => 'Avatar remove failed',
	
	'action:user:validate:already' => "%s was already validated",
	'action:user:validate:success' => "%s has been validated",
	'action:user:validate:error' => "An error occurred while validating %s",

/**
 * Feeds
 */
	'feed:rss' => 'RSS',
	'feed:rss:title' => 'RSS feed for this page',
/**
 * Links
 */
	'link:view' => 'view link',
	'link:view:all' => 'View all',


/**
 * River
 */
	'river' => "River",
	'river:user:friend' => "%s is now a friend with %s",
	'river:update:user:avatar' => '%s has a new avatar',
	'river:noaccess' => 'You do not have permission to view this item.',
	'river:posted:generic' => '%s posted',
	'riveritem:single:user' => 'a user',
	'riveritem:plural:user' => 'some users',
	'river:ingroup' => 'in the group %s',
	'river:none' => 'No activity',
	'river:update' => 'Update for %s',
	'river:delete' => 'Remove this activity item',
	'river:delete:success' => 'Activity item has been deleted',
	'river:delete:fail' => 'Activity item could not be deleted',
	'river:delete:lack_permission' => 'You lack permission to delete this activity item',
	'river:subject:invalid_subject' => 'Invalid user',
	'activity:owner' => 'Activity',

/**
 * Relationships
 */
	
	'relationship:default' => "%s relates to %s",

/**
 * Notifications
 */
	'notification:method:email' => 'Email',
	'notification:method:email:from' => '%s (via %s)',
	'notification:method:delayed_email' => 'Delayed email',
	
	'usersettings:notifications:title' => "Notification settings",
	'usersettings:notifications:users:title' => 'Notifications per user',
	'usersettings:notifications:users:description' => 'To receive notifications from your friends (on an individual basis) when they create new content, find them below and select the notification method you would like to use.',
	
	'usersettings:notifications:menu:page' => "Notification settings",
	'usersettings:notifications:menu:filter:settings' => "Settings",
	
	'usersettings:notifications:default:description' => 'Default notification settings for events from the system',
	'usersettings:notifications:content_create:description' => 'Default notification settings for new content you created, this can cause notifications when others take action on you content like leaving a comment',
	'usersettings:notifications:create_comment:description' => "Default notification setting when you comment on content in order to follow the rest of the conversation",

	'usersettings:notifications:timed_muting' => "Temporarily disable notifications",
	'usersettings:notifications:timed_muting:help' => "If you don't wish to receive any notifications during a certain period (for example a holiday) you can set a start and end date to temporarily disable all notifications",
	'usersettings:notifications:timed_muting:start' => "First day",
	'usersettings:notifications:timed_muting:end' => "Last day",
	'usersettings:notifications:timed_muting:warning' => "Currently your notifications are temporarily disabled",
	
	'usersettings:notifications:save:ok' => "Notification settings were successfully saved.",
	'usersettings:notifications:save:fail' => "There was a problem saving the notification settings.",
	
	'usersettings:notifications:subscriptions:save:ok' => "Notification subscriptions were successfully saved.",
	'usersettings:notifications:subscriptions:save:fail' => "There was a problem saving the notification subscriptions.",

	'notification:default:salutation' => 'Dear %s,',
	'notification:default:sign-off' => 'Regards,

%s',
	'notification:subject' => 'Notification about %s',
	'notification:body' => 'View the new activity at %s',
	
	'notifications:delayed_email:subject:daily' => "Daily notifications",
	'notifications:delayed_email:subject:weekly' => "Weekly notifications",
	'notifications:delayed_email:body:intro' => "Below is a list of your delayed notifications.",
	
	'notifications:subscriptions:record:settings' => 'Show detailed selection',
	'notifications:subscriptions:no_results' => 'There are no subscription records yet',
	'notifications:subscriptions:details:reset' => 'Undo selection',

	'notifications:mute:title' => "Mute notifications",
	'notifications:mute:description' => "If you no longer wish to receive notifications like the one you received configure one or more of the following reasons to block all notifications:",
	'notifications:mute:error:content' => "No notification settings could be determined",
	'notifications:mute:entity' => "about '%s'",
	'notifications:mute:container' => "from '%s'",
	'notifications:mute:owner' => "by '%s'",
	'notifications:mute:actor' => "initiated by '%s'",
	'notifications:mute:group' => "written in the group '%s'",
	'notifications:mute:user' => "written by the user '%s'",
	
	'notifications:mute:save:success' => "Your notification settings have been saved",
	
	'notifications:mute:email:footer' => "Mute these emails",

/**
 * Search
 */

	'search' => "Search",
	'searchtitle' => "Search: %s",
	'users:searchtitle' => "Searching for users: %s",
	'groups:searchtitle' => "Searching for groups: %s",
	'advancedsearchtitle' => "%s with results matching %s",
	'notfound' => "No results found.",

	'viewtype:change' => "Change list type",
	'viewtype:list' => "List view",
	'viewtype:gallery' => "Gallery",
	'search:go' => 'Go',
	'userpicker:only_friends' => 'Only friends',

/**
 * Account
 */

	'account' => "Account",
	'settings' => "Settings",
	'tools' => "Tools",
	'settings:edit' => 'Edit settings',

	'register' => "Register",
	'registerok' => "You have successfully registered for %s.",
	'registerbad' => "Your registration was unsuccessful because of an unknown error.",
	'registerdisabled' => "Registration has been disabled by the system administrator",
	'register:fields' => 'All fields are required',

	'registration:noname' => 'Display name is required.',
	'registration:notemail' => 'The email address you provided does not appear to be a valid email address.',
	'registration:userexists' => 'That username already exists',
	'registration:usernametooshort' => 'Your username must be a minimum of %u characters long.',
	'registration:usernametoolong' => 'Your username is too long. It can have a maximum of %u characters.',
	'registration:dupeemail' => 'This email address has already been registered.',
	'registration:invalidchars' => 'Sorry, your username contains the character %s which is invalid. The following characters are invalid: %s',
	'registration:emailnotvalid' => 'Sorry, the email address you entered is invalid on this system',
	'registration:passwordnotvalid' => 'Sorry, the password you entered is invalid on this system',
	'registration:usernamenotvalid' => 'Sorry, the username you entered is invalid on this system',

	'adduser' => "Add User",
	'adduser:ok' => "You have successfully added a new user.",
	'adduser:bad' => "The new user could not be created.",

	'user:set:name' => "Account name settings",
	'user:name:label' => "Display name",
	'user:name:success' => "Successfully changed display name on the system.",
	'user:name:fail' => "Could not change display name on the system.",
	'user:username:success' => "Successfully changed username on the system.",
	'user:username:fail' => "Could not change username on the system.",

	'user:set:password' => "Account password",
	'user:current_password:label' => 'Current password',
	'user:password:label' => "New password",
	'user:password2:label' => "New password again",
	'user:password:success' => "Password changed",
	'user:password:fail' => "Could not change your password on the system.",
	'user:password:fail:notsame' => "The two passwords are not the same!",
	'user:password:fail:tooshort' => "Password is too short!",
	'user:password:fail:incorrect_current_password' => 'The current password entered is incorrect.',
	'user:changepassword:unknown_user' => 'Invalid user.',
	'user:changepassword:change_password_confirm' => 'This will change your password.',

	'user:set:language' => "Language settings",
	'user:language:label' => "Language",
	'user:language:success' => "Language settings have been updated.",
	'user:language:fail' => "Language settings could not be saved.",

	'user:username:notfound' => 'Username %s not found.',
	'user:username:help' => 'Please be aware that changing a username will change all dynamic user related links',

	'user:password:lost' => 'Lost password',
	'user:password:hash_missing' => 'Regretfully, we must ask you to reset your password. We have improved the security of passwords on the site, but were unable to migrate all accounts in the process.',
	'user:password:changereq:success' => 'Successfully requested a new password, email sent',
	'user:password:changereq:fail' => 'Could not request a new password.',

	'user:password:text' => 'To request a new password, enter your username or email address below and click the Request button.',

	'user:persistent' => 'Remember me',

	'walled_garden:home' => 'Home',

/**
 * Password requirements
 */
	'password:requirements:min_length' => "The password needs to be at least %s characters.",
	'password:requirements:lower' => "The password needs to have at least %s lower case characters.",
	'password:requirements:no_lower' => "The password shouldn't contain any lower case characters.",
	'password:requirements:upper' => "The password needs to have at least %s upper case characters.",
	'password:requirements:no_upper' => "The password shouldn't contain any upper case characters.",
	'password:requirements:number' => "The password needs to have at least %s number characters.",
	'password:requirements:no_number' => "The password shouldn't contain any number characters.",
	'password:requirements:special' => "The password needs to have at least %s special characters.",
	'password:requirements:no_special' => "The password shouldn't contain any special characters.",
	
/**
 * Administration
 */
	'menu:page:header:administer' => 'Administer',
	'menu:page:header:configure' => 'Configure',
	'menu:page:header:develop' => 'Develop',
	'menu:page:header:information' => 'Information',
	'menu:page:header:default' => 'Other',

	'admin:view_site' => 'View site',
	'admin:loggedin' => 'Logged in as %s',
	'admin:menu' => 'Menu',

	'admin:configuration:success' => "Your settings have been saved.",
	'admin:configuration:fail' => "Your settings could not be saved.",
	'admin:configuration:dataroot:relative_path' => 'Cannot set "%s" as the dataroot because it is not an absolute path.',
	'admin:configuration:default_limit' => 'The number of items per page must be at least 1.',

	'admin:unknown_section' => 'Invalid Admin Section.',

	'admin' => "Administration",
	'admin:header:release' => "Elgg release: %s",
	'admin:description' => "The admin panel allows you to control all aspects of the system, from user management to how plugins behave. Choose an option below to get started.",

	'admin:performance' => 'Performance',
	'admin:performance:label:generic' => 'Generic',
	'admin:performance:generic:description' => 'Below is a list of performance suggestions / values which could help in tuning your website',
	'admin:performance:simplecache' => 'Simplecache',
	'admin:performance:simplecache:settings:warning' => "It's recommended you configure the simplecache setting in the settings.php.
Configuring simplecache in the settings.php file improves caching performance.
It allows Elgg to skip connecting to the database when serving cached JavaScript and CSS files",
	'admin:performance:systemcache' => 'Systemcache',
	'admin:performance:apache:mod_cache' => 'Apache mod_cache',
	'admin:performance:apache:mod_cache:warning' => 'The mod_cache module provides HTTP-aware caching schemes. This means that the files will be cached according to an instruction specifying how long a page can be considered "fresh".',
	'admin:performance:php:open_basedir' => 'PHP open_basedir',
	'admin:performance:php:open_basedir:not_configured' => 'No limitations have been set',
	'admin:performance:php:open_basedir:warning' => 'A small amount of open_basedir limitations are in effect, this could impact performance.',
	'admin:performance:php:open_basedir:error' => 'A large amount of open_basedir limitations are in effect, this will probably impact performance.',
	'admin:performance:php:open_basedir:generic' => 'With open_basedir every file access will be checked against the list of limitations.
Since Elgg has a lot of file access this will negatively impact performance. Also PHPs opcache can no longer cache file paths in memory and has to resolve this upon every access.',
	
	'admin:statistics' => 'Statistics',
	'admin:server' => 'Server',
	'admin:cron' => 'Cron',
	'admin:cron:record' => 'Latest Cron Jobs',
	'admin:cron:period' => 'Cron period',
	'admin:cron:friendly' => 'Last completed',
	'admin:cron:date' => 'Date and time',
	'admin:cron:msg' => 'Message',
	'admin:cron:started' => 'Cron jobs for "%s" started at %s',
	'admin:cron:started:actual' => 'Cron interval "%s" started processing at %s',
	'admin:cron:complete' => 'Cron jobs for "%s" completed at %s',

	'admin:appearance' => 'Appearance',
	'admin:administer_utilities' => 'Utilities',
	'admin:develop_utilities' => 'Utilities',
	'admin:configure_utilities' => 'Utilities',
	'admin:configure_utilities:robots' => 'Robots.txt',

	'admin:users' => "Users",
	'admin:users:online' => 'Currently Online',
	'admin:users:newest' => 'Newest',
	'admin:users:admins' => 'Administrators',
	'admin:users:searchuser' => 'Search user to make them admin',
	'admin:users:existingadmins' => 'List of existing admins',
	'admin:users:add' => 'Add New User',
	'admin:users:description' => "This admin panel allows you to control user settings for your site. Choose an option below to get started.",
	'admin:users:adduser:label' => "Click here to add a new user...",
	'admin:users:opt:linktext' => "Configure users...",
	'admin:users:opt:description' => "Configure users and account information. ",
	'admin:users:find' => 'Find',
	'admin:users:unvalidated' => 'Unvalidated',
	'admin:users:unvalidated:no_results' => 'No unvalidated users.',
	'admin:users:unvalidated:registered' => 'Registered: %s',
	'admin:users:unvalidated:change_email' => 'Change e-mail address',
	'admin:users:unvalidated:change_email:user' => 'Change e-mail address for: %s',
	
	'admin:configure_utilities:maintenance' => 'Maintenance mode',
	'admin:upgrades' => 'Upgrades',
	'admin:upgrades:finished' => 'Completed',
	'admin:upgrades:db' => 'Database upgrades',
	'admin:upgrades:db:name' => 'Upgrade name',
	'admin:upgrades:db:start_time' => 'Start time',
	'admin:upgrades:db:end_time' => 'End time',
	'admin:upgrades:db:duration' => 'Duration',
	'admin:upgrades:menu:pending' => 'Pending upgrades',
	'admin:upgrades:menu:completed' => 'Completed upgrades',
	'admin:upgrades:menu:db' => 'Database upgrades',
	'admin:upgrades:menu:run_single' => 'Run this upgrade',
	'admin:upgrades:run' => 'Run upgrades now',
	'admin:upgrades:error:invalid_upgrade' => 'Entity %s does not exist or not a valid instance of ElggUpgrade',
	'admin:upgrades:error:invalid_batch' => 'Batch runner for the upgrade %s (%s) could not be instantiated',
	'admin:upgrades:completed' => 'Upgrade "%s" completed at %s',
	'admin:upgrades:completed:errors' => 'Upgrade "%s" completed at %s but encountered %s errors',
	'admin:upgrades:failed' => 'Upgrade "%s" failed',
	'admin:action:upgrade:reset:success' => 'Upgrade "%s" was reset',

	'admin:settings' => 'Settings',
	'admin:settings:basic' => 'Basic Settings',
	'admin:settings:i18n' => 'Internationalization',
	'admin:settings:advanced' => 'Advanced Settings',
	'admin:settings:users' => 'Users',
	'admin:site_settings' => "Site Settings",
	'admin:site:description' => "This admin panel allows you to control global settings for your site. Choose an option below to get started.",
	'admin:site:opt:linktext' => "Configure site...",
	'admin:settings:in_settings_file' => 'This setting is configured in settings.php',

	'site_secret:current_strength' => 'Key Strength',
	'site_secret:strength:weak' => "Weak",
	'site_secret:strength_msg:weak' => "We strongly recommend that you regenerate your site secret.",
	'site_secret:strength:moderate' => "Moderate",
	'site_secret:strength_msg:moderate' => "We recommend you regenerate your site secret for the best site security.",
	'site_secret:strength:strong' => "Strong",
	'site_secret:strength_msg:strong' => "Your site secret is sufficiently strong. There is no need to regenerate it.",

	'admin:dashboard' => 'Dashboard',
	'admin:widget:online_users' => 'Online users',
	'admin:widget:online_users:help' => 'Lists the users currently on the site',
	'admin:widget:new_users' => 'New users',
	'admin:widget:new_users:help' => 'Lists the newest users',
	'admin:widget:banned_users' => 'Banned users',
	'admin:widget:banned_users:help' => 'Lists the banned users',
	'admin:widget:content_stats' => 'Content statistics',
	'admin:widget:content_stats:help' => 'Keep track of the content created by your users',
	'admin:widget:cron_status' => 'Cron status',
	'admin:widget:cron_status:help' => 'Shows the status of the last time cron jobs finished',
	'admin:statistics:numentities' => 'Content Statistics',
	'admin:statistics:numentities:type' => 'Content type',
	'admin:statistics:numentities:number' => 'Number',
	'admin:statistics:numentities:searchable' => 'Searchable entities',
	'admin:statistics:numentities:other' => 'Other entities',

	'admin:widget:admin_welcome' => 'Welcome',
	'admin:widget:admin_welcome:help' => "A short introduction to Elgg's admin area",
	'admin:widget:admin_welcome:intro' => 'Welcome to Elgg! Right now you are looking at the administration dashboard. It\'s useful for tracking what\'s happening on the site.',

	'admin:widget:admin_welcome:registration' => "Registration for new users is currently disabled! You can enabled this on the %s page.",
	'admin:widget:admin_welcome:admin_overview' => "Navigation for the administration area is provided by the menu to the right. It is organized into
three sections:
	<dl>
		<dt>Administer</dt><dd>Basic tasks like managing users, monitoring reported content and activating plugins.</dd>
		<dt>Configure</dt><dd>Occasional tasks like setting the site name or configuring settings of a plugin.</dd>
		<dt>Information</dt><dd>Information about your site like statistics.</dd>
		<dt>Develop</dt><dd>For developers who are building plugins or designing themes. (Requires a developer plugin.)</dd>
	</dl>",

	// argh, this is ugly
	'admin:widget:admin_welcome:outro' => '<br />Be sure to check out the resources available through the footer links and thank you for using Elgg!',

	'admin:widget:control_panel' => 'Control panel',
	'admin:widget:control_panel:help' => "Provides easy access to common controls",

	'admin:cache:flush' => 'Flush the caches',
	'admin:cache:flushed' => "The site's caches have been flushed",
	'admin:cache:invalidate' => 'Invalidate the caches',
	'admin:cache:invalidated' => "The site's caches have been invalidated",
	'admin:cache:clear' => 'Clear the caches',
	'admin:cache:cleared' => "The site's caches have been cleared",
	'admin:cache:purge' => 'Purge the caches',
	'admin:cache:purged' => "The site's caches have been purged",

	'admin:footer:faq' => 'Administration FAQ',
	'admin:footer:manual' => 'Administration Manual',
	'admin:footer:community_forums' => 'Elgg Community Forums',
	'admin:footer:blog' => 'Elgg Blog',

	'admin:plugins:category:all' => 'All plugins',
	'admin:plugins:category:active' => 'Active plugins',
	'admin:plugins:category:inactive' => 'Inactive plugins',
	'admin:plugins:category:admin' => 'Admin',
	'admin:plugins:category:bundled' => 'Bundled',
	'admin:plugins:category:nonbundled' => 'Non-bundled',
	'admin:plugins:category:content' => 'Content',
	'admin:plugins:category:development' => 'Development',
	'admin:plugins:category:enhancement' => 'Enhancements',
	'admin:plugins:category:api' => 'Service/API',
	'admin:plugins:category:communication' => 'Communication',
	'admin:plugins:category:security' => 'Security and Spam',
	'admin:plugins:category:social' => 'Social',
	'admin:plugins:category:multimedia' => 'Multimedia',
	'admin:plugins:category:theme' => 'Themes',
	'admin:plugins:category:widget' => 'Widgets',
	'admin:plugins:category:utility' => 'Utilities',

	'admin:plugins:markdown:unknown_plugin' => 'Unknown plugin.',
	'admin:plugins:markdown:unknown_file' => 'Unknown file.',

	'admin:notices:delete_all' => 'Dismiss all %s notices',
	'admin:notices:could_not_delete' => 'Could not delete notice.',
	'item:object:admin_notice' => 'Admin notice',
	'collection:object:admin_notice' => 'Admin notices',

	'admin:options' => 'Admin options',

	'admin:security' => 'Security',
	'admin:security:information' => 'Information',
	'admin:security:information:description' => 'On this page you can find a list of security recommendations.',
	'admin:security:information:https' => 'Is the website protected by HTTPS',
	'admin:security:information:https:warning' => "It's recommended to protect your website using HTTPS, this helps protect data (eg. passwords) from being sniffed over the internet connection.",
	'admin:security:information:wwwroot' => 'Website main folder is writable',
	'admin:security:information:wwwroot:error' => "It's recommended that you install Elgg in a folder which isn't writable by your webserver. Malicious visitors could place unwanted code in your website.",
	'admin:security:information:validate_input' => 'Input validation',
	'admin:security:information:validate_input:error' => "Some plugin has disabled the input validation on your website, this will allow users to submit potentially harmfull content (eg. cross-site-scripting, etc)",
	'admin:security:information:password_length' => 'Minimal password length',
	'admin:security:information:password_length:warning' => "It's recommended to have a minimal password length of at least 6 characters.",
	'admin:security:information:username_length' => 'Minimal username length',
	'admin:security:information:username_length:warning' => "It's recommended to have a minimal username length of at least 4 characters.",
	'admin:security:information:php:session_gc' => "PHP session cleanup",
	'admin:security:information:php:session_gc:chance' => "Cleanup chance: %s%%",
	'admin:security:information:php:session_gc:lifetime' => "Session lifetime %s seconds",
	'admin:security:information:php:session_gc:error' => "It's recommended to set 'session.gc_probability' and 'session.gc_divisor' in your PHP settings,
this will cleanup expired sessions from your database and not allow users to reuse old sessions.",
	'admin:security:information:htaccess:hardening' => ".htaccess file access hardening",
	'admin:security:information:htaccess:hardening:help' => "In the .htaccess file access to certain files can be blocked to increase security on your site. For more information look in your .htaccess file.",
	
	'admin:security:settings' => 'Settings',
	'admin:security:settings:description' => 'On this page you can configure some security features. Please read the settings carefully.',
	'admin:security:settings:label:hardening' => 'Hardening',
	'admin:security:settings:label:account' => 'Account',
	'admin:security:settings:label:notifications' => 'Notifications',
	'admin:security:settings:label:site_secret' => 'Site secret',
	
	'admin:security:settings:notify_admins' => 'Notify all site administrators when an admin is added or removed',
	'admin:security:settings:notify_admins:help' => 'This will send out a notification to all site administrators that one of the admins added/removed a site administrator.',
	
	'admin:security:settings:notify_user_admin' => 'Notify the user when the admin role is added or removed',
	'admin:security:settings:notify_user_admin:help' => 'This will send a notification to the user that the admin role was added to/removed from their account.',
	
	'admin:security:settings:notify_user_ban' => 'Notify the user when their account gets (un)banned',
	'admin:security:settings:notify_user_ban:help' => 'This will send a notification to the user that their account was (un)banned.',
	
	'admin:security:settings:notify_user_password' => 'Notify the user when they change their password',
	'admin:security:settings:notify_user_password:help' => 'This will send a notification to the user when they change their password.',
	
	'admin:security:settings:protect_upgrade' => 'Protect upgrade.php',
	'admin:security:settings:protect_upgrade:help' => 'This will protect upgrade.php so you require a valid token or you\'ll have to be an administrator.',
	'admin:security:settings:protect_upgrade:token' => 'In order to be able to use the upgrade.php when logged out or as a non admin, the following URL needs to be used:',
	
	'admin:security:settings:protect_cron' => 'Protect the /cron URLs',
	'admin:security:settings:protect_cron:help' => 'This will protect the /cron URLs with a token, only if a valid token is provided will the cron execute.',
	'admin:security:settings:protect_cron:token' => 'In order to be able to use the /cron URLs the following tokens needs to be used. Please note that each interval has its own token.',
	'admin:security:settings:protect_cron:toggle' => 'Show/hide cron URLs',
	
	'admin:security:settings:disable_password_autocomplete' => 'Disable autocomplete on password fields',
	'admin:security:settings:disable_password_autocomplete:help' => 'Data entered in these fields will be cached by the browser. An attacker who can access the victim\'s browser could steal this information. This is especially important if the application is commonly used in shared computers such as cyber cafes or airport terminals. If you disable this, password management tools can no longer autofill these fields. The support for the autocomplete attribute can be browser specific.',
	
	'admin:security:settings:email_require_password' => 'Require password to change email address',
	'admin:security:settings:email_require_password:help' => 'When the user wishes to change their email address, require that they provide their current password.',
	
	'admin:security:settings:email_require_confirmation' => 'Require confirmation on email address change',
	'admin:security:settings:email_require_confirmation:help' => 'The new e-mail address needs to be confirmed before the change is in effect. After a successfull change a notification is send to the old e-mail address.',

	'admin:security:settings:session_bound_entity_icons' => 'Session bound entity icons',
	'admin:security:settings:session_bound_entity_icons:help' => 'Entity icons can be session bound by default. This means the URLs generated also contain information about the current session.
Having icons session bound makes icon urls not shareable between sessions. The side effect is that caching of these urls will only help the active session.',
	
	'admin:security:settings:site_secret:intro' => 'Elgg uses a key to create security tokens for various purposes.',
	'admin:security:settings:site_secret:regenerate' => "Regenerate site secret",
	'admin:security:settings:site_secret:regenerate:help' => "Note: Regenerating your site secret may inconvenience some users by invalidating tokens used in \"remember me\" cookies, e-mail validation requests, invitation codes, etc.",
	
	'admin:security:settings:minusername' => "Minimal username length",
	'admin:security:settings:minusername:help' => "Minimal number of characters required in a username",
	
	'admin:security:settings:min_password_length' => "Minimal password length",
	'admin:security:settings:min_password_length:help' => "Minimal number of characters required in a password",
	
	'admin:security:settings:min_password_lower' => "Minimal number of lower case characters in a password",
	'admin:security:settings:min_password_lower:help' => "Configure the minimal number of lower case (a-z) characters that should be present in a password. 0 for not present at all, empty for no requirements.",
	
	'admin:security:settings:min_password_upper' => "Minimal number of upper case characters in a password",
	'admin:security:settings:min_password_upper:help' => "Configure the minimal number of upper case (A-Z) characters that should be present in a password. 0 for not present at all, empty for no requirements.",
	
	'admin:security:settings:min_password_number' => "Minimal number of number characters in a password",
	'admin:security:settings:min_password_number:help' => "Configure the minimal number of number (0-9) characters that should be present in a password. 0 for not present at all, empty for no requirements.",
	
	'admin:security:settings:min_password_special' => "Minimal number of special characters in a password",
	'admin:security:settings:min_password_special:help' => "Configure the minimal number of special (!@$%^&*()<>,.?/[]{}-=_+) characters that should be present in a password. 0 for not present at all, empty for no requirements.",
	
	'admin:site:secret:regenerated' => "Your site secret has been regenerated",
	'admin:site:secret:prevented' => "The regeneration of the site secret was prevented",
	
	'admin:notification:make_admin:admin:subject' => 'A new site administrator was added to %s',
	'admin:notification:make_admin:admin:body' => '%s made %s a site administrator of %s.

To view the profile of the new administrator, click here:
%s',
	
	'admin:notification:make_admin:user:subject' => 'You were added as a site administator of %s',
	'admin:notification:make_admin:user:body' => '%s made you a site administrator of %s.

To go to the site, click here:
%s',
	'admin:notification:remove_admin:admin:subject' => 'A site administrator was removed from %s',
	'admin:notification:remove_admin:admin:body' => '%s removed %s as a site administrator of %s.

To view the profile of the old administrator, click here:
%s',
	
	'admin:notification:remove_admin:user:subject' => 'You were removed as a site administator from %s',
	'admin:notification:remove_admin:user:body' => '%s removed you as site administrator of %s.

To go to the site, click here:
%s',
	'user:notification:ban:subject' => 'Your account on %s was banned',
	'user:notification:ban:body' => 'Your account on %s was banned.

To go to the site, click here:
%s',
	
	'user:notification:unban:subject' => 'Your account on %s is no longer banned',
	'user:notification:unban:body' => 'Your account on %s is no longer banned. You can use the site again.

To go to the site, click here:
%s',
	
	'user:notification:password_change:subject' => 'Your password has been changed!',
	'user:notification:password_change:body' => "Your password on '%s' has been changed! If you made this change than you're all set.

If you didn't make this change, please reset your password here:
%s

Or contact a site administrator:
%s",
	
	'admin:notification:unvalidated_users:subject' => "Users awaiting approval on %s",
	'admin:notification:unvalidated_users:body' => "%d users of '%s' are awaiting approval by an administrator.

See the full list of users here:
%s",

/**
 * Plugins
 */

	'plugins:disabled' => 'Plugins are not being loaded because a file named "disabled" is in the mod directory.',
	'plugins:settings:save:ok' => "Settings for the %s plugin were saved successfully.",
	'plugins:settings:save:fail' => "There was a problem saving settings for the %s plugin.",
	'plugins:settings:remove:ok' => "All settings for the %s plugin have been removed",
	'plugins:settings:remove:fail' => "An error occured while removing all settings for the plugin %s",
	'plugins:usersettings:save:ok' => "User settings for the %s plugin were saved successfully.",
	'plugins:usersettings:save:fail' => "There was a problem saving  user settings for the %s plugin.",
	
	'item:object:plugin' => 'Plugin',
	'collection:object:plugin' => 'Plugins',
	
	'plugins:settings:remove:menu:text' => "Remove all settings",
	'plugins:settings:remove:menu:confirm' => "Are you sure you wish to remove all settings, including user settings from this plugin?",

	'admin:plugins' => "Plugins",
	'admin:plugins:activate_all' => 'Activate All',
	'admin:plugins:deactivate_all' => 'Deactivate All',
	'admin:plugins:activate' => 'Activate',
	'admin:plugins:deactivate' => 'Deactivate',
	'admin:plugins:description' => "This admin panel allows you to control and configure tools installed on your site.",
	'admin:plugins:opt:linktext' => "Configure tools...",
	'admin:plugins:opt:description' => "Configure the tools installed on the site. ",
	'admin:plugins:label:id' => "ID",
	'admin:plugins:label:name' => "Name",
	'admin:plugins:label:authors' => "Authors",
	'admin:plugins:label:copyright' => "Copyright",
	'admin:plugins:label:categories' => 'Categories',
	'admin:plugins:label:licence' => "License",
	'admin:plugins:label:website' => "URL",
	'admin:plugins:label:info' => "Info",
	'admin:plugins:label:files' => "Files",
	'admin:plugins:label:resources' => "Resources",
	'admin:plugins:label:screenshots' => "Screenshots",
	'admin:plugins:label:repository' => "Code",
	'admin:plugins:label:bugtracker' => "Report issue",
	'admin:plugins:label:donate' => "Donate",
	'admin:plugins:label:moreinfo' => 'more info',
	'admin:plugins:label:version' => 'Version',
	'admin:plugins:label:location' => 'Location',
	'admin:plugins:label:priority' => 'Priority',
	'admin:plugins:label:dependencies' => 'Dependencies',
	'admin:plugins:label:missing_dependency' => 'Missing dependency [%s].',

	'admin:plugins:warning:unmet_dependencies' => 'This plugin has unmet dependencies and cannot be activated. Check dependencies under more info.',
	'admin:plugins:warning:invalid' => 'This plugin is invalid: %s',
	'admin:plugins:warning:invalid:check_docs' => 'Check <a href="http://learn.elgg.org/en/stable/appendix/faqs.html">the Elgg documentation</a> for troubleshooting tips.',
	'admin:plugins:cannot_activate' => 'cannot activate',
	'admin:plugins:cannot_deactivate' => 'cannot deactivate',
	'admin:plugins:already:active' => 'The selected plugin(s) are already active.',
	'admin:plugins:already:inactive' => 'The selected plugin(s) are already inactive.',

	'admin:plugins:set_priority:yes' => "Reordered %s.",
	'admin:plugins:set_priority:no' => "Could not reorder %s.",
	'admin:plugins:deactivate:yes' => "Deactivated %s.",
	'admin:plugins:deactivate:no' => "Could not deactivate %s.",
	'admin:plugins:deactivate:no_with_msg' => "Could not deactivate %s. Error: %s",
	'admin:plugins:activate:yes' => "Activated %s.",
	'admin:plugins:activate:no' => "Could not activate %s.",
	'admin:plugins:activate:no_with_msg' => "Could not activate %s. Error: %s",
	'admin:plugins:categories:all' => 'All categories',
	'admin:plugins:plugin_website' => 'Plugin website',
	'admin:plugins:author' => '%s',
	'admin:plugins:version' => 'Version %s',
	'admin:plugin_settings' => 'Plugin Settings',
	'admin:plugins:warning:unmet_dependencies_active' => 'This plugin is active but has unmet dependencies. You may encounter problems. See "more info" below for details.',

	'admin:statistics:description' => "This is an overview of statistics on your site. If you need more detailed statistics, a professional administration feature is available.",
	'admin:statistics:opt:description' => "View statistical information about users and objects on your site.",
	'admin:statistics:opt:linktext' => "View statistics...",
	'admin:statistics:label:user' => "User statistics",
	'admin:statistics:label:numentities' => "Entities on site",
	'admin:statistics:label:numusers' => "Number of users",
	'admin:statistics:label:numonline' => "Number of users online",
	'admin:statistics:label:onlineusers' => "Users online now",
	'admin:statistics:label:admins'=>"Admins",
	'admin:statistics:label:version' => "Elgg version",
	'admin:statistics:label:version:release' => "Release",
	'admin:statistics:label:version:version' => "Database Version",
	'admin:statistics:label:version:code' => "Code Version",

	'admin:server:label:elgg' => 'Elgg',
	'admin:server:label:requirements' => 'Requirements',
	'admin:server:label:php' => 'PHP',
	'admin:server:label:phpinfo' => 'Show PHPInfo',
	'admin:server:label:web_server' => 'Web Server',
	'admin:server:label:server' => 'Server',
	'admin:server:label:log_location' => 'Log Location',
	'admin:server:label:php_version' => 'PHP version',
	'admin:server:label:php_version:required' => 'Elgg requires a minimal PHP version of 7.1',
	'admin:server:label:php_ini' => 'PHP ini file location',
	'admin:server:label:php_log' => 'PHP Log',
	'admin:server:label:mem_avail' => 'Memory available',
	'admin:server:label:mem_used' => 'Memory used',
	'admin:server:error_log' => "Web server's error log",
	'admin:server:label:post_max_size' => 'POST maximum size',
	'admin:server:label:upload_max_filesize' => 'Upload maximum size',
	'admin:server:warning:post_max_too_small' => '(Note: post_max_size must be larger than this value to support uploads of this size)',
	'admin:server:label:memcache' => 'Memcache',
	'admin:server:memcache:inactive' => 'Memcache is not setup on this server or it has not yet been configured in Elgg config.
For improved performance, it is recommended that you enable and configure memcache (or redis).',

	'admin:server:label:redis' => 'Redis',
	'admin:server:redis:inactive' => 'Redis is not setup on this server or it has not yet been configured in Elgg config.
For improved performance, it is recommended that you enable and configure redis (or memcache).',

	'admin:server:label:opcache' => 'OPcache',
	'admin:server:opcache:inactive' => 'OPcache is not available on this server or it has not yet been enabled.
For improved performance, it is recommended that you enable and configure OPcache.',
	
	'admin:server:requirements:php_extension' => "PHP extension: %s",
	'admin:server:requirements:php_extension:required' => "This PHP extension is required for the correct operation of Elgg",
	'admin:server:requirements:php_extension:recommended' => "This PHP extension is recommended for the optimal operation of Elgg",
	'admin:server:requirements:rewrite' => ".htaccess rewrite rules",
	'admin:server:requirements:rewrite:fail' => "Check your .htaccess for the correct rewrite rules",
	
	'admin:server:requirements:database:server' => "Database server",
	'admin:server:requirements:database:server:required' => "Elgg requires MySQL v5.5.3 or higher for its database",
	'admin:server:requirements:database:client' => "Database client",
	'admin:server:requirements:database:client:required' => "Elgg requires pdo_mysql to connect to the database server",
	
	'admin:user:label:search' => "Find users:",
	'admin:user:label:searchbutton' => "Search",

	'admin:user:ban:no' => "Can not ban user",
	'admin:user:ban:yes' => "User banned.",
	'admin:user:self:ban:no' => "You cannot ban yourself",
	'admin:user:unban:no' => "Can not unban user",
	'admin:user:unban:yes' => "User unbanned.",
	'admin:user:delete:no' => "Can not delete user",
	'admin:user:delete:yes' => "The user %s has been deleted",
	'admin:user:self:delete:no' => "You cannot delete yourself",

	'admin:user:resetpassword:yes' => "Password reset, user notified.",
	'admin:user:resetpassword:no' => "Password could not be reset.",

	'admin:user:makeadmin:yes' => "User is now an admin.",
	'admin:user:makeadmin:no' => "We could not make this user an admin.",

	'admin:user:removeadmin:yes' => "User is no longer an admin.",
	'admin:user:removeadmin:no' => "We could not remove administrator privileges from this user.",
	'admin:user:self:removeadmin:no' => "You cannot remove your own administrator privileges.",

	'admin:configure_utilities:menu_items' => 'Menu Items',
	'admin:menu_items:configure' => 'Configure main menu items',
	'admin:menu_items:description' => 'Select the order of site menu items. Unconfigured items will be added to the end of the list.',
	'admin:menu_items:hide_toolbar_entries' => 'Remove links from tool bar menu?',
	'admin:menu_items:saved' => 'Menu items saved.',
	'admin:add_menu_item' => 'Add a custom menu item',
	'admin:add_menu_item:description' => 'Fill out the Display name and URL to add custom items to your navigation menu.',

	'admin:configure_utilities:default_widgets' => 'Default Widgets',
	'admin:default_widgets:unknown_type' => 'Unknown widget type',
	'admin:default_widgets:instructions' => 'Add, remove, position, and configure default widgets for the selected widget page. These changes will only affect new users on the site.',

	'admin:robots.txt:instructions' => "Edit this site's robots.txt file below",
	'admin:robots.txt:plugins' => "Plugins are adding the following to the robots.txt file",
	'admin:robots.txt:subdir' => "The robots.txt tool will not work because Elgg is installed in a sub-directory",
	'admin:robots.txt:physical' => "The robots.txt tool will not work because a physical robots.txt is present",

	'admin:maintenance_mode:default_message' => 'This site is down for maintenance',
	'admin:maintenance_mode:instructions' => 'Maintenance mode should be used for upgrades and other large changes to the site. When it is on, only admins can log in and browse the site.',
	'admin:maintenance_mode:mode_label' => 'Maintenance mode',
	'admin:maintenance_mode:message_label' => 'Message displayed to users when maintenance mode is on',
	'admin:maintenance_mode:saved' => 'The maintenance mode settings were saved.',
	'admin:maintenance_mode:indicator_menu_item' => 'The site is in maintenance mode.',
	'admin:login' => 'Admin Login',

/**
 * User settings
 */

	'usersettings:description' => "The user settings panel allows you to control all your personal settings, from user management to how plugins behave. Choose an option below to get started.",

	'usersettings:statistics' => "Your statistics",
	'usersettings:statistics:opt:description' => "View statistical information about users and objects on your site.",
	'usersettings:statistics:opt:linktext' => "Account statistics",

	'usersettings:statistics:login_history' => "Login History",
	'usersettings:statistics:login_history:date' => "Date",
	'usersettings:statistics:login_history:ip' => "IP Address",

	'usersettings:user' => "%s's settings",
	'usersettings:user:opt:description' => "This allows you to control user settings.",
	'usersettings:user:opt:linktext' => "Change your settings",

	'usersettings:plugins' => "Tools",
	'usersettings:plugins:opt:description' => "Configure settings (if any) for your active tools.",
	'usersettings:plugins:opt:linktext' => "Configure your tools",

	'usersettings:plugins:description' => "This panel allows you to control and configure the personal settings for the tools installed by your system administrator.",
	'usersettings:statistics:label:numentities' => "Your content",

	'usersettings:statistics:yourdetails' => "Your details",
	'usersettings:statistics:label:name' => "Full name",
	'usersettings:statistics:label:email' => "Email",
	'usersettings:statistics:label:membersince' => "Member since",
	'usersettings:statistics:label:lastlogin' => "Last logged in",
	
	'usersettings:delayed_email' => "Delayed email settings",
	'usersettings:delayed_email:interval' => "Configure the interval at which delayed email notifications will be delivered",
	'usersettings:delayed_email:interval:help' => "All delayed email notifications will be saved up and delivered in one combined mail at the configured interval",

/**
 * Activity river
 */

	'river:all' => 'All Site Activity',
	'river:mine' => 'My Activity',
	'river:owner' => 'Activity of %s',
	'river:friends' => 'Friends Activity',
	'river:select' => 'Show %s',
	'river:comments:more' => '+%u more',
	'river:comments:all' => 'View all %u comments',
	'river:generic_comment' => 'commented on %s %s',

/**
 * Icons
 */

	'icon:size' => "Icon size",
	'icon:size:topbar' => "Topbar",
	'icon:size:tiny' => "Tiny",
	'icon:size:small' => "Small",
	'icon:size:medium' => "Medium",
	'icon:size:large' => "Large",
	'icon:size:master' => "Extra Large",
	
	'entity:edit:icon:crop_messages:generic' => "The selected image doesn't meet the recommended image dimensions. This could result in low quality icons.",
	'entity:edit:icon:crop_messages:width' => "It's recommended to use an image with a minimal width of at least %dpx.",
	'entity:edit:icon:crop_messages:height' => "It's recommended to use an image with a minimal height of at least %dpx.",
	'entity:edit:icon:file:label' => "Upload a new icon",
	'entity:edit:icon:file:help' => "Leave blank to keep current icon.",
	'entity:edit:icon:remove:label' => "Remove icon",

/**
 * Generic action words
 */

	'save' => "Save",
	'save_go' => "Save, and go to %s",
	'reset' => 'Reset',
	'publish' => "Publish",
	'cancel' => "Cancel",
	'saving' => "Saving ...",
	'update' => "Update",
	'preview' => "Preview",
	'edit' => "Edit",
	'delete' => "Delete",
	'accept' => "Accept",
	'reject' => "Reject",
	'decline' => "Decline",
	'approve' => "Approve",
	'activate' => "Activate",
	'deactivate' => "Deactivate",
	'disapprove' => "Disapprove",
	'revoke' => "Revoke",
	'load' => "Load",
	'upload' => "Upload",
	'download' => "Download",
	'ban' => "Ban",
	'unban' => "Unban",
	'banned' => "Banned",
	'enable' => "Enable",
	'disable' => "Disable",
	'request' => "Request",
	'complete' => "Complete",
	'open' => 'Open',
	'close' => 'Close',
	'hide' => 'Hide',
	'show' => 'Show',
	'reply' => "Reply",
	'more' => 'More',
	'more_info' => 'More info',
	'comments' => 'Comments',
	'import' => 'Import',
	'export' => 'Export',
	'untitled' => 'Untitled',
	'help' => 'Help',
	'send' => 'Send',
	'post' => 'Post',
	'submit' => 'Submit',
	'comment' => 'Comment',
	'upgrade' => 'Upgrade',
	'sort' => 'Sort',
	'filter' => 'Filter',
	'new' => 'New',
	'add' => 'Add',
	'create' => 'Create',
	'remove' => 'Remove',
	'revert' => 'Revert',
	'validate' => 'Validate',
	'read_more' => 'Read more',
	'next' => 'Next',
	'previous' => 'Previous',
	'older' => 'Older',
	'newer' => 'Newer',
	
	'site' => 'Site',
	'activity' => 'Activity',
	'members' => 'Members',
	'menu' => 'Menu',
	'item' => 'Item',

	'up' => 'Up',
	'down' => 'Down',
	'top' => 'Top',
	'bottom' => 'Bottom',
	'right' => 'Right',
	'left' => 'Left',
	'back' => 'Back',

	'invite' => "Invite",

	'resetpassword' => "Reset password",
	'changepassword' => "Change password",
	'makeadmin' => "Make admin",
	'removeadmin' => "Remove admin",

	'option:yes' => "Yes",
	'option:no' => "No",

	'unknown' => 'Unknown',
	'never' => 'Never',

	'active' => 'Active',
	'total' => 'Total',
	'unvalidated' => 'Unvalidated',
	
	'ok' => 'OK',
	'any' => 'Any',
	'error' => 'Error',

	'other' => 'Other',
	'options' => 'Options',
	'advanced' => 'Advanced',

	'learnmore' => "Click here to learn more.",
	'unknown_error' => 'Unknown error',

	'content' => "content",
	'content:latest' => 'Latest activity',
	
	'list:out_of_bounds' => "You have reached a part of the list without any content, however there is content available.",
	'list:out_of_bounds:link' => "Go back to the first page of this listing.",

	'link:text' => 'view link',

/**
 * Generic questions
 */

	'question:areyousure' => 'Are you sure?',

/**
 * Status
 */

	'status' => 'Status',
	'status:unsaved_draft' => 'Unsaved Draft',
	'status:draft' => 'Draft',
	'status:unpublished' => 'Unpublished',
	'status:published' => 'Published',
	'status:featured' => 'Featured',
	'status:open' => 'Open',
	'status:closed' => 'Closed',
	'status:enabled' => 'Enabled',
	'status:disabled' => 'Disabled',
	'status:unavailable' => 'Unavailable',
	'status:active' => 'Active',
	'status:inactive' => 'Inactive',

/**
 * Generic sorts
 */

	'sort:newest' => 'Newest',
	'sort:popular' => 'Popular',
	'sort:alpha' => 'Alphabetical',
	'sort:priority' => 'Priority',

/**
 * Generic data words
 */

	'title' => "Title",
	'description' => "Description",
	'tags' => "Tags",
	'all' => "All",
	'mine' => "Mine",

	'by' => 'by',
	'none' => 'none',

	'annotations' => "Annotations",
	'relationships' => "Relationships",
	'metadata' => "Metadata",
	'tagcloud' => "Tag cloud",

	'on' => 'On',
	'off' => 'Off',

	'number_counter:decimal_separator' => ".",
	'number_counter:thousands_separator' => ",",
	'number_counter:view:thousand' => "%sK",
	'number_counter:view:million' => "%sM",
	'number_counter:view:billion' => "%sB",
	'number_counter:view:trillion' => "%sT",

/**
 * Entity actions
 */

	'edit:this' => 'Edit this',
	'delete:this' => 'Delete this',
	'comment:this' => 'Comment on this',

/**
 * Input / output strings
 */

	'deleteconfirm' => "Are you sure you want to delete this item?",
	'deleteconfirm:plural' => "Are you sure you want to delete these items?",
	'fileexists' => "A file has already been uploaded. To replace it, select a new one below",
	'input:file:upload_limit' => 'Maximum allowed file size is %s',

/**
 * User add
 */

	'useradd:subject' => 'User account created',
	'useradd:body' => 'A user account has been created for you at %s. To log in, visit:

%s

And log in with these user credentials:

Username: %s
Password: %s

Once you have logged in, we highly recommend that you change your password.',

/**
 * Messages
 */
	'messages:title:success' => 'Success',
	'messages:title:error' => 'Error',
	'messages:title:warning' => 'Warning',
	'messages:title:help' => 'Help',
	'messages:title:notice' => 'Notice',
	'messages:title:info' => 'Info',

/**
 * Time
 */

	'input:date_format' => 'Y-m-d',
	'input:date_format:datepicker' => 'yy-mm-dd', // jQuery UI datepicker format
	'input:time_format' => 'g:ia',

	'friendlytime:justnow' => "just now",
	'friendlytime:minutes' => "%s minutes ago",
	'friendlytime:minutes:singular' => "a minute ago",
	'friendlytime:hours' => "%s hours ago",
	'friendlytime:hours:singular' => "an hour ago",
	'friendlytime:days' => "%s days ago",
	'friendlytime:days:singular' => "yesterday",
	'friendlytime:date_format' => 'j F Y @ g:ia',
	'friendlytime:date_format:short' => 'j M Y',

	'friendlytime:future:minutes' => "in %s minutes",
	'friendlytime:future:minutes:singular' => "in a minute",
	'friendlytime:future:hours' => "in %s hours",
	'friendlytime:future:hours:singular' => "in an hour",
	'friendlytime:future:days' => "in %s days",
	'friendlytime:future:days:singular' => "tomorrow",

	'date:month:01' => 'January %s',
	'date:month:02' => 'February %s',
	'date:month:03' => 'March %s',
	'date:month:04' => 'April %s',
	'date:month:05' => 'May %s',
	'date:month:06' => 'June %s',
	'date:month:07' => 'July %s',
	'date:month:08' => 'August %s',
	'date:month:09' => 'September %s',
	'date:month:10' => 'October %s',
	'date:month:11' => 'November %s',
	'date:month:12' => 'December %s',

	'date:month:short:01' => 'Jan %s',
	'date:month:short:02' => 'Feb %s',
	'date:month:short:03' => 'Mar %s',
	'date:month:short:04' => 'Apr %s',
	'date:month:short:05' => 'May %s',
	'date:month:short:06' => 'Jun %s',
	'date:month:short:07' => 'Jul %s',
	'date:month:short:08' => 'Aug %s',
	'date:month:short:09' => 'Sep %s',
	'date:month:short:10' => 'Oct %s',
	'date:month:short:11' => 'Nov %s',
	'date:month:short:12' => 'Dec %s',

	'date:weekday:0' => 'Sunday',
	'date:weekday:1' => 'Monday',
	'date:weekday:2' => 'Tuesday',
	'date:weekday:3' => 'Wednesday',
	'date:weekday:4' => 'Thursday',
	'date:weekday:5' => 'Friday',
	'date:weekday:6' => 'Saturday',

	'date:weekday:short:0' => 'Sun',
	'date:weekday:short:1' => 'Mon',
	'date:weekday:short:2' => 'Tue',
	'date:weekday:short:3' => 'Wed',
	'date:weekday:short:4' => 'Thu',
	'date:weekday:short:5' => 'Fri',
	'date:weekday:short:6' => 'Sat',

	'interval:minute' => 'Every minute',
	'interval:fiveminute' => 'Every five minutes',
	'interval:fifteenmin' => 'Every fifteen minutes',
	'interval:halfhour' => 'Every half hour',
	'interval:hourly' => 'Hourly',
	'interval:daily' => 'Daily',
	'interval:weekly' => 'Weekly',
	'interval:monthly' => 'Monthly',
	'interval:yearly' => 'Yearly',

/**
 * System settings
 */

	'installation:sitename' => "The name of your site:",
	'installation:sitedescription' => "Short description of your site (optional):",
	'installation:sitedescription:help' => "With bundled plugins this appears only in the description meta tag for search engine results.",
	'installation:wwwroot' => "The site URL:",
	'installation:path' => "The full path of the Elgg installation:",
	'installation:dataroot' => "The full path of the data directory:",
	'installation:dataroot:warning' => "You must create this directory manually. It should be in a different directory to your Elgg installation.",
	'installation:sitepermissions' => "The default access permissions:",
	'installation:language' => "The default language for your site:",
	'installation:debug' => "Control the amount of information written to the server's log.",
	'installation:debug:label' => "Log level:",
	'installation:debug:none' => 'Turn off logging (recommended)',
	'installation:debug:error' => 'Log only critical errors',
	'installation:debug:warning' => 'Log errors and warnings',
	'installation:debug:notice' => 'Log all errors, warnings and notices',
	'installation:debug:info' => 'Log everything',

	// Walled Garden support
	'installation:registration:description' => 'If enabled, visitors can create their own user accounts.',
	'installation:registration:label' => 'Allow visitors to register',
	'installation:adminvalidation:description' => 'If enabled, newly registered users require manual validation by an administrator before they can use the site.',
	'installation:adminvalidation:label' => 'New users require manual validation by an administrator',
	'installation:adminvalidation:notification:description' => 'When enabled, site administrators will get a notification that there are pending user validations. An administrator can disable the notification on their personal settings page.',
	'installation:adminvalidation:notification:label' => 'Notify administrators of pending user validations',
	'installation:adminvalidation:notification:direct' => 'Direct',
	'installation:walled_garden:description' => 'If enabled, logged-out visitors can see only pages marked public (such as login and registration).',
	'installation:walled_garden:label' => 'Restrict pages to logged-in users',

	'installation:view' => "Enter the view which will be used as the default for your site or leave this blank for the default view (if in doubt, leave as default):",

	'installation:siteemail' => "Site email address (used when sending system emails):",
	'installation:siteemail:help' => "Warning: Do no use an email address that you may have associated with other third-party services, such as ticketing systems, that perform inbound email parsing, as it may expose you and your users to unintentional leakage of private data and security tokens. Ideally, create a new dedicated email address that will serve only this website.",
	'installation:default_limit' => "Default number of items per page",

	'admin:site:access:warning' => "This is the privacy setting suggested to users when they create new content. Changing it does not change access to content.",
	'installation:allow_user_default_access:description' => "Enable this to allow users to set their own suggested privacy setting that overrides the system suggestion.",
	'installation:allow_user_default_access:label' => "Allow user default access",

	'installation:simplecache:description' => "The simple cache increases performance by caching static content including some CSS and JavaScript files.",
	'installation:simplecache:label' => "Use simple cache (recommended)",

	'installation:cache_symlink:description' => "The symbolic link to the simple cache directory allows the server to serve static views bypassing the engine, which considerably improves performance and reduces the server load",
	'installation:cache_symlink:label' => "Use symbolic link to simple cache directory (recommended)",
	'installation:cache_symlink:warning' => "Symbolic link has been established. If, for some reason, you want to remove the link, delete the symbolic link directory from your server",
	'installation:cache_symlink:paths' => 'Correctly configured symbolic link must link <i>%s</i> to <i>%s</i>',
	'installation:cache_symlink:error' => "Due to your server configuration the symbolic link can not be established automatically. Please refer to the documentation and establish the symbolic link manually.",

	'installation:minify:description' => "The simple cache can also improve performance by compressing JavaScript and CSS files. (Requires that simple cache is enabled.)",
	'installation:minify_js:label' => "Compress JavaScript (recommended)",
	'installation:minify_css:label' => "Compress CSS (recommended)",

	'installation:htaccess:needs_upgrade' => "You must update your .htaccess file (use install/config/htaccess.dist as a guide).",
	'installation:htaccess:localhost:connectionfailed' => "Elgg cannot connect to itself to test rewrite rules properly. Check that curl is working and there are no IP restrictions preventing localhost connections.",

	'installation:systemcache:description' => "The system cache decreases the loading time of Elgg by caching data to files.",
	'installation:systemcache:label' => "Use system cache (recommended)",

	'admin:legend:system' => 'System',
	'admin:legend:caching' => 'Caching',
	'admin:legend:content' => 'Content',
	'admin:legend:content_access' => 'Content Access',
	'admin:legend:site_access' => 'Site Access',
	'admin:legend:debug' => 'Debugging and Logging',
	
	'config:i18n:allowed_languages' => "Allowed languages",
	'config:i18n:allowed_languages:help' => "Only allowed languages can be used by users. English and the site language are always allowed.",
	'config:users:can_change_username' => "Allow users to change their username",
	'config:users:can_change_username:help' => "If not allowed only admins can change a users username",
	'config:remove_branding:label' => "Remove Elgg branding",
	'config:remove_branding:help' => "Throughout the site there are various links and logo's that show this site is made using Elgg. If you remove the branding consider donating on https://elgg.org/about/supporters",
	'config:disable_rss:label' => "Disable RSS feeds",
	'config:disable_rss:help' => "Disable this to no longer promote the availability of RSS feeds",
	'config:friendly_time_number_of_days:label' => "Number of days friendly time is presented",
	'config:friendly_time_number_of_days:help' => "You can configure how many days the friendly time notation is used. After the set amount of days the friendly time will change into a regular date format. Setting this to 0 will disable the friendly time format.",
	'config:content:comment_box_collapses' => "The comment box collapses after the first comment on content",
	'config:content:comment_box_collapses:help' => "This only applies if the comments list is sorted latest first",
	'config:content:comments_latest_first' => "The comments should be listed with the latest comment first",
	'config:content:comments_latest_first:help' => "This controls the default behaviour of the listing of comments on a content detail page. If disabled this will also move the comment box to the end of the comments list",
	'config:content:comments_per_page' => "The number of comments per page",
	'config:content:pagination_behaviour' => "Default pagination behaviour of lists",
	'config:content:pagination_behaviour:help' => "Controls how list data is updated when using pagination. Individual listings can override this default behaviour.",
	'config:content:pagination_behaviour:navigate' => "Navigate to the next page",
	'config:content:pagination_behaviour:ajax-replace' => "Replace the list data without reloading the full page",
	'config:content:pagination_behaviour:ajax-append' => "Append new list data before or after the list",
	'config:content:pagination_behaviour:ajax-append-auto' => "Append new list data before or after the list (automatically if scrolled into view)",
	'config:email' => "Email",
	'config:email_html_part:label' => "Enable HTML mail",
	'config:email_html_part:help' => "Outgoing mail will be wrapped in a HTML template",
	'config:email_html_part_images:label' => "Replace email images",
	'config:email_html_part_images:help' => "Control if and how images in outgoing emails should be processed. When enabled all images will be embedded in the e-mails. Not all e-mail clients support the different options, be sure to test the chosen option.",
	'config:email_html_part_images:base64' => "Base64 encoded",
	'config:email_html_part_images:attach' => "Attachments",
	'config:delayed_email:label' => "Enable delayed email notifications",
	'config:delayed_email:help' => "Offer users delayed email notifications to bundle notifications received in a period (daily, weekly)",

	'upgrading' => 'Upgrading...',
	'upgrade:core' => 'Your Elgg installation was upgraded.',
	'upgrade:unlock' => 'Unlock upgrade',
	'upgrade:unlock:confirm' => "The database is locked for another upgrade. Running concurrent upgrades is dangerous. You should only continue if you know there is not another upgrade running. Unlock?",
	'upgrade:terminated' => 'Upgrade has been terminated by an event handler',
	'upgrade:locked' => "Cannot upgrade. Another upgrade is running. To clear the upgrade lock, visit the Admin section.",
	'upgrade:unlock:success' => "Upgrade unlocked successfully.",

	'admin:pending_upgrades' => 'The site has pending upgrades that require your immediate attention.',
	'admin:view_upgrades' => 'View pending upgrades.',
	'item:object:elgg_upgrade' => 'Site upgrade',
	'collection:object:elgg_upgrade' => 'Site upgrades',
	'admin:upgrades:none' => 'Your installation is up to date!',

	'upgrade:success_count' => 'Upgraded:',
	'upgrade:error_count' => 'Errors: %s',
	'upgrade:finished' => 'Upgrade finished',
	'upgrade:finished_with_errors' => '<p>Upgrade finished with errors. Refresh the page and try running the upgrade again.</p></p><br />If the error recurs, check the server error log for possible cause. You can seek help for fixing the error from the <a href="http://community.elgg.org/groups/profile/179063/elgg-technical-support">Technical support group</a> in the Elgg community.</p>',
	'upgrade:should_be_skipped' => 'No items to upgrade',
	'upgrade:count_items' => '%d items to upgrade',
	
	// Strings specific for the database guid columns reply upgrade
	'admin:upgrades:database_guid_columns' => 'Align database GUID columns',
	
/**
 * Welcome
 */

	'welcome' => "Welcome",
	'welcome:user' => 'Welcome %s',

/**
 * Emails
 */

	'email:from' => 'From',
	'email:to' => 'To',
	'email:subject' => 'Subject',
	'email:body' => 'Body',

	'email:settings' => "Email settings",
	'email:address:label' => "Email address",
	'email:address:help:confirm' => "Pending e-mail address change to '%s', please check the inbox for instructions.",
	'email:address:password' => "Password",
	'email:address:password:help' => "In order to be able to change your email address you need to provide your current password.",

	'email:save:success' => "New email address saved.",
	'email:save:fail' => "New email address could not be saved.",
	'email:save:fail:password' => "The password doesn't match your current password, could not change your email address",

	'friend:newfriend:subject' => "%s has made you a friend!",
	'friend:newfriend:body' => "%s has made you a friend!

To view their profile, click here:

%s",

	'email:changepassword:subject' => "Password changed!",
	'email:changepassword:body' => "Your password has been changed.",

	'email:resetpassword:subject' => "Password reset!",
	'email:resetpassword:body' => "Your password has been reset to: %s",

	'email:changereq:subject' => "Request for password change.",
	'email:changereq:body' => "Somebody (from the IP address %s) has requested a password change for this account.

If you requested this, click on the link below. Otherwise ignore this email.

%s",
	
	'account:email:request:success' => "Your new e-mail address will be saved after confirmation, please check the inbox of '%s' for more instructions.",
	'email:request:email:subject' => "Please confirm your e-mail address",
	'email:request:email:body' => "You requested to change your e-mail address on '%s'.
If you didn't request this change, you can ignore this email.

In order to confirm the e-mail address change, please click this link:
%s

Please note this link is only valid for 1 hour.",
	
	'account:email:request:error:no_new_email' => "No e-mail address change pending",
	
	'email:confirm:email:old:subject' => "You're e-mail address was changed",
	'email:confirm:email:old:body' => "Your e-mail address on '%s' was changed.
From now on you'll receive notifications on '%s'.

If you didn't request this change, please contact a site administrator.
%s",
	
	'email:confirm:email:new:subject' => "You're e-mail address was changed",
	'email:confirm:email:new:body' => "Your e-mail address on '%s' was changed.
From now on you'll receive notifications on this e-mail address.

If you didn't request this change, please contact a site administrator.
%s",

	'account:email:admin:validation_notification' => "Notify me when there are users requiring validation by an administrator",
	'account:email:admin:validation_notification:help' => "Because of the site settings, newly registered users require manual validation by an administrator. With this setting you can disable notifications about pending validation requests.",
	
	'account:validation:pending:title' => "Account validation pending",
	'account:validation:pending:content' => "Your account has been registered successfully! However before you can use you account a site administrator needs to validate you account. You'll receive an e-mail when you account is validated.",
	
	'account:notification:validation:subject' => "Your account on %s has been validated!",
	'account:notification:validation:body' => "Your account on '%s' has been validated. You can now use your account.

To go the the website, click here:
%s",

/**
 * user default access
 */

	'default_access:settings' => "Your default access level",
	'default_access:label' => "Default access",
	'user:default_access:success' => "Your new default access level was saved.",
	'user:default_access:failure' => "Your new default access level could not be saved.",

/**
 * Comments
 */

	'comments:count' => "%s comments",
	'item:object:comment' => 'Comment',
	'collection:object:comment' => 'Comments',
	'notification:object:comment:create' => "Send a notification when a comment is created",

	'river:object:default:comment' => '%s commented on %s',

	'generic_comments:add' => "Leave a comment",
	'generic_comments:edit' => "Edit comment",
	'generic_comments:post' => "Post comment",
	'generic_comments:text' => "Comment",
	'generic_comments:latest' => "Latest comments",
	'generic_comment:posted' => "Your comment was successfully posted.",
	'generic_comment:updated' => "The comment was successfully updated.",
	'entity:delete:object:comment:success' => "The comment was successfully deleted.",
	'generic_comment:blank' => "Sorry, you need to actually put something in your comment before we can save it.",
	'generic_comment:notfound' => "Sorry, we could not find the specified comment.",
	'generic_comment:failure' => "An unexpected error occurred when saving the comment.",
	'generic_comment:none' => 'No comments',
	'generic_comment:title' => 'Comment by %s',
	'generic_comment:on' => '%s on %s',
	'generic_comments:latest:posted' => 'posted a',

	'generic_comment:notification:subject' => 'Re: %s',
	'generic_comment:notification:owner:summary' => 'You have a new comment on: %s',
	'generic_comment:notification:owner:body' => "You have a new comment. It reads:

%s

To reply or view the original item, click here:
%s",
	
	'generic_comment:notification:user:summary' => 'A new comment on: %s',
	'generic_comment:notification:user:body' => "A new comment was made. It reads:

%s

To reply or view the original item, click here:
%s",

/**
 * Entities
 */

	'byline' => 'By %s',
	'byline:ingroup' => 'in the group %s',
	
	'entity:delete:item' => 'Item',
	'entity:delete:item_not_found' => 'Item not found.',
	'entity:delete:permission_denied' => 'You do not have permissions to delete this item.',
	'entity:delete:success' => '%s has been deleted.',
	'entity:delete:fail' => '%s could not be deleted.',
	
	'entity:subscribe' => "Subscribe",
	'entity:subscribe:disabled' => "Your default notification settings prevent you from subscribing to this content",
	'entity:subscribe:success' => "You've successfully subscribed to %s",
	'entity:subscribe:fail' => "An error occured while subscribing to %s",
	
	'entity:unsubscribe' => "Unsubscribe",
	'entity:unsubscribe:success' => "You've successfully unsubscribed from %s",
	'entity:unsubscribe:fail' => "An error occured while unsubscribing from %s",
	
	'entity:mute' => "Mute notifications",
	'entity:mute:success' => "You've successfully muted notifications of %s",
	'entity:mute:fail' => "An error occured while muting notifications of %s",
	
	'entity:unmute' => "Unmute notifications",
	'entity:unmute:success' => "You've successfully unmuted notifications of %s",
	'entity:unmute:fail' => "An error occured while unmuting notifications of %s",

/**
 * Annotations
 */
	
	'annotation:delete:fail' => "An error occured while removing the annotation",
	'annotation:delete:success' => "The annotation was removed successfully",
	
/**
 * Action gatekeeper
 */

	'actiongatekeeper:missingfields' => 'Form is missing __token or __ts fields',
	'actiongatekeeper:tokeninvalid' => "The page you were using had expired. Please try again.",
	'actiongatekeeper:timeerror' => 'The page you were using has expired. Please refresh and try again.',
	'actiongatekeeper:pluginprevents' => 'Sorry. Your form could not be submitted for an unknown reason.',
	'actiongatekeeper:uploadexceeded' => 'The size of file(s) uploaded exceeded the limit set by your site administrator',

/**
 * Javascript
 */

	'js:security:token_refresh_failed' => 'Failed to contact %s. You may experience problems saving content. Please refresh this page.',
	'js:lightbox:current' => "image %s of %s",

/**
 * Diagnostics
 */
	'diagnostics:report' => 'Diagnostics Report',
	'diagnostics:description' => 'The following diagnostic report can be useful for diagnosing problems with Elgg. The developers of Elgg may request that you attach it to a bug report.',
	'diagnostics:header' => '========================================================================
Elgg Diagnostic Report
Generated %s by %s
========================================================================

',
	'diagnostics:report:basic' => '
Elgg Release %s, version %s

------------------------------------------------------------------------',
	'diagnostics:report:php' => '
PHP info:
%s
------------------------------------------------------------------------',
	'diagnostics:report:md5' => '
Installed files and checksums:

%s
------------------------------------------------------------------------',
	'diagnostics:report:globals' => '
Global variables:

%s
------------------------------------------------------------------------',
	
/**
 * Miscellaneous
 */
	'elgg:powered' => "Powered by Elgg",
	
/**
 * Cli commands
 */
	'cli:login:error:unknown' => "Unable to login as %s",
	'cli:login:success:log' => "Logged in as %s [guid: %s]",
	'cli:response:output' => "Response:",
	'cli:option:as' => "Execute the command on behalf of a user with the given username",
	'cli:option:language' => "Execute the command in the given language (eg. en, nl or de)",
	
	'cli:cache:clear:description' => "Clear Elgg caches",
	'cli:cache:invalidate:description' => "Invalidate Elgg caches",
	'cli:cache:purge:description' => "Purge Elgg caches",
	
	'cli:cron:description' => "Execute cron handlers for all or specified interval",
	'cli:cron:option:interval' => "Name of the interval (e.g. hourly)",
	'cli:cron:option:force' => "Force cron commands to run even if they are not yet due",
	'cli:cron:option:time' => "Time of the cron initialization",
	
	'cli:database:seed:description' => "Seeds the database with fake entities",
	'cli:database:seed:argument:create' => "Always create new entities during seeding",
	'cli:database:seed:option:limit' => "Number of entities to seed",
	'cli:database:seed:option:image_folder' => "Path to a local folder containing images for seeding",
	'cli:database:seed:option:type' => "Type of entities to (un)seed (%s)",
	'cli:database:seed:option:create_since' => "A PHP time string to set the lower bound creation time of seeded entities",
	'cli:database:seed:option:create_until' => "A PHP time string to set the upper bound creation time of seeded entities",
	'cli:database:seed:log:error:faker' => "This is a developer tool currently intended for testing purposes only. Please refrain from using it.",
	'cli:database:seed:log:error:logged_in' => "Database seeding should not be run with a logged in user",
	
	'cli:database:unseed:description' => "Removes seeded fake entities from the database",
	
	'cli:plugins:activate:description' => "Activate plugin(s)",
	'cli:plugins:activate:option:force' => "Resolve conflicts by deactivating conflicting plugins and enabling required ones",
	'cli:plugins:activate:argument:plugins' => "Plugin IDs to be activated",
	'cli:plugins:activate:progress:start' => "Activating plugins",
	
	'cli:plugins:deactivate:description' => "Deactivate plugin(s)",
	'cli:plugins:deactivate:option:force' => "Force deactivation of all dependent plugins",
	'cli:plugins:deactivate:argument:plugins' => "Plugin IDs to be deactivated",
	'cli:plugins:deactivate:progress:start' => "Deactivating plugins",
	
	'cli:plugins:list:description' => "List all plugins installed on the site",
	'cli:plugins:list:option:status' => "Plugin status ( %s )",
	'cli:plugins:list:option:refresh' => "Refresh plugin list with recently installed plugins",
	'cli:plugins:list:error:status' => "%s is not a valid status. Allowed options are: %s",
	
	'cli:simpletest:description' => "Run simpletest test suite (deprecated)",
	'cli:simpletest:option:config' => "Path to settings file that the Elgg Application should be bootstrapped with",
	'cli:simpletest:option:plugins' => "A list of plugins to enable for testing or 'all' to enable all plugins",
	'cli:simpletest:option:filter' => "Only run tests that match filter pattern",
	'cli:simpletest:error:class' => "You must install your Elgg application using '%s'",
	'cli:simpletest:error:file' => "%s is not a valid simpletest class",
	'cli:simpletest:output:summary' => "Time: %.2f seconds, Memory: %.2fMb",
	
	'cli:upgrade:description' => "Run system upgrades",
	'cli:upgrade:option:force' => "Force the upgrades to run even if currently an upgrade is already running.",
	'cli:upgrade:argument:async' => "Execute pending asynchronous upgrades",
	'cli:upgrade:system:upgraded' => "System upgrades have been executed",
	'cli:upgrade:system:failed' => "System upgrades have failed",
	'cli:upgrade:async:upgraded' => "Asynchronous upgrades have been executed",
	'cli:upgrade:aysnc:failed' => "Asynchronous upgrades have failed",
	
	'cli:upgrade:batch:description' => "Executes one or more upgrades",
	'cli:upgrade:batch:argument:upgrades' => "One or more upgrades (class names) to be executed",
	'cli:upgrade:batch:option:force' => "Run upgrade even if it has been completed before",
	'cli:upgrade:batch:finished' => "Running upgrades finished",
	'cli:upgrade:batch:notfound' => "No upgrade class found for %s",

	'cli:upgrade:list:description' => "Lists all upgrades in the system",
	'cli:upgrade:list:completed' => "Completed upgrades",
	'cli:upgrade:list:pending' => "Pending upgrades",
	'cli:upgrade:list:notfound' => "No upgrades found",
	
/**
 * Languages according to ISO 639-1 (with a couple of exceptions)
 */

	"aa" => "Afar",
	"ab" => "Abkhazian",
	"af" => "Afrikaans",
	"am" => "Amharic",
	"ar" => "Arabic",
	"as" => "Assamese",
	"ay" => "Aymara",
	"az" => "Azerbaijani",
	"ba" => "Bashkir",
	"be" => "Byelorussian",
	"bg" => "Bulgarian",
	"bh" => "Bihari",
	"bi" => "Bislama",
	"bn" => "Bengali; Bangla",
	"bo" => "Tibetan",
	"br" => "Breton",
	"ca" => "Catalan",
	"cmn" => "Mandarin Chinese", // ISO 639-3
	"co" => "Corsican",
	"cs" => "Czech",
	"cy" => "Welsh",
	"da" => "Danish",
	"de" => "German",
	"dz" => "Bhutani",
	"el" => "Greek",
	"en" => "English",
	"eo" => "Esperanto",
	"es" => "Spanish",
	"et" => "Estonian",
	"eu" => "Basque",
	"eu_es" => "Basque (Spain)",
	"fa" => "Persian",
	"fi" => "Finnish",
	"fj" => "Fiji",
	"fo" => "Faeroese",
	"fr" => "French",
	"fy" => "Frisian",
	"ga" => "Irish",
	"gd" => "Scots / Gaelic",
	"gl" => "Galician",
	"gn" => "Guarani",
	"gu" => "Gujarati",
	"he" => "Hebrew",
	"ha" => "Hausa",
	"hi" => "Hindi",
	"hr" => "Croatian",
	"hu" => "Hungarian",
	"hy" => "Armenian",
	"ia" => "Interlingua",
	"id" => "Indonesian",
	"ie" => "Interlingue",
	"ik" => "Inupiak",
	//"in" => "Indonesian",
	"is" => "Icelandic",
	"it" => "Italian",
	"iu" => "Inuktitut",
	"iw" => "Hebrew (obsolete)",
	"ja" => "Japanese",
	"ji" => "Yiddish (obsolete)",
	"jw" => "Javanese",
	"ka" => "Georgian",
	"kk" => "Kazakh",
	"kl" => "Greenlandic",
	"km" => "Cambodian",
	"kn" => "Kannada",
	"ko" => "Korean",
	"ks" => "Kashmiri",
	"ku" => "Kurdish",
	"ky" => "Kirghiz",
	"la" => "Latin",
	"ln" => "Lingala",
	"lo" => "Laothian",
	"lt" => "Lithuanian",
	"lv" => "Latvian/Lettish",
	"mg" => "Malagasy",
	"mi" => "Maori",
	"mk" => "Macedonian",
	"ml" => "Malayalam",
	"mn" => "Mongolian",
	"mo" => "Moldavian",
	"mr" => "Marathi",
	"ms" => "Malay",
	"mt" => "Maltese",
	"my" => "Burmese",
	"na" => "Nauru",
	"ne" => "Nepali",
	"nl" => "Dutch",
	"no" => "Norwegian",
	"oc" => "Occitan",
	"om" => "(Afan) Oromo",
	"or" => "Oriya",
	"pa" => "Punjabi",
	"pl" => "Polish",
	"ps" => "Pashto / Pushto",
	"pt" => "Portuguese",
	"pt_br" => "Portuguese (Brazil)",
	"qu" => "Quechua",
	"rm" => "Rhaeto-Romance",
	"rn" => "Kirundi",
	"ro" => "Romanian",
	"ro_ro" => "Romanian (Romania)",
	"ru" => "Russian",
	"rw" => "Kinyarwanda",
	"sa" => "Sanskrit",
	"sd" => "Sindhi",
	"sg" => "Sangro",
	"sh" => "Serbo-Croatian",
	"si" => "Singhalese",
	"sk" => "Slovak",
	"sl" => "Slovenian",
	"sm" => "Samoan",
	"sn" => "Shona",
	"so" => "Somali",
	"sq" => "Albanian",
	"sr" => "Serbian",
	"sr_latin" => "Serbian (Latin)",
	"ss" => "Siswati",
	"st" => "Sesotho",
	"su" => "Sundanese",
	"sv" => "Swedish",
	"sw" => "Swahili",
	"ta" => "Tamil",
	"te" => "Tegulu",
	"tg" => "Tajik",
	"th" => "Thai",
	"ti" => "Tigrinya",
	"tk" => "Turkmen",
	"tl" => "Tagalog",
	"tn" => "Setswana",
	"to" => "Tonga",
	"tr" => "Turkish",
	"ts" => "Tsonga",
	"tt" => "Tatar",
	"tw" => "Twi",
	"ug" => "Uigur",
	"uk" => "Ukrainian",
	"ur" => "Urdu",
	"uz" => "Uzbek",
	"vi" => "Vietnamese",
	"vo" => "Volapuk",
	"wo" => "Wolof",
	"xh" => "Xhosa",
	//"y" => "Yiddish",
	"yi" => "Yiddish",
	"yo" => "Yoruba",
	"za" => "Zuang",
	"zh" => "Chinese",
	"zh_hans" => "Chinese Simplified",
	"zu" => "Zulu",

	"field:required" => 'Required',

	"core:upgrade:2017080900:title" => "Alter database encoding for multi-byte support",
	"core:upgrade:2017080900:description" => "Alters database and table encoding to utf8mb4, in order to support multi-byte characters such as emoji",
	
	"core:upgrade:2020102301:title" => "Remove the diagnostics plugin",
	"core:upgrade:2020102301:description" => "Deletes the entity associated with the Diagnostics plugin removed in Elgg 4.0",
	
	"core:upgrade:2021022401:title" => "Migrate notification subscriptions",
	"core:upgrade:2021022401:description" => "Notifcation subscriptions are stored differently in the database. Use this upgrade to migrate all subscriptions to the new form.",
	
	"core:upgrade:2021040701:title" => "Migrate user notification settings",
	"core:upgrade:2021040701:description" => "In order to have a more developer friendly way to store notification settings of a user a migration is needed to the new naming convention.",
	
	'core:upgrade:2021040801:title' => "Migrate Access collection notification preferences",
	'core:upgrade:2021040801:description' => "A new way to store notification preferences has been introduced. This upgrade migrates the old settings to the new logic.",
	
	'core:upgrade:2021041901:title' => "Remove the notifications plugin",
	'core:upgrade:2021041901:description' => "Deletes the entity associated with the Notifications plugin removed in Elgg 4.0",
	
	'core:upgrade:2021060401:title' => "Add content owners to the subscribers",
	'core:upgrade:2021060401:description' => "Content owners should be subscribed on their own content, this upgrade migrates all old content.",
);
