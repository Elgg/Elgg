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
	'login:empty' => "Username/email and password are required.",
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
	'exception:contact_admin' => 'An unrecoverable error has occurred and has been logged. Contact the site administrator with the following information:',

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
	'DatabaseException:InvalidQuery' => "Invalid query",
	'DatabaseException:InvalidDBLink' => "Connection to database was lost.",

	'IOException:FailedToLoadGUID' => "Failed to load new %s from GUID:%d",
	'InvalidParameterException:NonElggObject' => "Passing a non-ElggObject to an ElggObject constructor!",
	'InvalidParameterException:UnrecognisedValue' => "Unrecognised value passed to constuctor.",

	'InvalidClassException:NotValidElggStar' => "GUID:%d is not a valid %s",

	'PluginException:MisconfiguredPlugin' => "%s (guid: %s) is a misconfigured plugin. It has been disabled. Please search the Elgg wiki for possible causes (http://docs.elgg.org/wiki/).",
	'PluginException:CannotStart' => '%s (guid: %s) cannot start and has been deactivated.  Reason: %s',
	'PluginException:InvalidID' => "%s is an invalid plugin ID.",
	'PluginException:InvalidPath' => "%s is an invalid plugin path.",
	'PluginException:InvalidManifest' => 'Invalid manifest file for plugin %s',
	'PluginException:InvalidPlugin' => '%s is not a valid plugin.',
	'PluginException:InvalidPlugin:Details' => '%s is not a valid plugin: %s',
	'PluginException:NullInstantiated' => 'ElggPlugin cannot be null instantiated. You must pass a GUID, a plugin ID, or a full path.',

	'ElggPlugin:MissingID' => 'Missing plugin ID (guid %s)',
	'ElggPlugin:NoPluginPackagePackage' => 'Missing ElggPluginPackage for plugin ID %s (guid %s)',

	'ElggPluginPackage:InvalidPlugin:MissingFile' => 'The required file "%s" is missing.',
	'ElggPluginPackage:InvalidPlugin:InvalidDependency' => 'Its manifest contains an invalid dependency type "%s".',
	'ElggPluginPackage:InvalidPlugin:InvalidProvides' => 'Its manifest contains an invalid provides type "%s".',
	'ElggPluginPackage:InvalidPlugin:CircularDep' => 'There is an invalid %s dependency "%s" in plugin %s.  Plugins cannot conflict with or require something they provide!',

	'ElggPlugin:Exception:CannotIncludeFile' => 'Cannot include %s for plugin %s (guid: %s) at %s.',
	'ElggPlugin:Exception:CannotRegisterViews' => 'Cannot open views dir for plugin %s (guid: %s) at %s.',
	'ElggPlugin:Exception:CannotRegisterLanguages' => 'Cannot register languages for plugin %s (guid: %s) at %s.',
	'ElggPlugin:Exception:NoID' => 'No ID for plugin guid %s!',

	'PluginException:ParserError' => 'Error parsing manifest with API version %s in plugin %s.',
	'PluginException:NoAvailableParser' => 'Cannot find a parser for manifest API version %s in plugin %s.',
	'PluginException:ParserErrorMissingRequiredAttribute' => "Missing required '%s' attribute in manifest for plugin %s.",

	'ElggPlugin:Dependencies:Requires' => 'Requires',
	'ElggPlugin:Dependencies:Suggests' => 'Suggests',
	'ElggPlugin:Dependencies:Conflicts' => 'Conflicts',
	'ElggPlugin:Dependencies:Conflicted' => 'Conflicted',
	'ElggPlugin:Dependencies:Provides' => 'Provides',
	'ElggPlugin:Dependencies:Priority' => 'Priority',

	'ElggPlugin:Dependencies:Elgg' => 'Elgg version',
	'ElggPlugin:Dependencies:PhpExtension' => 'PHP extension: %s',
	'ElggPlugin:Dependencies:PhpIni' => 'PHP ini setting: %s',
	'ElggPlugin:Dependencies:Plugin' => 'Plugin: %s',
	'ElggPlugin:Dependencies:Priority:After' => 'After %s',
	'ElggPlugin:Dependencies:Priority:Before' => 'Before %s',
	'ElggPlugin:Dependencies:Priority:Uninstalled' => '%s is not installed',
	'ElggPlugin:Dependencies:Suggests:Unsatisfied' => 'Missing',
	
	'ElggPlugin:Dependencies:ActiveDependent' => 'There are other plugins that list %s as a dependency.  You must disable the following plugins before disabling this one: %s',

	'ElggPlugin:InvalidAndDeactivated' => '%s is an invalid plugin and has been deactivated.',

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
	'InvalidParameterException:LibraryNotRegistered' => '%s is not a registered library',
	'InvalidParameterException:LibraryNotFound' => 'Could not load the %s library from %s',

	'APIException:ApiResultUnknown' => "API Result is of an unknown type, this should never happen.",
	'ConfigurationException:NoSiteID' => "No site ID has been specified.",
	'SecurityException:APIAccessDenied' => "Sorry, API access has been disabled by the administrator.",
	'SecurityException:NoAuthMethods' => "No authentication methods were found that could authenticate this API request.",
	'SecurityException:ForwardFailedToRedirect' => 'Redirect could not be issued due to headers already being sent. Halting execution for security. Output started in file %s at line %d. Search http://docs.elgg.org/ for more information.',
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
	'LoginException:UsernameFailure' => 'We could not log you in. Please check your username/email and password.',
	'LoginException:PasswordFailure' => 'We could not log you in. Please check your username/email and password.',
	'LoginException:AccountLocked' => 'Your account has been locked for too many log in failures.',
	'LoginException:ChangePasswordFailure' => 'Failed current password check.',
	'LoginException:Unknown' => 'We could not log you in due to an unknown error.',

	'deprecatedfunction' => 'Warning: This code uses the deprecated function \'%s\' and is not compatible with this version of Elgg',

	'pageownerunavailable' => 'Warning: The page owner %d is not accessible!',
	'viewfailure' => 'There was an internal failure in the view %s',
	'changebookmark' => 'Please change your bookmark for this page',
	'noaccess' => 'You need to login to view this content or the content has been removed or you do not have permission to view it.',
	'error:missing_data' => 'There was some data missing in your request',

	'error:default' => 'Oops...something went wrong.',
	'error:404' => 'Sorry. We could not find the page that you requested.',

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
	'access:limited:label' => "Limited",
	'access:help' => "The access level",

/**
 * Dashboard and widgets
 */

	'dashboard' => "Dashboard",
	'dashboard:nowidgets' => "Your dashboard lets you track the activity and content on this site that matters to you.",

	'widgets:add' => 'Add widgets',
	'widgets:add:description' => "Click on any widget button below to add it to the page.",
	'widgets:position:fixed' => '(Fixed position on page)',
	'widget:unavailable' => 'You have already added this widget',
	'widget:numbertodisplay' => 'Number of items to display',

	'widget:delete' => 'Remove %s',
	'widget:edit' => 'Customize this widget',

	'widgets' => "Widgets",
	'widget' => "Widget",
	'item:object:widget' => "Widgets",
	'widgets:save:success' => "The widget was successfully saved.",
	'widgets:save:failure' => "We could not save your widget.",
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

	'friends' => "Friends",
	'friends:yours' => "Your friends",
	'friends:owned' => "%s's friends",
	'friend:add' => "Add friend",
	'friend:remove' => "Remove friend",

	'friends:add:successful' => "You have successfully added %s as a friend.",
	'friends:add:failure' => "We couldn't add %s as a friend.",

	'friends:remove:successful' => "You have successfully removed %s from your friends.",
	'friends:remove:failure' => "We couldn't remove %s from your friends.",

	'friends:none' => "No friends yet.",
	'friends:none:you' => "You don't have any friends yet.",

	'friends:none:found' => "No friends were found.",

	'friends:of:none' => "Nobody has added this user as a friend yet.",
	'friends:of:none:you' => "Nobody has added you as a friend yet. Start adding content and fill in your profile to let people find you!",

	'friends:of:owned' => "People who have made %s a friend",

	'friends:of' => "Friends of",
	'friends:collections' => "Friend collections",
	'collections:add' => "New collection",
	'friends:collections:add' => "New friends collection",
	'friends:addfriends' => "Select friends",
	'friends:collectionname' => "Collection name",
	'friends:collectionfriends' => "Friends in collection",
	'friends:collectionedit' => "Edit this collection",
	'friends:nocollections' => "You do not have any collections yet.",
	'friends:collectiondeleted' => "Your collection has been deleted.",
	'friends:collectiondeletefailed' => "We were unable to delete the collection. Either you don't have permission, or some other problem has occurred.",
	'friends:collectionadded' => "Your collection was successfully created",
	'friends:nocollectionname' => "You need to give your collection a name before it can be created.",
	'friends:collections:members' => "Collection members",
	'friends:collections:edit' => "Edit collection",
	'friends:collections:edited' => "Saved collection",
	'friends:collection:edit_failed' => 'Could not save collection.',

	'friendspicker:chararray' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',

	'avatar' => 'Avatar',
	'avatar:noaccess' => "You're not allowed to edit this user's avatar",
	'avatar:create' => 'Create your avatar',
	'avatar:edit' => 'Edit avatar',
	'avatar:preview' => 'Preview',
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

	'profile:edit' => 'Edit profile',
	'profile:aboutme' => "About me",
	'profile:description' => "About me",
	'profile:briefdescription' => "Brief description",
	'profile:location' => "Location",
	'profile:skills' => "Skills",
	'profile:interests' => "Interests",
	'profile:contactemail' => "Contact email",
	'profile:phone' => "Telephone",
	'profile:mobile' => "Mobile phone",
	'profile:website' => "Website",
	'profile:twitter' => "Twitter username",
	'profile:saved' => "Your profile was successfully saved.",

	'profile:field:text' => 'Short text',
	'profile:field:longtext' => 'Large text area',
	'profile:field:tags' => 'Tags',
	'profile:field:url' => 'Web address',
	'profile:field:email' => 'Email address',
	'profile:field:location' => 'Location',
	'profile:field:date' => 'Date',

	'admin:appearance:profile_fields' => 'Edit Profile Fields',
	'profile:edit:default' => 'Edit profile fields',
	'profile:label' => "Profile label",
	'profile:type' => "Profile type",
	'profile:editdefault:delete:fail' => 'Removed default profile item field failed',
	'profile:editdefault:delete:success' => 'Profile field deleted',
	'profile:defaultprofile:reset' => 'Profile fields reset to the system default',
	'profile:resetdefault' => 'Reset profile fields to system defaults',
	'profile:resetdefault:confirm' => 'Are you sure you want to delete your custom profile fields?',
	'profile:explainchangefields' => "You can replace the existing profile fields with your own using the form below. \n\n Give the new profile field a label, for example, 'Favorite team', then select the field type (eg. text, url, tags), and click the 'Add' button. To re-order the fields drag on the handle next to the field label. To edit a field label - click on the label's text to make it editable. \n\n At any time you can revert back to the default profile set up, but you will lose any information already entered into custom fields on profile pages.",
	'profile:editdefault:success' => 'New profile field added',
	'profile:editdefault:fail' => 'Default profile could not be saved',
	'profile:field_too_long' => 'Cannot save your profile information because the "%s" section is too long.',
	'profile:noaccess' => "You do not have permission to edit this profile.",


/**
 * Feeds
 */
	'feed:rss' => 'RSS feed for this page',
/**
 * Links
 */
	'link:view' => 'view link',
	'link:view:all' => 'View all',


/**
 * River
 */
	'river' => "River",
	'river:friend:user:default' => "%s is now a friend with %s",
	'river:update:user:avatar' => '%s has a new avatar',
	'river:update:user:profile' => '%s has updated their profile',
	'river:noaccess' => 'You do not have permission to view this item.',
	'river:posted:generic' => '%s posted',
	'riveritem:single:user' => 'a user',
	'riveritem:plural:user' => 'some users',
	'river:ingroup' => 'in the group %s',
	'river:none' => 'No activity',
	'river:update' => 'Update for %s',
	'river:delete:success' => 'River item has been deleted',
	'river:delete:fail' => 'River item could not be deleted',

	'river:widget:title' => "Activity",
	'river:widget:description' => "Display latest activity",
	'river:widget:type' => "Type of activity",
	'river:widgets:friends' => 'Friends activity',
	'river:widgets:all' => 'All site activity',

/**
 * Notifications
 */
	'notifications:usersettings' => "Notification settings",
	'notifications:methods' => "Select your notification methods.",
	'notification:method:email' => 'Email',

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

	'viewtype:change' => "Change list type",
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
	'tools' => "Tools",
	'settings:edit' => 'Edit settings',

	'register' => "Register",
	'registerok' => "You have successfully registered for %s.",
	'registerbad' => "Your registration was unsuccessful because of an unknown error.",
	'registerdisabled' => "Registration has been disabled by the system administrator",
	'register:fields' => 'All fields are required',

	'registration:notemail' => 'The email address you provided does not appear to be a valid email address.',
	'registration:userexists' => 'That username already exists',
	'registration:usernametooshort' => 'Your username must be a minimum of %u characters long.',
	'registration:usernametoolong' => 'Your username is too long. It can have a maximum of %u characters.',
	'registration:passwordtooshort' => 'The password must be a minimum of %u characters long.',
	'registration:dupeemail' => 'This email address has already been registered.',
	'registration:invalidchars' => 'Sorry, your username contains the character %s which is invalid. The following characters are invalid: %s',
	'registration:emailnotvalid' => 'Sorry, the email address you entered is invalid on this system',
	'registration:passwordnotvalid' => 'Sorry, the password you entered is invalid on this system',
	'registration:usernamenotvalid' => 'Sorry, the username you entered is invalid on this system',

	'adduser' => "Add User",
	'adduser:ok' => "You have successfully added a new user.",
	'adduser:bad' => "The new user could not be created.",

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

	'user:password:text' => 'To request a new password, enter your username or email address below and click the Request button.',

	'user:persistent' => 'Remember me',

	'walled_garden:welcome' => 'Welcome to',

/**
 * Administration
 */
	'menu:page:header:administer' => 'Administer',
	'menu:page:header:configure' => 'Configure',
	'menu:page:header:develop' => 'Develop',
	'menu:page:header:default' => 'Other',

	'admin:view_site' => 'View site',
	'admin:loggedin' => 'Logged in as %s',
	'admin:menu' => 'Menu',

	'admin:configuration:success' => "Your settings have been saved.",
	'admin:configuration:fail' => "Your settings could not be saved.",
	'admin:configuration:dataroot:relative_path' => 'Cannot set "%s" as the dataroot because it is not an absolute path.',

	'admin:unknown_section' => 'Invalid Admin Section.',

	'admin' => "Administration",
	'admin:description' => "The admin panel allows you to control all aspects of the system, from user management to how plugins behave. Choose an option below to get started.",

	'admin:statistics' => "Statistics",
	'admin:statistics:overview' => 'Overview',
	'admin:statistics:server' => 'Server Info',

	'admin:appearance' => 'Appearance',
	'admin:administer_utilities' => 'Utilities',
	'admin:develop_utilities' => 'Utilities',

	'admin:users' => "Users",
	'admin:users:online' => 'Currently Online',
	'admin:users:newest' => 'Newest',
	'admin:users:admins' => 'Administrators',
	'admin:users:add' => 'Add New User',
	'admin:users:description' => "This admin panel allows you to control user settings for your site. Choose an option below to get started.",
	'admin:users:adduser:label' => "Click here to add a new user...",
	'admin:users:opt:linktext' => "Configure users...",
	'admin:users:opt:description' => "Configure users and account information. ",
	'admin:users:find' => 'Find',

	'admin:settings' => 'Settings',
	'admin:settings:basic' => 'Basic Settings',
	'admin:settings:advanced' => 'Advanced Settings',
	'admin:settings:advanced/site_secret' => 'Site Secret',
	'admin:site:description' => "This admin panel allows you to control global settings for your site. Choose an option below to get started.",
	'admin:settings:advanced:site_secret' => 'Site Secret',
	'admin:site:opt:linktext' => "Configure site...",
	'admin:site:access:warning' => "Changing the access setting only affects the permissions on content created in the future.",

	'admin:site:secret:intro' => 'Elgg uses a key to create security tokens for various purposes.',
	'admin:site:secret_regenerated' => "Your site secret has been regenerated.",
	'admin:site:secret:regenerate' => "Regenerate site secret",
	'admin:site:secret:regenerate:help' => "Note: This may inconvenience some users by invalidating tokens used in \"remember me\" cookies, e-mail validation requests, invitation codes, etc.",
	'site_secret:current_strength' => 'Key Strength',
	'site_secret:strength:weak' => "Weak",
	'site_secret:strength_msg:weak' => "We strongly recommend that you regenerate your site secret.",
	'site_secret:strength:moderate' => "Moderate",
	'site_secret:strength_msg:moderate' => "We recommend you regenerate your site secret for the best site security.",
	'site_secret:strength:strong' => "Strong",
	'site_secret:strength_msg:strong' => "&#x2713; Your site secret is sufficiently strong.",

	'admin:dashboard' => 'Dashboard',
	'admin:widget:online_users' => 'Online users',
	'admin:widget:online_users:help' => 'Lists the users currently on the site',
	'admin:widget:new_users' => 'New users',
	'admin:widget:new_users:help' => 'Lists the newest users',
	'admin:widget:content_stats' => 'Content statistics',
	'admin:widget:content_stats:help' => 'Keep track of the content created by your users',
	'widget:content_stats:type' => 'Content type',
	'widget:content_stats:number' => 'Number',

	'admin:widget:admin_welcome' => 'Welcome',
	'admin:widget:admin_welcome:help' => "A short introduction to Elgg's admin area",
	'admin:widget:admin_welcome:intro' =>
'Welcome to Elgg! Right now you are looking at the administration dashboard. It\'s useful for tracking what\'s happening on the site.',

	'admin:widget:admin_welcome:admin_overview' =>
"Navigation for the administration area is provided by the menu to the right. It is organized into"
. " three sections:
	<dl>
		<dt>Administer</dt><dd>Everyday tasks like monitoring reported content, checking who is online, and viewing statistics.</dd>
		<dt>Configure</dt><dd>Occasional tasks like setting the site name or activating a plugin.</dd>
		<dt>Develop</dt><dd>For developers who are building plugins or designing themes. (Requires a developer plugin.)</dd>
	</dl>
	",

	// argh, this is ugly
	'admin:widget:admin_welcome:outro' => '<br />Be sure to check out the resources available through the footer links and thank you for using Elgg!',

	'admin:widget:control_panel' => 'Control panel',
	'admin:widget:control_panel:help' => "Provides easy access to common controls",

	'admin:cache:flush' => 'Flush the caches',
	'admin:cache:flushed' => "The site's caches have been flushed",

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

	'admin:plugins:sort:priority' => 'Priority',
	'admin:plugins:sort:alpha' => 'Alphabetical',
	'admin:plugins:sort:date' => 'Newest',

	'admin:plugins:markdown:unknown_plugin' => 'Unknown plugin.',
	'admin:plugins:markdown:unknown_file' => 'Unknown file.',


	'admin:notices:could_not_delete' => 'Could not delete notice.',
	'item:object:admin_notice' => 'Admin notice',

	'admin:options' => 'Admin options',


/**
 * Plugins
 */
	'plugins:disabled' => 'Plugins are not being loaded because a file named "disabled" is in the mod directory.',
	'plugins:settings:save:ok' => "Settings for the %s plugin were saved successfully.",
	'plugins:settings:save:fail' => "There was a problem saving settings for the %s plugin.",
	'plugins:usersettings:save:ok' => "User settings for the %s plugin were saved successfully.",
	'plugins:usersettings:save:fail' => "There was a problem saving  user settings for the %s plugin.",
	'item:object:plugin' => 'Plugins',

	'admin:plugins' => "Plugins",
	'admin:plugins:activate_all' => 'Activate All',
	'admin:plugins:deactivate_all' => 'Deactivate All',
	'admin:plugins:activate' => 'Activate',
	'admin:plugins:deactivate' => 'Deactivate',
	'admin:plugins:description' => "This admin panel allows you to control and configure tools installed on your site.",
	'admin:plugins:opt:linktext' => "Configure tools...",
	'admin:plugins:opt:description' => "Configure the tools installed on the site. ",
	'admin:plugins:label:author' => "Author",
	'admin:plugins:label:copyright' => "Copyright",
	'admin:plugins:label:categories' => 'Categories',
	'admin:plugins:label:licence' => "License",
	'admin:plugins:label:website' => "URL",
	'admin:plugins:label:repository' => "Code",
	'admin:plugins:label:bugtracker' => "Report issue",
	'admin:plugins:label:donate' => "Donate",
	'admin:plugins:label:moreinfo' => 'more info',
	'admin:plugins:label:version' => 'Version',
	'admin:plugins:label:location' => 'Location',
	'admin:plugins:label:dependencies' => 'Dependencies',

	'admin:plugins:warning:elgg_version_unknown' => 'This plugin uses a legacy manifest file and does not specify a compatible Elgg version. It probably will not work!',
	'admin:plugins:warning:unmet_dependencies' => 'This plugin has unmet dependencies and cannot be activated. Check dependencies under more info.',
	'admin:plugins:warning:invalid' => 'This plugin is invalid: %s',
	'admin:plugins:warning:invalid:check_docs' => 'Check <a href="http://docs.elgg.org/Invalid_Plugin">the Elgg documentation</a> for troubleshooting tips.',
	'admin:plugins:cannot_activate' => 'cannot activate',

	'admin:plugins:set_priority:yes' => "Reordered %s.",
	'admin:plugins:set_priority:no' => "Could not reorder %s.",
	'admin:plugins:set_priority:no_with_msg' => "Could not reorder %s. Error: %s",
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

	'admin:plugins:dependencies:type' => 'Type',
	'admin:plugins:dependencies:name' => 'Name',
	'admin:plugins:dependencies:expected_value' => 'Expected Value',
	'admin:plugins:dependencies:local_value' => 'Actual value',
	'admin:plugins:dependencies:comment' => 'Comment',

	'admin:statistics:description' => "This is an overview of statistics on your site. If you need more detailed statistics, a professional administration feature is available.",
	'admin:statistics:opt:description' => "View statistical information about users and objects on your site.",
	'admin:statistics:opt:linktext' => "View statistics...",
	'admin:statistics:label:basic' => "Basic site statistics",
	'admin:statistics:label:numentities' => "Entities on site",
	'admin:statistics:label:numusers' => "Number of users",
	'admin:statistics:label:numonline' => "Number of users online",
	'admin:statistics:label:onlineusers' => "Users online now",
	'admin:statistics:label:admins'=>"Admins",
	'admin:statistics:label:version' => "Elgg version",
	'admin:statistics:label:version:release' => "Release",
	'admin:statistics:label:version:version' => "Version",

	'admin:server:label:php' => 'PHP',
	'admin:server:label:web_server' => 'Web Server',
	'admin:server:label:server' => 'Server',
	'admin:server:label:log_location' => 'Log Location',
	'admin:server:label:php_version' => 'PHP version',
	'admin:server:label:php_ini' => 'PHP ini file location',
	'admin:server:label:php_log' => 'PHP Log',
	'admin:server:label:mem_avail' => 'Memory available',
	'admin:server:label:mem_used' => 'Memory used',
	'admin:server:error_log' => "Web server's error log",
	'admin:server:label:post_max_size' => 'POST maximum size',
	'admin:server:label:upload_max_filesize' => 'Upload maximum size',
	'admin:server:warning:post_max_too_small' => '(Note: post_max_size must be larger than this value to support uploads of this size)',

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

	'admin:appearance:menu_items' => 'Menu Items',
	'admin:menu_items:configure' => 'Configure main menu items',
	'admin:menu_items:description' => 'Select which menu items you want to show as featured links.  Unused items will be added as "More" at the end of the list.',
	'admin:menu_items:hide_toolbar_entries' => 'Remove links from tool bar menu?',
	'admin:menu_items:saved' => 'Menu items saved.',
	'admin:add_menu_item' => 'Add a custom menu item',
	'admin:add_menu_item:description' => 'Fill out the Display name and URL to add custom items to your navigation menu.',

	'admin:appearance:default_widgets' => 'Default Widgets',
	'admin:default_widgets:unknown_type' => 'Unknown widget type',
	'admin:default_widgets:instructions' => 'Add, remove, position, and configure default widgets for the selected widget page.'
		. '  These changes will only affect new users on the site.',

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
 * Activity river
 */
	'river:all' => 'All Site Activity',
	'river:mine' => 'My Activity',
	'river:friends' => 'Friends Activity',
	'river:select' => 'Show %s',
	'river:comments:more' => '+%u more',
	'river:generic_comment' => 'commented on %s %s',

	'friends:widget:description' => "Displays some of your friends.",
	'friends:num_display' => "Number of friends to display",
	'friends:icon_size' => "Icon size",
	'friends:tiny' => "tiny",
	'friends:small' => "small",

/**
 * Generic action words
 */

	'save' => "Save",
	'reset' => 'Reset',
	'publish' => "Publish",
	'cancel' => "Cancel",
	'saving' => "Saving ...",
	'update' => "Update",
	'preview' => "Preview",
	'edit' => "Edit",
	'delete' => "Delete",
	'accept' => "Accept",
	'load' => "Load",
	'upload' => "Upload",
	'ban' => "Ban",
	'unban' => "Unban",
	'banned' => "Banned",
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
	'comment' => 'Comment',
	'upgrade' => 'Upgrade',
	'sort' => 'Sort',
	'filter' => 'Filter',
	'new' => 'New',
	'add' => 'Add',
	'create' => 'Create',
	'remove' => 'Remove',
	'revert' => 'Revert',

	'site' => 'Site',
	'activity' => 'Activity',
	'members' => 'Members',

	'up' => 'Up',
	'down' => 'Down',
	'top' => 'Top',
	'bottom' => 'Bottom',
	'back' => 'Back',

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
	'unknown_error' => 'Unknown error',

	'content' => "content",
	'content:latest' => 'Latest activity',
	'content:latest:blurb' => 'Alternatively, click here to view the latest content from across the site.',

	'link:text' => 'view link',
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
	'mine' => "Mine",

	'by' => 'by',
	'none' => 'none',

	'annotations' => "Annotations",
	'relationships' => "Relationships",
	'metadata' => "Metadata",
	'tagcloud' => "Tag cloud",
	'tagcloud:allsitetags' => "All site tags",

	'on' => 'On',
	'off' => 'Off',

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
	'installation:dataroot' => "The full path of the data directory:",
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

	'installation:disableapi' => "Elgg provides an API for building web services so that remote applications can interact with your site.",
	'installation:disableapi:label' => "Enable Elgg's web services API",

	'installation:allow_user_default_access:description' => "If checked, individual users will be allowed to set their own default access level that can over-ride the system default access level.",
	'installation:allow_user_default_access:label' => "Allow user default access",

	'installation:simplecache:description' => "The simple cache increases performance by caching static content including some CSS and JavaScript files. Normally you will want this on.",
	'installation:simplecache:label' => "Use simple cache (recommended)",

	'installation:systemcache:description' => "The system cache decreases the loading time of the Elgg engine by caching data to files.",
	'installation:systemcache:label' => "Use system cache (recommended)",

	'upgrading' => 'Upgrading...',
	'upgrade:db' => 'Your database was upgraded.',
	'upgrade:core' => 'Your Elgg installation was upgraded.',
	'upgrade:unlock' => 'Unlock upgrade',
	'upgrade:unlock:confirm' => "The database is locked for another upgrade. Running concurrent upgrades is dangerous. You should only continue if you know there is not another upgrade running. Unlock?",
	'upgrade:locked' => "Cannot upgrade. Another upgrade is running. To clear the upgrade lock, visit the Admin section.",
	'upgrade:unlock:success' => "Upgrade unlocked successfully.",
	'upgrade:unable_to_upgrade' => 'Unable to upgrade.',
	'upgrade:unable_to_upgrade_info' =>
		'This installation cannot be upgraded because legacy views
		were detected in the Elgg core views directory. These views have been deprecated and need to be
		removed for Elgg to function correctly. If you have not made changes to Elgg core, you can
		simply delete the views directory and replace it with the one from the latest
		package of Elgg downloaded from <a href="http://elgg.org">elgg.org</a>.<br /><br />

		If you need detailed instructions, please visit the <a href="http://docs.elgg.org/wiki/Upgrading_Elgg">
		Upgrading Elgg documentation</a>.  If you require assistance, please post to the
		<a href="http://community.elgg.org/pg/groups/discussion/">Community Support Forums</a>.',

	'update:twitter_api:deactivated' => 'Twitter API (previously Twitter Service) was deactivated during the upgrade. Please activate it manually if required.',
	'update:oauth_api:deactivated' => 'OAuth API (previously OAuth Lib) was deactivated during the upgrade.  Please activate it manually if required.',
	'upgrade:site_secret_warning:moderate' => "You are encouraged to regenerate your site key to improve system security. See Configure &gt; Settings &gt; Site Secret",
	'upgrade:site_secret_warning:weak' => "You are strongly encouraged to regenerate your site key to improve system security. See Configure &gt; Settings &gt; Site Secret",

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

	'email:save:success' => "New email address saved.",
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

If you requested this, click on the link below. Otherwise ignore this email.

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
 * Comments
 */

	'comments:count' => "%s comments",

	'river:comment:object:default' => '%s commented on %s',

	'generic_comments:add' => "Leave a comment",
	'generic_comments:post' => "Post comment",
	'generic_comments:text' => "Comment",
	'generic_comments:latest' => "Latest comments",
	'generic_comment:posted' => "Your comment was successfully posted.",
	'generic_comment:deleted' => "The comment was successfully deleted.",
	'generic_comment:blank' => "Sorry, you need to actually put something in your comment before we can save it.",
	'generic_comment:notfound' => "Sorry, we could not find the specified item.",
	'generic_comment:notdeleted' => "Sorry, we could not delete this comment.",
	'generic_comment:failure' => "An unexpected error occurred when adding your comment.",
	'generic_comment:none' => 'No comments',
	'generic_comment:title' => 'Comment by %s',
	'generic_comment:on' => '%s on %s',

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
	'byline' => 'By %s',
	'entity:default:strapline' => 'Created %s by %s',
	'entity:default:missingsupport:popup' => 'This entity cannot be displayed correctly. This may be because it requires support provided by a plugin that is no longer installed.',

	'entity:delete:success' => 'Entity %s has been deleted',
	'entity:delete:fail' => 'Entity %s could not be deleted',


/**
 * Action gatekeeper
 */
	'actiongatekeeper:missingfields' => 'Form is missing __token or __ts fields',
	'actiongatekeeper:tokeninvalid' => "The page you were using had expired. Please try again.",
	'actiongatekeeper:timeerror' => 'The page you were using has expired. Please refresh and try again.',
	'actiongatekeeper:pluginprevents' => 'A extension has prevented this form from being submitted.',
	'actiongatekeeper:uploadexceeded' => 'The size of file(s) uploaded exceeded the limit set by your site administrator',
	'actiongatekeeper:crosssitelogin' => "Sorry, logging in from a different domain is not permitted. Please try again.",


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
 * Javascript
 */

	'js:security:token_refresh_failed' => 'Failed to contact %s. You may experience problems saving content. Please refresh this page.',
	'js:security:token_refreshed' => 'Connection to %s restored!',

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
