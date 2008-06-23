<?php

	$english = array(
	
		/**
		 * Sessions
		 */
			
			'login' => "Log in",
			'loginok' => "You have been logged in.",
			'loginerror' => "We couldn't log you in. Make sure your details are correct and please try again.",
	
			'logout' => "Log out",
			'logoutok' => "You have been logged out.",
			'logouterror' => "We couldn't log you out. Please try again.",
	
		/**
		 * Errors
		 */
			'exception:title' => "We've encountered a problem.",
	
			'InstallationException:CantCreateSite' => "Unable to create a default ElggSite with credentials Name:%s, Url: %s",
		
			'actionundefined' => "The requested action (%s) was not defined in the system.",
			'actionloggedout' => "Sorry, you cannot perform this action while logged out.",
	
			'notfound' => "The requested resource could not be found, or you do not have access to it.",
			
			'SecurityException:Codeblock' => "Denied access to execute privileged code block",
			'DatabaseException:WrongCredentials' => "Elgg couldn't connect to the database using the given credentials %s@%s (pw: %s).",
			'DatabaseException:NoConnect' => "Elgg couldn't select the database '%s', please check that the database is created and you have access to it.",
			'SecurityException:FunctionDenied' => "Access to privileged function '%s' is denied.",
			'DatabaseException:DBSetupIssues' => "There were a number of issues: ",
			'DatabaseException:ScriptNotFound' => "Elgg couldn't find the requested database script at %s.",
			
			'IOException:FailedToLoadGUID' => "Failed to load new %s from GUID:%d",
			'InvalidParameterException:NonElggObject' => "Passing a non-ElggObject to an ElggObject constructor!",
			'InvalidParameterException:UnrecognisedValue' => "Unrecognised value passed to constuctor.",
			
			'InvalidClassException:NotValidElggStar' => "GUID:%d is not a valid %s",
			
			'PluginException:MisconfiguredPlugin' => "%s is a misconfigured plugin.",
			
			'InvalidParameterException:NonElggUser' => "Passing a non-ElggUser to an ElggUser constructor!",
			
			'InvalidParameterException:NonElggSite' => "Passing a non-ElggSite to an ElggSite constructor!",
			
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
			'InvalidParameterException:MissingOwner' => "All files must have an owner!",
			'IOException:CouldNotMake' => "Could not make %s",
			'IOException:MissingFileName' => "You must specify a name before opening a file.",
			'ClassNotFoundException:NotFoundNotSavedWithFile' => "Filestore not found or class not saved with file!",
			'NotificationException:NoNotificationMethod' => "No notification method specified.",
			'NotificationException:NoHandlerFound' => "No handler found for '%s' or it was not callable.",
			'NotificationException:ErrorNotifyingGuid' => "There was an error while notifying %d",
			'NotificationException:NoEmailAddress' => "Could not get the email address for GUID:%d",
			
			'DatabaseException:WhereSetNonQuery' => "Where set contains non WhereQueryComponent",
			'DatabaseException:SelectFieldsMissing' => "Fields missing on a select style query",
			'DatabaseException:UnspecifiedQueryType' => "Unrecognised or unspecified query type.",
			'DatabaseException:NoTablesSpecified' => "No tables specified for query.",
			'DatabaseException:NoACL' => "No access control was provided on query",
			
			'InvalidParameterException:NoEntityFound' => "No entity found, it either doesn't exist or you don't have access to it.",
			
			
			'InvalidParameterException:IdNotExistForGUID' => "Sorry, '%s' does not exist for guid:%d",
			'InvalidParameterException:CanNotExportType' => "Sorry, I don't know how to export '%s'",
			'InvalidParameterException:NoDataFound' => "Could not find any data.",
			'InvalidParameterException:DoesNotBelong' => "Does not belong to entity.",
			'InvalidParameterException:DoesNotBelongOrRefer' => "Does not belong to entity or refer to entity.",
			'InvalidParameterException:MissingParameter' => "Missing parameter, you need to provide a GUID.",
			
			'SecurityException:APIAccessDenied' => "Sorry, API access has been disabled by the administrator.",
			'SecurityException:NoAuthMethods' => "No authentication methods were found that could authenticate this API request.",
			'APIException:ApiResultUnknown' => "API Result is of an unknown type, this should never happen.", 
			
			'ConfigurationException:NoSiteID' => "No site ID has been specified.",
			'InvalidParameterException:UnrecognisedMethod' => "Unrecognised call method '%s'",
			'APIException:MissingParameterInMethod' => "Missing parameter %s in method %s",
			'APIException:ParameterNotArray' => "%s does not appear to be an array.",
			'APIException:UnrecognisedTypeCast' => "Unrecognised type in cast %s for variable '%s' in method '%s'",
			'APIException:InvalidParameter' => "Invalid parameter found for '%s' in method '%s'.",
			'APIException:FunctionParseError' => "%s(%s) has a parsing error.",
			'APIException:FunctionNoReturn' => "%s(%s) returned no value.",
			'SecurityException:AuthTokenExpired' => "Authentication token either missing, invalid or expired.",
			'CallException:InvalidCallMethod' => "%s must be called using '%s'",
			'APIException:MethodCallNotImplemented' => "Method call '%s' has not been implemented.",
			'APIException:AlgorithmNotSupported' => "Algorithm '%s' is not supported or has been disabled.",
			'ConfigurationException:CacheDirNotSet' => "Cache directory 'cache_path' not set.",
			'APIException:NotGetOrPost' => "Request method must be GET or POST",
			'APIException:MissingAPIKey' => "Missing X-Elgg-apikey HTTP header",
			'APIException:MissingHmac' => "Missing X-Elgg-hmac header",
			'APIException:MissingHmacAlgo' => "Missing X-Elgg-hmac-algo header",
			'APIException:MissingTime' => "Missing X-Elgg-time header",
			'APIException:TemporalDrift' => "X-Elgg-time is too far in the past or future",
			'APIException:NoQueryString' => "No data on the query string",
			'APIException:MissingPOSTHash' => "Missing X-Elgg-posthash header",
			'APIException:MissingPOSTAlgo' => "Missing X-Elgg-posthash_algo header",
			'APIException:MissingContentType' => "Missing content type for post data",
			'SecurityException:InvalidPostHash' => "POST data hash is invalid - Expected %s but got %s.",
			'SecurityException:DupePacket' => "Packet signature already seen.",
			'SecurityException:InvalidAPIKey' => "Invalid or missing API Key.",
	
			'NotImplementedException:XMLRPCMethodNotImplemented' => "XML-RPC method call '%s' not implemented.",
			'InvalidParameterException:UnexpectedReturnFormat' => "Call to method '%s' returned an unexpected result.",
			'CallException:NotRPCCall' => "Call does not appear to be a valid XML-RPC call",
	
			'PluginException:NoPluginName' => "The plugin name could not be found",
			
		/**
		 * User details
		 */

			'name' => "Display name",
			'email' => "Email address",
			'username' => "Username",
			'password' => "Password",
			'passwordagain' => "Password (again for verification)",
	
		/**
		 * Access
		 */
	
			'ACCESS_PRIVATE' => "Private",
			'ACCESS_LOGGED_IN' => "Logged in users",
			'ACCESS_PUBLIC' => "Public",
			'PRIVATE' => "Private",
			'LOGGED_IN' => "Logged in users",
			'PUBLIC' => "Public",
			'access' => "Access",
	
		/**
		 * Dashboard
		 */
	
			'dashboard' => "Dashboard",
	
		/**
		 * Profile
		 */
	
			'profile' => "Profile",
	
		/**
		 * Friends
		 */
	
			'friends' => "Friends",
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
	
			'friends:of:none' => "Nobody has added this user as a friend yet.",
			'friends:of:none:you' => "Nobody has added you as a friend yet. Start adding content and fill in your profile to let people find you!",
	
			'friends:of' => "Friends of",
			'friends:of:owned' => "People who have made %s a friend",

		/**
		 * River
		 */
			'river' => "River",
			
			'river:relationship:friend' => 'is now friends with',
		/**
		 * Plugins
		 */
			'plugins:settings:save:ok' => "Settings for the %s plugin were saved successfully.",
			'plugins:settings:save:fail' => "There was a problem saving settings for the %s plugin.",
			
		/**
		 * Search
		 */
	
			'search' => "Search",
			'searchtitle' => "Search: %s",
			'notfound' => "No results found.",
			'next' => "Next",
			'previous' => "Previous",
	
		/**
		 * Account
		 */
	
			'account' => "Account",
			'settings' => "Settings",
	
			'register' => "Register",
			'registerok' => "You have successfully registered for %s.",
			'registerbad' => "Your registration was unsuccessful. The username may already exist, or your passwords might not match.",
	
		/**
		 * Administration
		 */

			'admin:configuration:success' => "Your settings have been saved.",
			'admin:configuration:fail' => "Your settings could not be saved.",
	
			'admin' => "Administration",
			'admin:description' => "The admin panel allows you to control all aspects of the system, from user management to how plugins behave. Choose an option below to get started.",
			
			'admin:user' => "User Administration",
			'admin:user:description' => "This admin panel allows you to control user settings for your site. Choose an option below to get started.",
			'admin:user:opt:linktext' => "Configure users...",
			'admin:user:opt:description' => "Configure users and account information. ",
			
			'admin:site' => "Site Administration",
			'admin:site:description' => "This admin panel allows you to control global settings for your site. Choose an option below to get started.",
			'admin:site:opt:linktext' => "Configure site...",
			'admin:site:opt:description' => "Configure the site technical and non-technical settings. ",
			
			'admin:plugins' => "Tool Administration",
			'admin:plugins:description' => "This admin panel allows you to control and configure tools installed on your site.",
			'admin:plugins:opt:linktext' => "Configure tools...",
			'admin:plugins:opt:description' => "Configure the tools installed on the site. ",
			'admin:plugins:label:author' => "Author",
			'admin:plugins:label:copyright' => "Copyright",
			'admin:plugins:label:licence' => "Licence",
			'admin:plugins:label:website' => "URL",
			'admin:plugins:disable:yes' => "Plugin %s was disabled successfully.",
			'admin:plugins:disable:no' => "Plugin %s could not be disabled.",
			'admin:plugins:enable:yes' => "Plugin %s was enabled successfully.",
			'admin:plugins:enable:no' => "Plugin %s could not be enabled.",
	
			'admin:statistics' => "Statistics",
			'admin:statistics:description' => "This admin panel will allow you to view statistics about your site.",
			'admin:statistics:opt:description' => "View statistical information about users and objects on your site.",
			'admin:statistics:opt:linktext' => "View statistics...",
			'admin:statistics:label:basic' => "Basic site statistics",
			'admin:statistics:label:numentities' => "Entities on site",
			'admin:statistics:label:numusers' => "Number of users",
			'admin:statistics:label:numonline' => "Number of users online",
			'admin:statistics:label:onlineusers' => "User's online now",
			'admin:statistics:label:version' => "Elgg version",
			'admin:statistics:label:version:release' => "Release",
			'admin:statistics:label:version:version' => "Version",
	
			'admin:user:label:search' => "Find users:",
			'admin:user:label:seachbutton' => "Search", 
	
			'admin:user:ban:no' => "Can not ban user",
			'admin:user:ban:yes' => "User banned.",
			'admin:user:delete:no' => "Can not delete user",
			'admin:user:delete:yes' => "User deleted",
			
	
		/**
		 * Generic action words
		 */
	
			'save' => "Save",
			'update' => "Update",
			'edit' => "Edit",
			'delete' => "Delete",
			'load' => "Load",
			'upload' => "Upload",
			'ban' => "Ban",
			'enable' => "Enable",
			'disable' => "Disable",
	
		/**
		 * Generic data words
		 */
	
			'title' => "Title",
			'description' => "Description",
			'tags' => "Tags",
			'spotlight' => "Spotlight",
	
		/**
		 * Input / output strings
		 */

			'deleteconfirm' => "Are you sure you want to delete this item?",
			'fileexists' => "A file has already been uploaded. To replace it, select it below:",
	
		/**
		 * Import / export
		 */
			'importsuccess' => "Import of data was successful",
			
		/**
		 * Widgets
		 */
	
			'widgets' => "Widgets",
			'widget' => "Widget",
			'layout:customise' => "Customise layout",
			'widgets:gallery' => "Widget gallery",
			'widgets:maincontent' => "Main content area widgets",
			'widgets:sidebar' => "Sidebar widgets",
			'widgets:panel:save:success' => "Your widgets were successfully saved.",
			'widgets:panel:save:failure' => "There was a problem saving your widgets. Please try again.",
			'widgets:save:success' => "The widget was successfully saved.",
			'widgets:save:failure' => "We could not save your widget. Please try again.",
	
		/**
		 * Time
		 */
	
			'friendlytime:justnow' => "less than a minute ago",
			'friendlytime:minutes' => "about %s minutes ago",
			'friendlytime:minutes:singular' => "about a minute ago",
			'friendlytime:hours' => "about %s hours ago",
			'friendlytime:hours:singular' => "about an hour ago",
			'friendlytime:days' => "about %s days ago",
			'friendlytime:days:singular' => "yesterday",
	
		/**
		 * Installation and system settings
		 */
	
			'installation:error:htaccess' => "Elgg requires a file called .htaccess to be set in the root directory of its installation. We tried to create it for you, but Elgg doesn't have permission to write to that directory. 

Creating this is easy. Copy the contents of the textbox below into a text editor and save it as .htaccess

",
			'installation:error:settings' => "Elgg couldn't find its settings file. Most of Elgg's settings will be handled for you, but we need you to supply your database details. To do this:

1. Rename engine/settings.example.php to settings.php in your Elgg installation.

2. Open it with a text editor and enter your MySQL database details. If you don't know these, ask your system administrator or technical support for help.

Alternatively, you can enter your database settings below and we will try and do this for you...",
	
			'installation:error:configuration' => "Once you've corrected any configuration issues, press reload to try again.",
	
			'installation' => "Installation",
			'installation:success' => "Elgg's database was installed successfully.",
			'installation:configuration:success' => "Your initial configuration settings have been saved. Now register your initial user; this will be your first system administrator.",
	
			'installation:settings' => "System settings",
			'installation:settings:description' => "Now that the Elgg database has been successfully installed, you need to enter a couple of pieces of information to get your site fully up and running. We've tried to guess where we could, but you may find that you need to tweak these details.",
	
			'installation:settings:dbwizard:prompt' => "Enter your database settings below and hit save:",
			'installation:settings:dbwizard:label:user' => "Database user",
			'installation:settings:dbwizard:label:pass' => "Database password",
			'installation:settings:dbwizard:label:dbname' => "Elgg database",
			'installation:settings:dbwizard:label:host' => "Database hostname (usually 'localhost')",
			'installation:settings:dbwizard:label:prefix' => "Database table prefix (usually 'elgg')",
	
			'installation:settings:dbwizard:savefail' => "We were unable to save the new settings.php. Please save the following file as engine/settings.php using a text editor.",
	
			'sitename' => "The name of your site (eg \"My social networking site\"):",
			'wwwroot' => "The site URL, followed by a trailing slash:",
			'path' => "The full path to your site root on your disk, followed by a trailing slash:",
			'dataroot' => "The full path to the directory where uploaded files will be stored, followed by a trailing slash:",
			'language' => "The default language code for your site, e.g. 'en' for English, 'fr' for French, 'ru' for Russian:",
			'debug' => "Debug mode provides extra information which can be used to diagnose faults, however it can slow your system down so should only be used if you are having problems:",
			'debug:label' => "Turn on debug mode",
			'view' => "Enter the view which will be used as the default for your site (e.g. 'mobile') or leave this blank for the default view:",
	
		/**
		 * Welcome
		 */
	
			'welcome' => "Welcome %s",
			'welcome_message' => "Welcome to this Elgg installation.",
	
	
		/**
		 * XML-RPC
		 */
			'xmlrpc:noinputdata'	=>	"Input data missing",
	
			
	);
	
	add_translation("en",$english);

?>
