<?php
	/**
	 * Russian Translation 0.1
	 * 
	 * Change history:
	 * 0.1 : Olga Ivannikova <olga-ivannikova@rambler.ru>
	 */

	$russian = array(

		/**
		 * Sites
		 */
	
			'item:site' => 'Sites',
	
		/**
		 * Sessions
		 */
			
			'login' => "Войти",
			'loginok' => "Вы вошли.",
			'loginerror' => "Пароль или имя не найдены. Система не может авторизовать вас.",
	
			'logout' => "Выйти",
			'logoutok' => "You have been logged out.",
			'logouterror' => "We couldn't log you out. Please try again.",
	
		/**
		 * Errors
		 */
			'exception:title' => "Добро пожаловать.",
	
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
			
			'InvalidClassException:NotValidElggStar' => "GUID:%d недействителен %s",
			
			'PluginException:MisconfiguredPlugin' => "%s is a misconfigured plugin.",
			
			'InvalidParameterException:NonElggUser' => "Passing a non-ElggUser to an ElggUser constructor!",
			
			'InvalidParameterException:NonElggSite' => "Passing a non-ElggSite to an ElggSite constructor!",
			
			'InvalidParameterException:NonElggGroup' => "Passing a non-ElggGroup to an ElggGroup constructor!",
	
			'IOException:UnableToSaveNew' => "Невозможно сохранить новый %s",
			
			'InvalidParameterException:GUIDNotForExport' => "GUID has not been specified during export, this should never happen.",
			'InvalidParameterException:NonArrayReturnValue' => "Entity serialisation function passed a non-array returnvalue parameter",
			
			'ConfigurationException:NoCachePath' => "Cache path set to nothing!",
			'IOException:NotDirectory' => "%s is not a directory.",
			
			'IOException:BaseEntitySaveFailed' => "Unable to save new object's base entity information!",
			'InvalidParameterException:UnexpectedODDClass' => "import() passed an unexpected ODD class",
			'InvalidParameterException:EntityTypeNotSet' => "Entity type must be set.",
			
			'ClassException:ClassnameNotClass' => "%s не %s.",
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
	
			'ConfigurationException:BadDatabaseVersion' => "The database backend you have installed doesn't meet the basic requirements to run Elgg. Please consult your documentation.",
			'ConfigurationException:BadPHPVersion' => "You need at least PHP version 5.2 to run Elgg.",
			'configurationwarning:phpversion' => "Elgg requires at least PHP version 5.2, you can install it on 5.1.6 but some features may not work. Use at your own risk.",
	
	
			'InstallationException:DatarootNotWritable' => "Your data directory %s is not writable.",
			'InstallationException:DatarootUnderPath' => "Your data directory %s must be outside of your install path.",
			'InstallationException:DatarootBlank' => "You have not specified a data directory.",
	
			'SecurityException:authenticationfailed' => "User could not be authenticated",
		/**
		 * API
		 */
			'system.api.list' => "List all available API calls on the system.",
			'auth.gettoken' => "This API call lets a user log in, returning an authentication token which can be used in leu of a username and password for authenticating further calls.",
	
		/**
		 * User details
		 */

			'name' => "Имя",
			'email' => "Адрес эл. почты",
			'username' => "Имя пользователя",
			'password' => "Пароль",
			'passwordagain' => "Повторите пароль",
			'admin_option' => "Сделать пользователя администратором?",
	
		/**
		 * Access
		 */
	
			'ACCESS_PRIVATE' => "Приватный",
			'ACCESS_LOGGED_IN' => "Человек онлайн",
			'ACCESS_PUBLIC' => "Публичный",
			'PRIVATE' => "Приватный",
			'LOGGED_IN' => "Человек онлайн",
			'PUBLIC' => "Публичный",
			'access' => "Доступ",
	
		/**
		 * Dashboard and widgets
		 */
	
			'dashboard' => "Dashboard",
            'dashboard:configure' => "Редактировать страницу",
			'dashboard:nowidgets' => "Your dashboard is your gateway into the site. Click 'Edit page' to add widgets to keep track of content and your life within the system.",

			'widgets:add' => 'Add widgets to your page',
			'widgets:add:description' => "Choose the features you want to add to your page by dragging them from the <b>Widget gallery</b> on the right, to any of the three widget areas below, and position them where you would like them to appear.

To remove a widget drag it back to the <b>Widget gallery</b>.",
			'widgets:position:fixed' => '(Fixed position on page)',
	
			'widgets' => "Widgets",
			'widget' => "Widget",
			'item:object:widget' => "Widgets",
			'layout:customise' => "Customise layout",
			'widgets:gallery' => "Widget gallery",
			'widgets:leftcolumn' => "Left widgets",
			'widgets:fixed' => "Fixed position",
			'widgets:middlecolumn' => "Middle widgets",
			'widgets:rightcolumn' => "Right widgets",
			'widgets:profilebox' => "Profile box",
			'widgets:panel:save:success' => "Your widgets were successfully saved.",
			'widgets:panel:save:failure' => "There was a problem saving your widgets. Please try again.",
			'widgets:save:success' => "The widget was successfully saved.",
			'widgets:save:failure' => "We could not save your widget. Please try again.",
			
	
		/**
		 * Groups
		 */
	
			'group' => "Группа", 
			'item:group' => "Группы",
	
		/**
		 * Profile
		 */
	
			'profile' => "Профайл",
			'user' => "Пользователь",
			'item:user' => "Пользователи",

		/**
		 * Profile menu items and titles
		 */
	
			'profile:yours' => "Ваш профайл",
			'profile:user' => "%s's profile",
	
			'profile:edit' => "Редактировать профайл",
			'profile:editicon' => "Upload a new profile picture",
			'profile:profilepictureinstructions' => "The profile picture is the image that's displayed on your profile page. <br /> You can change it as often as you'd like. (File formats accepted: GIF, JPG or PNG)",
			'profile:icon' => "Profile picture",
			'profile:createicon' => "Создать ваш аватор",
			'profile:currentavatar' => "Current avatar",
			'profile:createicon:header' => "Profile picture",
			'profile:profilepicturecroppingtool' => "Profile picture cropping tool",
			'profile:createicon:instructions' => "Click and drag a square below to match how you want your picture cropped.  A preview of your cropped picture will appear in the box on the right.  When you are happy with the preview, click 'Create your avatar'. This cropped image will be used throughout the site as your avatar. ",
	
			'profile:editdetails' => "Редактировать детали",
			'profile:editicon' => "Edit profile icon",
	
			'profile:aboutme' => "О себе", 
			'profile:description' => "О себе",
			'profile:briefdescription' => "Краткое описание",
			'profile:location' => "Местонахождение",
			'profile:skills' => "Навыки", 
			'profile:interests' => "Интересы", 
			'profile:contactemail' => "Контактный имэйл",
			'profile:phone' => "Telephone",
			'profile:mobile' => "Мобильныйтелефон",
			'profile:website' => "Веб-сайт",

			'profile:river:update' => "%s updated their profile",
			'profile:river:iconupdate' => "%s updated their profile icon",
	
		/**
		 * Profile status messages
		 */
	
			'profile:saved' => "Профайл успешно сохранён.",
			'profile:icon:uploaded' => "Your profile picture was successfully uploaded.",
	
		/**
		 * Profile error messages
		 */
	
			'profile:noaccess' => "You do not have permission to edit this profile.",
			'profile:notfound' => "Sorry; we could not find the specified profile.",
			'profile:cantedit' => "Sorry; you do not have permission to edit this profile.",
			'profile:icon:notfound' => "Sorry; there was a problem uploading your profile picture.",
	
		/**
		 * Friends
		 */
	
			'friends' => "Друзья",
			'friends:yours' => "Ваши друзья",
			'friends:owned' => "%s's friends",
			'friend:add' => "Добавить друга",
			'friend:remove' => "Убрать из друзей",
	
			'friends:add:successful' => "Вы успешно добавили %s в друзья.",
			'friends:add:failure' => "Невозможно добавить %s в друзья. Попробуйте ещё раз.",
	
			'friends:remove:successful' => "Выуспешно убрали %s из друзей.",
			'friends:remove:failure' => "We couldn't remove %s from your friends. Please try again.",
	
			'friends:none' => "This user hasn't added anyone as a friend yet.",
			'friends:none:you' => "You haven't added anyone as a friend! Search for your interests to begin finding people to follow.",
	
			'friends:none:found' => "No friends were found.",
	
			'friends:of:none' => "Nobody has added this user as a friend yet.",
			'friends:of:none:you' => "Nobody has added you as a friend yet. Start adding content and fill in your profile to let people find you!",
	
			'friends:of' => "Friends of",
			'friends:of:owned' => "Люди, которые добавили %s в друзья",

			 'friends:num_display' => "Number of friends to display",
			 'friends:icon_size' => "Icon size",
			 'friends:tiny' => "tiny",
			 'friends:small' => "small",
			 'friends' => "Друзья",
			 'friends:of' => "Friends of",
			 'friends:collections' => "Collections of friends",
			 'friends:collections:add' => "New friends collection",
			 'friends:addfriends' => "Добавить друзей",
			 'friends:collectionname' => "Collection name",
			 'friends:collectionfriends' => "Friends in collection",
			 'friends:collectionedit' => "Edit this collection",
			 'friends:nocollections' => "You do not yet have any collections.",
			 'friends:collectiondeleted' => "Your collection has been deleted.",
			 'friends:collectiondeletefailed' => "We were unable to delete the collection. Either you don't have permission, or some other problem has occurred.",
			 'friends:collectionadded' => "Your collection was successfuly created",
			 'friends:nocollectionname' => "You need to give your collection a name before it can be created.",
		
	        'friends:river:created' => "%s added the friends widget.",
	        'friends:river:updated' => "%s updated their friends widget.",
	        'friends:river:delete' => "%s removed their friends widget.",
	        'friends:river:add' => "%s add someone as a friend.",
	
		/**
		 * Feeds
		 */
			'feed:rss' => 'Subscribe to feed',
			'feed:odd' => 'Syndicate OpenDD',
	
		/**
		 * River
		 */
			'river' => "River",			
			'river:relationship:friend' => 'теперь друзья с',

		/**
		 * Plugins
		 */
			'plugins:settings:save:ok' => "Settings for the %s plugin were saved successfully.",
			'plugins:settings:save:fail' => "There was a problem saving settings for the %s plugin.",
			'plugins:usersettings:save:ok' => "User settings for the %s plugin were saved successfully.",
			'plugins:usersettings:save:fail' => "There was a problem saving  user settings for the %s plugin.",
	
			'item:object:plugin' => 'Plugin configuration settings',
			
		/**
		 * Notifications
		 */
			'notifications:usersettings' => "Настройки оповещения",
			'notifications:methods' => "Пожалуйста укажите методы оповещания.",
	
			'notifications:usersettings:save:ok' => "Настройки оповещения были успешно сохранены.",
			'notifications:usersettings:save:fail' => "Не удалось сохранить изменения настроек оповещения.",
	
			'user.notification.get' => 'Вернуться с настройкам оповещения данного пользователя.',
			'user.notification.set' => 'Set the notification settings for a given user.',
		/**
		 * Search
		 */
	
			'search' => "Поиск",
			'searchtitle' => "Поиск: %s",
			'users:searchtitle' => "Поиск пользователей: %s",
			'advancedsearchtitle' => "%s with results matching %s",
			'notfound' => "Ваш запрос не дал результатов.",
			'next' => "Дальше",
			'previous' => "Previous",
	
			'viewtype:change' => "Change listing type",
			'viewtype:list' => "List view",
			'viewtype:gallery' => "Gallery",
	
			'tag:search:startblurb' => "Items with tags matching '%s':",

			'user:search:startblurb' => "Users matching '%s':",
			'user:search:finishblurb' => "To view more, click here.",
	
		/**
		 * Account
		 */
	
			'account' => "Account",
			'settings' => "Настройки",
            'tools' => "Tools",
            'tools:yours' => "Your tools",
	
			'register' => "Register",
			'registerok' => "You have successfully registered for %s. To activate your account, please confirm your email address by clicking on the link we sent you.",
			'registerbad' => "Your registration was unsuccessful. The username may already exist, your passwords might not match, or your username or password may be too short.",
			'registerdisabled' => "Registration has been disabled by the system administrator",
	
			'registration:notemail' => 'The email address you provided does not appear to be a valid email address.',
			'registration:userexists' => 'That username already exists',
			'registration:usernametooshort' => 'Your username must be a minimum of 4 characters long.',
			'registration:passwordtooshort' => 'The password must be a minimum of 6 characters long.',
			'registration:dupeemail' => 'This email address has already been registered.',
			'registration:invalidchars' => 'Sorry, your email address contains invalid characters.',
			'registration:emailnotvalid' => 'Sorry, the email address you entered is invalid on this system',
			'registration:passwordnotvalid' => 'Sorry, the password you entered is invalid on this system',
			'registration:usernamenotvalid' => 'Sorry, the username you entered is invalid on this system',
	
			'adduser' => "Добавить пользоваетля",
			'adduser:ok' => "You have successfully added a new user.",
			'adduser:bad' => "The new user could not be created.",
			
			'item:object:reported_content' => "Reported items",
	
			'user:set:name' => "Account name settings",
			'user:name:label' => "Your name",
			'user:name:success' => "Successfully changed your name on the system.",
			'user:name:fail' => "Could not change your name on the system.",
	
			'user:set:password' => "Account password",
			'user:password:label' => "Новый пароль",
			'user:password2:label' => "Повторите новый пароль",
			'user:password:success' => "Пароль изменён",
			'user:password:fail' => "Невозможно изменить пароль.",
			'user:password:fail:notsame' => "Пароли не совпадают!",
			'user:password:fail:tooshort' => "Пароль слишком короткий!",
	
			'user:set:language' => "Настройки языка",
			'user:language:label' => "Your language",
			'user:language:success' => "Your language settings have been updated.",
			'user:language:fail' => "Your language settings could not be saved.",
	
			'user:username:notfound' => 'Username %s not found.',
	
			'user:password:lost' => 'Забыли пароль',
			'user:password:resetreq:success' => 'Successfully requested a new password, email sent',
			'user:password:resetreq:fail' => 'Could not request a new password.',
	
			'user:password:text' => 'To generate a new password, enter your username below. We will send the address of a unique verification page to you via email click on the link in the body of the message and a new password will be sent to you.',
	
		/**
		 * Administration
		 */

			'admin:configuration:success' => "Изменения были сохранены.",
			'admin:configuration:fail' => "Изменения не были сохранены.",
	
			'admin' => "Администрация",
			'admin:description' => "The admin panel allows you to control all aspects of the system, from user management to how plugins behave. Choose an option below to get started.",
			
			'admin:user' => "User Administration",
			'admin:user:description' => "This admin panel allows you to control user settings for your site. Choose an option below to get started.",
			'admin:user:adduser:label' => "Click here to add a new user...",
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
	
			'admin:statistics' => "Статистика",
			'admin:statistics:description' => "This is an overview of statistics on your site. If you need more detailed statistics, a professional administration feature is available.",
			'admin:statistics:opt:description' => "View statistical information about users and objects on your site.",
			'admin:statistics:opt:linktext' => "Просмотреть статистику...",
			'admin:statistics:label:basic' => "Basic site statistics",
			'admin:statistics:label:numentities' => "Entities on site",
			'admin:statistics:label:numusers' => "Количество ползователей",
			'admin:statistics:label:numonline' => "Количество пользователей в сети",
			'admin:statistics:label:onlineusers' => "Пользователей в сети",
			'admin:statistics:label:version' => "Версия Elgg",
			'admin:statistics:label:version:release' => "Release",
			'admin:statistics:label:version:version' => "Версия",
	
			'admin:user:label:search' => "Найти пользователей:",
			'admin:user:label:seachbutton' => "Поиск", 
	
			'admin:user:ban:no' => "Can not ban user",
			'admin:user:ban:yes' => "User banned.",
			'admin:user:unban:no' => "Can not unban user",
			'admin:user:unban:yes' => "User un-banned.",
			'admin:user:delete:no' => "Невозможно удалить пользователя",
			'admin:user:delete:yes' => "Пользователь удалён",
	
			'admin:user:resetpassword:yes' => "Password reset, user notified.",
			'admin:user:resetpassword:no' => "Password could not be reset.",
	
			'admin:user:makeadmin:yes' => "Пользователь теперь администратор.",
			'admin:user:makeadmin:no' => "We could not make this user an admin.",
			
		/**
		 * User settings
		 */
			'usersettings:description' => "The user settings panel allows you to control all your personal settings, from user management to how plugins behave. Choose an option below to get started.",
	
			'usersettings:statistics' => "Ваша статистика",
			'usersettings:statistics:opt:description' => "View statistical information about users and objects on your site.",
			'usersettings:statistics:opt:linktext' => "Account statistics",
	
			'usersettings:user' => "Ваши настройки",
			'usersettings:user:opt:description' => "This allows you to control user settings.",
			'usersettings:user:opt:linktext' => "Change your settings",
	
			'usersettings:plugins' => "Tools",
			'usersettings:plugins:opt:description' => "Configure settings for your active tools.",
			'usersettings:plugins:opt:linktext' => "Configure your tools...",
	
			'usersettings:plugins:description' => "This panel allows you to control and configure the personal settings for the tools installed by your system administrator.",
			'usersettings:statistics:label:numentities' => "Your entities",
	
			'usersettings:statistics:yourdetails' => "Ваши детали",
			'usersettings:statistics:label:name' => "Полное имя",
			'usersettings:statistics:label:email' => "Адрес эл. почты",
			'usersettings:statistics:label:membersince' => "Дата создания",
			'usersettings:statistics:label:lastlogin' => "Последний раз в сети",
	
			
	
		/**
		 * Generic action words
		 */
	
			'save' => "Сохранить",
			'cancel' => "Отменить",
			'saving' => "Saving ...",
			'update' => "Обновить",
			'edit' => "Редактировать",
			'delete' => "Удалить",
			'load' => "Load",
			'upload' => "Загрузить",
			'ban' => "Ban",
			'unban' => "Unban",
			'enable' => "Enable",
			'disable' => "Disable",
			'request' => "Запросить",
			'complete' => "Завершить",
	
			'invite' => "Пригласить",
	
			'resetpassword' => "Изменить пароли",
			'makeadmin' => "Сделать администратором",
	
			'option:yes' => "Да",
			'option:no' => "Нет",
	
			'unknown' => 'Неизвестный',
	
			'active' => 'Активный',
			'total' => 'Общий',
	
			'learnmore' => "Click here to learn more.",
	
			'content' => "content",
			'content:latest' => 'Latest activity',
			'content:latest:blurb' => 'Alternatively, click here to view the latest content from across the site.',
	
			'link:text' => 'view link',
	
	
		/**
		 * Generic data words
		 */
	
			'title' => "Название",
			'description' => "Описание",
			'tags' => "Тэги",
			'spotlight' => "Spotlight",
			'all' => "All",
	
			'by' => 'by',
	
			'annotations' => "Annotations",
			'relationships' => "Relationships",
			'metadata' => "Metadata",
	
		/**
		 * Input / output strings
		 */

			'deleteconfirm' => "Are you sure you want to delete this item?",
			'fileexists' => "A file has already been uploaded. To replace it, select it below:",
	
		/**
		 * Import / export
		 */
			'importsuccess' => "Import of data was successful",
			'importfail' => "OpenDD import of data failed.",
	
		/**
		 * Time
		 */
	
			'friendlytime:justnow' => "только сейчас",
			'friendlytime:minutes' => "%s минут назад",
			'friendlytime:minutes:singular' => "минуту назад",
			'friendlytime:hours' => "%s часов назад",
			'friendlytime:hours:singular' => "час назад",
			'friendlytime:days' => "%s дней назад",
			'friendlytime:days:singular' => "вчера",
	
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
	
			'installation:settings' => "Системные настройки",
			'installation:settings:description' => "Now that the Elgg database has been successfully installed, you need to enter a couple of pieces of information to get your site fully up and running. We've tried to guess where we could, but <b>you should check these details.</b>",
	
			'installation:settings:dbwizard:prompt' => "Enter your database settings below and hit save:",
			'installation:settings:dbwizard:label:user' => "Database user",
			'installation:settings:dbwizard:label:pass' => "Database password",
			'installation:settings:dbwizard:label:dbname' => "Elgg database",
			'installation:settings:dbwizard:label:host' => "Database hostname (usually 'localhost')",
			'installation:settings:dbwizard:label:prefix' => "Database table prefix (usually 'elgg')",
	
			'installation:settings:dbwizard:savefail' => "We were unable to save the new settings.php. Please save the following file as engine/settings.php using a text editor.",
	
			'installation:sitename' => "The name of your site (eg \"My social networking site\"):",
			'installation:sitedescription' => "Short description of your site (optional)",
			'installation:wwwroot' => "The site URL, followed by a trailing slash:",
			'installation:path' => "The full path to your site root on your disk, followed by a trailing slash:",
			'installation:dataroot' => "The full path to the directory where uploaded files will be stored, followed by a trailing slash:",
			'installation:dataroot:warning' => "You must create this directory manually. It should sit in a different directory to your Elgg installation.",
			'installation:language' => "The default language for your site:",
			'installation:debug' => "Debug mode provides extra information which can be used to diagnose faults, however it can slow your system down so should only be used if you are having problems:",
			'installation:debug:label' => "Turn on debug mode",
			'installation:usage' => "This option lets Elgg send anonymous usage statistics back to Curverider.",
			'installation:usage:label' => "Send anonymous usage statistics",
			'installation:view' => "Enter the view which will be used as the default for your site or leave this blank for the default view (if in doubt, leave as default):",

			'installation:siteemail' => "Site email address (used when sending system emails)",
	
			'installation:disableapi' => "Elgg comes with an flexible and extendible API that enables applications use certain Elgg features remotely",
			'installation:disableapi:label' => "Enable the RESTful API",
	
			'upgrade:db' => 'Your database was upgraded.',
	
		/**
		 * Welcome
		 */
	
			'welcome' => "Welcome %s",
			'welcome_message' => "Welcome to this Elgg installation.",
	
		/**
		 * Emails
		 */
			'email:settings' => "Настройки имэйл адреса",
			'email:address:label' => "Ваш адрес электронной почты",
			
			'email:save:success' => "Новый имэйл сохранён,подтверждение запрошено.",
			'email:save:fail' => "Новый имэйл не был сохранён",
	
			'email:confirm:success' => "Вы подтвердили ваш новый имэйл!",
			'email:confirm:fail' => "Адрес вашей электронной почты не был изменён...",
	
			'friend:newfriend:subject' => "%s добавил вас в друзья!",
			'friend:newfriend:body' => "%s добавил вас в друзья!

To view their profile, click here:

	%s

You cannot reply to this email.",
	
	
			'email:validate:subject' => "%s please confirm your email address!",
			'email:validate:body' => "Hi %s,

Please confirm your email address by clicking on the link below:

%s
",
			'email:validate:success:subject' => "Email validated %s!",
			'email:validate:success:body' => "Hi %s,
			
Congratulations, you have successfully validated your email address.",
	
	
			'email:resetpassword:subject' => "Пароль изменён!",
			'email:resetpassword:body' => "Hi %s,
			
Your password has been reset to: %s",
	
	
			'email:resetreq:subject' => "Запрос нового пароля.",
			'email:resetreq:body' => "Hi %s,
			
Somebody (from the IP address %s) has requested a new password for their account.

If you requested this click on the link below, otherwise ignore this email.

%s
",

	
		/**
		 * XML-RPC
		 */
			'xmlrpc:noinputdata'	=>	"Input data missing",
	
		/**
		 * Comments
		 */
	
			'comments:count' => "%s комментарии",
			'generic_comments:add' => "Добавить комментарий",
			'generic_comments:text' => "Комментарий",
			'generic_comment:posted' => "Your comment was successfully posted.",
			'generic_comment:deleted' => "Your comment was successfully deleted.",
			'generic_comment:blank' => "Sorry; you need to actually put something in your comment before we can save it.",
			'generic_comment:notfound' => "Sorry; we could not find the specified item.",
			'generic_comment:notdeleted' => "Sorry; we could not delete this comment.",
			'generic_comment:failure' => "An unexpected error occurred when adding your comment. Please try again.",
	
			'generic_comment:email:subject' => 'У вас новый комментарий!',
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
			'actiongatekeeper:tokeninvalid' => 'Token provided by form does not match that generated by server.',
			'actiongatekeeper:timeerror' => 'Form has expired, please refresh and try again.',
			'actiongatekeeper:pluginprevents' => 'A extension has prevented this form from being submitted.',
	
		/**
		 * Languages according to ISO 639-1
		 */
			"aa" => "Афарский",
			"ab" => "Абхазский",
			"af" => "Африкаанс",
			"am" => "Амхарский",
			"ar" => "Арабский",
			"as" => "Ассаамский",
			"ay" => "Аймара",
			"az" => "Азербайнджанский",
			"ba" => "Башкирский",
			"be" => "Белорусский",
			"bg" => "Болгарский",
			"bh" => "Бихари",
			"bi" => "Бислама",
			"bn" => "Бенгальский,",
			"bo" => "Тибетский",
			"br" => "Бретонский",
			"ca" => "Каталанский",
			"co" => "Корсиканский",
			"cs" => "Чешский",
			"cy" => "Уэльский",
			"da" => "Датский",
			"de" => "Немецкий",
			"dz" => "Бхутани",
			"el" => "Греческий",
			"en" => "Английский",
			"eo" => "Эсперанто",
			"es" => "Испанский",
			"et" => "Эстонский",
			"eu" => "Баскский язык",
			"fa" => "Персидский",
			"fi" => "Финский",
			"fj" => "Фиджийский",
			"fo" => "Фарерский",
			"fr" => "Французский",
			"fy" => "Фарерский",
			"ga" => "Ирландский",
			"gd" => "Шотландский(гальский)",
			"gl" => "Galician",
			"gn" => "Гуарани",
			"gu" => "Гуджаратский",
			"he" => "Иврит",
			"ha" => "Хауса",
			"hi" => "Хинди",
			"hr" => "Хорватский",
			"hu" => "Венгерский",
			"hy" => "Армянский",
			"ia" => "Интерлингва",
			"id" => "Индонезейский",
			"ie" => "Окйиденталь",
			"ik" => "Инупиак",
			//"in" => "Индонезийский",
			"is" => "Исландский",
			"it" => "Итальянский",
			"iu" => "Инуктитут",
			"iw" => "Иврит",
			"ja" => "Японский",
			"ji" => "Йдиш",
			"jw" => "Яванский",
			"ka" => "Грузинский",
			"kk" => "Казахский",
			"kl" => "Гренландский",
			"km" => "Кхмерский",
			"kn" => "Kанада",
			"ko" => "Корейский",
			"ks" => "Кашмири",
			"ku" => "Курдский",
			"ky" => "Киргизский",
			"la" => "Латинский",
			"ln" => "Лингала",
			"lo" => "Лаосский",
			"lt" => "Латышский",
			"lv" => "Литовский",
			"mg" => "Малагасийский",
			"mi" => "Маори",
			"mk" => "Mакедонский",
			"ml" => "Малаялам",
			"mn" => "Монгольский",
			"mo" => "Молдавский",
			"mr" => "Маратхи",
			"ms" => "Малагасийский",
			"mt" => "Мальтийский",
			"my" => "Бирманский",
			"na" => "Науранский",
			"ne" => "Непальский",
			"nl" => "Датский",
			"no" => "Норвежский",
			"oc" => "Окситанский",
			"om" => "Афан Оромо",
			"or" => "Ория",
			"pa" => "Панджаби",
			"pl" => "Польский",
			"ps" => "Пашто",
			"pt" => "Португальский",
			"qu" => "Кечуа",
			"rm" => "Рето-романский",
			"rn" => "Рунди",
			"ro" => "Румынский",
			"ru" => "Русский",
			"rw" => "Руанда",
			"sa" => "Санскрит",
			"sd" => "Синдхи",
			"sg" => "Сангхо",
			"sh" => "Сербохорватский",
			"si" => "Сингальский",
			"sk" => "Словацкий",
			"sl" => "Словенский",
			"sm" => "Самоанский",
			"sn" => "Шона",
			"so" => "Сомали",
			"sq" => "Албанский",
			"sr" => "Сербский",
			"ss" => "Свати",
			"st" => "Сеперди",
			"su" => "Сунданский",
			"sv" => "Шведский",
			"sw" => "Суахили",
			"ta" => "Тамильский",
			"te" => "Телугу",
			"tg" => "Таджикский",
			"th" => "Тайский",
			"ti" => "Семитский",
			"tk" => "Туркменский",
			"tl" => "Тагальский",
			"tn" => "Тсвана",
			"to" => "Tонганский",
			"tr" => "Турецкий",
			"ts" => "Тсонга",
			"tt" => "Татарский",
			"tw" => "Тви",
			"ug" => "Уйгурский",
			"uk" => "Украинский",
			"ur" => "Урду",
			"uz" => "Узбекский",
			"vi" => "Вьетнамский",
			"vo" => "Волапюк",
			"wo" => "Волоф",
			"xh" => "Коса",
			//"y" => "Yiddish",
			"yi" => "Идиш",
			"yo" => "Йоруба",
			"za" => "Чжуанский",
			"zh" => "Китайский",
			"zu" => "Зулусский",
	);
	
	add_translation("ru",$russian);

?>