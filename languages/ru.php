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
	'collection:site:site' => 'Сайты',
	'index:content' => '<p>Добро пожаловать на ваш сайт Elgg.</p><p><strong>Совет:</strong> Многие сайты используют плагин <code>activity</code> для размещения ленты активности на этой странице.</p>',

/**
 * Sessions
 */

	'login' => "Войти",
	'loginok' => "Вы вошли в систему.",
	'login:continue' => "Войдите, чтобы продолжить",
	'login:empty' => "Необходимо ввести имя пользователя/email и пароль.",
	'login:baduser' => "Не удалось загрузить вашу учетную запись.",

	'logout' => "Выйти",
	'logoutok' => "Вы вышли из системы.",
	'logouterror' => "Не удалось выйти. Попробуйте снова.",
	'session_expired' => "Ваша сессия истекла. Пожалуйста, <a href='javascript:location.reload(true)'>обновите</a> страницу, чтобы войти.",
	'session_changed_user' => "Вы вошли как другой пользователь. Вам следует <a href='javascript:location.reload(true)'>обновить</a> страницу.",

	'loggedinrequired' => "Вы должны войти в систему, чтобы просмотреть запрошенную страницу.",
	'loggedoutrequired' => "Вы должны выйти из системы, чтобы просмотреть запрошенную страницу.",
	'adminrequired' => "Вы должны быть администратором, чтобы просмотреть запрошенную страницу.",
	'membershiprequired' => "Вы должны быть участником этой группы, чтобы просмотреть запрошенную страницу.",
	'limited_access' => "У вас нет прав для просмотра запрошенной страницы.",
	'invalid_request_signature' => "URL-адрес страницы, к которой вы пытаетесь получить доступ, неверен или истек",

/**
 * Errors
 */

	'exception:title' => "Неустранимая ошибка.",
	'exception:contact_admin' => 'Произошла неустранимая ошибка, информация о ней записана в журнал. Свяжитесь с администратором сайта, предоставив следующую информацию:',

	'actionnotfound' => "Файл действия %s не найден.",
	'actionunauthorized' => 'У вас нет прав на выполнение этого действия',

	'ajax:error' => 'Неожиданная ошибка при выполнении AJAX-запроса. Возможно, потеряно соединение с сервером.',
	'ajax:not_is_xhr' => 'Вы не можете обращаться к AJAX-представлениям напрямую',
	'ajax:pagination:no_data' => 'Новые данные страницы не найдены',
	'ajax:pagination:load_more' => 'Загрузить еще',

	'ElggEntity:Error:SetSubtype' => 'Используйте %s вместо магического сеттера для "subtype"',
	'ElggEntity:Error:SetEnabled' => 'Используйте %s вместо магического сеттера для "enabled"',
	'ElggEntity:Error:SetDeleted' => 'Используйте %s вместо магического сеттера для "deleted"',
	'ElggUser:Error:SetAdmin' => 'Используйте %s вместо магического сеттера для "admin"',
	'ElggUser:Error:SetBanned' => 'Используйте %s вместо магического сеттера для "banned"',

	'PluginException:CannotStart' => '%s (guid: %s) не может быть запущен и был деактивирован. Причина: %s',
	'PluginException:InvalidID' => "%s является недопустимым идентификатором плагина.",
	'PluginException:PluginMustBeActive' => "Требуется активный плагин '%s'.",
	'PluginException:PluginMustBeAfter' => "Должен располагаться после плагина '%s'.",
	'PluginException:PluginMustBeBefore' => "Должен располагаться до плагина '%s'.",
	'ElggPlugin:MissingID' => 'Отсутствует ID плагина (guid %s)',
	'ElggPlugin:NoPluginComposer' => 'Отсутствует composer.json для плагина %s (guid %s)',
	'ElggPlugin:StartFound' => 'Для плагина %s найден start.php. Это может указывать на неподдерживаемую версию плагина.',
	'ElggPlugin:IdMismatch' => 'Директорию этого плагина необходимо переименовать в "%s", чтобы соответствовать projectname в composer.json плагина.',
	'ElggPlugin:Error' => 'Ошибка плагина',
	'ElggPlugin:Exception:CannotIncludeFile' => 'Не удалось подключить %s для плагина %s (guid: %s) в %s.',
	'ElggPlugin:Exception:IncludeFileThrew' => 'Вызвано исключение при подключении %s для плагина %s (guid: %s) в %s.',
	'ElggPlugin:Exception:CannotRegisterViews' => 'Не удалось открыть директорию views для плагина %s (guid: %s) в %s.',
	'ElggPlugin:InvalidAndDeactivated' => '%s является недопустимым плагином и был деактивирован.',
	'ElggPlugin:activate:BadConfigFormat' => 'Файл плагина "elgg-plugin.php" не вернул сериализуемый массив.',
	'ElggPlugin:activate:ConfigSentOutput' => 'Файл плагина "elgg-plugin.php" вывел данные.',

	'ElggPlugin:Dependencies:ActiveDependent' => 'Есть другие плагины, которые указывают %s как зависимость. Вы должны отключить следующие плагины перед отключением этого: %s',
	'ElggPlugin:Dependencies:MustBeActive' => 'Должен быть активен',
	'ElggPlugin:Dependencies:Position' => 'Позиция',

	'ElggMenuBuilder:Trees:NoParents' => 'Найдены пункты меню без родительских элементов для привязки',
	'ElggMenuBuilder:Trees:OrphanedChild' => 'Найден пункт меню [%s] с отсутствующим родителем [%s]',
	'ElggMenuBuilder:Trees:DuplicateChild' => 'Найдена дублирующаяся регистрация пункта меню [%s]',

	'RegistrationException:EmptyPassword' => 'Поля пароля не могут быть пустыми',
	'RegistrationException:PasswordMismatch' => 'Пароли должны совпадать',
	'LoginException:BannedUser' => 'Вы заблокированы на этом сайте и не можете войти',
	'LoginException:UsernameFailure' => 'Не удалось войти. Проверьте имя пользователя/email и пароль.',
	'LoginException:PasswordFailure' => 'Не удалось войти. Проверьте имя пользователя/email и пароль.',
	'LoginException:AccountLocked' => 'Ваша учетная запись заблокирована из-за слишком большого количества неудачных попыток входа.',
	'LoginException:ChangePasswordFailure' => 'Не удалось проверить текущий пароль.',
	'LoginException:Unknown' => 'Не удалось войти из-за неизвестной ошибки.',
	'LoginException:AdminValidationPending' => "Ваша учетная запись должна быть проверена администратором сайта, прежде чем вы сможете ее использовать. Вы получите уведомление после проверки.",
	'LoginException:DisabledUser' => "Ваша учетная запись отключена. Вход не разрешен.",

	'UserFetchFailureException' => 'Невозможно проверить права для user_guid [%s], так как пользователь не существует.',

	'PageNotFoundException' => 'Страница, которую вы пытаетесь просмотреть, не существует или у вас нет прав для ее просмотра',
	'EntityNotFoundException' => 'Запрашиваемый контент был удален или у вас нет прав для доступа к нему',
	'EntityPermissionsException' => 'У вас недостаточно прав для выполнения этого действия',
	'GatekeeperException' => 'У вас нет прав для просмотра страницы, к которой вы пытаетесь получить доступ',
	'RegistrationAllowedGatekeeperException:invalid_invitecode' => "Предоставленный код приглашения недействителен",
	'BadRequestException' => 'Неверный запрос',
	'BadRequestException:invalid_host_header' => 'Запрос содержит неверный заголовок HOST',
	'BadRequestException:livesearch:no_query' => 'Живой поиск требует поисковый запрос',
	'ValidationException' => 'Отправленные данные не соответствуют требованиям, проверьте введенные данные',
	'ValidationException:field:required' => 'Поле %s является обязательным, предоставлены пустые данные',
	'ValidationException:field:url' => 'Поле %s не соответствует требованиям URL, проверьте введенные данные',
	'LogicException:InterfaceNotImplemented' => '%s должен реализовывать %s',
	'ForbiddenException' => 'У вас недостаточно прав для просмотра этой страницы',
	'GoneException' => 'Запрашиваемый ресурс больше не доступен',
	'InternalServerErrorException' => 'При обработке вашего запроса произошла неизвестная ошибка',
	'MethodNotAllowedException' => 'Запрошенный метод не разрешен для этого ресурса',
	'NotImplementedException' => 'Запрошенный метод не реализован для этого ресурса',
	'ServiceUnavailableException' => 'Сервер не смог обработать ваш запрос, попробуйте позже',
	'TooManyRequestsException' => 'Слишком много запросов, пожалуйста, притормозите',
	'UnauthorizedException' => 'У вас нет действительных учетных данных для доступа к целевому ресурсу',
	
	'Security:InvalidPasswordCharacterRequirementsException' => "Предоставленный пароль не соответствует требованиям к символам",
	'Security:InvalidPasswordLengthException' => "Предоставленный пароль не соответствует минимальной длине в %s символов",
	
	'Entity:Subscriptions:InvalidMethodsException' => '%s требует, чтобы $methods был строкой или массивом строк',

	'changebookmark' => 'Пожалуйста, обновите закладку для этой страницы',
	'error:missing_data' => 'В вашем запросе отсутствуют некоторые данные',
	'save:fail' => 'Ошибка сохранения данных',
	'save:success' => 'Ваши данные сохранены',

	'error:default:title' => 'Упс...',
	'error:default:content' => 'Упс... что-то пошло не так.',
	'error:400:title' => 'Неверный запрос',
	'error:400:content' => 'Извините. Запрос неверен или неполон.',
	'error:401:title' => 'Не авторизован',
	'error:403:title' => 'Доступ запрещен',
	'error:403:content' => 'Извините. Вам не разрешен доступ к запрошенной странице.',
	'error:404:title' => 'Страница не найдена',
	'error:404:content' => 'Извините. Мы не смогли найти запрошенную вами страницу.',
	'error:407:title' => 'Требуется аутентификация прокси',
	'error:500:title' => 'Внутренняя ошибка сервера',
	'error:503:title' => 'Сервис недоступен',

	'upload:error:ini_size' => 'Файл, который вы пытались загрузить, слишком большой.',
	'upload:error:form_size' => 'Файл, который вы пытались загрузить, слишком большой.',
	'upload:error:partial' => 'Загрузка файла не завершена.',
	'upload:error:no_file' => 'Файл не выбран.',
	'upload:error:no_tmp_dir' => 'Невозможно сохранить загруженный файл.',
	'upload:error:cant_write' => 'Невозможно сохранить загруженный файл.',
	'upload:error:extension' => 'Невозможно сохранить загруженный файл.',
	'upload:error:unknown' => 'Загрузка файла завершилась с ошибкой.',

/**
 * Table columns
 */
	'table_columns:fromView:admin' => 'Админ',
	'table_columns:fromView:banned' => 'Заблокирован',
	'table_columns:fromView:checkbox' => 'Выбрать',
	'table_columns:fromView:container' => 'Контейнер',
	'table_columns:fromView:entity_menu' => 'Меню',
	'table_columns:fromView:excerpt' => 'Описание',
	'table_columns:fromView:link' => 'Имя/Заголовок',
	'table_columns:fromView:icon' => 'Иконка',
	'table_columns:fromView:item' => 'Элемент',
	'table_columns:fromView:language' => 'Язык',
	'table_columns:fromView:last_action' => 'Последнее действие',
	'table_columns:fromView:last_login' => 'Последний вход',
	'table_columns:fromView:owner' => 'Владелец',
	'table_columns:fromView:prev_last_login' => 'Предыдущий последний вход',
	'table_columns:fromView:time_created' => 'Время создания',
	'table_columns:fromView:time_updated' => 'Время обновления',
	'table_columns:fromView:unvalidated_menu' => 'Меню',
	'table_columns:fromView:user' => 'Пользователь',

	'table_columns:fromProperty:description' => 'Описание',
	'table_columns:fromProperty:email' => 'Email',
	'table_columns:fromProperty:name' => 'Имя',
	'table_columns:fromProperty:type' => 'Тип',
	'table_columns:fromProperty:username' => 'Имя пользователя',
	'table_columns:fromProperty:validated' => 'Проверено',

	'table_columns:fromMethod:getSubtype' => 'Подтип',
	'table_columns:fromMethod:getDisplayName' => 'Имя/Заголовок',
	'table_columns:fromMethod:getMimeType' => 'MIME-тип',
	'table_columns:fromMethod:getSimpleType' => 'Тип',

/**
 * User details
 */

	'name' => "Отображаемое имя",
	'email' => "Адрес электронной почты",
	'username' => "Имя пользователя",
	'loginusername' => "Имя пользователя или email",
	'password' => "Пароль",
	'passwordagain' => "Пароль (повторно для проверки)",
	'admin_option' => "Сделать этого пользователя администратором?",
	'autogen_password_option' => "Автоматически сгенерировать безопасный пароль?",

/**
 * Access
 */

	'access:label:private' => "Личное",
	'access:label:logged_in' => "Авторизованные пользователи",
	'access:label:public' => "Публичное",
	'access:label:logged_out' => "Неавторизованные пользователи",
	'access:label:friends' => "Друзья",
	'access' => "Кто может видеть это",
	'access:limited:label' => "Ограничено",
	'access:help' => "Уровень доступа",
	'access:read' => "Доступ на чтение",
	'access:write' => "Доступ на запись",
	'access:admin_only' => "Только администраторы",
	
/**
 * Dashboard and widgets
 */

	'dashboard' => "Панель управления",
	'dashboard:nowidgets' => "Ваша панель управления позволяет отслеживать активность и контент на этом сайте, который важен для вас.",

	'widgets:add' => 'Добавить виджеты',
	'widgets:add:description' => "Нажмите на кнопку любого виджета ниже, чтобы добавить его на страницу.",
	'widget:unavailable' => 'Вы уже добавили этот виджет',
	'widget:numbertodisplay' => 'Количество отображаемых элементов',

	'widget:delete' => 'Удалить %s',
	'widget:edit' => 'Настроить этот виджет',

	'item:object:widget' => "Виджет",
	'collection:object:widget' => 'Виджеты',
	'widgets:add:success' => "Виджет успешно добавлен.",
	'widgets:add:failure' => "Не удалось добавить ваш виджет.",
	'widgets:move:failure' => "Не удалось сохранить новую позицию виджета.",
	'widgets:remove:failure' => "Не удалось удалить этот виджет",
	'widgets:not_configured' => "Этот виджет еще не настроен",
	
/**
 * Groups
 */

	'group' => "Группа",
	'item:group' => "Группа",
	'collection:group' => 'Группы',
	'item:group:group' => "Группа",
	'collection:group:group' => 'Группы',
	'groups:tool_gatekeeper' => "Запрошенная функциональность в настоящее время отключена в этой группе",

/**
 * Users
 */

	'user' => "Пользователь",
	'item:user' => "Пользователь",
	'collection:user' => 'Пользователи',
	'item:user:user' => 'Пользователь',
	'collection:user:user' => 'Пользователи',
	'notification:user:user:make_admin' => "Отправлять уведомление, когда пользователь получает права администратора",
	'notification:user:user:remove_admin' => "Отправлять уведомление, когда права администратора пользователя отзываются",
	'notification:user:user:unban' => "Отправлять уведомление, когда пользователь разблокирован",

	'friends' => "Друзья",
	'collection:friends' => 'Друзья %s',

	'avatar' => 'Аватар',
	'avatar:edit' => 'Редактировать аватар',
	'avatar:upload:instructions' => "Ваш аватар отображается по всему сайту. Вы можете менять его так часто, как захотите. (Принимаемые форматы файлов: GIF, JPG или PNG)",
	'avatar:upload:success' => 'Аватар успешно загружен',
	'avatar:upload:fail' => 'Ошибка загрузки аватара',
	'avatar:resize:fail' => 'Ошибка изменения размера аватара',
	'avatar:remove:success' => 'Аватар успешно удален',
	'avatar:remove:fail' => 'Ошибка удаления аватара',
	
	'header:remove:success' => 'Шапка успешно удалена',
	'header:remove:fail' => 'Ошибка удаления шапки',
	'header:upload:success' => 'Шапка успешно загружена',
	'header:upload:fail' => 'Ошибка загрузки шапки',
	
	'action:user:validate:already' => "%s уже проверен",
	'action:user:validate:success' => "%s успешно проверен",
	'action:user:validate:error' => "Произошла ошибка при проверке %s",
	
	'action:user:login_as' => "Войти как",
	'action:user:logout_as' => "Вернуться к %s",
	
	'action:user:login_as:success' => "Вы вошли как %s",
	'action:user:login_as:unknown' => "Неизвестный пользователь. Не удалось войти.",
	'action:user:login_as:error' => "Не удалось войти как %s",
	
	'action:admin:user:bulk:ban' => "Успешно заблокировано %s пользователей",
	'action:admin:user:bulk:unban' => "Успешно разблокировано %s пользователей",

/**
 * Feeds
 */
	'feed:rss' => 'RSS',
	'feed:rss:title' => 'RSS-лента для этой страницы',
/**
 * Links
 */
	'link:view' => 'посмотреть ссылку',
	'link:view:all' => 'Показать все',
	'link:skip_to_main' => 'Перейти к основному содержимому',

/**
 * River
 */
	'river' => "Лента активности",
	'river:user:friend' => "%s теперь в друзьях с %s",
	'river:site:site:join' => "%s присоединился к сайту",
	'river:update:user:avatar' => '%s обновил аватар',
	'river:posted:generic' => '%s опубликовал',
	'river:ingroup' => 'в группе %s',
	'river:none' => 'Нет активности',
	'river:update' => 'Обновление для %s',
	'river:delete' => 'Удалить элемент активности',
	'river:delete:success' => 'Элемент активности удален',
	'river:delete:fail' => 'Не удалось удалить элемент активности',
	'river:delete:lack_permission' => 'У вас недостаточно прав для удаления этого элемента активности',
	'river:subject:invalid_subject' => 'Недействительный пользователь',
	'activity:owner' => 'Активность',

/**
 * Relationships
 */
	
	'relationship:default' => "%s связан с %s",

/**
 * Notifications
 */
	'notification:method:email' => 'Email',
	'notification:method:email:from' => '%s (через %s)',
	'notification:method:delayed_email' => 'Задержка email',
	
	'usersettings:notifications:title' => "Настройки уведомлений",
	'usersettings:notifications:users:title' => 'Уведомления от пользователей',
	'usersettings:notifications:users:description' => 'Чтобы получать уведомления от друзей (индивидуально) при создании ими нового контента, найдите их ниже и выберите метод уведомлений.',
	
	'usersettings:notifications:menu:page' => "Настройки уведомлений",
	'usersettings:notifications:menu:filter:settings' => "Настройки",
	
	'usersettings:notifications:default:description' => 'Настройки уведомлений по умолчанию для системных событий',
	'usersettings:notifications:content_create:description' => 'Настройки уведомлений по умолчанию для созданного вами контента. Могут приходить уведомления, когда другие взаимодействуют с вашим контентом, например оставляют комментарий',
	'usersettings:notifications:create_comment:description' => "Настройка уведомлений по умолчанию при комментировании контента для отслеживания дальнейшей беседы",
	'usersettings:notifications:mentions:description' => "Получать уведомление, когда вас упоминают через @",
	'usersettings:notifications:admin_validation_notification:description' => "Получать уведомление, когда новый зарегистрированный пользователь ожидает проверки",

	'usersettings:notifications:timed_muting' => "Временно отключить уведомления",
	'usersettings:notifications:timed_muting:help' => "Если вы не хотите получать уведомления в определенный период (например, в отпуске), вы можете установить дату начала и окончания для временного отключения всех уведомлений",
	'usersettings:notifications:timed_muting:start' => "Первый день",
	'usersettings:notifications:timed_muting:end' => "Последний день",
	'usersettings:notifications:timed_muting:warning' => "В настоящее время ваши уведомления временно отключены",
	
	'usersettings:notifications:save:ok' => "Настройки уведомлений успешно сохранены.",
	'usersettings:notifications:save:fail' => "Возникла проблема при сохранении настроек уведомлений.",
	
	'usersettings:notifications:subscriptions:save:ok' => "Подписки на уведомления успешно сохранены.",
	'usersettings:notifications:subscriptions:save:fail' => "Возникла проблема при сохранении подписок на уведомления.",

	'notification:default:salutation' => 'Уважаемый %s,',
	'notification:default:sign-off' => 'С уважением,
%s',
	'notification:subject' => 'Уведомление о %s',
	'notification:body' => 'Посмотреть новую активность на %s',
	
	'notification:mentions:subject' => '%s упомянул вас',
	'notification:mentions:body' => "%s упомянул вас в '%s'.
Чтобы увидеть полную запись, перейдите по ссылке ниже:
%s",
	
	'notifications:delayed_email:subject:daily' => "Ежедневные уведомления",
	'notifications:delayed_email:subject:weekly' => "Еженедельные уведомления",
	'notifications:delayed_email:body:intro' => "Ниже приведен список ваших накопленных уведомлений.",
	
	'notifications:subscriptions:record:settings' => 'Показать подробный выбор',
	'notifications:subscriptions:no_results' => 'Записей о подписках пока нет',
	'notifications:subscriptions:details:no_results' => 'Нет подробных подписок для настройки.',
	'notifications:subscriptions:details:reset' => 'Отменить выбор',

	'notifications:mute:title' => "Отключить уведомления",
	'notifications:mute:description' => "Если вы больше не хотите получать уведомления, подобные полученному, настройте одну или несколько следующих причин для блокировки всех уведомлений:",
	'notifications:mute:error:content' => "Не удалось определить настройки уведомлений",
	'notifications:mute:entity' => "о '%s'",
	'notifications:mute:container' => "из '%s'",
	'notifications:mute:owner' => "от '%s'",
	'notifications:mute:actor' => "начато '%s'",
	'notifications:mute:group' => "написано в группе '%s'",
	'notifications:mute:user' => "написано пользователем '%s'",
	
	'notifications:mute:save:success' => "Настройки уведомлений сохранены",
	
	'notifications:mute:email:footer' => "Отключить эти письма",

/**
 * Search
 */

	'search' => "Поиск",
	'notfound' => "Ничего не найдено.",

	'viewtype:change' => "Изменить тип списка",
	'viewtype:list' => "Список",
	'viewtype:gallery' => "Галерея",
	'search:go' => 'Найти',
	'userpicker:only_friends' => 'Только друзья',

/**
 * Account
 */

	'account' => "Аккаунт",
	'settings' => "Настройки",
	'tools' => "Инструменты",
	'settings:edit' => 'Редактировать настройки',

	'register' => "Регистрация",
	'registerok' => "Вы успешно зарегистрировались на %s.",
	'registerbad' => "Регистрация не удалась из-за неизвестной ошибки.",
	'registerdisabled' => "Регистрация отключена администратором сайта",
	'register:fields' => 'Все поля обязательны для заполнения',

	'registration:noname' => 'Отображаемое имя обязательно.',
	'registration:notemail' => 'Предоставленный адрес электронной почты не является действительным.',
	'registration:userexists' => 'Это имя пользователя уже занято',
	'registration:usernametooshort' => 'Имя пользователя должно содержать минимум %u символов.',
	'registration:usernametoolong' => 'Имя пользователя слишком длинное. Максимально допустимо %u символов.',
	'registration:dupeemail' => 'Этот адрес электронной почты уже зарегистрирован.',
	'registration:invalidchars' => 'Извините, ваше имя пользователя содержит недопустимый символ %s. Запрещены следующие символы: %s',
	'registration:invalidchars:route' => 'Извините, ваше имя пользователя содержит недопустимый символ %s.',
	'registration:emailnotvalid' => 'Извините, введенный адрес электронной почты недействителен в этой системе',
	'registration:passwordnotvalid' => 'Извините, введенный пароль недействителен в этой системе',
	'registration:usernamenotvalid' => 'Извините, введенное имя пользователя недействительно в этой системе',

	'adduser:ok' => "Вы успешно добавили нового пользователя.",
	
	'user:name:label' => "Отображаемое имя",
	'user:name:success' => "Отображаемое имя успешно изменено.",
	'user:name:fail' => "Не удалось изменить отображаемое имя.",
	'user:username:success' => "Имя пользователя успешно изменено.",
	'user:username:fail' => "Не удалось изменить имя пользователя.",

	'user:set:password' => "Пароль аккаунта",
	'user:current_password:label' => 'Текущий пароль',
	'user:password:label' => "Новый пароль",
	'user:password2:label' => "Повторите новый пароль",
	'user:password:success' => "Пароль изменен",
	'user:changepassword:unknown_user' => 'Недействительный пользователь.',
	'user:changepassword:change_password_confirm' => 'Это изменит ваш пароль.',

	'user:delete:title' => 'Подтверждение удаления аккаунта',
	'user:delete:description' => 'Подтвердите, что хотите удалить аккаунт %s. Удаление аккаунта также удалит весь контент (включая группы), принадлежащий этому пользователю. Это также может включать связанный контент, такой как содержимое групп, подстраницы или комментарии. Ниже вы можете просмотреть список контента, принадлежащего пользователю.',
	'user:delete:confirm' => "Я подтверждаю, что хочу удалить этого пользователя",
	
	'user:color_scheme:label' => "Цветовая схема",
	'user:color_scheme:browser' => "Предпочтение браузера",
	'user:color_scheme:success' => "Цветовая схема обновлена.",
	
	'user:language:label' => "Язык",
	'user:language:success' => "Настройки языка обновлены.",

	'user:username:notfound' => 'Имя пользователя %s не найдено.',
	'user:username:help' => 'Обратите внимание, что изменение имени пользователя изменит все динамические ссылки, связанные с пользователем',

	'user:password:lost' => 'Забыли пароль',
	'user:password:hash_missing' => 'К сожалению, мы просим вас сбросить пароль. Мы улучшили безопасность паролей на сайте, но не смогли перенести все аккаунты в процессе.',
	'user:password:changereq:success' => 'Запрос на новый пароль успешно отправлен, письмо отправлено',

	'user:password:text' => 'Чтобы запросить новый пароль, введите ваше имя пользователя или адрес электронной почты ниже и нажмите кнопку "Запросить".',

	'user:persistent' => 'Запомнить меня',

	'walled_garden:home' => 'Домашняя страница',

/**
 * Password requirements
 */
	'password:requirements:min_length' => "Пароль должен содержать не менее %s символов.",
	'password:requirements:lower' => "Пароль должен содержать не менее %s строчных букв.",
	'password:requirements:no_lower' => "Пароль не должен содержать строчных букв.",
	'password:requirements:upper' => "Пароль должен содержать не менее %s заглавных букв.",
	'password:requirements:no_upper' => "Пароль не должен содержать заглавных букв.",
	'password:requirements:number' => "Пароль должен содержать не менее %s цифр.",
	'password:requirements:no_number' => "Пароль не должен содержать цифр.",
	'password:requirements:special' => "Пароль должен содержать не менее %s специальных символов.",
	'password:requirements:no_special' => "Пароль не должен содержать специальных символов.",
	
/**
 * Administration
 */
	'menu:page:header:administer' => 'Управление',
	'menu:page:header:configure' => 'Настройка',
	'menu:page:header:utilities' => 'Утилиты',
	'menu:page:header:develop' => 'Разработка',
	'menu:page:header:information' => 'Информация',
	'menu:page:header:default' => 'Другое',
	'menu:page:header:plugin_settings' => 'Настройки плагинов',

	'admin:view_site' => 'Просмотр сайта',
	'admin:loggedin' => 'Вошли как %s',
	'admin:menu' => 'Меню',

	'admin:configuration:success' => "Настройки сохранены.",
	'admin:configuration:fail' => "Не удалось сохранить настройки.",
	'admin:configuration:dataroot:relative_path' => 'Невозможно установить "%s" в качестве dataroot, так как это не абсолютный путь.',

	'admin:unknown_section' => 'Недопустимый раздел админки.',

	'admin' => "Администрирование",
	'admin:header:release' => "Версия Elgg: %s",
	'admin:description' => "Панель администратора позволяет управлять всеми аспектами системы, от управления пользователями до поведения плагинов. Выберите нужный раздел ниже.",

	'admin:performance' => 'Производительность',
	'admin:performance:label:generic' => 'Общее',
	'admin:performance:generic:description' => 'Ниже приведен список рекомендаций/значений производительности, которые могут помочь в оптимизации вашего сайта',
	'admin:performance:simplecache' => 'Простой кэш',
	'admin:performance:simplecache:settings:warning' => "Рекомендуется настроить параметр simplecache в settings.php.
Настройка simplecache в settings.php улучшает производительность кэширования.
Это позволяет Elgg пропускать подключение к базе данных при отдаче кэшированных файлов JavaScript и CSS",
	'admin:performance:systemcache' => 'Системный кэш',
	'admin:performance:apache:mod_cache' => 'Apache mod_cache',
	'admin:performance:apache:mod_cache:warning' => 'Модуль mod_cache предоставляет схемы кэширования с учетом HTTP. Это означает, что файлы будут кэшироваться в соответствии с инструкцией, указывающей, как долго страница может считаться "свежей".',
	'admin:performance:php:open_basedir' => 'PHP open_basedir',
	'admin:performance:php:open_basedir:not_configured' => 'Ограничения не установлены',
	'admin:performance:php:open_basedir:warning' => 'Действует небольшое количество ограничений open_basedir, это может повлиять на производительность.',
	'admin:performance:php:open_basedir:error' => 'Действует большое количество ограничений open_basedir, это, вероятно, повлияет на производительность.',
	'admin:performance:php:open_basedir:generic' => 'С open_basedir каждый доступ к файлу проверяется по списку ограничений.
Поскольку Elgg активно работает с файлами, это негативно скажется на производительности. Кроме того, PHP opcache больше не может кэшировать пути к файлам в памяти и должен разрешать их при каждом доступе.',
	
	'admin:statistics' => 'Статистика',
	'admin:server' => 'Сервер',
	'admin:cron' => 'Cron',
	'admin:cron:record' => 'Последние задачи Cron',
	'admin:cron:period' => 'Интервал Cron',
	'admin:cron:friendly' => 'Последнее выполнение',
	'admin:cron:date' => 'Дата и время',
	'admin:cron:msg' => 'Сообщение',
	'admin:cron:started' => 'Задачи Cron для "%s" запущены в %s',
	'admin:cron:started:actual' => 'Интервал Cron "%s" начал обработку в %s',
	'admin:cron:complete' => 'Задачи Cron для "%s" завершены в %s',

	'admin:appearance' => 'Внешний вид',
	'admin:administer_utilities' => 'Утилиты',
	'admin:develop_utilities' => 'Утилиты',
	'admin:configure_utilities' => 'Утилиты',
	'admin:configure_utilities:robots' => 'Robots.txt',

	'admin:users' => "Пользователи",
	'admin:users:online' => 'Сейчас онлайн',
	'admin:users:newest' => 'Новые',
	'admin:users:admins' => 'Администраторы',
	'admin:users:banned' => 'Заблокированные',
	'admin:users:searchuser' => 'Поиск пользователя для назначения админом',
	'admin:users:existingadmins' => 'Список текущих администраторов',
	'admin:users:add' => 'Добавить пользователя',
	'admin:users:description' => "Эта панель позволяет управлять настройками пользователей на сайте. Выберите нужный раздел ниже.",
	'admin:users:adduser:label' => "Нажмите здесь, чтобы добавить нового пользователя...",
	'admin:users:opt:linktext' => "Настроить пользователей...",
	'admin:users:opt:description' => "Настройка пользователей и данных аккаунтов. ",
	'admin:users:find' => 'Найти',
	'admin:users:unvalidated' => 'Не проверенные',
	'admin:users:unvalidated:no_results' => 'Нет пользователей, ожидающих проверки.',
	'admin:users:unvalidated:registered' => 'Зарегистрирован: %s',
	'admin:users:unvalidated:change_email' => 'Изменить email',
	'admin:users:unvalidated:change_email:user' => 'Изменить email для: %s',
	'admin:users:inactive' => 'Неактивные',
	'admin:users:inactive:last_login_before' => "Показать пользователей, не входивших после",
	'admin:users:inactive:last_login_before:help' => "Это также покажет пользователей, которые никогда не входили.",
	'admin:users:details:attributes' => 'Атрибуты пользователя',
	'admin:users:details:profile' => 'Информация профиля',
	'admin:users:details:profile:no_fields' => 'Поля профиля не настроены',
	'admin:users:details:profile:no_information' => 'Информация профиля отсутствует',
	'admin:users:details:statistics' => 'Статистика контента',
	
	'admin:configure_utilities:maintenance' => 'Режим обслуживания',
	'admin:upgrades' => 'Обновления',
	'admin:upgrades:finished' => 'Завершены',
	'admin:upgrades:db' => 'Обновления БД',
	'admin:upgrades:db:name' => 'Название обновления',
	'admin:upgrades:db:start_time' => 'Время начала',
	'admin:upgrades:db:end_time' => 'Время окончания',
	'admin:upgrades:db:duration' => 'Длительность',
	'admin:upgrades:menu:pending' => 'Ожидающие обновления',
	'admin:upgrades:menu:completed' => 'Завершенные обновления',
	'admin:upgrades:menu:db' => 'Обновления БД',
	'admin:upgrades:menu:run_single' => 'Запустить это обновление',
	'admin:upgrades:run' => 'Запустить обновления сейчас',
	'admin:upgrades:error:invalid_upgrade' => 'Сущность %s не существует или не является допустимым экземпляром ElggUpgrade',
	'admin:upgrades:error:invalid_batch' => 'Не удалось создать пакетный обработчик для обновления %s (%s)',
	'admin:upgrades:completed' => 'Обновление "%s" завершено в %s',
	'admin:upgrades:completed:errors' => 'Обновление "%s" завершено в %s, но возникло %s ошибок',
	'admin:upgrades:failed' => 'Обновление "%s" завершилось ошибкой',
	'admin:action:upgrade:reset:success' => 'Обновление "%s" сброшено',

	'admin:settings' => 'Настройки',
	'admin:settings:basic' => 'Базовые настройки',
	'admin:settings:i18n' => 'Интернационализация',
	'admin:settings:advanced' => 'Расширенные настройки',
	'admin:settings:users' => 'Пользователи',
	'admin:site_icons' => "Иконки сайта",
	'admin:site_icons:site_icon' => "Иконка сайта",
	'admin:site_icons:info' => "Загрузите иконку, связанную с вашим сайтом. Она будет использоваться как favicon и при отображении сайта, например, как отправитель в уведомлениях.",
	'admin:site_icons:font_awesome' => "Font Awesome",
	'admin:site_icons:font_awesome:zip' => "Загрузить ZIP-архив",
	'admin:site_icons:font_awesome:zip:help' => "Здесь вы можете загрузить архив Font Awesome с https://fontawesome.com/download. Этот веб-шрифт будет обслуживаться локально.",
	'admin:site_icons:font_awesome:zip:error' => "Не удалось распаковать загруженный ZIP",
	'admin:site_icons:font_awesome:remove_zip' => "Удалить загруженный шрифт",
	'admin:theme' => "Тема",
	'admin:theme:info' => "В этой форме можно настроить различные переменные темы. Эти настройки переопределят существующие.",
	'admin:theme:warning' => "Учтите, что эти изменения могут потенциально сломать верстку.",
	'admin:theme:css_variable:name' => "CSS-переменная",
	'admin:theme:css_variable:value' => "Значение",
	'admin:site_settings' => "Настройки сайта",
	'admin:site:description' => "Эта панель позволяет управлять глобальными настройками сайта. Выберите нужный раздел ниже.",
	'admin:site:opt:linktext' => "Настроить сайт...",
	'admin:settings:in_settings_file' => 'Этот параметр настроен в settings.php',

	'site_secret:current_strength' => 'Надежность ключа',
	'site_secret:strength:weak' => "Низкая",
	'site_secret:strength_msg:weak' => "Настоятельно рекомендуется перегенерировать секретный ключ сайта.",
	'site_secret:strength:moderate' => "Средняя",
	'site_secret:strength_msg:moderate' => "Рекомендуется перегенерировать секретный ключ для максимальной безопасности.",
	'site_secret:strength:strong' => "Высокая",
	'site_secret:strength_msg:strong' => "Секретный ключ достаточно надежен. Перегенерация не требуется.",

	'admin:dashboard' => 'Панель управления',
	'admin:widget:online_users' => 'Пользователи онлайн',
	'admin:widget:online_users:help' => 'Список пользователей, находящихся на сайте',
	'admin:widget:new_users' => 'Новые пользователи',
	'admin:widget:new_users:help' => 'Список последних зарегистрированных пользователей',
	'admin:widget:banned_users' => 'Заблокированные пользователи',
	'admin:widget:banned_users:help' => 'Список заблокированных пользователей',
	'admin:widget:content_stats' => 'Статистика контента',
	'admin:widget:content_stats:help' => 'Отслеживание контента, созданного пользователями',
	'admin:widget:cron_status' => 'Статус Cron',
	'admin:widget:cron_status:help' => 'Показывает статус последнего выполнения задач Cron',
	'admin:widget:elgg_blog' => 'Блог Elgg',
	'admin:widget:elgg_blog:help' => 'Показывает последние новости из блога Elgg',
	'admin:widget:elgg_blog:no_results' => 'Не удалось получить последние новости Elgg',
	'admin:statistics:numentities' => 'Статистика контента',
	'admin:statistics:numentities:type' => 'Тип контента',
	'admin:statistics:numentities:number' => 'Количество',
	'admin:statistics:numentities:searchable' => 'Доступные для поиска сущности',
	'admin:statistics:numentities:other' => 'Другие сущности',

	'admin:statistics:database' => 'Информация о БД',
	'admin:statistics:database:table' => 'Таблица',
	'admin:statistics:database:row_count' => 'Количество строк',

	'admin:statistics:queue' => 'Информация о очереди',
	'admin:statistics:queue:name' => 'Название',
	'admin:statistics:queue:row_count' => 'Количество строк',
	'admin:statistics:queue:oldest' => 'Самая старая запись',
	'admin:statistics:queue:newest' => 'Самая новая запись',

	'admin:widget:admin_welcome' => 'Добро пожаловать',
	'admin:widget:admin_welcome:help' => "Краткое введение в административную панель Elgg",
	'admin:widget:admin_welcome:intro' => 'Добро пожаловать в Elgg! Сейчас вы находитесь в панели администратора. Она полезна для отслеживания происходящего на сайте.',

	'admin:widget:admin_welcome:registration' => "Регистрация новых пользователей в настоящее время отключена! Вы можете включить её на странице %s.",
	'admin:widget:admin_welcome:admin_overview' => "Навигация по административной панели находится в меню справа. Оно разделено на три раздела:
<dl>
<dt>Управление</dt><dd>Базовые задачи: управление пользователями, модерация жалоб и активация плагинов.</dd>
<dt>Настройка</dt><dd>Периодические задачи: установка имени сайта или настройка параметров безопасности.</dd>
<dt>Утилиты</dt><dd>Различные инструменты для поддержки сайта.</dd>
<dt>Информация</dt><dd>Статистика и данные о вашем сайте.</dd>
<dt>Разработка</dt><dd>Для разработчиков плагинов или отладки сайта. (Требуется плагин разработчика.)</dd>
</dl>",

	// argh, this is ugly
	'admin:widget:admin_welcome:outro' => '<br />Обязательно ознакомьтесь с ресурсами по ссылкам в подвале сайта. Спасибо, что используете Elgg!',

	'admin:widget:control_panel' => 'Панель управления',
	'admin:widget:control_panel:help' => "Быстрый доступ к основным элементам управления",

	'admin:cache:flush' => 'Очистить кэши',
	'admin:cache:flushed' => "Кэши сайта очищены",
	'admin:cache:invalidate' => 'Инвалидировать кэши',
	'admin:cache:invalidated' => "Кэши сайта инвалидированы",
	'admin:cache:clear' => 'Сбросить кэши',
	'admin:cache:cleared' => "Кэши сайта сброшены",
	'admin:cache:purge' => 'Удалить кэши',
	'admin:cache:purged' => "Кэши сайта удалены",

	'admin:footer:faq' => 'FAQ по администрированию',
	'admin:footer:manual' => 'Руководство администратора',
	'admin:footer:community_forums' => 'Форум сообщества Elgg',
	'admin:footer:blog' => 'Блог Elgg',

	'admin:plugins:category:all' => 'Все плагины',
	'admin:plugins:category:active' => 'Активные плагины',
	'admin:plugins:category:inactive' => 'Неактивные плагины',
	'admin:plugins:category:admin' => 'Администрирование',
	'admin:plugins:category:bundled' => 'В комплекте',
	'admin:plugins:category:nonbundled' => 'Сторонние',
	'admin:plugins:category:content' => 'Контент',
	'admin:plugins:category:development' => 'Разработка',
	'admin:plugins:category:enhancement' => 'Улучшения',
	'admin:plugins:category:api' => 'Сервис/API',
	'admin:plugins:category:communication' => 'Связь',
	'admin:plugins:category:security' => 'Безопасность и спам',
	'admin:plugins:category:social' => 'Социальные',
	'admin:plugins:category:multimedia' => 'Мультимедиа',
	'admin:plugins:category:theme' => 'Темы',
	'admin:plugins:category:widget' => 'Виджеты',
	'admin:plugins:category:utility' => 'Утилиты',

	'admin:plugins:markdown:unknown_plugin' => 'Неизвестный плагин.',
	'admin:plugins:markdown:unknown_file' => 'Неизвестный файл.',

	'admin:notices:delete_all' => 'Скрыть все уведомления (%s)',
	'admin:notices:could_not_delete' => 'Не удалось удалить уведомление.',
	'item:object:admin_notice' => 'Уведомление админа',
	'collection:object:admin_notice' => 'Уведомления админа',

	'admin:options' => 'Опции админа',

	'admin:security' => 'Безопасность',
	'admin:security:information' => 'Информация',
	'admin:security:information:description' => 'На этой странице вы найдете список рекомендаций по безопасности.',
	'admin:security:information:https' => 'Защищен ли сайт протоколом HTTPS',
	'admin:security:information:https:warning' => "Рекомендуется защитить ваш сайт с помощью HTTPS, это помогает защитить данные (например, пароли) от перехвата.",
	'admin:security:information:wwwroot' => 'Основная папка сайта доступна для записи',
	'admin:security:information:wwwroot:error' => "Рекомендуется устанавливать Elgg в папку, недоступную для записи веб-сервером. Злоумышленники могут разместить вредоносный код на вашем сайте.",
	'admin:security:information:validate_input' => 'Валидация ввода',
	'admin:security:information:validate_input:error' => "Какой-то плагин отключил валидацию ввода на вашем сайте, это позволит пользователям отправлять потенциально вредоносный контент (например, XSS)",
	'admin:security:information:password_length' => 'Минимальная длина пароля',
	'admin:security:information:password_length:warning' => "Рекомендуется устанавливать минимальную длину пароля не менее 6 символов.",
	'admin:security:information:username_length' => 'Минимальная длина имени пользователя',
	'admin:security:information:username_length:warning' => "Рекомендуется устанавливать минимальную длину имени пользователя не менее 4 символов.",
	'admin:security:information:php:session_gc' => "Очистка сессий PHP",
	'admin:security:information:php:session_gc:chance' => "Вероятность очистки: %s%%",
	'admin:security:information:php:session_gc:lifetime' => "Время жизни сессии %s секунд",
	'admin:security:information:php:session_gc:error' => "Рекомендуется настроить \'session.gc_probability\' и \'session.gc_divisor\' в PHP,
это позволит очищать истекшие сессии из БД и не позволит пользователям повторно использовать старые сессии.",
	'admin:security:information:htaccess:hardening' => "Усиление защиты файлов через .htaccess",
	'admin:security:information:htaccess:hardening:help' => "В файле .htaccess можно заблокировать доступ к определенным файлам для повышения безопасности. Для получения информации посмотрите в вашем .htaccess.",
	
	'admin:security:settings' => 'Настройки',
	'admin:security:settings:description' => 'На этой странице можно настроить некоторые функции безопасности. Внимательно прочитайте описания.',
	'admin:security:settings:label:hardening' => 'Усиление защиты',
	'admin:security:settings:label:account' => 'Аккаунт',
	'admin:security:settings:label:notifications' => 'Уведомления',
	'admin:security:settings:label:site_secret' => 'Секретный ключ сайта',
	
	'admin:security:settings:notify_admins' => 'Уведомлять всех администраторов при добавлении или удалении админа',
	'admin:security:settings:notify_admins:help' => 'Будет отправляться уведомление всем администраторам о том, что один из них добавил/удалил администратора сайта.',
	
	'admin:security:settings:notify_user_admin' => 'Уведомлять пользователя при назначении или снятии прав администратора',
	'admin:security:settings:notify_user_admin:help' => 'Пользователю будет отправлено уведомление о том, что ему назначены/отозваны права администратора.',
	
	'admin:security:settings:notify_user_ban' => 'Уведомлять пользователя при (раз)блокировке аккаунта',
	'admin:security:settings:notify_user_ban:help' => 'Пользователю будет отправлено уведомление о том, что его аккаунт был (раз)блокирован.',
	
	'admin:security:settings:notify_user_password' => 'Уведомлять пользователя при смене пароля',
	'admin:security:settings:notify_user_password:help' => 'Пользователю будет отправлено уведомление при смене его пароля.',
	
	'admin:security:settings:protect_upgrade' => 'Защитить upgrade.php',
	'admin:security:settings:protect_upgrade:help' => 'Защитит upgrade.php, требуя действительный токен или права администратора.',
	'admin:security:settings:protect_upgrade:token' => 'Чтобы использовать upgrade.php без авторизации или не администратору, используйте следующий URL:',
	
	'admin:security:settings:protect_cron' => 'Защитить URL /cron',
	'admin:security:settings:protect_cron:help' => 'Защитит URL /cron токеном. Cron выполнится только при наличии действительного токена.',
	'admin:security:settings:protect_cron:token' => 'Для использования URL /cron необходимы следующие токены. Обратите внимание, что каждый интервал имеет свой токен.',
	'admin:security:settings:protect_cron:toggle' => 'Показать/скрыть URL cron',
	
	'admin:security:settings:disable_password_autocomplete' => 'Отключить автозаполнение в полях пароля',
	'admin:security:settings:disable_password_autocomplete:help' => 'Данные, введенные в эти поля, будут кэшироваться браузером. Злоумышленник, получивший доступ к браузеру жертвы, может украсть эту информацию. Это особенно важно, если приложение используется на общих компьютерах. При отключении менеджеры паролей не смогут автозаполнять эти поля. Поддержка атрибута autocomplete зависит от браузера.',
	
	'admin:security:settings:email_require_password' => 'Требовать пароль для смены email',
	'admin:security:settings:email_require_password:help' => 'При попытке смены email требовать ввод текущего пароля.',
	
	'admin:security:settings:email_require_confirmation' => 'Требовать подтверждение при смене email',
	'admin:security:settings:email_require_confirmation:help' => 'Новый email должен быть подтвержден перед применением изменений. После успешной смены на старый email будет отправлено уведомление.',

	'admin:security:settings:session_bound_entity_icons' => 'Иконки сущностей, привязанные к сессии',
	'admin:security:settings:session_bound_entity_icons:help' => 'Иконки сущностей по умолчанию могут быть привязаны к сессии. Это означает, что сгенерированные URL содержат информацию о текущей сессии.
Привязка делает URL иконок недействительными между сессиями. Побочный эффект: кэширование этих URL поможет только активной сессии.',

	'admin:security:settings:subresource_integrity_enabled' => 'Целостность субресурсов (SRI)',
	'admin:security:settings:subresource_integrity_enabled:help' => 'Добавляет метаданные целостности к субресурсам, таким как JS и CSS файлы. Это позволяет браузеру проверять содержимое ресурса.',
	
	'admin:security:settings:site_secret:intro' => 'Elgg использует ключ для создания токенов безопасности для различных целей.',
	'admin:security:settings:site_secret:regenerate' => "Перегенерировать секретный ключ",
	'admin:security:settings:site_secret:regenerate:help' => "Примечание: Перегенерация ключа может создать неудобства для некоторых пользователей, инвалидируя токены в cookie \"Запомнить меня\", запросах подтверждения email, кодах приглашений и т.д.",
	
	'admin:security:settings:minusername' => "Минимальная длина имени пользователя",
	'admin:security:settings:minusername:help' => "Минимальное количество символов в имени пользователя",
	
	'admin:security:settings:min_password_length' => "Минимальная длина пароля",
	'admin:security:settings:min_password_length:help' => "Минимальное количество символов в пароле",
	
	'admin:security:settings:min_password_lower' => "Минимальное количество строчных букв в пароле",
	'admin:security:settings:min_password_lower:help' => "Настройте минимальное количество строчных букв (a-z). 0 для отсутствия требования, пусто для снятия ограничения.",
	
	'admin:security:settings:min_password_upper' => "Минимальное количество заглавных букв в пароле",
	'admin:security:settings:min_password_upper:help' => "Настройте минимальное количество заглавных букв (A-Z). 0 для отсутствия требования, пусто для снятия ограничения.",
	
	'admin:security:settings:min_password_number' => "Минимальное количество цифр в пароле",
	'admin:security:settings:min_password_number:help' => "Настройте минимальное количество цифр (0-9). 0 для отсутствия требования, пусто для снятия ограничения.",
	
	'admin:security:settings:min_password_special' => "Минимальное количество спецсимволов в пароле",
	'admin:security:settings:min_password_special:help' => "Настройте минимальное количество спецсимволов (!@$%^&*()<>,.?/[]{}-=_+). 0 для отсутствия требования, пусто для снятия ограничения.",
	
	'admin:security:security_txt' => "Security.txt",
	'admin:security:security_txt:description' => "Куда сообщать о найденных уязвимостях? Файл security.txt — это стандарт, помогающий структурировать информацию для исследователей безопасности. Подробнее на %s. Содержимое вашего файла доступно на %s.",
	'admin:security:security_txt:expired' => "Содержимое security.txt истекло, проверьте актуальность данных.",
	'admin:security:security_txt:contact' => "Контакт",
	'admin:security:security_txt:contact:help' => "Ссылка или email для связи по вопросам безопасности. Указывайте \'https://\' для URL и \'mailto:\' для email. См. %s",
	'admin:security:security_txt:expires' => "Истекает",
	'admin:security:security_txt:expires:help' => "Дата и время, после которых данные security.txt считаются устаревшими. Регулярно обновляйте это значение. См. %s",
	'admin:security:security_txt:encryption' => "Шифрование",
	'admin:security:security_txt:encryption:help' => "Ссылка на ключ для безопасной связи с исследователями. Указывайте \'https://\'. См. %s",
	'admin:security:security_txt:acknowledgments' => "Благодарности",
	'admin:security:security_txt:acknowledgments:help' => "Ссылка на страницу, где вы благодарите исследователей. Указывайте \'https://\'. См. %s",
	'admin:security:security_txt:language' => "Язык",
	'admin:security:security_txt:language:help' => "Список кодов языков вашей команды безопасности через запятую. См. %s",
	'admin:security:security_txt:canonical' => "Канонический URL",
	'admin:security:security_txt:canonical:help' => "URL для доступа к файлу security.txt. Обязательно укажите, если используете цифровую подпись. См. %s",
	'admin:security:security_txt:policy' => "Политика",
	'admin:security:security_txt:policy:help' => "Ссылка на политику действий для исследователей. Указывайте \'https://\'. См. %s",
	'admin:security:security_txt:hiring' => "Вакансии",
	'admin:security:security_txt:hiring:help' => "Ссылка на вакансии в сфере безопасности. Указывайте \'https://\'. См. %s",
	'admin:security:security_txt:csaf' => "CSAF",
	'admin:security:security_txt:csaf:help' => "Ссылка на provider-metadata.json вашего провайдера CSAF. Указывайте \'https://\'. См. %s",
	
	'admin:site:secret:regenerated' => "Секретный ключ сайта перегенерирован",
	'admin:site:secret:prevented' => "Перегенерация секретного ключа была отменена",
	
	'admin:notification:make_admin:admin:subject' => 'На сайт %s добавлен новый администратор',
	'admin:notification:make_admin:admin:body' => '%s назначил %s администратором сайта %s.
Чтобы посмотреть профиль нового администратора, нажмите здесь:
%s',
	
	'admin:notification:make_admin:user:subject' => 'Вам назначены права администратора на %s',
	'admin:notification:make_admin:user:body' => '%s назначил вас администратором сайта %s.
Чтобы перейти на сайт, нажмите здесь:
%s',
	'admin:notification:remove_admin:admin:subject' => 'С сайта %s удален администратор',
	'admin:notification:remove_admin:admin:body' => '%s лишил %s прав администратора на %s.
Чтобы посмотреть профиль бывшего администратора, нажмите здесь:
%s',
	
	'admin:notification:remove_admin:user:subject' => 'С вас сняты права администратора на %s',
	'admin:notification:remove_admin:user:body' => '%s лишил вас прав администратора на %s.
Чтобы перейти на сайт, нажмите здесь:
%s',
	'user:notification:validate:subject' => 'Ваш аккаунт на %s готов к использованию',
	'user:notification:validate:body' => 'Ваш аккаунт на %s успешно проверен. Теперь вы можете им пользоваться.
Чтобы перейти на сайт, нажмите здесь:
%s',
	'user:notification:ban:subject' => 'Ваш аккаунт на %s заблокирован',
	'user:notification:ban:body' => 'Ваш аккаунт на %s заблокирован.
Чтобы перейти на сайт, нажмите здесь:
%s',
	
	'user:notification:unban:subject' => 'Блокировка вашего аккаунта на %s снята',
	'user:notification:unban:body' => 'Блокировка вашего аккаунта на %s снята. Вы снова можете пользоваться сайтом.
Чтобы перейти на сайт, нажмите здесь:
%s',
	
	'user:notification:password_change:subject' => 'Ваш пароль был изменен!',
	'user:notification:password_change:body' => "Ваш пароль на '%s' был изменен! Если вы сделали это сами, все в порядке.
Если нет, пожалуйста, сбросьте пароль здесь:
%s
Или свяжитесь с администратором сайта:
%s",
	
	'admin:notification:unvalidated_users:subject' => "Пользователи, ожидающие проверки на %s",
	'admin:notification:unvalidated_users:body' => "%d пользователей на '%s' ожидают проверки администратором.
Полный список пользователей здесь:
%s",

/**
 * Plugins
 */

	'plugins:disabled' => 'Плагины не загружаются, так как в директории mod находится файл "disabled".',
	'plugins:settings:save:ok' => "Настройки плагина %s успешно сохранены.",
	'plugins:settings:save:fail' => "Возникла проблема при сохранении настроек плагина %s.",
	'plugins:settings:remove:ok' => "Все настройки плагина %s удалены",
	'plugins:settings:remove:fail' => "Произошла ошибка при удалении всех настроек плагина %s",
	'plugins:usersettings:save:ok' => "Ваши настройки для %s успешно сохранены.",
	'plugins:usersettings:save:fail' => "Возникла проблема при сохранении настроек для %s.",
	
	'item:object:plugin' => 'Плагин',
	'collection:object:plugin' => 'Плагины',
	
	'plugins:settings:remove:menu:text' => "Удалить все настройки",
	'plugins:settings:remove:menu:confirm' => "Вы уверены, что хотите удалить все настройки, включая пользовательские, для этого плагина?",

	'admin:plugins' => "Плагины",
	'admin:plugins:activate_all' => 'Активировать все',
	'admin:plugins:deactivate_all' => 'Деактивировать все',
	'admin:plugins:activate' => 'Активировать',
	'admin:plugins:deactivate' => 'Деактивировать',
	'admin:plugins:description' => "Эта панель позволяет управлять и настраивать инструменты, установленные на вашем сайте.",
	'admin:plugins:opt:linktext' => "Настроить инструменты...",
	'admin:plugins:opt:description' => "Настройка инструментов, установленных на сайте. ",
	'admin:plugins:label:id' => "ID",
	'admin:plugins:label:name' => "Название",
	'admin:plugins:label:authors' => "Авторы",
	'admin:plugins:label:copyright' => "Авторские права",
	'admin:plugins:label:categories' => 'Категории',
	'admin:plugins:label:licence' => "Лицензия",
	'admin:plugins:label:website' => "URL",
	'admin:plugins:label:info' => "Информация",
	'admin:plugins:label:files' => "Файлы",
	'admin:plugins:label:resources' => "Ресурсы",
	'admin:plugins:label:screenshots' => "Скриншоты",
	'admin:plugins:label:repository' => "Код",
	'admin:plugins:label:bugtracker' => "Сообщить об ошибке",
	'admin:plugins:label:donate' => "Поддержать",
	'admin:plugins:label:moreinfo' => 'подробнее',
	'admin:plugins:label:version' => 'Версия',
	'admin:plugins:label:location' => 'Расположение',
	'admin:plugins:label:priority' => 'Приоритет',
	'admin:plugins:label:dependencies' => 'Зависимости',
	'admin:plugins:label:missing_dependency' => 'Отсутствует зависимость [%s].',

	'admin:plugins:warning:unmet_dependencies' => 'У плагина есть неудовлетворенные зависимости, и он не может быть активирован. Проверьте зависимости в разделе "подробнее".',
	'admin:plugins:warning:invalid' => 'Этот плагин недействителен: %s',
	'admin:plugins:warning:invalid:check_docs' => 'Проверьте <a href="http://learn.elgg.org/en/stable/appendix/faqs.html">документацию Elgg</a> для решения проблем.',
	'admin:plugins:cannot_activate' => 'невозможно активировать',
	'admin:plugins:cannot_deactivate' => 'невозможно деактивировать',
	'admin:plugins:already:active' => 'Выбранный плагин(и) уже активен(ны).',
	'admin:plugins:already:inactive' => 'Выбранный плагин(и) уже неактивен(ны).',

	'admin:plugins:set_priority:yes' => "%s переупорядочен.",
	'admin:plugins:set_priority:no' => "Не удалось переупорядочить %s.",
	'admin:plugins:deactivate:yes' => "%s деактивирован.",
	'admin:plugins:deactivate:no' => "Не удалось деактивировать %s.",
	'admin:plugins:deactivate:no_with_msg' => "Не удалось деактивировать %s. Ошибка: %s",
	'admin:plugins:activate:yes' => "%s активирован.",
	'admin:plugins:activate:no' => "Не удалось активировать %s.",
	'admin:plugins:activate:no_with_msg' => "Не удалось активировать %s. Ошибка: %s",
	'admin:plugins:categories:all' => 'Все категории',
	'admin:plugins:plugin_website' => 'Сайт плагина',
	'admin:plugins:author' => '%s',
	'admin:plugins:version' => 'Версия %s',
	'admin:plugin_settings' => 'Настройки плагина',
	'admin:plugins:warning:unmet_dependencies_active' => 'Этот плагин активен, но имеет неудовлетворенные зависимости. Возможны ошибки. См. "подробнее" ниже.',

	'admin:statistics:description' => "Обзор статистики вашего сайта. Для более детальной статистики доступна профессиональная функция администрирования.",
	'admin:statistics:opt:description' => "Просмотр статистической информации о пользователях и объектах на сайте.",
	'admin:statistics:opt:linktext' => "Просмотр статистики...",
	'admin:statistics:label:user' => "Статистика пользователей",
	'admin:statistics:label:numentities' => "Сущности на сайте",
	'admin:statistics:label:numusers' => "Количество пользователей",
	'admin:statistics:label:numonline' => "Пользователей онлайн",
	'admin:statistics:label:onlineusers' => "Пользователи сейчас онлайн",
	'admin:statistics:label:admins' => "Администраторы",
	'admin:statistics:label:version' => "Версия Elgg",
	'admin:statistics:label:version:release' => "Релиз",
	'admin:statistics:label:version:version' => "Версия БД",
	'admin:statistics:label:version:code' => "Версия кода",

	'admin:server:label:elgg' => 'Elgg',
	'admin:server:label:requirements' => 'Требования',
	'admin:server:label:php' => 'PHP',
	'admin:server:label:phpinfo' => 'Показать PHPInfo',
	'admin:server:label:web_server' => 'Веб-сервер',
	'admin:server:label:server' => 'Сервер',
	'admin:server:label:log_location' => 'Расположение логов',
	'admin:server:label:php_version' => 'Версия PHP',
	'admin:server:label:php_version:required_version' => 'Elgg требует минимальную версию PHP %s',
	'admin:server:label:php_ini' => 'Расположение файла php.ini',
	'admin:server:label:php_log' => 'Лог PHP',
	'admin:server:label:mem_avail' => 'Доступно памяти',
	'admin:server:label:mem_used' => 'Использовано памяти',
	'admin:server:error_log' => "Лог ошибок веб-сервера",
	'admin:server:label:post_max_size' => 'Максимальный размер POST',
	'admin:server:label:upload_max_filesize' => 'Максимальный размер загрузки',
	'admin:server:warning:post_max_too_small' => '(Примечание: post_max_size должно быть больше этого значения для поддержки загрузок такого размера)',

	'admin:server:label:opcache' => 'OPcache',
	'admin:server:opcache:inactive' => 'OPcache недоступен на этом сервере или не включен.
Для повышения производительности рекомендуется включить и настроить OPcache.',
	
	'admin:server:requirements:php_extension' => "Расширение PHP: %s",
	'admin:server:requirements:php_extension:required' => "Это расширение PHP обязательно для корректной работы Elgg",
	'admin:server:requirements:php_extension:recommended' => "Это расширение PHP рекомендуется для оптимальной работы Elgg",
	'admin:server:requirements:rewrite' => "Правила перезаписи .htaccess",
	'admin:server:requirements:rewrite:fail' => "Проверьте ваш .htaccess на наличие корректных правил перезаписи",
	
	'admin:server:requirements:database:server' => "Сервер БД",
	'admin:server:requirements:database:client' => "Клиент БД",
	'admin:server:requirements:database:client:required' => "Elgg требует pdo_mysql для подключения к серверу БД",

	'admin:server:requirements:webp' => "Поддержка WebP",

	'admin:server:requirements:gc' => "Сборка мусора сессий",
	'admin:server:requirements:gc:info' => "Если сборка мусора не настроена, таблица сессий не будет очищаться. Настройте session.gc_divisor и session.gc_probability в php.ini.",
	
	'admin:user:label:search' => "Поиск пользователей:",
	'admin:user:label:searchbutton' => "Найти",

	'admin:user:ban:no' => "Не удалось заблокировать пользователя",
	'admin:user:ban:yes' => "Пользователь заблокирован.",
	'admin:user:self:ban:no' => "Вы не можете заблокировать себя",
	'admin:user:unban:no' => "Не удалось разблокировать пользователя",
	'admin:user:unban:yes' => "Пользователь разблокирован.",
	'admin:user:delete:no' => "Не удалось удалить пользователя",
	'admin:user:delete:yes' => "Пользователь %s удален",
	'admin:user:self:delete:no' => "Вы не можете удалить себя",

	'admin:user:resetpassword:yes' => "Пароль сброшен, пользователь уведомлен.",
	'admin:user:resetpassword:no' => "Не удалось сбросить пароль.",

	'admin:user:makeadmin:yes' => "Пользователь теперь администратор.",
	'admin:user:makeadmin:no' => "Не удалось сделать этого пользователя администратором.",

	'admin:user:removeadmin:yes' => "Пользователь больше не администратор.",
	'admin:user:removeadmin:no' => "Не удалось отозвать права администратора у этого пользователя.",
	'admin:user:self:removeadmin:no' => "Вы не можете отозвать собственные права администратора.",

	'admin:configure_utilities:menu_items' => 'Пункты меню',
	'admin:menu_items:configure' => 'Настроить пункты главного меню',
	'admin:menu_items:description' => 'Выберите порядок пунктов меню сайта. Не настроенные пункты будут добавлены в конец списка.',
	'admin:menu_items:hide_toolbar_entries' => 'Удалить ссылки из меню панели инструментов?',
	'admin:menu_items:saved' => 'Пункты меню сохранены.',
	'admin:add_menu_item' => 'Добавить пользовательский пункт меню',
	'admin:add_menu_item:description' => 'Заполните отображаемое имя и URL, чтобы добавить пользовательские элементы в навигационное меню.',

	'admin:configure_utilities:default_widgets' => 'Виджеты по умолчанию',
	'admin:default_widgets:unknown_type' => 'Неизвестный тип виджета',
	'admin:default_widgets:instructions' => 'Добавляйте, удаляйте, перемещайте и настраивайте виджеты по умолчанию для выбранной страницы. Эти изменения коснутся только новых пользователей.',

	'admin:robots.txt:instructions' => "Отредактируйте файл robots.txt вашего сайта ниже",
	'admin:robots.txt:plugins' => "Плагины добавляют следующее в robots.txt",
	'admin:robots.txt:subdir' => "Инструмент robots.txt не работает, так как Elgg установлен в поддиректории",
	'admin:robots.txt:physical' => "Инструмент robots.txt не работает, так как физический robots.txt уже существует",

	'admin:maintenance_mode:default_message' => 'Сайт находится на техническом обслуживании',
	'admin:maintenance_mode:instructions' => 'Режим обслуживания следует использовать для обновлений и других крупных изменений. Включенный режим позволяет входить и просматривать сайт только администраторам.',
	'admin:maintenance_mode:mode_label' => 'Режим обслуживания',
	'admin:maintenance_mode:message_label' => 'Сообщение, отображаемое пользователям при включенном режиме обслуживания',
	'admin:maintenance_mode:saved' => 'Настройки режима обслуживания сохранены.',
	'admin:maintenance_mode:indicator_menu_item' => 'Сайт находится в режиме обслуживания.',
	'admin:login' => 'Вход администратора',

/**
 * User settings
 */

	'usersettings:statistics' => "Ваша статистика",
	'usersettings:statistics:user' => "Статистика %s",
	'usersettings:statistics:opt:linktext' => "Статистика аккаунта",

	'usersettings:statistics:login_history' => "История входов",
	'usersettings:statistics:login_history:date' => "Дата",
	'usersettings:statistics:login_history:ip' => "IP-адрес",

	'usersettings:user' => "Настройки %s",
	'usersettings:user:opt:linktext' => "Изменить настройки",

	'usersettings:plugins:opt:linktext' => "Настроить инструменты",
	
	'usersettings:statistics:yourdetails' => "Ваши данные",
	'usersettings:statistics:details:user' => "Данные для %s",
	'usersettings:statistics:numentities:user' => "Статистика контента для %s",
	'usersettings:statistics:label:name' => "Полное имя",
	'usersettings:statistics:label:email' => "Email",
	'usersettings:statistics:label:lastlogin' => "Последний вход",
	'usersettings:statistics:label:membersince' => "Участник с",
	'usersettings:statistics:label:numentities' => "Ваш контент",
	
	'usersettings:delayed_email:interval' => "Настроить интервал доставки отложенных email-уведомлений",
	'usersettings:delayed_email:interval:help' => "Все отложенные уведомления будут сохраняться и доставляться одним письмом в указанный интервал",

/**
 * Activity river
 */

	'river:all' => 'Вся активность сайта',
	'river:mine' => 'Моя активность',
	'river:owner' => 'Активность %s',
	'river:friends' => 'Активность друзей',
	'river:select' => 'Показать %s',
	'river:comments:all' => 'Посмотреть все комментарии (%u)',

/**
 * Icons
 */

	'icon:size' => "Размер иконки",
	'icon:size:topbar' => "Верхняя панель",
	'icon:size:tiny' => "Крошечный",
	'icon:size:small' => "Маленький",
	'icon:size:medium' => "Средний",
	'icon:size:large' => "Большой",
	'icon:size:master' => "Очень большой",
	
	'entity:edit:icon:crop_messages:generic' => "Выбранное изображение не соответствует рекомендуемым размерам. Это может привести к низкому качеству иконок.",
	'entity:edit:icon:crop_messages:width' => "Рекомендуется использовать изображение шириной не менее %dpx.",
	'entity:edit:icon:crop_messages:height' => "Рекомендуется использовать изображение высотой не менее %dpx.",
	'entity:edit:icon:crop:img:alt' => "Загруженное изображение",
	'entity:edit:icon:file:label' => "Загрузить новую иконку",
	'entity:edit:icon:file:help' => "Оставьте пустым, чтобы сохранить текущую.",
	'entity:edit:icon:remove:label' => "Удалить иконку",

	'entity:edit:header:file:label' => "Загрузить новую шапку",
	'entity:edit:header:file:help' => "Оставьте пустым, чтобы сохранить текущую.",
	'entity:edit:header:remove:label' => "Удалить изображение шапки",

/**
 * Generic action words
 */

	'save' => "Сохранить",
	'save_go' => "Сохранить и перейти в %s",
	'reset' => 'Сбросить',
	'publish' => "Опубликовать",
	'feature' => "В избранное",
	'unfeature' => "Убрать из избранного",
	'cancel' => "Отмена",
	'saving' => "Сохранение ...",
	'update' => "Обновить",
	'preview' => "Предпросмотр",
	'edit' => "Редактировать",
	'delete' => "Удалить",
	'trash' => "В корзину",
	'accept' => "Принять",
	'reject' => "Отклонить",
	'decline' => "Отказать",
	'approve' => "Утвердить",
	'activate' => "Активировать",
	'deactivate' => "Деактивировать",
	'disapprove' => "Отказать",
	'revoke' => "Отозвать",
	'load' => "Загрузить",
	'upload' => "Загрузить",
	'download' => "Скачать",
	'ban' => "Заблокировать",
	'unban' => "Разблокировать",
	'banned' => "Заблокирован",
	'enable' => "Включить",
	'disable' => "Отключить",
	'request' => "Запросить",
	'complete' => "Завершить",
	'open' => 'Открыть',
	'close' => 'Закрыть',
	'hide' => 'Скрыть',
	'show' => 'Показать',
	'reply' => "Ответить",
	'more' => 'Еще',
	'more_info' => 'Подробнее',
	'comments' => 'Комментарии',
	'import' => 'Импорт',
	'export' => 'Экспорт',
	'untitled' => 'Без названия',
	'help' => 'Помощь',
	'send' => 'Отправить',
	'resend' => 'Отправить повторно',
	'post' => 'Опубликовать',
	'submit' => 'Отправить',
	'comment' => 'Комментировать',
	'upgrade' => 'Обновить',
	'sort' => 'Сортировка',
	'filter' => 'Фильтр',
	'new' => 'Новый',
	'add' => 'Добавить',
	'create' => 'Создать',
	'remove' => 'Удалить',
	'revert' => 'Откатить',
	'validate' => 'Проверить',
	'read_more' => 'Читать далее',
	'next' => 'Далее',
	'previous' => 'Назад',
	'older' => 'Старее',
	'newer' => 'Новее',
	
	'site' => 'Сайт',
	'activity' => 'Активность',
	'members' => 'Участники',
	'menu' => 'Меню',
	'item' => 'Элемент',

	'up' => 'Вверх',
	'down' => 'Вниз',
	'top' => 'Наверх',
	'bottom' => 'Вниз',
	'right' => 'Вправо',
	'left' => 'Влево',
	'back' => 'Назад',

	'invite' => "Пригласить",

	'resetpassword' => "Сбросить пароль",
	'changepassword' => "Изменить пароль",
	'makeadmin' => "Назначить админом",
	'removeadmin' => "Снять права админа",

	'option:yes' => "Да",
	'option:no' => "Нет",

	'unknown' => 'Неизвестно',
	'never' => 'Никогда',

	'active' => 'Активен',
	'total' => 'Всего',
	'unvalidated' => 'Не проверен',
	
	'ok' => 'OK',
	'any' => 'Любой',
	'error' => 'Ошибка',

	'other' => 'Другое',
	'options' => 'Опции',
	'advanced' => 'Расширенные',

	'learnmore' => "Нажмите здесь, чтобы узнать больше.",
	'unknown_error' => 'Неизвестная ошибка',

	'content' => "контент",
	'content:latest' => 'Последняя активность',
	
	'list:out_of_bounds' => "Вы достигли конца списка, где нет контента, однако контент доступен.",
	'list:out_of_bounds:link' => "Вернуться на первую страницу",
	'list:error:getter:user' => 'Произошла ошибка при получении контента',
	'list:error:getter:admin' => "Геттер '%s' вернул '%s', однако просмотру '%s' требуется массив",

	'link:text' => 'посмотреть ссылку',
	
	'scroll_to_top' => 'Прокрутить наверх',

/**
 * Generic questions
 */

	'question:areyousure' => 'Вы уверены?',

/**
 * Status
 */

	'status' => 'Статус',
	'status:unsaved_draft' => 'Не сохраненный черновик',
	'status:draft' => 'Черновик',
	'status:unpublished' => 'Не опубликовано',
	'status:published' => 'Опубликовано',
	'status:featured' => 'Рекомендуемое',
	'status:open' => 'Открыт',
	'status:closed' => 'Закрыт',
	'status:enabled' => 'Включен',
	'status:disabled' => 'Отключен',
	'status:unavailable' => 'Недоступен',
	'status:active' => 'Активен',
	'status:inactive' => 'Неактивен',
	'status:deleted' => 'Удален',
	'status:trashed' => 'В корзине',

/**
 * Generic sorts
 */

	'sort:newest' => 'Новые',
	'sort:oldest' => 'Старые',
	'sort:popular' => 'Популярные',
	'sort:alpha' => 'По алфавиту',
	'sort:priority' => 'По приоритету',
	'sort:relevance' => 'По релевантности',
	'sort:az' => '%s (А-Я)',
	'sort:za' => '%s (Я-А)',

/**
 * Generic data words
 */

	'title' => "Заголовок",
	'description' => "Описание",
	'tags' => "Теги",
	'url' => "URL",
	'website' => "Сайт",
	
	'all' => "Все",
	'mine' => "Мои",

	'by' => 'от',
	'none' => 'нет',

	'annotations' => "Аннотации",
	'relationships' => "Связи",
	'metadata' => "Метаданные",
	'tagcloud' => "Облако тегов",

	'on' => 'Вкл',
	'off' => 'Выкл',

	'number_counter:decimal_separator' => ".",
	'number_counter:thousands_separator' => ",",
	'number_counter:view:thousand' => "%s тыс.",
	'number_counter:view:million' => "%s млн.",
	'number_counter:view:billion' => "%s млрд.",
	'number_counter:view:trillion' => "%s трлн.",

/**
 * Entity actions
 */

	'edit:this' => 'Редактировать',
	'delete:this' => 'Удалить',
	'trash:this' => 'В корзину',
	'restore:this' => 'Восстановить',
	'restore:this:move' => 'Восстановить и переместить',
	'comment:this' => 'Комментировать',

/**
 * Input / output strings
 */

	'deleteconfirm' => "Вы уверены, что хотите удалить этот элемент?",
	'trashconfirm' => "Вы уверены, что хотите переместить этот элемент в корзину?",
	'restoreconfirm' => "Вы уверены, что хотите восстановить этот элемент?",
	'restoreandmoveconfirm' => "Вы уверены, что хотите восстановить и переместить этот элемент?",
	'deleteconfirm:plural' => "Вы уверены, что хотите удалить эти элементы?",
	'fileexists' => "Файл уже загружен. Чтобы заменить его, выберите новый ниже",
	'input:file:upload_limit' => 'Максимально допустимый размер файла: %s',
	'input:container_guid:info' => 'Этот контент будет опубликован в %s',

/**
 * User add
 */

	'useradd:subject' => 'Создан аккаунт пользователя',
	'useradd:body' => 'Для вас создан аккаунт на %s. Для входа посетите:
%s
Используйте следующие данные:
Имя пользователя: %s
Пароль: %s
После входа мы настоятельно рекомендуем сменить пароль.',

/**
 * Messages
 */
	'messages:title:success' => 'Успешно',
	'messages:title:error' => 'Ошибка',
	'messages:title:warning' => 'Предупреждение',
	'messages:title:help' => 'Помощь',
	'messages:title:notice' => 'Уведомление',
	'messages:title:info' => 'Информация',

/**
 * Time
 */

	'input:date_format' => 'Y-m-d',
	'input:date_format:datepicker' => 'yy-mm-dd', // jQuery UI datepicker format
	'input:time_format' => 'H:i',

	'friendlytime:updated' => "%s - Отредактировано",
	'friendlytime:updated:title' => "Создано: %s
Отредактировано: %s",
	
	'friendlytime:justnow' => "только что",
	'friendlytime:minutes' => "%s мин. назад",
	'friendlytime:minutes:singular' => "минуту назад",
	'friendlytime:hours' => "%s ч. назад",
	'friendlytime:hours:singular' => "час назад",
	'friendlytime:days' => "%s дн. назад",
	'friendlytime:days:singular' => "вчера",
	'friendlytime:date_format' => 'j F Y @ H:i',
	'friendlytime:date_format:short' => 'j M Y',

	'friendlytime:future:minutes' => "через %s мин.",
	'friendlytime:future:minutes:singular' => "через минуту",
	'friendlytime:future:hours' => "через %s ч.",
	'friendlytime:future:hours:singular' => "через час",
	'friendlytime:future:days' => "через %s дн.",
	'friendlytime:future:days:singular' => "завтра",

	'date:month:01' => 'Январь %s',
	'date:month:02' => 'Февраль %s',
	'date:month:03' => 'Март %s',
	'date:month:04' => 'Апрель %s',
	'date:month:05' => 'Май %s',
	'date:month:06' => 'Июнь %s',
	'date:month:07' => 'Июль %s',
	'date:month:08' => 'Август %s',
	'date:month:09' => 'Сентябрь %s',
	'date:month:10' => 'Октябрь %s',
	'date:month:11' => 'Ноябрь %s',
	'date:month:12' => 'Декабрь %s',

	'date:month:short:01' => 'Янв %s',
	'date:month:short:02' => 'Фев %s',
	'date:month:short:03' => 'Мар %s',
	'date:month:short:04' => 'Апр %s',
	'date:month:short:05' => 'Май %s',
	'date:month:short:06' => 'Июн %s',
	'date:month:short:07' => 'Июл %s',
	'date:month:short:08' => 'Авг %s',
	'date:month:short:09' => 'Сен %s',
	'date:month:short:10' => 'Окт %s',
	'date:month:short:11' => 'Ноя %s',
	'date:month:short:12' => 'Дек %s',

	'date:weekday:0' => 'Воскресенье',
	'date:weekday:1' => 'Понедельник',
	'date:weekday:2' => 'Вторник',
	'date:weekday:3' => 'Среда',
	'date:weekday:4' => 'Четверг',
	'date:weekday:5' => 'Пятница',
	'date:weekday:6' => 'Суббота',

	'date:weekday:short:0' => 'Вс',
	'date:weekday:short:1' => 'Пн',
	'date:weekday:short:2' => 'Вт',
	'date:weekday:short:3' => 'Ср',
	'date:weekday:short:4' => 'Чт',
	'date:weekday:short:5' => 'Пт',
	'date:weekday:short:6' => 'Сб',

	'interval:minute' => 'Каждую минуту',
	'interval:fiveminute' => 'Каждые пять минут',
	'interval:fifteenmin' => 'Каждые пятнадцать минут',
	'interval:halfhour' => 'Каждые полчаса',
	'interval:hourly' => 'Каждый час',
	'interval:daily' => 'Ежедневно',
	'interval:weekly' => 'Еженедельно',
	'interval:monthly' => 'Ежемесячно',
	'interval:yearly' => 'Ежегодно',

/**
 * System settings
 */

	'installation:sitename' => "Название вашего сайта:",
	'installation:sitedescription' => "Краткое описание сайта (необязательно):",
	'installation:sitedescription:help' => "С включенными плагинами отображается только в meta-теге description для поисковых систем.",
	'installation:sitepermissions' => "Права доступа по умолчанию:",
	'installation:language' => "Язык сайта по умолчанию:",
	'installation:debug' => "Уровень детализации записей в лог сервера.",
	'installation:debug:label' => "Уровень логирования:",
	'installation:debug:none' => 'Отключить логирование (рекомендуется)',
	'installation:debug:error' => 'Логировать только критические ошибки',
	'installation:debug:warning' => 'Логировать ошибки и предупреждения',
	'installation:debug:notice' => 'Логировать все ошибки, предупреждения и уведомления',
	'installation:debug:info' => 'Логировать всё',

	// Walled Garden support
	'installation:registration:description' => 'Если включено, посетители могут создавать собственные аккаунты.',
	'installation:registration:label' => 'Разрешить регистрацию посетителям',
	'installation:adminvalidation:description' => 'Если включено, новым пользователям требуется ручная проверка администратором перед использованием сайта.',
	'installation:adminvalidation:label' => 'Новые пользователи требуют ручной проверки администратором',
	'installation:adminvalidation:notification:description' => 'При включении администраторы получат уведомление о наличии пользователей, ожидающих проверки. Администратор может отключить это в личных настройках.',
	'installation:adminvalidation:notification:label' => 'Уведомлять администраторов о пользователях, ожидающих проверки',
	'installation:adminvalidation:notification:direct' => 'Прямое',
	'installation:walled_garden:description' => 'Если включено, неавторизованные посетители видят только общедоступные страницы (например, вход и регистрацию).',
	'installation:walled_garden:label' => 'Ограничить страницы для авторизованных пользователей',

	'installation:siteemail' => "Email сайта (используется для системных писем):",
	'installation:siteemail:help' => "Внимание: Не используйте email, связанный с другими сторонними сервисами (например, тикет-системы с парсингом входящих писем), чтобы избежать случайной утечки приватных данных и токенов безопасности. Идеально создать новый выделенный email только для этого сайта.",
	'installation:default_limit' => "Количество элементов на страницу по умолчанию",

	'admin:site:access:warning' => "Это настройка приватности, предлагаемая пользователям при создании контента. Изменение не меняет доступ к уже созданному контенту.",
	'installation:allow_user_default_access:description' => "Разрешить пользователям устанавливать свой уровень приватности по умолчанию, переопределяющий системный.",
	'installation:allow_user_default_access:label' => "Разрешить пользовательский уровень доступа по умолчанию",

	'installation:simplecache:description' => "Простой кэш повышает производительность за счет кэширования статического контента, включая некоторые CSS и JS файлы.",
	'installation:simplecache:label' => "Использовать простой кэш (рекомендуется)",

	'installation:cache_symlink:description' => "Символическая ссылка на директорию простого кэша позволяет серверу отдавать статические файлы без запуска движка, что значительно повышает производительность и снижает нагрузку",
	'installation:cache_symlink:label' => "Использовать символьную ссылку на директорию простого кэша (рекомендуется)",
	'installation:cache_symlink:warning' => "Символьная ссылка создана. Если по какой-то причине вы захотите удалить её, удалите ссылку с сервера вручную",
	'installation:cache_symlink:paths' => 'Корректно настроенная символическая ссылка должна связывать <i>%s</i> с <i>%s</i>',
	'installation:cache_symlink:error' => "Из-за конфигурации вашего сервера символическая ссылка не может быть создана автоматически. Обратитесь к документации и создайте её вручную.",

	'installation:minify:description' => "Простой кэш также может повысить производительность за счет сжатия JavaScript и CSS файлов. (Требуется включенный простой кэш.)",
	'installation:minify_js:label' => "Сжимать JavaScript (рекомендуется)",
	'installation:minify_css:label' => "Сжимать CSS (рекомендуется)",

	'installation:htaccess:needs_upgrade' => "Вам необходимо обновить файл .htaccess (используйте install/config/htaccess.dist как руководство).",
	'installation:htaccess:localhost:connectionfailed' => "Elgg не может подключиться к себе для проверки правил перезаписи. Убедитесь, что curl работает и нет ограничений IP для localhost.",

	'installation:systemcache:description' => "Системный кэш уменьшает время загрузки Elgg за счет кэширования данных в файлы.",
	'installation:systemcache:label' => "Использовать системный кэш (рекомендуется)",

	'admin:legend:system' => 'Система',
	'admin:legend:caching' => 'Кэширование',
	'admin:legend:content' => 'Контент',
	'admin:legend:comments' => 'Комментарии',
	'admin:legend:content_access' => 'Доступ к контенту',
	'admin:legend:site_access' => 'Доступ к сайту',
	'admin:legend:debug' => 'Отладка и логирование',
	
	'config:i18n:allowed_languages' => "Разрешенные языки",
	'config:i18n:allowed_languages:help' => "Только разрешенные языки могут использоваться пользователями. Английский и язык сайта разрешены всегда.",
	'config:i18n:who_can_change_language' => "Кто может менять язык",
	'config:i18n:who_can_change_language:everyone' => "Все",
	'config:i18n:who_can_change_language:admin_only' => "Только администраторы",
	'config:i18n:who_can_change_language:nobody' => "Никто",
	
	'config:users:remove_unvalidated_users_days' => "Количество дней, после которого не проверенные пользователи будут удалены",
	'config:users:remove_unvalidated_users_days:help' => "Не проверенные пользователи будут автоматически удалены через указанное количество дней. Если оставить пустым, удаление не произойдет.",
	'config:users:can_change_username' => "Разрешить пользователям менять имя пользователя",
	'config:users:can_change_username:help' => "Если отключено, только админы могут менять имя пользователя",
	'config:users:user_joined_river' => "Добавлять активность в ленту при регистрации пользователя на сайте",
	'config:remove_branding:label' => "Убрать брендинг Elgg",
	'config:remove_branding:help' => "На сайте есть различные ссылки и логотипы, указывающие, что сайт работает на Elgg. Если вы убираете брендинг, рассмотрите возможность поддержки на https://elgg.org/about/supporters",
	'config:disable_rss:label' => "Отключить RSS-ленты",
	'config:disable_rss:help' => "Отключите, чтобы больше не рекламировать наличие RSS-лент",
	'config:friendly_time_number_of_days:label' => "Количество дней отображения дружелюбного времени",
	'config:friendly_time_number_of_days:help' => "Настройте, сколько дней используется дружелюбный формат времени. После этого периода он сменится на обычный. Установите 0 для отключения.",
	'config:content:comment_box_collapses' => "Форма комментария сворачивается после первого комментария к контенту",
	'config:content:comment_box_collapses:help' => "Применяется только если комментарии сортируются от новых к старым",
	'config:content:comments_group_only' => "Только участники группы могут комментировать контент группы",
	'config:content:comments_latest_first' => "Комментарии должны отображаться от новых к старым",
	'config:content:comments_latest_first:help' => "Контролирует поведение по умолчанию при отображении комментариев на странице контента. Если отключено, форма комментария переместится в конец списка",
	'config:content:comments_max_depth' => "Максимальная глубина вложенных комментариев",
	'config:content:comments_max_depth:help' => "При включении можно отвечать на комментарии до указанной глубины.",
	'config:content:comments_max_depth:none' => "Вложенные комментарии запрещены",
	'config:content:comments_per_page' => "Количество комментариев на страницу",
	'config:content:pagination_behaviour' => "Поведение пагинации списков по умолчанию",
	'config:content:pagination_behaviour:help' => "Контролирует, как обновляются данные списка при использовании пагинации. Отдельные списки могут переопределять это поведение.",
	'config:content:pagination_behaviour:navigate' => "Переход на следующую страницу",
	'config:content:pagination_behaviour:ajax-replace' => "Замена данных списка без перезагрузки страницы",
	'config:content:pagination_behaviour:ajax-append' => "Добавление новых данных списка до или после текущего",
	'config:content:pagination_behaviour:ajax-append-auto' => "Автоматическое добавление новых данных списка при прокрутке",
	'config:content:mentions_display_format' => "Формат отображения упоминаний",
	'config:content:mentions_display_format:help' => "Определяет, как будет отображаться упомянутый пользователь в контенте",
	'config:content:mentions_display_format:username' => "Имя пользователя",
	'config:content:mentions_display_format:display_name' => "Отображаемое имя",
	'config:content:trash_enabled:label' => "Включить корзину",
	'config:content:trash_enabled:help' => "При удалении элемент перемещается в корзину перед окончательным удалением. Элементы можно восстановить.",
	'config:content:trash_retention:label' => "Количество дней хранения контента в корзине",
	'config:content:trash_retention:help' => "Настройте, сколько дней удаленные сущности хранятся в корзине. По истечении срока они удаляются навсегда. Используйте 0 для бессрочного хранения.",
	'config:email' => "Email",
	'config:email_html_part:label' => "Включить HTML-письма",
	'config:email_html_part:help' => "Исходящие письма будут оборачиваться в HTML-шаблон",
	'config:email_html_part_images:label' => "Обработка изображений в письмах",
	'config:email_html_part_images:help' => "Контролируйте, как обрабатываются изображения в исходящих письмах. При включении все изображения встраиваются. Не все клиенты поддерживают разные опции, тестируйте выбранную.",
	'config:email_html_part_images:base64' => "Кодирование Base64",
	'config:email_html_part_images:attach' => "Вложения",
	'config:delayed_email:label' => "Включить отложенные email-уведомления",
	'config:delayed_email:help' => "Предлагать пользователям отложенные уведомления для объединения писем за период (ежедневно, еженедельно)",
	'config:message_delay:label' => "Задержка системных сообщений",
	'config:message_delay:help' => "Количество секунд до исчезновения сообщения об успехе по умолчанию",
	'config:color_schemes_enabled:label' => "Включенные цветовые схемы",
	'config:color_schemes_enabled:help' => "В зависимости от предпочтений пользователя или браузера тему можно переключать на разные схемы, например темную",

	'upgrading' => 'Обновление...',
	'upgrade:core' => 'Ваша установка Elgg обновлена.',
	'upgrade:unlock' => 'Разблокировать обновление',
	'upgrade:unlock:confirm' => "БД заблокирована другим обновлением. Параллельные обновления опасны. Продолжайте только если уверены, что другое обновление не запущено. Разблокировать?",
	'upgrade:terminated' => 'Обновление прервано обработчиком событий',
	'upgrade:locked' => "Невозможно обновить. Запущено другое обновление. Чтобы снять блокировку, перейдите в раздел Администрирования.",
	'upgrade:unlock:success' => "Обновление успешно разблокировано.",

	'admin:pending_upgrades' => 'На сайте есть ожидающие обновления, требующие немедленного внимания.',
	'admin:view_upgrades' => 'Просмотреть ожидающие обновления.',
	'item:object:elgg_upgrade' => 'Обновление сайта',
	'collection:object:elgg_upgrade' => 'Обновления сайта',
	'admin:upgrades:none' => 'Ваша установка обновлена!',

	'upgrade:success_count' => 'Обновлено:',
	'upgrade:error_count' => 'Ошибки: %s',
	'upgrade:finished' => 'Обновление завершено',
	'upgrade:finished_with_errors' => '<p>Обновление завершено с ошибками. Обновите страницу и попробуйте запустить обновление снова.</p></p><br />Если ошибка повторяется, проверьте лог ошибок сервера. За помощью обращайтесь в <a href="http://community.elgg.org/groups/profile/179063/elgg-technical-support">группу техподдержки</a> сообщества Elgg.</p>',
	'upgrade:should_be_skipped' => 'Нет элементов для обновления',
	'upgrade:count_items' => '%d элементов для обновления',
	
	// Strings specific for the database guid columns reply upgrade
	'admin:upgrades:database_guid_columns' => 'Выравнивание столбцов GUID в БД',
	
/**
 * Welcome
 */

	'welcome' => "Добро пожаловать",
	'welcome:user' => 'Добро пожаловать, %s',

/**
 * Emails
 */

	'email:from' => 'От',
	'email:to' => 'Кому',
	'email:subject' => 'Тема',
	'email:body' => 'Текст',

	'email:settings' => "Настройки email",
	'email:address:label' => "Адрес электронной почты",
	'email:address:help:confirm' => "Ожидает смены email на '%s', проверьте входящие для инструкций.",
	'email:address:password' => "Пароль",
	'email:address:password:help' => "Для смены email необходимо ввести текущий пароль.",

	'email:save:success' => "Новый email сохранен.",
	'email:save:fail' => "Не удалось сохранить новый email.",
	'email:save:fail:password' => "Пароль не совпадает с текущим, не удалось изменить email",

	'friend:newfriend:subject' => "%s добавил вас в друзья!",
	'friend:newfriend:body' => "%s добавил вас в друзья!
Чтобы посмотреть его профиль, нажмите здесь:
%s",

	'email:changepassword:subject' => "Пароль изменен!",
	'email:changepassword:body' => "Ваш пароль был изменен.",

	'email:resetpassword:subject' => "Пароль сброшен!",
	'email:resetpassword:body' => "Ваш пароль сброшен на: %s",

	'email:changereq:subject' => "Запрос на смену пароля.",
	'email:changereq:body' => "Кто-то (с IP-адреса %s) запросил смену пароля для этого аккаунта.
Если это вы, нажмите на ссылку ниже. В противном случае проигнорируйте это письмо.
%s",
	
	'account:email:request:success' => "Новый email будет сохранен после подтверждения, проверьте входящие '%s' для инструкций.",
	'email:request:email:subject' => "Подтвердите ваш адрес электронной почты",
	'email:request:email:body' => "Вы запросили смену email на '%s'.
Если вы не делали этого запроса, проигнорируйте письмо.
Для подтверждения смены email перейдите по ссылке:
%s
Ссылка действительна в течение 1 часа.",
	
	'account:email:request:error:no_new_email' => "Ожидание смены email не найдено",
	
	'email:confirm:email:old:subject' => "Ваш адрес электронной почты был изменен",
	'email:confirm:email:old:body' => "Ваш email на '%s' был изменен.
Теперь уведомления будут приходить на '%s'.
Если вы не делали этого изменения, свяжитесь с администратором сайта.
%s",
	
	'email:confirm:email:new:subject' => "Ваш адрес электронной почты был изменен",
	'email:confirm:email:new:body' => "Ваш email на '%s' был изменен.
Теперь уведомления будут приходить на этот email.
Если вы не делали этого изменения, свяжитесь с администратором сайта.
%s",

	'account:email:admin:validation_notification' => "Уведомлять меня о пользователях, ожидающих проверки администратором",
	'account:email:admin:validation_notification:help' => "Из-за настроек сайта новые пользователи требуют ручной проверки. С помощью этой настройки вы можете отключить уведомления об ожидающих проверках.",
	
	'account:validation:pending:title' => "Аккаунт ожидает проверки",
	'account:validation:pending:content' => "Ваш аккаунт успешно зарегистрирован! Однако перед использованием администратор сайта должен его проверить. Вы получите письмо после проверки.",

/**
 * user default access
 */

	'default_access:settings' => "Ваш уровень доступа по умолчанию",
	'default_access:label' => "Доступ по умолчанию",
	'user:default_access:success' => "Новый уровень доступа по умолчанию сохранен.",

/**
 * Comments
 */

	'comments:count' => "%s комментариев",
	'item:object:comment' => 'Комментарий',
	'collection:object:comment' => 'Комментарии',
	'notification:object:comment:create' => "Отправлять уведомление при создании комментария",

	'river:object:default:comment' => '%s прокомментировал %s',

	'generic_comments:add' => "Оставить комментарий",
	'generic_comments:edit' => "Редактировать комментарий",
	'generic_comments:latest' => "Последние комментарии",
	'generic_comment:login_required' => "Вы должны войти, чтобы комментировать.",
	'generic_comment:posted' => "Ваш комментарий успешно опубликован.",
	'generic_comment:updated' => "Комментарий успешно обновлен.",
	'entity:delete:object:comment:success' => "Комментарий успешно удален.",
	'generic_comment:blank' => "Извините, вы должны что-то написать, прежде чем мы сможем сохранить комментарий.",
	'generic_comment:notfound' => "Извините, указанный комментарий не найден.",
	'generic_comment:failure' => "Произошла непредвиденная ошибка при сохранении комментария.",
	'generic_comment:none' => 'Нет комментариев',
	'generic_comment:on' => '%s на %s',
	'generic_comment:by_owner' => 'Комментарий владельца',

	'generic_comment:notification:subject' => 'Re: %s',
	'generic_comment:notification:owner:summary' => 'У вас новый комментарий к: %s',
	'generic_comment:notification:owner:body' => "У вас новый комментарий. Текст:
%s
Чтобы ответить или просмотреть оригинал, нажмите здесь:
%s",
	
	'generic_comment:notification:user:summary' => 'Новый комментарий к: %s',
	'generic_comment:notification:user:body' => "Оставлен новый комментарий. Текст:
%s
Чтобы ответить или просмотреть оригинал, нажмите здесь:
%s",

	'notification:mentions:object:comment:subject' => '%s упомянул вас в комментарии',
	'notification:mentions:object:comment:body' => '%1$s упомянул вас в комментарии.
Чтобы увидеть полный комментарий, перейдите по ссылке:
%3$s',

/**
 * Entities
 */

	'byline' => 'Автор: %s',
	'byline:ingroup' => 'в группе %s',
	
	'entity:delete:item' => 'Элемент',
	'entity:delete:item_not_found' => 'Элемент не найден.',
	'entity:delete:permission_denied' => 'У вас нет прав на удаление этого элемента.',
	'entity:delete:success' => '%s успешно удален.',
	'entity:delete:fail' => 'Не удалось удалить %s.',

	'entity:edit:success' => 'Сущность успешно сохранена',
	'entity:edit:group:success' => 'Группа успешно сохранена',
	'entity:edit:object:success' => 'Объект успешно сохранен',
	'entity:edit:user:success' => 'Пользователь успешно сохранен',
	
	'entity:restore:item' => 'Элемент',
	'entity:restore:item_not_found' => 'Элемент не найден',
	'entity:restore:container_permission' => 'У вас нет прав на восстановление этого элемента в %s',
	'entity:restore:permission_denied' => 'У вас нет прав на восстановление этого элемента',
	'entity:restore:success' => '%s успешно восстановлен',
	'entity:restore:fail' => 'Не удалось восстановить %s',
	
	'entity:subscribe' => "Подписаться",
	'entity:subscribe:disabled' => "Ваши настройки уведомлений по умолчанию не позволяют подписываться на этот контент",
	'entity:subscribe:success' => "Вы успешно подписались на %s",
	'entity:subscribe:fail' => "Произошла ошибка при подписке на %s",
	
	'entity:unsubscribe' => "Отписаться",
	'entity:unsubscribe:success' => "Вы успешно отписались от %s",
	'entity:unsubscribe:fail' => "Произошла ошибка при отписке от %s",
	
	'entity:mute' => "Отключить уведомления",
	'entity:mute:success' => "Вы успешно отключили уведомления для %s",
	'entity:mute:fail' => "Произошла ошибка при отключении уведомлений для %s",
	
	'entity:unmute' => "Включить уведомления",
	'entity:unmute:success' => "Вы успешно включили уведомления для %s",
	'entity:unmute:fail' => "Произошла ошибка при включении уведомлений для %s",


/**
 * Annotations
 */
	
	'annotation:delete:fail' => "Произошла ошибка при удалении аннотации",
	'annotation:delete:success' => "Аннотация успешно удалена",
	
/**
 * Action gatekeeper
 */

	'actiongatekeeper:missingfields' => 'В форме отсутствуют поля __token или __ts',
	'actiongatekeeper:tokeninvalid' => "Страница, которую вы использовали, истекла. Попробуйте снова.",
	'actiongatekeeper:timeerror' => 'Страница, которую вы использовали, истекла. Обновите и попробуйте снова.',
	'actiongatekeeper:pluginprevents' => 'Извините. Ваша форма не может быть отправлена по неизвестной причине.',
	'actiongatekeeper:uploadexceeded' => 'Размер загруженных файлов превысил лимит, установленный администратором сайта',

/**
 * Javascript
 */

	'js:security:token_refresh_failed' => 'Не удалось связаться с %s. Возможны проблемы с сохранением контента. Пожалуйста, обновите страницу.',
	'js:lightbox:current' => "изображение %s из %s",

/**
 * Diagnostics
 */
	'diagnostics:report' => 'Диагностический отчет',
	'diagnostics:header' => '========================================================================
Диагностический отчет Elgg
Сгенерирован %s пользователем %s
========================================================================
',
	'diagnostics:report:basic' => '
Версия Elgg %s
------------------------------------------------------------------------',
	'diagnostics:report:php' => '
Информация PHP:
%s
------------------------------------------------------------------------',
	'diagnostics:report:md5' => '
Установленные файлы и контрольные суммы:
%s
------------------------------------------------------------------------',
	'diagnostics:report:globals' => '
Глобальные переменные:
%s
------------------------------------------------------------------------',
	
/**
 * Trash
 */
	'trash:menu:page' => "Корзина",
	
	'trash:imprint:actor' => "Удалено: %s",
	'trash:imprint:type' => "Тип: %s",
	
	'trash:owner:title' => "Корзина",
	'trash:owner:title_owner' => "Корзина %s",
	'trash:group:title' => "Корзина %s",
	
	'trash:no_results' => "В корзине нет элементов",
	
	'trash:notice:retention' => "Элементы в корзине будут автоматически удалены через %s дн.",
	
	'trash:restore:container:owner' => "Вы можете восстановить этот элемент в личный раздел, так как исходная группа также была удалена.",
	'trash:restore:container:choose' => "Так как исходная группа для этого элемента удалена, вы можете выбрать место восстановления.",
	'trash:restore:container:group' => "Восстановить в другую группу",
	'trash:restore:group' => "Поиск группы",
	'trash:restore:group:help' => "Убедитесь, что в выбранной группе активна функция для этого элемента, иначе может возникнуть ошибка.",
	'trash:restore:owner' => "Восстановить владельцу (%s)",

/**
 * Color schemes
 */
	'color_scheme:default' => "По умолчанию (Светлая)",
	'color_scheme:dark' => "Темная",

/**
 * Miscellaneous
 */
	'elgg:powered' => "Работает на Elgg",
	'field:required' => "Обязательно",

/**
 * Accessibility
 */
	'aria:label:admin:users:search' => "Поиск пользователей",

	'menu:admin_footer:header' => "Подвал админки",
	'menu:admin_header:header' => "Шапка админки",
	'menu:admin:users:bulk:header' => "Массовые действия с пользователями",
	'menu:annotation:header' => "Аннотация",
	'menu:breadcrumbs:header' => "Хлебные крошки",
	'menu:comments:header' => "Комментарии",
	'menu:entity:header' => "Сущность",
	'menu:entity_navigation:header' => "Навигация по сущности",
	'menu:filter:header' => "Фильтр",
	'menu:footer:header' => "Подвал",
	'menu:login:header' => "Вход",
	'menu:owner_block:header' => "Блок владельца",
	'menu:page:header' => "Страница",
	'menu:relationship:header' => "Отношение",
	'menu:river:header' => "Лента активности",
	'menu:site:header' => "Сайт",
	'menu:social:header' => "Социальное",
	'menu:title:header' => "Заголовок",
	'menu:title:widgets:header' => "Управление виджетами",
	'menu:topbar:header' => "Верхняя панель",
	'menu:user_hover:header' => "Наведение на пользователя",
	'menu:user:unvalidated:header' => "Не проверенный пользователь",
	'menu:walled_garden:header' => "Закрытый сад",
	'menu:widget:header' => "Управление виджетом",

/**
 * Cli commands
 */
	'cli:login:success:log' => "Вошли как %s [guid: %s]",
	'cli:response:output' => "Ответ:",
	'cli:option:as' => "Выполнить команду от имени пользователя с указанным именем",
	'cli:option:language' => "Выполнить команду на указанном языке (например, en, nl или de)",
	
	'cli:cache:clear:description' => "Очистить кэши Elgg",
	'cli:cache:invalidate:description' => "Инвалидировать кэши Elgg",
	'cli:cache:purge:description' => "Удалить кэши Elgg",
	
	'cli:cron:description' => "Выполнить обработчики cron для всех или указанного интервала",
	'cli:cron:option:interval' => "Название интервала (например, hourly)",
	'cli:cron:option:force' => "Принудительно запустить cron, даже если он еще не должен выполняться",
	'cli:cron:option:time' => "Время инициализации cron",
	
	'cli:database:seed:description' => "Заполнить БД тестовыми сущностями",
	'cli:database:seed:argument:create' => "Всегда создавать новые сущности при заполнении",
	'cli:database:seed:option:limit' => "Количество сущностей для заполнения",
	'cli:database:seed:option:image_folder' => "Путь к локальной папке с изображениями для заполнения",
	'cli:database:seed:option:type' => "Тип сущностей для (раз)заполнения (%s)",
	'cli:database:seed:option:create_since' => "Строка времени PHP для установки нижней границы времени создания сущностей",
	'cli:database:seed:option:create_until' => "Строка времени PHP для установки верхней границы времени создания сущностей",
	'cli:database:seed:log:error:faker' => "Это инструмент разработчика, предназначенный только для тестирования. Пожалуйста, не используйте его в рабочей среде.",
	'cli:database:seed:log:error:logged_in' => "Заполнение БД не должно выполняться при авторизованном пользователе",
	'cli:database:seed:ask:limit' => "Сколько элементов заполнить для '%s'",

	'cli:database:seeders:description' => "Список всех доступных заполнителей БД с текущим количеством созданных сущностей",
	'cli:database:seeders:handler' => "Обработчик заполнения",
	'cli:database:seeders:type' => "Тип заполнения",
	'cli:database:seeders:count' => "Создано",
	
	'cli:database:unseed:description' => "Удалить тестовые сущности из БД",
	
	'cli:plugins:activate:description' => "Активировать плагин(ы)",
	'cli:plugins:activate:option:force' => "Разрешить конфликты, деактивируя конфликтующие плагины и включая необходимые",
	'cli:plugins:activate:argument:plugins' => "ID плагинов для активации",
	'cli:plugins:activate:progress:start' => "Активация плагинов",
	
	'cli:plugins:deactivate:description' => "Деактивировать плагин(ы)",
	'cli:plugins:deactivate:option:force' => "Принудительно деактивировать все зависимые плагины",
	'cli:plugins:deactivate:argument:plugins' => "ID плагинов для деактивации",
	'cli:plugins:deactivate:progress:start' => "Деактивация плагинов",
	
	'cli:plugins:list:description' => "Список всех установленных плагинов",
	'cli:plugins:list:option:status' => "Статус плагина (%s)",
	'cli:plugins:list:option:refresh' => "Обновить список плагинов с недавно установленными",
	'cli:plugins:list:error:status' => "%s не является допустимым статусом. Допустимые варианты: %s",
	
	'cli:upgrade:description' => "Запустить системные обновления",
	'cli:upgrade:option:force' => "Принудительно запустить обновления, даже если обновление уже выполняется.",
	'cli:upgrade:argument:async' => "Выполнить ожидающие асинхронные обновления",
	'cli:upgrade:system:upgraded' => "Системные обновления выполнены",
	'cli:upgrade:system:failed' => "Системные обновления завершились ошибкой",
	'cli:upgrade:async:upgraded' => "Асинхронные обновления выполнены",
	'cli:upgrade:aysnc:failed' => "Асинхронные обновления завершились ошибкой",
	
	'cli:upgrade:batch:description' => "Выполняет одно или несколько обновлений",
	'cli:upgrade:batch:argument:upgrades' => "Одно или несколько обновлений (имена классов) для выполнения",
	'cli:upgrade:batch:option:force' => "Запустить обновление, даже если оно уже было выполнено",
	'cli:upgrade:batch:finished' => "Запуск обновлений завершен",
	'cli:upgrade:batch:notfound' => "Класс обновления для %s не найден",

	'cli:upgrade:list:description' => "Список всех обновлений в системе",
	'cli:upgrade:list:completed' => "Завершенные обновления",
	'cli:upgrade:list:pending' => "Ожидающие обновления",
	'cli:upgrade:list:notfound' => "Обновления не найдены",
	
/**
 * Languages according to ISO 639-1 (with a couple of exceptions)
 */

	"aa" => "Афар",
	"ab" => "Абхазский",
	"af" => "Африкаанс",
	"am" => "Амхарский",
	"ar" => "Арабский",
	"as" => "Ассамский",
	"ay" => "Аймара",
	"az" => "Азербайджанский",
	"ba" => "Башкирский",
	"be" => "Белорусский",
	"bg" => "Болгарский",
	"bh" => "Бихари",
	"bi" => "Бислама",
	"bn" => "Бенгальский / Бангла",
	"bo" => "Тибетский",
	"br" => "Бретонский",
	"ca" => "Каталонский",
	"cmn" => "Китайский (мандарин)", // ISO 639-3
	"co" => "Корсиканский",
	"cs" => "Чешский",
	"cy" => "Валлийский",
	"da" => "Датский",
	"de" => "Немецкий",
	"dz" => "Дзонг-кэ",
	"el" => "Греческий",
	"en" => "Английский",
	"eo" => "Эсперанто",
	"es" => "Испанский",
	"et" => "Эстонский",
	"eu" => "Баскский",
	"eu_es" => "Баскский (Испания)",
	"fa" => "Персидский",
	"fi" => "Финский",
	"fj" => "Фиджи",
	"fo" => "Фарерский",
	"fr" => "Французский",
	"fy" => "Фризский",
	"ga" => "Ирландский",
	"gd" => "Шотландский гэльский",
	"gl" => "Галисийский",
	"gn" => "Гуарани",
	"gu" => "Гуджарати",
	"he" => "Иврит",
	"ha" => "Хауса",
	"hi" => "Хинди",
	"hr" => "Хорватский",
	"hu" => "Венгерский",
	"hy" => "Армянский",
	"ia" => "Интерлингва",
	"id" => "Индонезийский",
	"ie" => "Интерлингве",
	"ik" => "Инупиак",
	"is" => "Исландский",
	"it" => "Итальянский",
	"iu" => "Инуктитут",
	"iw" => "Иврит (устар.)",
	"ja" => "Японский",
	"ji" => "Идиш (устар.)",
	"jw" => "Яванский",
	"ka" => "Грузинский",
	"kk" => "Казахский",
	"kl" => "Гренландский",
	"km" => "Кхмерский",
	"kn" => "Каннада",
	"ko" => "Корейский",
	"ks" => "Кашмири",
	"ku" => "Курдский",
	"ky" => "Киргизский",
	"la" => "Латинский",
	"ln" => "Лингала",
	"lo" => "Лаосский",
	"lt" => "Литовский",
	"lv" => "Латышский",
	"mg" => "Малагасийский",
	"mi" => "Маори",
	"mk" => "Македонский",
	"ml" => "Малаялам",
	"mn" => "Монгольский",
	"mo" => "Молдавский",
	"mr" => "Маратхи",
	"ms" => "Малайский",
	"mt" => "Мальтийский",
	"my" => "Бирманский",
	"na" => "Науру",
	"ne" => "Непальский",
	"nl" => "Голландский",
	"no" => "Норвежский",
	"oc" => "Окситанский",
	"om" => "Оромо",
	"or" => "Ория",
	"pa" => "Панджаби",
	"pl" => "Польский",
	"ps" => "Пушту",
	"pt" => "Португальский",
	"pt_br" => "Португальский (Бразилия)",
	"qu" => "Кечуа",
	"rm" => "Романшский",
	"rn" => "Кирунди",
	"ro" => "Румынский",
	"ro_ro" => "Румынский (Румыния)",
	"ru" => "Русский",
	"rw" => "Киньяруанда",
	"sa" => "Санскрит",
	"sd" => "Синдхи",
	"sg" => "Санго",
	"sh" => "Сербохорватский",
	"si" => "Сингальский",
	"sk" => "Словацкий",
	"sl" => "Словенский",
	"sm" => "Самоанский",
	"sn" => "Шона",
	"so" => "Сомалийский",
	"sq" => "Албанский",
	"sr" => "Сербский",
	"sr_latin" => "Сербский (лат.)",
	"ss" => "Свати",
	"st" => "Сото",
	"su" => "Сунданский",
	"sv" => "Шведский",
	"sw" => "Суахили",
	"ta" => "Тамильский",
	"te" => "Телугу",
	"tg" => "Таджикский",
	"th" => "Тайский",
	"ti" => "Тигринья",
	"tk" => "Туркменский",
	"tl" => "Тагальский",
	"tn" => "Тсвана",
	"to" => "Тонга",
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
	//"y" => "Идиш",
	"yi" => "Идиш",
	"yo" => "Йоруба",
	"za" => "Чжуан",
	"zh" => "Китайский",
	"zh_hans" => "Китайский упрощенный",
	"zu" => "Зулусский",

/**
 * Upgrades
 */
	"core:upgrade:2017080900:title" => "Изменение кодировки БД для поддержки многобайтовых символов",
	"core:upgrade:2017080900:description" => "Изменяет кодировку БД и таблиц на utf8mb4 для поддержки многобайтовых символов, таких как эмодзи",
	
	"core:upgrade:2020102301:title" => "Удаление плагина diagnostics",
	"core:upgrade:2020102301:description" => "Удаляет сущность, связанную с плагином Diagnostics, удаленным в Elgg 4.0",
	
	"core:upgrade:2021022401:title" => "Миграция подписок на уведомления",
	"core:upgrade:2021022401:description" => "Подписки на уведомления хранятся в БД по-новому. Используйте это обновление для миграции всех подписок.",
	
	"core:upgrade:2021040701:title" => "Миграция настроек уведомлений пользователей",
	"core:upgrade:2021040701:description" => "Для более удобного хранения настроек уведомлений требуется миграция на новые соглашения об именовании.",
	
	'core:upgrade:2021040801:title' => "Миграция предпочтений уведомлений коллекций доступа",
	'core:upgrade:2021040801:description' => "Введен новый способ хранения предпочтений уведомлений. Это обновление мигрирует старые настройки.",
	
	'core:upgrade:2021041901:title' => "Удаление плагина notifications",
	'core:upgrade:2021041901:description' => "Удаляет сущность, связанную с плагином Notifications, удаленным в Elgg 4.0",
	
	'core:upgrade:2021060401:title' => "Добавление владельцев контента в подписчиков",
	'core:upgrade:2021060401:description' => "Владельцы контента должны быть подписаны на свой контент, это обновление мигрирует весь старый контент.",
	
	'core:upgrade:2023011701:title' => "Удаление осиротевших вложенных комментариев",
	'core:upgrade:2023011701:description' => "Из-за ошибки в удалении вложенных комментариев могли создаваться сироты, это обновление удалит их.",
	
	'core:upgrade:2024020101:title' => "Миграция координат обрезки иконок",
	'core:upgrade:2024020101:description' => "Координаты обрезки хранятся единообразно, это обновление мигрирует старые метаданные x1, x2, y1 и y2",

	'core:upgrade:2024020901:title' => "Удаление метаданных icontime",
	'core:upgrade:2024020901:description' => "Удаляет ненадежные метаданные icontime из базы данных",

	'core:upgrade:2024070201:title' => "Миграция конфигурации отладки",
	'core:upgrade:2024070201:description' => "Изменяет значение конфигурации отладки в БД на поддерживаемое",

	'core:upgrade:2024071001:title' => "Миграция предпочтений уведомлений о проверке админом",
	'core:upgrade:2024071001:description' => "Перемещает хранение предпочтений уведомлений админа в настройки уведомлений",

	'core:upgrade:2025060201:title' => "Хранение корректных значений БД для булевых типов",
	'core:upgrade:2025060201:description' => "В таблицах аннотаций и метаданных значение ложного булевого типа сохранялось некорректно",
);
