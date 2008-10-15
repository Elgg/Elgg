<?php
	/**
	 * Translators
	 * Olga Ivannikova
	 * Pillgrim
	 * 
	 */

	$russian = array(

		/**
		 * Sites
		 */
	
			'item:site' => 'Сайты',
	
		/**
		 * Sessions
		 */
			
			'login' => "Войти",
			'loginok' => "Вы вошли.",
			'loginerror' => "Пароль или имя не найдены. Система не может авторизовать вас.",
	
			'logout' => "Выйти",
			'logoutok' => "Вы вышли из системы.",
			'logouterror' => "Ошибка при выходе. Пожалуйста попробуйте еще раз.",
	
		/**
		 * Errors
		 */
			'exception:title' => "Добро пожаловать в Elgg.",
	
			'InstallationException:CantCreateSite' => "Не удалось создать ElggSite по умолчанию со следующими настройками Имя:%s, Url: %s",
		
			'actionundefined' => "Указанное действие (%s) не зарегистрировано в системе.",
			'actionloggedout' => "Извините, что бы сделать это необходимо войти в систему.",
	
			'notfound' => "Указанный объект не найден, или у вас нет прав на доступ к нему.",
			
			'SecurityException:Codeblock' => "Доступ на выполнение привилегированного участка кода запрещён",
			'DatabaseException:WrongCredentials' => "Невозможно установить соединение с базой данных %s@%s (pw: %s).",
			'DatabaseException:NoConnect' => "Невозможно выбрать базу данных '%s', убедитесь что она существует и у вас есть к ней доступ.",
			'SecurityException:FunctionDenied' => "Доступ к привилегированной функции '%s' запрещён.",
			'DatabaseException:DBSetupIssues' => "Произошли следующие проблемы: ",
			'DatabaseException:ScriptNotFound' => "Невозможно найти запрошенный скрипт в %s.",
			
			'IOException:FailedToLoadGUID' => "Не удалось загрузить %s из GUID:%d",
			'InvalidParameterException:NonElggObject' => "Передан не-ElggObject в конструктор ElggObject!",
			'InvalidParameterException:UnrecognisedValue' => "Неизвестное значение предано в конструктор.",
			
			'InvalidClassException:NotValidElggStar' => "GUID:%d недействителен %s",
			
			'PluginException:MisconfiguredPlugin' => "Приложение %s установленно некорректно.",
			
			'InvalidParameterException:NonElggUser' => "Передан не-ElggUser в конструктор ElggUser!",
			
			'InvalidParameterException:NonElggSite' => "Передан не-ElggSite в конструктор ElggSite!",
			
			'InvalidParameterException:NonElggGroup' => "Передан не--ElggGroup в конструктор ElggGroup!",
	
			'IOException:UnableToSaveNew' => "Невозможно сохранить новый %s",
			
			'InvalidParameterException:GUIDNotForExport' => "GUID не был указан во врему экспорта, этого никогда не должно было произойти.",
			'InvalidParameterException:NonArrayReturnValue' => "В функцию сериализации Сущность передан параметр returnvalue не являющийся массивом",
			
			'ConfigurationException:NoCachePath' => "Путь к кэшу пуст!",
			'IOException:NotDirectory' => "%s не является директорией.",
			
			'IOException:BaseEntitySaveFailed' => "Невозможно сохранить базовую информацию об новой сущности!",
			'InvalidParameterException:UnexpectedODDClass' => "import() передал неожиданный класс ODD",
			'InvalidParameterException:EntityTypeNotSet' => "Тип сущности должен быть установлен.",
			
			'ClassException:ClassnameNotClass' => "%s не %s.",
			'ClassNotFoundException:MissingClass' => "Класс '%s' не найден, возможно не установлен нужны плагин?",
			'InstallationException:TypeNotSupported' => "Тип %s не поддерживается. Это указывает на проблемы в вашей копии Elgg, скорее всего после неудачного обновления.",

			'ImportException:ImportFailed' => "Невозможно импортировать элемент %d",
			'ImportException:ProblemSaving' => "При сохранении %s случилась ошибка",
			'ImportException:NoGUID' => "Создана новая сущность без GUID, этого не должно никогда случаться.",
			
			'ImportException:GUIDNotFound' => "Сущность '%d' не найдена.",
			'ImportException:ProblemUpdatingMeta' => "При обновлении свойства '%s' сущности '%d' произошла ошибка",
			
			'ExportException:NoSuchEntity' => "Нет такой сущности GUID:%d", 
			
			'ImportException:NoODDElements' => "Не найдены OpenDD элементы в импортированных данных, import не удался.",
			'ImportException:NotAllImported' => "Не все элементы были импортированы.",
			
			'InvalidParameterException:UnrecognisedFileMode' => "Неизвестный режим файла  '%s'",
			'InvalidParameterException:MissingOwner' => "Все файлы должны иметь владельца!",
			'IOException:CouldNotMake' => "Невозможно создать %s",
			'IOException:MissingFileName' => "Необходимо указать имя перед открытием файла.",
			'ClassNotFoundException:NotFoundNotSavedWithFile' => "Filestore не найден или имя класса не сохранёно с файлом!",
			'NotificationException:NoNotificationMethod' => "Не указан метод уведомления.",
			'NotificationException:NoHandlerFound' => "Нет обработчика для '%s', или его не возможно вызвать.",
			'NotificationException:ErrorNotifyingGuid' => "Случилась ошибка при уведомлении %d",
			'NotificationException:NoEmailAddress' => "Невозможно получить email адрес для GUID:%d",
			'NotificationException:MissingParameter' => "Не хватает обязательного параметра '%s'",
			
			'DatabaseException:WhereSetNonQuery' => "Where часть запроса содержит несовместимые с WhereQueryComponent компоненты",
			'DatabaseException:SelectFieldsMissing' => "Нет полей в SELECT запросе",
			'DatabaseException:UnspecifiedQueryType' => "В запросе не указан тип запроса.",
			'DatabaseException:NoTablesSpecified' => "В запросе не указаны таблицы.",
			'DatabaseException:NoACL' => "В запросе не указаны настройки доступа",
			
			'InvalidParameterException:NoEntityFound' => "Сущность не найдена, она либо не существует, либо у вас нет доступа к ней.",
			
			'InvalidParameterException:GUIDNotFound' => "GUID:%s не найден, либо у вас нет доступа к нему.",
			'InvalidParameterException:IdNotExistForGUID' => "Извините, '%s' не существует для guid:%d",
			'InvalidParameterException:CanNotExportType' => "Извините, не знаю как экспортировать '%s'",
			'InvalidParameterException:NoDataFound' => "Данные не найдены.",
			'InvalidParameterException:DoesNotBelong' => "Не принадлежит сущности.",
			'InvalidParameterException:DoesNotBelongOrRefer' => "Не принадлежит сущности и не указывает на сущность.",
			'InvalidParameterException:MissingParameter' => "Не хватает параметра, необходимо указать GUID.",
			
			'SecurityException:APIAccessDenied' => "Извините, доступ к API был отключен администратором.",
			'SecurityException:NoAuthMethods' => "Не удалось найти подходящего метода для авторизации данного API запроса.",
			'APIException:ApiResultUnknown' => "Тип результата API запроса неопределён, этого не должно было никогда случиться.", 
			
			'ConfigurationException:NoSiteID' => "Не указан ID сайта.",
			'InvalidParameterException:UnrecognisedMethod' => "Неизвестный метод '%s'",
			'APIException:MissingParameterInMethod' => "Не хватает параметра %s в методе %s",
			'APIException:ParameterNotArray' => "%s не является массивом.",
			'APIException:UnrecognisedTypeCast' => "Неизвестный тип при касте %s переменной '%s' в методе '%s'",
			'APIException:InvalidParameter' => "Невалидный параметр найден для '%s' в методе '%s'.",
			'APIException:FunctionParseError' => "Ошибка парсинга %s(%s).",
			'APIException:FunctionNoReturn' => "%s(%s) не вернул значения.",
			'SecurityException:AuthTokenExpired' => "Авторизационный token не передан, не валиден или просрочен.",
			'CallException:InvalidCallMethod' => "%s должен вызываться при помощи '%s'",
			'APIException:MethodCallNotImplemented' => "Метод '%s' не существует.",
			'APIException:AlgorithmNotSupported' => "Алгоритм '%s' не поддерживается или выключен.",
			'ConfigurationException:CacheDirNotSet' => "Директория для кэша 'cache_path' не установлена.",
			'APIException:NotGetOrPost' => "Метод должен быть GET или POST",
			'APIException:MissingAPIKey' => "Не хватает X-Elgg-apikey HTTP заголовка",
			'APIException:MissingHmac' => "Не хватает X-Elgg-hmac заголовка",
			'APIException:MissingHmacAlgo' => "Не хватает X-Elgg-hmac-algo заголовка",
			'APIException:MissingTime' => "Не хватает X-Elgg-time заголовка",
			'APIException:TemporalDrift' => "X-Elgg-time в слишком отдалённом будущем.",
			'APIException:NoQueryString' => "Нет данных в строке запроса",
			'APIException:MissingPOSTHash' => "Не хватает X-Elgg-posthash заголовка",
			'APIException:MissingPOSTAlgo' => "Не хватает X-Elgg-posthash_algo заголовка",
			'APIException:MissingContentType' => "Не указан тип содержимого для POST запроса",
			'SecurityException:InvalidPostHash' => "Hash для POST данных не валиден - ождался %s, а получен %s.",
			'SecurityException:DupePacket' => "Подпись пакета повторяется.",
			'SecurityException:InvalidAPIKey' => "Не валидный или пустой API Key.",
			'NotImplementedException:CallMethodNotImplemented' => "Вызов метода '%s' пока не поддерживается.",
	
			'NotImplementedException:XMLRPCMethodNotImplemented' => "XML-RPC вызов метода '%s' не поддерживается.",
			'InvalidParameterException:UnexpectedReturnFormat' => "Метод '%s' вернул неожиданный результат.",
			'CallException:NotRPCCall' => "Вызов не похож на валидный XML-RPC запрос",
	
			'PluginException:NoPluginName' => "Приложение не найдено",
	
			'ConfigurationException:BadDatabaseVersion' => "База данных не соответствует требованиям Elgg. Пожалуйста, проконсультируйтесь с документацией.",
			'ConfigurationException:BadPHPVersion' => "Необходим PHP версии не менее 5.2 что бы запустить Elgg.",
			'configurationwarning:phpversion' => "Elgg нуждается по крайней мере в PHP версии 5.2, возможно будет работать с ограничениями на 5.1.6. Используйте на ваш страх и риск.",
			
	
			'InstallationException:DatarootNotWritable' => "Директория для данных %s не имеет прав на запись.",
			'InstallationException:DatarootUnderPath' => "Директория для данных %s должна находится вне пути, в котором находится Elgg.",
			'InstallationException:DatarootBlank' => "Директория для данных не указана.",

			'SecurityException:authenticationfailed' => "Пользователь не может быть авторизован",
			'CronException:unknownperiod' => '%s не является правильным периодом.',
			
			'system.api.list' => "Выводит список всех доступных API вызовов в системе.",
			'auth.gettoken' => "Этот вызов API позволяет пользователю войти в систему, возвращая token авторизации, который может быть использован вместо имени пользователя и пароля",

	
		/**
		 * User details
		 */
			'name' => "Отображаемое имя",
			'email' => "Адрес электронной почты",
			'username' => "Логин",
			'password' => "Пароль",
			'passwordagain' => "Пароль (повтор для проверки)",
			'admin_option' => "Сделать этого пользователя администратором?",
	
		/**
		 * Access
		 */
	
			'ACCESS_PRIVATE' => "Только себе",
			'ACCESS_LOGGED_IN' => "Зарегистрированным пользователям",
			'ACCESS_PUBLIC' => "Всем",
			'PRIVATE' => "Только себе",
			'LOGGED_IN' => "Зарегистрированным пользователям",
			'PUBLIC' => "Всем",
			'access' => "Доступ",
	
		/**
		 * Dashboard and widgets
		 */
	
			'dashboard' => "Панель инструментов",
			'dashboard:configure' => "Настроить вид",
			'dashboard:nowidgets' => "Это Ваша главная страница. Нажмите 'Редактировать' что бы добавить виджеты, позволяющие следить за происходящим в системе.",

			'widgets:add' => 'Добавить виджеты на вашу страницу',
			'widgets:add:description' => "Выбирите интересующие вас виджеты перетащив их мышью из <b>Галлереи</b> в правой части страницы в любую из 3-х колонок ниже, и расположите в том порядке, в котором они должны отображаться.

Чтобы удалить виджет, переместите его обратно в <b>Галлерею</b>.",
			'widgets:position:fixed' => '(Фиксированная позиция на странице)',
	
			'widgets' => "Виджеты",
			'widget' => "Виджет",
			'item:object:widget' => "Виджеты",
			'layout:customise' => "Настроить расположение",
			'widgets:gallery' => "Галлерея",
			'widgets:leftcolumn' => "Левая колонка",
			'widgets:fixed' => "Фиксированная позиция",
			'widgets:middlecolumn' => "Средняя колонка",
			'widgets:rightcolumn' => "Правая колонка",
			'widgets:profilebox' => "Блок личных данных",
			'widgets:panel:save:success' => "Настройки виджетов успешно сохранены.",
			'widgets:panel:save:failure' => "При сохранении виджетов, произошла какая-то ошибка. Попробуйте ещё раз.",
			'widgets:save:success' => "Виджет успешно сохранён.",
			'widgets:save:failure' => "Невозможно сохранить виджет, попробуйте ещё раз.",
			
	
		/**
		 * Groups
		 */
	
			'group' => "Группы", 
			'item:group' => "Группы",
	
		/**
		 * Profile
		 */
	
			'profile' => "Личные данные",
			'user' => "Пользователь",
			'item:user' => "Пользователи",

		/**
		 * Profile menu items and titles
		 */
	
			'profile:yours' => "Ваши личные данные",
			'profile:user' => "Личные данные пользователя %s",
	
			'profile:edit' => "Редактировать личные данные",
			'profile:editicon' => "Закачать новое фото",
			'profile:profilepictureinstructions' => "Моё фото - это картинка, которая будет показываться на странице вашего профайла. <br /> Вы можете её менять так часто, как пожелаете. (Разрешённые форматы: GIF, JPG или PNG)",
			'profile:icon' => "Моё фото",
			'profile:createicon' => "Сохранить",
			'profile:currentavatar' => "Текущее",
			'profile:createicon:header' => "Моё фото",
			'profile:profilepicturecroppingtool' => "Выбор границ",
			'profile:createicon:instructions' => "Нажмите и, удерживая кнопку мыши, укажите область, которую вы хотите обрезать на вашей картинке. Предварительный просмотр вашей обрезанной картинки появится справа.  Когда вы будете удовлетворены картинкой в предварительном просмотре, нажмите <b>'Сохранить'</b>. Это обрезанное изображение будет использоваться как ваше фото во всех разделах сайта.",
	
			'profile:editdetails' => "Редактировать",
			'profile:editicon' => "Моё фото",
	
			'profile:aboutme' => "Обо мне", 
			'profile:description' => "Обо мне",
			'profile:briefdescription' => "Коротко о себе",
			'profile:location' => "Город, Страна",
			'profile:skills' => "Навыки",  
			'profile:interests' => "Интересы", 
			'profile:contactemail' => "Контактный имэйл",
			'profile:phone' => "Telephone",
			'profile:mobile' => "Мобильныйтелефон",
			'profile:website' => "Веб-сайт",

			'name' => "Отображаемое имя",
			'email' => "Адрес электронной почты",
			'username' => "Логин",
			'password' => "Пароль",
			'passwordagain' => "Пароль (повтор для проверки)",
			'admin_option' => "Сделать этого пользователя администратором?",
	
		/**
		 * Access
		 */
	
			'ACCESS_PRIVATE' => "Только себе",
			'ACCESS_LOGGED_IN' => "Зарегистрированным пользователям",
			'ACCESS_PUBLIC' => "Всем",
			'PRIVATE' => "Только себе",
			'LOGGED_IN' => "Зарегистрированным пользователям",
			'PUBLIC' => "Всем",
			'access' => "Доступ",
	
		/**
		 * Dashboard and widgets
		 */
	
			'dashboard' => "Панель инструментов",
			'dashboard:configure' => "Настроить вид",
			'dashboard:nowidgets' => "Это Ваша главная страница. Нажмите 'Редактировать' что бы добавить виджеты, позволяющие следить за происходящим в системе.",

			'widgets:add' => 'Добавить виджеты на вашу страницу',
			'widgets:add:description' => "Выбирите интересующие вас виджеты перетащив их мышью из <b>Галлереи</b> в правой части страницы в любую из 3-х колонок ниже, и расположите в том порядке, в котором они должны отображаться.

Чтобы удалить виджет, переместите его обратно в <b>Галлерею</b>.",
			'widgets:position:fixed' => '(Фиксированная позиция на странице)',
	
			'widgets' => "Виджеты",
			'widget' => "Виджет",
			'item:object:widget' => "Виджеты",
			'layout:customise' => "Настроить расположение",
			'widgets:gallery' => "Галлерея",
			'widgets:leftcolumn' => "Левая колонка",
			'widgets:fixed' => "Фиксированная позиция",
			'widgets:middlecolumn' => "Средняя колонка",
			'widgets:rightcolumn' => "Правая колонка",
			'widgets:profilebox' => "Блок личных данных",
			'widgets:panel:save:success' => "Настройки виджетов успешно сохранены.",
			'widgets:panel:save:failure' => "При сохранении виджетов, произошла какая-то ошибка. Попробуйте ещё раз.",
			'widgets:save:success' => "Виджет успешно сохранён.",
			'widgets:save:failure' => "Невозможно сохранить виджет, попробуйте ещё раз.",
			
	
		/**
		 * Groups
		 */
	
			'group' => "Группы", 
			'item:group' => "Группы",
	
		/**
		 * Profile
		 */
	
			'profile' => "Личные данные",
			'user' => "Пользователь",
			'item:user' => "Пользователи",

		/**
		 * Profile menu items and titles
		 */
	
			'profile:yours' => "Ваши личные данные",
			'profile:user' => "Личные данные пользователя %s",
	
			'profile:edit' => "Редактировать личные данные",
			'profile:editicon' => "Закачать новое фото",
			'profile:profilepictureinstructions' => "Моё фото - это картинка, которая будет показываться на странице вашего профайла. <br /> Вы можете её менять так часто, как пожелаете. (Разрешённые форматы: GIF, JPG или PNG)",
			'profile:icon' => "Моё фото",
			'profile:createicon' => "Сохранить",
			'profile:currentavatar' => "Текущее",
			'profile:createicon:header' => "Моё фото",
			'profile:profilepicturecroppingtool' => "Выбор границ",
			'profile:createicon:instructions' => "Нажмите и, удерживая кнопку мыши, укажите область, которую вы хотите обрезать на вашей картинке. Предварительный просмотр вашей обрезанной картинки появится справа.  Когда вы будете удовлетворены картинкой в предварительном просмотре, нажмите <b>'Сохранить'</b>. Это обрезанное изображение будет использоваться как ваше фото во всех разделах сайта.",
	
			'profile:editdetails' => "Редактировать",
			'profile:editicon' => "Моё фото",
	
			'profile:aboutme' => "Обо мне", 
			'profile:description' => "Обо мне",
			'profile:briefdescription' => "Коротко о себе",
			'profile:location' => "Город, Страна",
			'profile:skills' => "Навыки",  
			'profile:interests' => "Интересы", 
			'profile:contactemail' => "Контактный email",
			'profile:phone' => "Домашний телефон",
			'profile:mobile' => "Мобильный телефон",
			'profile:website' => "Вебсайт",

			'profile:river:update' => "%s обновил свои личные данные",
			'profile:river:iconupdate' => "%s обновил своё фото",
	
		/**
		 * Profile status messages
		 */
	
			'profile:saved' => "Профайл успешно сохранён.",
			'profile:icon:uploaded' => "Ваше фото успешно сохранено.",
	
		/**
		 * Profile error messages
		 */
	
			'profile:noaccess' => "У вас нет прав на редактирование данных этого пользователя.",
			'profile:notfound' => "Извините, мы не можем найти данные указанного пользователя.",
			'profile:cantedit' => "Извините, у вас нет прав на редактирование данных этого пользователя.",
			'profile:icon:notfound' => "Извините, не удалось сохранить ваше фото.",
	
		/**
		 * Friends
		 */
	
			'friends' => "Друзья",
			'friends:yours' => "Ваши друзья",
			'friends:owned' => "Друзья пользователя %s",
			'friend:add' => "Добавить друга",
			'friend:remove' => "Убрать из друзей",
	
			'friends:add:successful' => "Вы успешно добавили %s в друзья.",
			'friends:add:failure' => "Невозможно добавить %s в друзья. Попробуйте ещё раз.",
	
			'friends:remove:successful' => "Выуспешно убрали %s из друзей.",
			'friends:remove:failure' => "Мы не смогли удалить пользователя %s из списка ваших друзей. Попробуйте ещё раз.",
	
			'friends:none' => "У этого пользователя пока нет друзей.",
			'friends:none:you' => "У вас пока нет друзей! Воспользуйтесь поиском что бы найти ваших старых друзей или найти новых друзей по интересам.",
	
			'friends:none:found' => "Не найдено ни одно друга.",
	
			'friends:of:none' => "Никто не добавил данного пользователя как своего друга.",
			'friends:of:none:you' => "Никто вас не добавил как своего друга. Заполните свои личные данные что бы вас было легче найти!",
	
			'friends:of' => "Friends of",
			'friends:of:owned' => "Люди, которые добавили %s в друзья",

			 'friends:num_display' => "Количество друзей на одной странице",
			 'friends:icon_size' => "Размер фото",
			 'friends:tiny' => "крошечный",
			 'friends:small' => "маленький",
			 'friends' => "Друзья",
			 'friends:of' => "Чей друг",
			 'friends:collections' => "Группы друзей",
			 'friends:collections:add' => "Создать группу друзей",
			 'friends:addfriends' => "Добавить друзей",
			 'friends:collectionname' => "Название группы",
			 'friends:collectionfriends' => "Друзья в группе",
			 'friends:collectionedit' => "Редактировать эту группу",
			 'friends:nocollections' => "У вас нет групп друзей.",
			 'friends:collectiondeleted' => "Группа была удалена.",
			 'friends:collectiondeletefailed' => "Мы не смогли удалить группу. Либо у вас нет прав, либо случилась какая-то другая ошибка.",
			 'friends:collectionadded' => "Ваша группа была успешно создана",
			 'friends:nocollectionname' => "Необходимо указать название группы что бы её создать.",
		
	        'friends:river:created' => "%s добавил себе виджет 'Друзья'.",
	        'friends:river:updated' => "%s обновил виджет 'Друзья'.",
	        'friends:river:delete' => "%s удалил виджет 'Друзья'.",
	        'friends:river:add' => "%s добавил себе друга.",
	
		/**
		 * Feeds
		 */
			'feed:rss' => 'Подписаться на ленту',
			'feed:odd' => 'Синхронизировать через OpenDD',
	
		/**
		 * River
		 */
			'river' => "River",			
			'river:relationship:friend' => 'теперь друзья с',

		/**
		 * Plugins
		 */
			'plugins:settings:save:ok' => "Настройки для приложения %s успешно сохранены.",
			'plugins:settings:save:fail' => "Настройки для приложения %s не сохранены.",
			'plugins:usersettings:save:ok' => "Пользовательские настройки для приложения %s успешно сохранены.",
			'plugins:usersettings:save:fail' => "Пользовательский настройки для приложения %s не сохранены.",
			
			'item:object:plugin' => 'Настройки приложения',
		/**
		 * Notifications
		 */
			'notifications:usersettings' => "Настройки оповещения",
			'notifications:methods' => "Пожалуйста укажите методы оповещания.",
	
			'notifications:usersettings:save:ok' => "Настройки оповещения были успешно сохранены.",
			'notifications:usersettings:save:fail' => "Не удалось сохранить изменения настроек оповещения.",
	
			'user.notification.get' => 'Вернуться с настройкам оповещения данного пользователя.',
			'user.notification.set' => 'Устанавливает настройки уведомлений для указанного пользователя.',
		/**
		 * Search
		 */
	
			'search' => "Поиск",
			'searchtitle' => "Поиск: %s",
			'users:searchtitle' => "Поиск пользователей: %s",
			'advancedsearchtitle' => "%s среди результатов, подходящих под %s",
			'notfound' => "Ваш запрос не дал результатов.",
			'next' => "Дальше",
			'previous' => "Назад",
	
			'viewtype:change' => "Изменить вид",
			'viewtype:list' => "Список",
			'viewtype:gallery' => "Галлерея",
	
			'tag:search:startblurb' => "Записи, отвечающие запросу '%s':",

			'user:search:startblurb' => "Пользователи, отвечающие запросу '%s':",
			'user:search:finishblurb' => "Нажмите сюда, что бы увидеть больше.",
	
		/**
		 * Account
		 */
	
			'account' => "Учётная запись",
			'settings' => "Настройки",
            'tools' => "Приложения",
            'tools:yours' => "Мои приложения",
	
			'register' => "Регистрация",
			'registerok' => "Вы успешно зарегистрировались в %s. Чтобы активировать аккаунт, пожалуйста подтвердите email адрес перейдя по ссылке, которую мы вам выслали.",
			'registerbad' => "Регистрация не возможна. Имя пользователя уже занято, либо пароли не совпадают, либо имя пользователя или пароль слишком короткие.",
			'registerdisabled' => "Регистрация новых пользователей отключена администратором.",
	
			'registration:notemail' => 'Адрес электронной почты, указанный вами, не корректен.',
			'registration:userexists' => 'Такой пользователь уже зарегистрирован в системе',
			'registration:usernametooshort' => 'Имя пользователя должно быть длиной не менее 4 символов.',
			'registration:passwordtooshort' => 'Пароль должен быть длиной не менее 6 символов.',
			'registration:dupeemail' => 'Этот адрес электронной почты уже используется в системе.',
			'registration:invalidchars' => 'Имя пользователя содержит недопустимые символы.',
			'registration:emailnotvalid' => 'Такой адрес электронной почты недопустим',
			'registration:passwordnotvalid' => 'Такой пароль недопустим',
			'registration:usernamenotvalid' => 'Такое имя пользователя не допустимо',
	
			'adduser' => "Добавить пользователя",
			'adduser:ok' => "Вы успешно добавили нового пользователя.",
			'adduser:bad' => "Новый пользователь не может быть создан.",
			
			'item:object:reported_content' => "Нарушения",
	
			'user:set:name' => "Имя пользователя",
			'user:name:label' => "Ваше имя",
			'user:name:success' => "Ваше имя успешно изменено.",
			'user:name:fail' => "Не удалось изменить ваше имя.",
	
			'user:set:password' => "Пароль",
			'user:password:label' => "Новый пароль",
			'user:password2:label' => "Повторите новый пароль",
			'user:password:success' => "Пароль изменён",
			'user:password:fail' => "Невозможно изменить пароль.",
			'user:password:fail:notsame' => "Пароли не совпадают!",
			'user:password:fail:tooshort' => "Пароль слишком короткий!",
	
			'user:set:language' => "Языковые настройки",
			'user:language:label' => "Ваш язык",
			'user:language:success' => "Ваши языковые настройки успешно сохранены.",
			'user:language:fail' => "Не удалось сохранить ваши языковые настройки.",
	
			'user:username:notfound' => 'Пользователь %s не найден.',
	
			'user:password:lost' => 'Забыл пароль',
			'user:password:resetreq:success' => 'Новый пароль успешно установлен, инструкции высланы по почте',
			'user:password:resetreq:fail' => 'Не возможно сбросить пароль.',
	
			'user:password:text' => 'Что бы сбросить пароль, введите ваш логин ниже. Мы вышлем вам уникальный адрес страницы, на который вам надо будет зайти, что бы получить новый пароль.',
	
		/**
		 * Administration
		 */

			'admin:configuration:success' => "Изменения были сохранены.",
			'admin:configuration:fail' => "Изменения не были сохранены.",
	
			'admin' => "Администрирование",
			'admin:description' => "Административная панель позволяет вам управлять всеми аспектами системы, от управления пользователями до поведения приложений.",
			
			'admin:user' => "Управление пользователями",
			'admin:user:description' => "Эта страница позволяет менять настройки вашего сайта.",
			'admin:user:adduser:label' => "Кликнуть тут что бы добавить нового пользователя...",
			'admin:user:opt:linktext' => "Настроить пользователей...",
			'admin:user:opt:description' => "Настроить пользователей и информацию об аккаунтах. ",
			
			'admin:site' => "Управление сайтом",
			'admin:site:description' => "Эта страница позволяет вам управлять глобальными настройками вашего сайта.",
			'admin:site:opt:linktext' => "Настроить сайт...",
			'admin:site:opt:description' => "Установить технические и нетехнические настройки. ",
			
			'admin:plugins' => "Управление приложениями",
			'admin:plugins:description' => "Эта страница позволяет вам управлять приложениями, установленными на вашем сайте.",
			'admin:plugins:opt:linktext' => "Настроить приложения...",
			'admin:plugins:opt:description' => "Настроить установленные на сайте приложения. ",
			'admin:plugins:label:author' => "Автор",
			'admin:plugins:label:copyright' => "Копирайт",
			'admin:plugins:label:licence' => "Лицензия",
			'admin:plugins:label:website' => "URL",
			'admin:plugins:disable:yes' => "Приложение %s было успешно выключено.",
			'admin:plugins:disable:no' => "Не удалось выключить приложение %s.",
			'admin:plugins:enable:yes' => "Приложение %s было успешно включено.",
			'admin:plugins:enable:no' => "Не удалось включить приложение %s.",
	
			'admin:statistics' => "Статистика",
			'admin:statistics:description' => "Это обзорная статистика вашего сайта. Если вам нужна более детальная статистика, вы можете воспользоваться профессиональными услугами.",
			'admin:statistics:opt:description' => "Просмотреть статистику о пользователях и объектах на вашем сайте.",
			'admin:statistics:opt:linktext' => "Посмотреть статистику...",
			'admin:statistics:label:basic' => "Статистика сайта",
			'admin:statistics:label:numentities' => "Объекты",
			'admin:statistics:label:numusers' => "Количество ползователей",
			'admin:statistics:label:numonline' => "Количество пользователей в сети",
			'admin:statistics:label:onlineusers' => "Пользователей в сети",
			'admin:statistics:label:version' => "Версия Elgg",
			'admin:statistics:label:version:release' => "Релиз",
			'admin:statistics:label:version:version' => "Версия",
	
			'admin:user:label:search' => "Найти пользователей:",
			'admin:user:label:seachbutton' => "Поиск", 
	
			'admin:user:ban:no' => "Невозможно забанить пользователя",
			'admin:user:ban:yes' => "Пользователь забанен.",
			'admin:user:unban:no' => "Невозможно разбанить пользователя",
			'admin:user:unban:yes' => "Пользователь разбанен.",
			'admin:user:delete:no' => "Невозможно удалить пользователя",
			'admin:user:delete:yes' => "Пользователь удалён",
	
			'admin:user:resetpassword:yes' => "Пароль сброшен, пользователь уведомлён.",
			'admin:user:resetpassword:no' => "Не удалось сбросить пароль.",
	
			'admin:user:makeadmin:yes' => "Пользователю даны права администратора.",
			'admin:user:makeadmin:no' => "Не удалось дать пользователю права администратора.",
			
		/**
		 * User settings
		 */
			'usersettings:description' => "Страница управления пользовательскими настройками позволяет вам контролировать все ваши персональные настройки, от управления пользователями до поведения приложений.",
	
			'usersettings:statistics' => "Ваша статистика",
			'usersettings:statistics:opt:description' => "Просмотр статистической информации о пользователях и объетах вашего сайта.",
			'usersettings:statistics:opt:linktext' => "Моя статистика",
	
			'usersettings:user' => "Мои настройки",
			'usersettings:user:opt:description' => "Это позволяет менять пользовательские настройки.",
			'usersettings:user:opt:linktext' => "Мои настройки",
	
			'usersettings:plugins' => "Приложения",
			'usersettings:plugins:opt:description' => "Настроить активные приложения.",
			'usersettings:plugins:opt:linktext' => "Настроить активные приложения...",
	
			'usersettings:plugins:description' => "Эта страница позволяет вам контролировать и настраивать установленные в системе приложения.",
			'usersettings:statistics:label:numentities' => "Мои объекты",
	
			'usersettings:statistics:yourdetails' => "Мои детали",
			'usersettings:statistics:label:name' => "Полное имя",
			'usersettings:statistics:label:email' => "Адрес эл. почты",
			'usersettings:statistics:label:membersince' => "Дата создания",
			'usersettings:statistics:label:lastlogin' => "Последний раз в сети",
	
			
	
		/**
		 * Generic action words
		 */
	
			'save' => "Сохранить",
			'cancel' => "Отменить",
			'saving' => "Сохранение ...",
			'update' => "Обновить",
			'edit' => "Редактировать",
			'delete' => "Удалить",
			'load' => "Загрузить",
			'upload' => "Закачать",
			'ban' => "Забанить",
			'unban' => "Разбанить",
			'enable' => "Включить",
			'disable' => "Выключить",
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
	
			'learnmore' => "Нажмите здесь, что бы узнать больше.",
	
			'content' => "содержимое",
			'content:latest' => 'Последние события',
			'content:latest:blurb' => 'Нажмите сюда, что бы посмотреть последние посты со всего сайта.',
			
			'link:text' => 'дальше &raquo;',
	
		/**
		 * Generic data words
		 */
	
			'title' => "Название",
			'description' => "Описание",
			'tags' => "Тэги",
			'spotlight' => "Spotlight",
			'all' => "Все",
	
			'by' => 'от',
	
			'annotations' => "Аннотации",
			'relationships' => "Связи",
			'metadata' => "Метаданные",
	
		/**
		 * Input / output strings
		 */

			'deleteconfirm' => "Вы уверены, что хотите удалит этот объект?",
			'fileexists' => "Файл уже отправлен на сервер. Что бы заменить его, выберите его ниже:",
			
		/**
         * System messages
         **/
            'systemmessages:dismiss' => "закрыть",

	
		/**
		 * Import / export
		 */
			'importsuccess' => "Импорт данных завершился успешно",
			'importfail' => "OpenDD импорт данных .",
	
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
	
			'installation:error:configuration' => "После исправления всех проблем конфигурации, обновите страницу что бы попытаться ещё раз.",
	
			'installation' => "Инсталляция",
			'installation:success' => "База данных Elgg успешно установлена.",
			'installation:configuration:success' => "Ваши настройки сохранены. Теперь зарегистрируйте первого пользователя, который будет являться администратором.",
	
			'installation:settings' => "Системные настройки",
			'installation:settings:description' => "Теперь, когда база данных была успешно установлена, необходимо настроить остальные аспекты сайта. Мы попробовали автоматически определить настройки, но <b>вам следует их перепроверить.</b>",
	
			'installation:settings:dbwizard:prompt' => "Настройте доступ к базе данных и нажмите 'Сохранить':",
			'installation:settings:dbwizard:label:user' => "Пользователь",
			'installation:settings:dbwizard:label:pass' => "Пароль",
			'installation:settings:dbwizard:label:dbname' => "База данных",
			'installation:settings:dbwizard:label:host' => "Хост (обычно 'localhost')",
			'installation:settings:dbwizard:label:prefix' => "Префикс таблиц (обычно 'elgg')",
	
			'installation:settings:dbwizard:savefail' => "Не удалось сохранить settings.php. Пожалуйста, сохраните следующую информацию в файл engine/settings.php используя текстовый редактор.",
	
			'installation:sitename' => "Название вашего сайта (например \"Моя социальная сеть\"):",
			'installation:sitedescription' => "Краткое описание вашего сайта (не обязательно)",
			'installation:wwwroot' => "URL вашего сайта, заканчивающийся на /:",
			'installation:path' => "Полный путь к корневой директории сайта на сервере, заканчивающийся на /:",
			'installation:dataroot' => "Полный путь к директории, в которой будут храниться закачанные на сайт файлы, заканчивающийся на /:",
			'installation:dataroot:warning' => "Вам придётся создать её самостоятельно. Она должна находится вне директории в которую установлен Elgg.",
			'installation:language' => "Язык сайта по умолчанию:",
			'installation:debug' => "Режим отладки предоставляет дополнительную инофрмацию для диагностики ошибок, но это может замедлить системы, поэтому режим должен быть использован только для отладки:",
			'installation:debug:label' => "Включить режим отладки",
			'installation:usage' => "Это позволит Elgg посылать анонимную статистику использования разработчикам Elgg.",
			'installation:usage:label' => "Отсылать анонимную статистику использования",
			'installation:view' => "Укажите, какой view должен быть использован по умолчанию для сайта, или оставьте пустым, что бы использовался default:",

			'installation:disableapi' => "Elgg предоставляет гибкое и расширяемое API, которое позволяет приложениям использовать некоторые возможности Elgg удалённо",
			'installation:disableapi:label' => "Включить RESTful API",
			
			'installation:siteemail' => "Обратный адрес электронной почты, который будет использоваться для посылки системных писем",
			
			'upgrade:db' => 'База данных была обновлена.',
			'upgrade:core' => 'Ваша копия Elgg была обновлена.',

	
		/**
		 * Welcome
		 */
	
			'welcome' => "Привет %s",
			'welcome_message' => "Рады привествовать вас в свежеустановленной системе.",
	
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

Что бы посмотреть его профайл, нажмите сюда:

	%s

Не надо отвечать на это письмо.",
	
	
			'email:validate:subject' => "%s, пожалуйста, подтвердите адрес!",
			'email:validate:body' => "Привет %s,

Пожалуйста, подтвердите ваш адрес электронной почты нажав на ссылку ниже:

%s
",
			'email:validate:success:subject' => "Адрес электронной почты проверен!",
			'email:validate:success:body' => "Привет, %s,
			
Поздравляем, ваш адрес электронной почты успешно проверен.",
	
	
			'email:resetpassword:subject' => "Пароль сброшен!",
			'email:resetpassword:body' => "Привет %s,
			
Ваш пароль был сброшен, новый пароль: %s",
	
	
			'email:resetreq:subject' => "Запрос нового пароля.",
			'email:resetreq:body' => "Привет %s,
			
Кто-то (IP адрес %s) запросил новый пароль для вашего аккаунта.

Если это были вы, нажмите на ссылку ниже, иначе просто проигнорируйте это сообщение.

%s
",

	
		/**
		 * XML-RPC
		 */
			'xmlrpc:noinputdata'	=>	"Отсутствуют входные данные",
	
		/**
		 * Comments
		 */
	
			'comments:count' => "%s комментарии",
			'generic_comments:add' => "Добавить комментарий",
			'generic_comments:text' => "Комментарий",
			'generic_comment:posted' => "Ваш комментарий успешно добавлен.",
			'generic_comment:deleted' => "Ваш комментарий успешно удалён.",
			'generic_comment:blank' => "Извините, нельзя добавить пустой комментарий.",
			'generic_comment:notfound' => "Извините, мы не можем найти указанный объект.",
			'generic_comment:notdeleted' => "Извините, мы не можем удалить этот комментарий.",
			'generic_comment:failure' => "Не удалоcь добавить ваш комментарий. Попробуйте ещё раз.",
	
			'generic_comment:email:subject' => 'Вы получили новый комментарий!',
			'generic_comment:email:body' => "Вы получили новый комментарий на ваше \"%s\" от %s:

			
%s


Что бы ответить или просто посмотреть оригинальный объект, перейдите по ссылке:

	%s

Что бы посмотреть профайл пользователя %s, перейдите по ссылке:

	%s

Не надо отвечать на это письмо.",
	
		/**
		 * Entities
		 */
			'entity:default:strapline' => 'Добавлено %s пользователем %s',
			'entity:default:missingsupport:popup' => 'Этот объект не может быть корректно отображён. Возможно, это случилось потому, что был удалёно приложение, которое умеет его отображать.',
	
			'entity:delete:success' => 'Объект %s успешно удалён',
			'entity:delete:fail' => 'Объект %s не может быть удалён',
	
	
		/**
		 * Action gatekeeper
		 */
			'actiongatekeeper:missingfields' => 'В форме не хватает полей __token или __ts',
			'actiongatekeeper:tokeninvalid' => 'Токен в  форме не соответствует токену, сгенерированному на сервере.',
			'actiongatekeeper:timeerror' => 'Форма потеряла актуальность, пожалуйста обновите страницу и попробуйте ещё раз.',
			'actiongatekeeper:pluginprevents' => 'Какое-то браузерное расширение не позволило отправить эту форму.',
		/**
		 * Extras
		 */
	        'more info' => 'больше &raquo;',
	
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
