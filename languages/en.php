<?php
/**
 * Core English Language
 *
 * @package Elgg.Core
 * @subpackage Languages.English
 */

$english = array(

/**
 * Sites
 */

	'item:site' => 'Sites',

/**
 * Sessions
 */

	'login' => "Log in",
	'loginok' => "You have been logged in.",
	'loginerror' => "We couldn't log you in. Please check your credentials and try again.",
	'login:empty' => "Username and password are required.",
	'login:baduser' => "Unable to load your user account.",
	'auth:nopams' => "Internal error. No user authentication method installed.",

	'logout' => "Log out",
	'logoutok' => "You have been logged out.",
	'logouterror' => "We couldn't log you out. Please try again.",

	'loggedinrequired' => "You must be logged in to view that page.",
	'adminrequired' => "You must be an administrator to view that page.",
	'membershiprequired' => "You must be a member of this group to view that page.",


/**
 * Errors
 */
	'exception:title' => "Fatal Error.",

	'actionundefined' => "The requested action (%s) was not defined in the system.",
	'actionnotfound' => "The action file for %s was not found.",
	'actionloggedout' => "Sorry, you cannot perform this action while logged out.",
	'actionunauthorized' => 'You are unauthorized to perform this action',

	'InstallationException:SiteNotInstalled' => 'Unable to handle this request. This site '
		. ' is not configured or the database is down.',
	'InstallationException:MissingLibrary' => 'Could not load %s',
	'InstallationException:CannotLoadSettings' => 'Elgg could not load the settings file. It does not exist or there is a file permissions issue.',

	'SecurityException:Codeblock' => "Denied access to execute privileged code block",
	'DatabaseException:WrongCredentials' => "Elgg couldn't connect to the database using the given credentials. Check the settings file.",
	'DatabaseException:NoConnect' => "Elgg couldn't select the database '%s', please check that the database is created and you have access to it.",
	'SecurityException:FunctionDenied' => "Access to privileged function '%s' is denied.",
	'DatabaseException:DBSetupIssues' => "There were a number of issues: ",
	'DatabaseException:ScriptNotFound' => "Elgg couldn't find the requested database script at %s.",

	'IOException:FailedToLoadGUID' => "Failed to load new %s from GUID:%d",
	'InvalidParameterException:NonElggObject' => "Passing a non-ElggObject to an ElggObject constructor!",
	'InvalidParameterException:UnrecognisedValue' => "Unrecognised value passed to constuctor.",

	'InvalidClassException:NotValidElggStar' => "GUID:%d is not a valid %s",

	'PluginException:MisconfiguredPlugin' => "%s is a misconfigured plugin. It has been disabled. Please search the Elgg wiki for possible causes (http://docs.elgg.org/wiki/).",
	'PluginException:InvalidID' => "%s is an invalid plugin ID.",
	'PluginException:InvalidPath' => "%s is an invalid plugin path.",
	'PluginException:InvalidManifest' => 'Invalid manifest file for plugin %s',
	'PluginException:InvalidPlugin' => '%s is not a valid plugin.',
	'PluginException:InvalidPlugin:Details' => '%s is not a valid plugin: %s',
	'ElggPluginPackage:InvalidPlugin:MissingFile' => 'Missing file %s in package',
	'ElggPluginPackage:InvalidPlugin:InvalidDependency' => 'Invalid dependency type "%s"',
	'ElggPluginPackage:InvalidPlugin:InvalidProvides' => 'Invalid provides type "%s"',
	'ElggPluginPackage:InvalidPlugin:CircularDep' => 'Invalid %s dependency "%s" in plugin %s.  Plugins cannot conflict with or require something they provide!',

	'PluginException:ParserError' => 'Error parsing manifest with API version %s in plugin %s.',
	'PluginException:NoAvailableParser' => 'Cannot find a parser for manifest API version %s in plugin %s.',
	'PluginException:ParserErrorMissingRequiredAttribute' => "Missing required '%s' attribute in manifest for plugin %s.",

	'ElggPluginPackage:UnknownDep' => 'Unknown dependency %s in plugin %s',

	'ElggPluginPackage:Requires:Plugin:lt' => '%s requires the %s plugin below version %s.  Version %s is installed.',
	'ElggPluginPackage:Requires:Plugin:le' => '%s requires the %s plugin version %s or below.  Version %s is installed.',
	'ElggPluginPackage:Requires:Plugin:=' => '%s requires the %s plugin version %s.  Version %s is installed.',
	'ElggPluginPackage:Requires:Plugin:!=' => '%s requires the %s plugin not equal to version %s.  Version %s is installed.',
	'ElggPluginPackage:Requires:Plugin:ge' => '%s requires the %s plugin version %s or higher.  Version %s is installed.',
	'ElggPluginPackage:Requires:Plugin:gt' => '%s requires the %s plugin higher than version %s.  Version %s is installed.',

	'ElggPluginPackage:Requires:Plugin:NotEnabled:lt' => '%s requires the %s plugin below version %s.',
	'ElggPluginPackage:Requires:Plugin:NotEnabled:le' => '%s requires the %s plugin version %s or below.',
	'ElggPluginPackage:Requires:Plugin:NotEnabled:=' => '%s requires the %s plugin version %s.',
	'ElggPluginPackage:Requires:Plugin:NotEnabled:!=' => '%s requires the %s plugin not equal to version %s.',
	'ElggPluginPackage:Requires:Plugin:NotEnabled:ge' => '%s requires the %s plugin version %s or higher.',
	'ElggPluginPackage:Requires:Plugin:NotEnabled:gt' => '%s requires the %s plugin higher than version %s.',
	'ElggPluginPackage:Requires:Plugin:NotEnabled:NoVersion' => '%s requires the %s plugin.',

	'ElggPluginPackage:Requires:Elgg:lt' => '%s requires Elgg below version %s.',
	'ElggPluginPackage:Requires:Elgg:le' => '%s requires Elgg %s or below.',
	'ElggPluginPackage:Requires:Elgg:=' => '%s requires Elgg version %s.',
	'ElggPluginPackage:Requires:Elgg:!=' => '%s requires Elgg not equal to version %s.',
	'ElggPluginPackage:Requires:Elgg:ge' => '%s requires Elgg version %s or higher.',
	'ElggPluginPackage:Requires:Elgg:gt' => '%s requires Elgg higher than version %s.',

	'ElggPluginPackage:Requires:PhpExtension:NotInstalled:lt' => '%s requires the %s PHP extension below version %s.',
	'ElggPluginPackage:Requires:PhpExtension:NotInstalled:le' => '%s requires the %s PHP extension version %s or below.',
	'ElggPluginPackage:Requires:PhpExtension:NotInstalled:=' => '%s requires the %s PHP extension version %s.',
	'ElggPluginPackage:Requires:PhpExtension:NotInstalled:!=' => '%s requires the %s PHP extension not equal to version %s.',
	'ElggPluginPackage:Requires:PhpExtension:NotInstalled:ge' => '%s requires the %s PHP extension version %s or higher.',
	'ElggPluginPackage:Requires:PhpExtension:NotInstalled:gt' => '%s requires the %s PHP extension higher than version %s.',
	'ElggPluginPackage:Requires:PhpExtension:NotInstalled:NoVersion' => '%s requires the %s PHP extension.',

	'ElggPluginPackage:Requires:PhpExtension:lt' => '%s requires the %s PHP extension below version %s.  Version %s is installed.',
	'ElggPluginPackage:Requires:PhpExtension:le' => '%s requires the %s PHP extension version %s or below.  Version %s is installed.',
	'ElggPluginPackage:Requires:PhpExtension:=' => '%s requires the %s PHP extension version %s.  Version %s is installed.',
	'ElggPluginPackage:Requires:PhpExtension:!=' => '%s requires the %s PHP extension not equal to version %s.  Version %s is installed.',
	'ElggPluginPackage:Requires:PhpExtension:ge' => '%s requires the %s PHP extension version %s or higher.  Version %s is installed.',
	'ElggPluginPackage:Requires:PhpExtension:gt' => '%s requires the %s PHP extension higher than version %s.  Version %s is installed.',

	'ElggPluginPackage:Requires:PhpIni:lt' => '%s requires the %s php.ini option set less than %s.  Currently set to %s.',
	'ElggPluginPackage:Requires:PhpIni:le' => '%s requires the %s php.ini option set less than or equal to %s.  Currently set to %s.',
	'ElggPluginPackage:Requires:PhpIni:=' => '%s requires the %s php.ini option set to %s.  Currently set to %s.',
	'ElggPluginPackage:Requires:PhpIni:!=' => '%s requires the %s php.ini option not set to %s.  Currently set to %s.',
	'ElggPluginPackage:Requires:PhpIni:ge' => '%s requires the %s php.ini option set greater than or equal to %s.  Currently set to %s.',
	'ElggPluginPackage:Requires:PhpIni:gt' => '%s requires the %s php.ini option set greater than %s.  Currently set to %s.',

	'InvalidParameterException:NonElggUser' => "Passing a non-ElggUser to an ElggUser constructor!",

	'InvalidParameterException:NonElggSite' => "Passing a non-ElggSite to an ElggSite constructor!",

	'InvalidParameterException:NonElggGroup' => "Passing a non-ElggGroup to an ElggGroup constructor!",

	'IOException:UnableToSaveNew' => "Unable to save new %s",

	'InvalidParameterException:GUIDNotForExport' => "GUID has not been specified during export, this should never happen.",
	'InvalidParameterException:NonArrayReturnValue' => "Entity serialisation function passed a non-array returnvalue parameter",

	'ConfigurationException:NoCachePath' => "Cache path set to nothing!",
	'IOException:NotDirectory' => "%s is not a directory.",

	'IOException:BaseEntitySaveFailed' => "Unable to save new object's base entity information!",
	'InvalidParameterException:UnexpectedODDClass' => "import() passed an unexpected ODD class",
	'InvalidParameterException:EntityTypeNotSet' => "Entity type must be set.",

	'ClassException:ClassnameNotClass' => "%s is not a %s.",
	'ClassNotFoundException:MissingClass' => "Class '%s' was not found, missing plugin?",
	'InstallationException:TypeNotSupported' => "Type %s is not supported. This indicates an error in your installation, most likely caused by an incomplete upgrade.",

	'ImportException:ImportFailed' => "Could not import element %d",
	'ImportException:ProblemSaving' => "There was a problem saving %s",
	'ImportException:NoGUID' => "New entity created but has no GUID, this should not happen.",

	'ImportException:GUIDNotFound' => "Entity '%d' could not be found.",
	'ImportException:ProblemUpdatingMeta' => "There was a problem updating '%s' on entity '%d'",

	'ExportException:NoSuchEntity' => "No such entity GUID:%d",

	'ImportException:NoODDElements' => "No OpenDD elements found in import data, import failed.",
	'ImportException:NotAllImported' => "Not all elements were imported.",

	'InvalidParameterException:UnrecognisedFileMode' => "Unrecognised file mode '%s'",
	'InvalidParameterException:MissingOwner' => "File %s (file guid:%d) (owner guid:%d) is missing an owner!",
	'IOException:CouldNotMake' => "Could not make %s",
	'IOException:MissingFileName' => "You must specify a name before opening a file.",
	'ClassNotFoundException:NotFoundNotSavedWithFile' => "Unable to load filestore class %s for file %u",
	'NotificationException:NoNotificationMethod' => "No notification method specified.",
	'NotificationException:NoHandlerFound' => "No handler found for '%s' or it was not callable.",
	'NotificationException:ErrorNotifyingGuid' => "There was an error while notifying %d",
	'NotificationException:NoEmailAddress' => "Could not get the email address for GUID:%d",
	'NotificationException:MissingParameter' => "Missing a required parameter, '%s'",

	'DatabaseException:WhereSetNonQuery' => "Where set contains non WhereQueryComponent",
	'DatabaseException:SelectFieldsMissing' => "Fields missing on a select style query",
	'DatabaseException:UnspecifiedQueryType' => "Unrecognised or unspecified query type.",
	'DatabaseException:NoTablesSpecified' => "No tables specified for query.",
	'DatabaseException:NoACL' => "No access control was provided on query",

	'InvalidParameterException:NoEntityFound' => "No entity found, it either doesn't exist or you don't have access to it.",

	'InvalidParameterException:GUIDNotFound' => "GUID:%s could not be found, or you can not access it.",
	'InvalidParameterException:IdNotExistForGUID' => "Sorry, '%s' does not exist for guid:%d",
	'InvalidParameterException:CanNotExportType' => "Sorry, I don't know how to export '%s'",
	'InvalidParameterException:NoDataFound' => "Could not find any data.",
	'InvalidParameterException:DoesNotBelong' => "Does not belong to entity.",
	'InvalidParameterException:DoesNotBelongOrRefer' => "Does not belong to entity or refer to entity.",
	'InvalidParameterException:MissingParameter' => "Missing parameter, you need to provide a GUID.",

	'APIException:ApiResultUnknown' => "API Result is of an unknown type, this should never happen.",
	'ConfigurationException:NoSiteID' => "No site ID has been specified.",
	'SecurityException:APIAccessDenied' => "Sorry, API access has been disabled by the administrator.",
	'SecurityException:NoAuthMethods' => "No authentication methods were found that could authenticate this API request.",
	'InvalidParameterException:APIMethodOrFunctionNotSet' => "Method or function not set in call in expose_method()",
	'InvalidParameterException:APIParametersArrayStructure' => "Parameters array structure is incorrect for call to expose method '%s'",
	'InvalidParameterException:UnrecognisedHttpMethod' => "Unrecognised http method %s for api method '%s'",
	'APIException:MissingParameterInMethod' => "Missing parameter %s in method %s",
	'APIException:ParameterNotArray' => "%s does not appear to be an array.",
	'APIException:UnrecognisedTypeCast' => "Unrecognised type in cast %s for variable '%s' in method '%s'",
	'APIException:InvalidParameter' => "Invalid parameter found for '%s' in method '%s'.",
	'APIException:FunctionParseError' => "%s(%s) has a parsing error.",
	'APIException:FunctionNoReturn' => "%s(%s) returned no value.",
	'APIException:APIAuthenticationFailed' => "Method call failed the API Authentication",
	'APIException:UserAuthenticationFailed' => "Method call failed the User Authentication",
	'SecurityException:AuthTokenExpired' => "Authentication token either missing, invalid or expired.",
	'CallException:InvalidCallMethod' => "%s must be called using '%s'",
	'APIException:MethodCallNotImplemented' => "Method call '%s' has not been implemented.",
	'APIException:FunctionDoesNotExist' => "Function for method '%s' is not callable",
	'APIException:AlgorithmNotSupported' => "Algorithm '%s' is not supported or has been disabled.",
	'ConfigurationException:CacheDirNotSet' => "Cache directory 'cache_path' not set.",
	'APIException:NotGetOrPost' => "Request method must be GET or POST",
	'APIException:MissingAPIKey' => "Missing API key",
	'APIException:BadAPIKey' => "Bad API key",
	'APIException:MissingHmac' => "Missing X-Elgg-hmac header",
	'APIException:MissingHmacAlgo' => "Missing X-Elgg-hmac-algo header",
	'APIException:MissingTime' => "Missing X-Elgg-time header",
	'APIException:MissingNonce' => "Missing X-Elgg-nonce header",
	'APIException:TemporalDrift' => "X-Elgg-time is too far in the past or future. Epoch fail.",
	'APIException:NoQueryString' => "No data on the query string",
	'APIException:MissingPOSTHash' => "Missing X-Elgg-posthash header",
	'APIException:MissingPOSTAlgo' => "Missing X-Elgg-posthash_algo header",
	'APIException:MissingContentType' => "Missing content type for post data",
	'SecurityException:InvalidPostHash' => "POST data hash is invalid - Expected %s but got %s.",
	'SecurityException:DupePacket' => "Packet signature already seen.",
	'SecurityException:InvalidAPIKey' => "Invalid or missing API Key.",
	'NotImplementedException:CallMethodNotImplemented' => "Call method '%s' is currently not supported.",

	'NotImplementedException:XMLRPCMethodNotImplemented' => "XML-RPC method call '%s' not implemented.",
	'InvalidParameterException:UnexpectedReturnFormat' => "Call to method '%s' returned an unexpected result.",
	'CallException:NotRPCCall' => "Call does not appear to be a valid XML-RPC call",

	'PluginException:NoPluginName' => "The plugin name could not be found",

	'SecurityException:authenticationfailed' => "User could not be authenticated",

	'CronException:unknownperiod' => '%s is not a recognised period.',

	'SecurityException:deletedisablecurrentsite' => 'You can not delete or disable the site you are currently viewing!',

	'RegistrationException:EmptyPassword' => 'The password fields cannot be empty',
	'RegistrationException:PasswordMismatch' => 'Passwords must match',
	'LoginException:BannedUser' => 'You have been banned from this site and cannot log in',
	'LoginException:UsernameFailure' => 'We could not log you in. Please check your username and password.',
	'LoginException:PasswordFailure' => 'We could not log you in. Please check your username and password.',
	'LoginException:AccountLocked' => 'Your account has been locked for too many log in failures.',

	'memcache:notinstalled' => 'PHP memcache module not installed, you must install php5-memcache',
	'memcache:noservers' => 'No memcache servers defined, please populate the $CONFIG->memcache_servers variable',
	'memcache:versiontoolow' => 'Memcache needs at least version %s to run, you are running %s',
	'memcache:noaddserver' => 'Multiple server support disabled, you may need to upgrade your PECL memcache library',

	'deprecatedfunction' => 'Warning: This code uses the deprecated function \'%s\' and is not compatible with this version of Elgg',

	'pageownerunavailable' => 'Warning: The page owner %d is not accessible!',
	'viewfailure' => 'There was an internal failure in the view %s',
/**
 * API
 */
	'system.api.list' => "List all available API calls on the system.",
	'auth.gettoken' => "This API call lets a user obtain a user authentication token which can be used for authenticating future API calls. Pass it as the parameter auth_token",

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

/**
 * Access
 */

	'PRIVATE' => "Private",
	'LOGGED_IN' => "Logged in users",
	'PUBLIC' => "Public",
	'access:friends:label' => "Friends",
	'access' => "Access",

/**
 * Dashboard and widgets
 */

	'dashboard' => "Dashboard",
	'dashboard:nowidgets' => "Your dashboard lets you track the activity and content on this site that matters to you.",

	'widgets:add' => 'Add widgets',
	'widgets:add:description' => "Click on any widget button below to add it to your page.",
	'widgets:position:fixed' => '(Fixed position on page)',
	'widget:unavailable' => 'You have already added this widget',

	'widget:delete' => 'Remove %s',
	'widget:edit' => 'Customize this widget',

	'widgets' => "Widgets",
	'widget' => "Widget",
	'item:object:widget' => "Widgets",
	'widgets:save:success' => "The widget was successfully saved.",
	'widgets:save:failure' => "We could not save your widget. Please try again.",
	'widgets:add:success' => "The widget was successfully added.",
	'widgets:add:failure' => "We could not add your widget.",
	'widgets:move:failure' => "We could not store the new widget position.",
	'widgets:remove:failure' => "Unable to remove this widget",

/**
 * Groups
 */

	'group' => "Group",
	'item:group' => "Groups",

/**
 * Users
 */

	'user' => "User",
	'item:user' => "Users",

/**
 * Friends
 */

	'friends' => "My Friends",
	'friends:yours' => "Your friends",
	'friends:owned' => "%s's friends",
	'friend:add' => "Add friend",
	'friend:remove' => "Remove friend",

	'friends:add:successful' => "You have successfully added %s as a friend.",
	'friends:add:failure' => "We couldn't add %s as a friend. Please try again.",

	'friends:remove:successful' => "You have successfully removed %s from your friends.",
	'friends:remove:failure' => "We couldn't remove %s from your friends. Please try again.",

	'friends:none' => "This user hasn't added anyone as a friend yet.",
	'friends:none:you' => "You haven't added anyone as a friend! Search for your interests to begin finding people to follow.",

	'friends:none:found' => "No friends were found.",

	'friends:of:none' => "Nobody has added this user as a friend yet.",
	'friends:of:none:you' => "Nobody has added you as a friend yet. Start adding content and fill in your profile to let people find you!",

	'friends:of:owned' => "People who have made %s a friend",

	'friends:of' => "Friends of",
	'friends:collections' => "Collections of friends",
	'friends:collections:add' => "New friends collection",
	'friends:addfriends' => "Add friends",
	'friends:collectionname' => "Collection name",
	'friends:collectionfriends' => "Friends in collection",
	'friends:collectionedit' => "Edit this collection",
	'friends:nocollections' => "You do not yet have any collections.",
	'friends:collectiondeleted' => "Your collection has been deleted.",
	'friends:collectiondeletefailed' => "We were unable to delete the collection. Either you don't have permission, or some other problem has occurred.",
	'friends:collectionadded' => "Your collection was successfully created",
	'friends:nocollectionname' => "You need to give your collection a name before it can be created.",
	'friends:collections:members' => "Collection members",
	'friends:collections:edit' => "Edit collection",

	'friends:river:add' => "%s is now a friend with",

	'friendspicker:chararray' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',

/**
 * Feeds
 */
	'feed:rss' => 'RSS feed for this page',
/**
 * links
 **/

	'link:view' => 'view link',
	'link:view:all' => 'View all',


/**
 * River
 */
	'river' => "River",
	'river:relationship:friend' => 'is now friends with',
	'river:noaccess' => 'You do not have permission to view this item.',
	'river:posted:generic' => '%s posted',
	'riveritem:single:user' => 'a user',
	'riveritem:plural:user' => 'some users',

/**
 * Notifications
 */
	'notifications:usersettings' => "Notification settings",
	'notifications:methods' => "Please specify which methods you want to permit.",

	'notifications:usersettings:save:ok' => "Your notification settings were successfully saved.",
	'notifications:usersettings:save:fail' => "There was a problem saving your notification settings.",

	'user.notification.get' => 'Return the notification settings for a given user.',
	'user.notification.set' => 'Set the notification settings for a given user.',
/**
 * Search
 */

	'search' => "Search",
	'searchtitle' => "Search: %s",
	'users:searchtitle' => "Searching for users: %s",
	'groups:searchtitle' => "Searching for groups: %s",
	'advancedsearchtitle' => "%s with results matching %s",
	'notfound' => "No results found.",
	'next' => "Next",
	'previous' => "Previous",

	'viewtype:change' => "Change listing type",
	'viewtype:list' => "List view",
	'viewtype:gallery' => "Gallery",

	'tag:search:startblurb' => "Items with tags matching '%s':",

	'user:search:startblurb' => "Users matching '%s':",
	'user:search:finishblurb' => "To view more, click here.",

	'group:search:startblurb' => "Groups matching '%s':",
	'group:search:finishblurb' => "To view more, click here.",
	'search:go' => 'Go',
	'userpicker:only_friends' => 'Only friends',

/**
 * Account
 */

	'account' => "Account",
	'settings' => "Settings",
	'tools' => "My stuff",
	'tools:yours' => "My tools",

	'register' => "Register",
	'registerok' => "You have successfully registered for %s.",
	'registerbad' => "Your registration was unsuccessful because of an unknown error.",
	'registerdisabled' => "Registration has been disabled by the system administrator",

	'firstadminlogininstructions' => "Enable and disable plugins to customize your new Elgg site! (Remember to hit 'Save' when you're done.)",

	'registration:notemail' => 'The email address you provided does not appear to be a valid email address.',
	'registration:userexists' => 'That username already exists',
	'registration:usernametooshort' => 'Your username must be a minimum of 4 characters long.',
	'registration:passwordtooshort' => 'The password must be a minimum of 6 characters long.',
	'registration:dupeemail' => 'This email address has already been registered.',
	'registration:invalidchars' => 'Sorry, your username contains the following invalid character: %s.  All of these characters are invalid: %s',
	'registration:emailnotvalid' => 'Sorry, the email address you entered is invalid on this system',
	'registration:passwordnotvalid' => 'Sorry, the password you entered is invalid on this system',
	'registration:usernamenotvalid' => 'Sorry, the username you entered is invalid on this system',

	'adduser' => "Add User",
	'adduser:ok' => "You have successfully added a new user.",
	'adduser:bad' => "The new user could not be created.",

	'item:object:reported_content' => "Reported items",

	'user:set:name' => "Account name settings",
	'user:name:label' => "My display name",
	'user:name:success' => "Successfully changed your name on the system.",
	'user:name:fail' => "Could not change your name on the system.  Please make sure your name isn't too long and try again.",

	'user:set:password' => "Account password",
	'user:current_password:label' => 'Current password',
	'user:password:label' => "Your new password",
	'user:password2:label' => "Your new password again",
	'user:password:success' => "Password changed",
	'user:password:fail' => "Could not change your password on the system.",
	'user:password:fail:notsame' => "The two passwords are not the same!",
	'user:password:fail:tooshort' => "Password is too short!",
	'user:password:fail:incorrect_current_password' => 'The current password entered is incorrect.',
	'user:resetpassword:unknown_user' => 'Invalid user.',
	'user:resetpassword:reset_password_confirm' => 'Resetting your password will email a new password to your registered email address.',

	'user:set:language' => "Language settings",
	'user:language:label' => "Your language",
	'user:language:success' => "Your language settings have been updated.",
	'user:language:fail' => "Your language settings could not be saved.",

	'user:username:notfound' => 'Username %s not found.',

	'user:password:lost' => 'Lost password',
	'user:password:resetreq:success' => 'Successfully requested a new password, email sent',
	'user:password:resetreq:fail' => 'Could not request a new password.',

	'user:password:text' => 'To request a new password, enter your username below and click the Request button.',

	'user:persistent' => 'Remember me',
/**
 * Administration
 */

	'admin:configuration:success' => "Your settings have been saved.",
	'admin:configuration:fail' => "Your settings could not be saved.",

	'admin:unknown_section' => 'Invalid Admin Section.',

	'admin' => "Administration",
	'admin:description' => "The admin panel allows you to control all aspects of the system, from user management to how plugins behave. Choose an option below to get started.",

	'admin:overview' => 'Overview',

	'admin:appearance' => 'Appearance',

	'admin:users' => "Users",
	'admin:users:online' => 'Currently Online',
	'admin:users:newest' => 'Newest',
	'admin:users:add' => 'Add',
	'admin:users:description' => "This admin panel allows you to control user settings for your site. Choose an option below to get started.",
	'admin:users:adduser:label' => "Click here to add a new user...",
	'admin:users:opt:linktext' => "Configure users...",
	'admin:users:opt:description' => "Configure users and account information. ",
	'admin:users:find' => 'Find',

	'admin:site' => "Site Settings",
	'admin:site:basic' => 'Basic',
	'admin:site:advanced' => 'Advanced',
	'admin:site:description' => "This admin panel allows you to control global settings for your site. Choose an option below to get started.",
	'admin:site:opt:linktext' => "Configure site...",
	'admin:site:access:warning' => "Changing the access setting only affects the permissions on content created in the future.",

/**
 * Plugins
 */
	'plugins:settings:save:ok' => "Settings for the %s plugin were saved successfully.",
	'plugins:settings:save:fail' => "There was a problem saving settings for the %s plugin.",
	'plugins:usersettings:save:ok' => "User settings for the %s plugin were saved successfully.",
	'plugins:usersettings:save:fail' => "There was a problem saving  user settings for the %s plugin.",
	'item:object:plugin' => 'Plugin configuration settings',

	'admin:plugins' => "Plugins",
	'admin:plugins:description' => "This admin panel allows you to control and configure tools installed on your site.",
	'admin:plugins:opt:linktext' => "Configure tools...",
	'admin:plugins:opt:description' => "Configure the tools installed on the site. ",
	'admin:plugins:label:author' => "Author",
	'admin:plugins:label:copyright' => "Copyright",
	'admin:plugins:label:categories' => 'Categories',
	'admin:plugins:label:licence' => "Licence",
	'admin:plugins:label:website' => "URL",
	'admin:plugins:label:moreinfo' => 'more info',
	'admin:plugins:label:version' => 'Version',
	'admin:plugins:label:directory' => 'Directory',
	'admin:plugins:warning:elggversionunknown' => 'Warning: This plugin does not specify a compatible Elgg version.',
	'admin:plugins:warning:elggtoolow' => 'Warning: This plugin requires a later version of Elgg!',
	'admin:plugins:reorder:yes' => "Plugin %s was reordered successfully.",
	'admin:plugins:reorder:no' => "Plugin %s could not be reordered.",
	'admin:plugins:disable:yes' => "Plugin %s was disabled successfully.",
	'admin:plugins:disable:no' => "Plugin %s could not be disabled.",
	'admin:plugins:enable:yes' => "Plugin %s was enabled successfully.",
	'admin:plugins:enable:no' => "Plugin %s could not be enabled.",
	'admin:plugins:categories:all' => 'All categories',
	'admin:plugins:plugin_website' => 'Plugin website',
	'admin:plugins:author' => '%s',
	'admin:plugins:version' => 'Version %s',
	'admin:plugins:simple' => 'Simple',
	'admin:plugins:advanced' => 'Advanced',
	'admin:plugin_settings' => 'Plugin Settings',
	'admin:plugins:simple_simple_fail' => 'Could not save settings.',
	'admin:plugins:simple_simple_success' => 'Settings saved.',

	'admin:statistics' => "Statistics",
	'admin:statistics:description' => "This is an overview of statistics on your site. If you need more detailed statistics, a professional administration feature is available.",
	'admin:statistics:opt:description' => "View statistical information about users and objects on your site.",
	'admin:statistics:opt:linktext' => "View statistics...",
	'admin:statistics:label:basic' => "Basic site statistics",
	'admin:statistics:label:numentities' => "Entities on site",
	'admin:statistics:label:numusers' => "Number of users",
	'admin:statistics:label:numonline' => "Number of users online",
	'admin:statistics:label:onlineusers' => "Users online now",
	'admin:statistics:label:version' => "Elgg version",
	'admin:statistics:label:version:release' => "Release",
	'admin:statistics:label:version:version' => "Version",

	'admin:user:label:search' => "Find users:",
	'admin:user:label:searchbutton' => "Search",

	'admin:user:ban:no' => "Can not ban user",
	'admin:user:ban:yes' => "User banned.",
	'admin:user:self:ban:no' => "You cannot ban yourself",
	'admin:user:unban:no' => "Can not unban user",
	'admin:user:unban:yes' => "User un-banned.",
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

	'admin:menu_items' => 'Menu Items',
	'admin:menu_items:configure' => 'Configure main menu items',
	'admin:menu_items:description' => 'Select which menu items you want to show as featured links.  Unused items will be added as "More" at the end of the list.',
	'admin:menu_items:hide_toolbar_entries' => 'Remove links from tool bar menu?',
	'admin:menu_items:saved' => 'Menu items saved.',
	'admin:add_menu_item' => 'Add a custom menu item',
	'admin:add_menu_item:description' => 'Fill out the Display name and URL to add custom items to your navigation menu.',

/**
 * User settings
 */
	'usersettings:description' => "The user settings panel allows you to control all your personal settings, from user management to how plugins behave. Choose an option below to get started.",

	'usersettings:statistics' => "Your statistics",
	'usersettings:statistics:opt:description' => "View statistical information about users and objects on your site.",
	'usersettings:statistics:opt:linktext' => "Account statistics",

	'usersettings:user' => "Your settings",
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



/**
 * Generic action words
 */

	'save' => "Save",
	'reset' => 'Reset',
	'publish' => "Publish",
	'cancel' => "Cancel",
	'saving' => "Saving ...",
	'update' => "Update",
	'edit' => "Edit",
	'delete' => "Delete",
	'accept' => "Accept",
	'load' => "Load",
	'upload' => "Upload",
	'ban' => "Ban",
	'unban' => "Unban",
	'enable' => "Enable",
	'disable' => "Disable",
	'request' => "Request",
	'complete' => "Complete",
	'open' => 'Open',
	'close' => 'Close',
	'reply' => "Reply",
	'more' => 'More',
	'comments' => 'Comments',
	'import' => 'Import',
	'export' => 'Export',
	'untitled' => 'Untitled',
	'help' => 'Help',
	'send' => 'Send',
	'post' => 'Post',
	'submit' => 'Submit',
	'site' => 'Site',

	'up' => 'Up',
	'down' => 'Down',
	'top' => 'Top',
	'bottom' => 'Bottom',

	'invite' => "Invite",

	'resetpassword' => "Reset password",
	'makeadmin' => "Make admin",
	'removeadmin' => "Remove admin",

	'option:yes' => "Yes",
	'option:no' => "No",

	'unknown' => 'Unknown',

	'active' => 'Active',
	'total' => 'Total',

	'learnmore' => "Click here to learn more.",

	'content' => "content",
	'content:latest' => 'Latest activity',
	'content:latest:blurb' => 'Alternatively, click here to view the latest content from across the site.',

	'link:text' => 'view link',

	'enableall' => 'Enable All',
	'disableall' => 'Disable All',

/**
 * Generic questions
 */

	'question:areyousure' => 'Are you sure?',

/**
 * Generic data words
 */

	'title' => "Title",
	'description' => "Description",
	'tags' => "Tags",
	'spotlight' => "Spotlight",
	'all' => "All",

	'by' => 'by',

	'annotations' => "Annotations",
	'relationships' => "Relationships",
	'metadata' => "Metadata",
	'tagcloud' => "Tag cloud",
	'tagcloud:allsitetags' => "All site tags",

/**
 * Input / output strings
 */

	'deleteconfirm' => "Are you sure you want to delete this item?",
	'fileexists' => "A file has already been uploaded. To replace it, select it below:",

/**
 * User add
 */

	'useradd:subject' => 'User account created',
	'useradd:body' => '
%s,

A user account has been created for you at %s. To log in, visit:

%s

And log in with these user credentials:

Username: %s
Password: %s

Once you have logged in, we highly recommend that you change your password.
',

/**
 * System messages
 **/

	'systemmessages:dismiss' => "click to dismiss",


/**
 * Import / export
 */
	'importsuccess' => "Import of data was successful",
	'importfail' => "OpenDD import of data failed.",

/**
 * Time
 */

	'friendlytime:justnow' => "just now",
	'friendlytime:minutes' => "%s minutes ago",
	'friendlytime:minutes:singular' => "a minute ago",
	'friendlytime:hours' => "%s hours ago",
	'friendlytime:hours:singular' => "an hour ago",
	'friendlytime:days' => "%s days ago",
	'friendlytime:days:singular' => "yesterday",
	'friendlytime:date_format' => 'j F Y @ g:ia',

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


/**
 * System settings
 */

	'installation:sitename' => "The name of your site:",
	'installation:sitedescription' => "Short description of your site (optional):",
	'installation:wwwroot' => "The site URL:",
	'installation:path' => "The full path of the Elgg installation:",
	'installation:dataroot' => "The full path the data directory:",
	'installation:dataroot:warning' => "You must create this directory manually. It should be in a different directory to your Elgg installation.",
	'installation:sitepermissions' => "The default access permissions:",
	'installation:language' => "The default language for your site:",
	'installation:debug' => "Debug mode provides extra information which can be used to diagnose faults. However, it can slow your system down so should only be used if you are having problems:",
	'installation:debug:none' => 'Turn off debug mode (recommended)',
	'installation:debug:error' => 'Display only critical errors',
	'installation:debug:warning' => 'Display errors and warnings',
	'installation:debug:notice' => 'Log all errors, warnings and notices',

	// Walled Garden support
	'installation:registration:description' => 'User registration is enabled by default. Turn this off if you do not want new users to be able to register on their own.',
	'installation:registration:label' => 'Allow new users to register',
	'installation:walled_garden:description' => 'Enable the site to run as a private network. This will not allow non logged-in users to view any site pages other than those specifically marked as public.',
	'installation:walled_garden:label' => 'Restrict pages to logged-in users',

	'installation:httpslogin' => "Enable this to have user logins performed over HTTPS. You will need to have https enabled on your server for this to work.",
	'installation:httpslogin:label' => "Enable HTTPS logins",
	'installation:view' => "Enter the view which will be used as the default for your site or leave this blank for the default view (if in doubt, leave as default):",

	'installation:siteemail' => "Site email address (used when sending system emails):",

	'installation:disableapi' => "The RESTful API is a flexible and extensible interface that enables applications to use certain Elgg features remotely.",
	'installation:disableapi:label' => "Enable the RESTful API",

	'installation:allow_user_default_access:description' => "If checked, individual users will be allowed to set their own default access level that can over-ride the system default access level.",
	'installation:allow_user_default_access:label' => "Allow user default access",

	'installation:simplecache:description' => "The simple cache increases performance by caching static content including some CSS and JavaScript files. Normally you will want this on.",
	'installation:simplecache:label' => "Use simple cache (recommended)",

	'installation:viewpathcache:description' => "The view filepath cache decreases the loading times of plugins by caching the location of their views.",
	'installation:viewpathcache:label' => "Use view filepath cache (recommended)",

	'upgrading' => 'Upgrading...',
	'upgrade:db' => 'Your database was upgraded.',
	'upgrade:core' => 'Your elgg installation was upgraded.',

	'deprecated:function' => '%s() was deprecated by %s()',

/**
 * Welcome
 */

	'welcome' => "Welcome",
	'welcome:user' => 'Welcome %s',

/**
 * Emails
 */
	'email:settings' => "Email settings",
	'email:address:label' => "Your email address",

	'email:save:success' => "New email address saved, verification requested.",
	'email:save:fail' => "Your new email address could not be saved.",

	'friend:newfriend:subject' => "%s has made you a friend!",
	'friend:newfriend:body' => "%s has made you a friend!

To view their profile, click here:

%s

You cannot reply to this email.",



	'email:resetpassword:subject' => "Password reset!",
	'email:resetpassword:body' => "Hi %s,

Your password has been reset to: %s",


	'email:resetreq:subject' => "Request for new password.",
	'email:resetreq:body' => "Hi %s,

Somebody (from the IP address %s) has requested a new password for their account.

If you requested this click on the link below, otherwise ignore this email.

%s
",

/**
 * user default access
 */

'default_access:settings' => "Your default access level",
'default_access:label' => "Default access",
'user:default_access:success' => "Your new default access level was saved.",
'user:default_access:failure' => "Your new default access level could not be saved.",

/**
 * XML-RPC
 */
	'xmlrpc:noinputdata'	=>	"Input data missing",

/**
 * Likes
 **/
	'likes:this' => 'liked this',
	'likes:deleted' => 'Your like has been removed',
	'likes:remove' => 'See who else liked this',
	'likes:notdeleted' => 'There was a problem removing your like',
	'likes:likes' => 'You now like this item',
	'likes:failure' => 'There was a problem liking this item',
	'likes:alreadyliked' => 'You have already liked this item',
	'likes:notfound' => 'The item you are trying to like cannot be found',
	'likes:likethis' => 'Like this',
	'likes:userlikedthis' => 'user liked this',
	'likes:userslikedthis' => 'users like this',
	'likes:river:annotate' => 'likes',
	'likes:email:body' => '%s liked %s',
	'likes:email:subject' => 'A user liked one of your objects',

/**
 * Comments
 */

	'comments:count' => "%s comments",

	'riveraction:annotation:generic_comment' => '%s commented on %s',

	'generic_comments:add' => "Add a comment",
	'generic_comments:post' => "Post comment",
	'generic_comments:text' => "Comment",
	'generic_comments:latest' => "Latest comments",
	'generic_comment:posted' => "Your comment was successfully posted.",
	'generic_comment:deleted' => "Your comment was successfully deleted.",
	'generic_comment:blank' => "Sorry, you need to actually put something in your comment before we can save it.",
	'generic_comment:notfound' => "Sorry, we could not find the specified item.",
	'generic_comment:notdeleted' => "Sorry, we could not delete this comment.",
	'generic_comment:failure' => "An unexpected error occurred when adding your comment. Please try again.",

	'generic_comment:email:subject' => 'You have a new comment!',
	'generic_comment:email:body' => "You have a new comment on your item \"%s\" from %s. It reads:


%s


To reply or view the original item, click here:

%s

To view %s's profile, click here:

%s

You cannot reply to this email.",

/**
 * Entities
 */
	'entity:default:strapline' => 'Created %s by %s',
	'entity:default:missingsupport:popup' => 'This entity cannot be displayed correctly. This may be because it requires support provided by a plugin that is no longer installed.',

	'entity:delete:success' => 'Entity %s has been deleted',
	'entity:delete:fail' => 'Entity %s could not be deleted',


/**
 * Action gatekeeper
 */
	'actiongatekeeper:missingfields' => 'Form is missing __token or __ts fields',
	'actiongatekeeper:tokeninvalid' => "We encountered an error (token mismatch). This probably means that the page you were using expired. Please try again.",
	'actiongatekeeper:timeerror' => 'The page you were using has expired. Please refresh and try again.',
	'actiongatekeeper:pluginprevents' => 'A extension has prevented this form from being submitted.',


/**
 * Word blacklists
 */
	'word:blacklist' => 'and, the, then, but, she, his, her, him, one, not, also, about, now, hence, however, still, likewise, otherwise, therefore, conversely, rather, consequently, furthermore, nevertheless, instead, meanwhile, accordingly, this, seems, what, whom, whose, whoever, whomever',

/**
 * Tag labels
 */

	'tag_names:tags' => 'Tags',
	'tags:site_cloud' => 'Site Tag Cloud',

/**
 * Languages according to ISO 639-1
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
	"qu" => "Quechua",
	"rm" => "Rhaeto-Romance",
	"rn" => "Kirundi",
	"ro" => "Romanian",
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
	"zu" => "Zulu",
);

add_translation("en",$english);
