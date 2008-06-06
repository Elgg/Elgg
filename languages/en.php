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
	
			'actionundefined' => "The requested action (%s) was not defined in the system.",
			'actionloggedout' => "Sorry, you cannot perform this action while logged out.",
	
			'notfound' => "The requested resource could not be found, or you do not have access to it.",
			
			'SecurityException:Codeblock' => "Denied access to execute privileged code block",
			'DatabaseException:WrongCredentials' => "Elgg couldn't connect to the database using the given credentials.",
			'DatabaseException:NoConnect' => "Elgg couldn't select the database %s.",
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
	
			'PRIVATE' => "Private",
			'LOGGED_IN' => "Logged in users",
			'PUBLIC' => "Public",
	
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
			
			
		/**
		 * Search
		 */
	
			'search' => "Search",
			'searchtitle' => "Search: %s",
	
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
	
			'admin' => "Administration",
			'admin:description' => "The admin panel allows you to control all aspects of the system, from user management to how plugins behave. Choose an option below to get started.",
	
		/**
		 * Generic action words
		 */
	
			'save' => "Save",
			'update' => "Update",
			'edit' => "Edit",
			'delete' => "Delete",
			'load' => "Load",
			'upload' => "Upload",
	
		/**
		 * Generic data words
		 */
	
			'title' => "Title",
			'description' => "Description",
			'tags' => "Tags",
	
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
		 * Installation and system settings
		 */
	
			'installation' => "Installation",
			'installation:success' => "Elgg's database was installed successfully.",
			'installation:configuration:success' => "Your initial configuration settings have been saved. Now register your initial user; this will be your first system administrator.",
	
			'installation:settings' => "System settings",
			'installation:settings:description' => "Now that the Elgg database has been successfully installed, you need to enter a couple of pieces of information to get your site fully up and running. We've tried to guess where we could, but you may find that you need to tweak these details.",
			'sitename' => "The name of your site (eg \"My social networking site\"):",
			'wwwroot' => "The site URL, followed by a trailing slash:",
			'path' => "The full path to your site root on your disk, followed by a trailing slash:",
			'dataroot' => "The full path to the directory where uploaded files will be stored, followed by a trailing slash:",
	
		/**
		 * Welcome
		 */
	
			'welcome' => "Welcome %s",
			'welcome_message' => "Welcome to this Elgg installation.",
	
	);
	
	add_translation("en",$english);

?>