<?php
return array(
/**
 * Sites
 */

	'item:site' => 'Сайты',

/**
 * Sessions
 */

	'login' => "Войти",
	'loginok' => "Добро пожаловать!",
	'loginerror' => "Пароль или имя не найдены. Вход невозможен.",
	'login:empty' => "Имя пользователя и пароль не требуется.",
	'login:baduser' => "Не удается загрузить учетную запись.",
	'auth:nopams' => "Внутренняя ошибка.Метод аутентификации пользователей не установлен.",

	'logout' => "Выйти",
	'logoutok' => "Вы вышли.",
	'logouterror' => "Не удалось выйти. Пожалуйста, повторите попытку.",
	'session_expired' => "Время вашей сессии истекло. Пожалуйста, перезагрузите страницу чтобы войти.",

	'loggedinrequired' => "Извините, страница недоступна незарегистрированным посетителям.",
	'adminrequired' => "Извините, страница доступна только администраторам.",
	'membershiprequired' => "Вы должны быть членом этой группы, чтобы посмотреть эту страницу.",
	'limited_access' => "У вас нет полномочий для просмотра запрошенной страницы.",


/**
 * Errors
 */

	'exception:title' => "Критическая ошибка.",
	'exception:contact_admin' => 'Произошла неустранимая ошибка, которая была залогирована. Свяжитесь с администратором сайта используя эти логи.',

	'actionundefined' => "Запрошенное действие (%s) не определено.",
	'actionnotfound' => "Действие файла %s не найдено.",
	'actionloggedout' => "Извините, но Вы не можете выполнить это действие, необходим вход.",
	'actionunauthorized' => 'Вы не авторизованы.',
	
	'ajax:error' => 'Произошла непредвиденная ошибка во время вызова AJAX. Пожалуства изучите википедия по Elgg для нахождения возможных причин возникновения ошибки. ',
	'ajax:not_is_xhr' => 'You cannot access AJAX views directly',

	'PluginException:MisconfiguredPlugin' => "%s (guid: %s) этот плагин настроен неправильно, и будет отключен. Пожалуйста, ищите решения на информационных ресурсах ELGG проекта (http://learn.elgg.org/).",
	'PluginException:CannotStart' => '%s (guid: %s) не может запуститься.  Причина: %s',
	'PluginException:InvalidID' => "%s неверный ID плагина.",
	'PluginException:InvalidPath' => "%s неверный путь до плагина.",
	'PluginException:InvalidManifest' => 'Неправильный manigest в плагине %s',
	'PluginException:InvalidPlugin' => '%s неправильный плагин.',
	'PluginException:InvalidPlugin:Details' => '%s неправильный плагин: %s',
	'PluginException:NullInstantiated' => 'Плагин Elgg нельзя инстанцировать таким образом. Укажите GUID, ID плагина или полный путь.',
	'ElggPlugin:MissingID' => 'Отсутствует ID плагина (guid %s)',
	'ElggPlugin:NoPluginPackagePackage' => 'Отсутствует УпаковщикПлагиновДвижка для ID плагина %s (guid %s)',
	'ElggPluginPackage:InvalidPlugin:MissingFile' => 'Отсутствует файл %s в пакете',
	'ElggPluginPackage:InvalidPlugin:InvalidId' => 'Папка этого плагина должна быть переименована в "%s", чтобы соответствовать ID в его манифесте.',
	'ElggPluginPackage:InvalidPlugin:InvalidDependency' => 'Недопустимый тип зависимости "%s"',
	'ElggPluginPackage:InvalidPlugin:InvalidProvides' => 'Недопустимый тип предоставляет "%s"',
	'ElggPluginPackage:InvalidPlugin:CircularDep' => 'Недопустимая %s зависимость "%s" в плагине %s.',
	'ElggPlugin:Exception:CannotIncludeFile' => 'Не могу подключить %s в плагине %s (guid: %s) at %s.  Проверьте права!',
	'ElggPlugin:Exception:CannotRegisterViews' => 'Не могу открыть папку views в плагине %s (guid: %s) at %s.  Проверьте права!',
	'ElggPlugin:Exception:CannotRegisterLanguages' => 'Не могу зарегистрировать язык в плагине %s (guid: %s) at %s.  Проверьте права!',
	'ElggPlugin:Exception:NoID' => 'Нет ID для плагина %s!',
	'PluginException:NoPluginName' => "Имя плагина не найдено",
	'PluginException:ParserError' => 'Ошибка при анализе manifest с API функциями %s в плагине %s.',
	'PluginException:NoAvailableParser' => 'Не могу найти parser для манифеста API %s в плагине %s.',
	'PluginException:ParserErrorMissingRequiredAttribute' => "Отсутствуют необходимые '%s' атрибута в манифесте плагина %s.",
	'ElggPlugin:InvalidAndDeactivated' => '%s недействительный плагин.',

	'ElggPlugin:Dependencies:Requires' => 'Требует',
	'ElggPlugin:Dependencies:Suggests' => 'Предлагает',
	'ElggPlugin:Dependencies:Conflicts' => 'Конфликты',
	'ElggPlugin:Dependencies:Conflicted' => 'Конфликтующее',
	'ElggPlugin:Dependencies:Provides' => 'Обеспечивает',
	'ElggPlugin:Dependencies:Priority' => 'Приоритет',

	'ElggPlugin:Dependencies:Elgg' => 'Версия движка',
	'ElggPlugin:Dependencies:PhpVersion' => 'Версия PHP',
	'ElggPlugin:Dependencies:PhpExtension' => 'PHP расширение: %s',
	'ElggPlugin:Dependencies:PhpIni' => 'PHP настройки: %s',
	'ElggPlugin:Dependencies:Plugin' => 'Плагин: %s',
	'ElggPlugin:Dependencies:Priority:After' => 'После %s',
	'ElggPlugin:Dependencies:Priority:Before' => 'До %s',
	'ElggPlugin:Dependencies:Priority:Uninstalled' => '%s не установлено',
	'ElggPlugin:Dependencies:Suggests:Unsatisfied' => 'Отсутствует',
	
	'ElggPlugin:Dependencies:ActiveDependent' => 'Существуют другие плагины которым для работы нужен %s. Вы сначала должны отключить эти плагины до того как отключить %s.',

	'ElggMenuBuilder:Trees:NoParents' => 'Найден пункт меню без родителя к которому он должен был быть привзяан.',
	'ElggMenuBuilder:Trees:OrphanedChild' => 'Найден пункт меню (%s) с пропущенными параметрами (%s).',
	'ElggMenuBuilder:Trees:DuplicateChild' => 'В меню найден дубликат записи [%s]',

	'RegistrationException:EmptyPassword' => 'Поле пароля не может быть пустым.',
	'RegistrationException:PasswordMismatch' => 'Пароли должны совпадать!',
	'LoginException:BannedUser' => 'Вы забанены и не можете войти!',
	'LoginException:UsernameFailure' => 'Ошибка авторизации. Проверьте имя пользователя и пароль.',
	'LoginException:PasswordFailure' => 'Ошибка авторизации. Проверьте имя пользователя и пароль.',
	'LoginException:AccountLocked' => 'Ваш акаунт заблокирован. Обратитесь к администрации.',
	'LoginException:ChangePasswordFailure' => 'Проверка текущего пароля не удалась.',
	'LoginException:Unknown' => 'Вход не удался из-за неизвестной ошибки.',

	'deprecatedfunction' => 'Внимание: этот код использует устаревшую функцию \'%s\' и несовместим с этой версией Движка',

	'pageownerunavailable' => 'Внимание: вледелец страницы %d недоступен!',
	'viewfailure' => 'Был внутренний сбой в представлении %s',
	'view:missing_param' => "Обязательный параметр '%s' отсутствует в %s",
	'changebookmark' => 'Измените вашу закладку для этой страницы',
	'noaccess' => 'Контент, который вы хотите посмотреть либо был удален, либо у вас нет на это прав.',
	'error:missing_data' => 'В запросе отсутствуют некоторые данные',
	'save:fail' => 'В процессе сохранения данных произошла ошибка',
	'save:success' => 'Ваши данные сохранены',

	'error:default:title' => 'Ой...',
	'error:default:content' => 'Ой... Что-то пошло не так.',
	'error:400:title' => 'Bad request',
	'error:400:content' => 'Sorry. The request is invalid or incomplete.',
	'error:403:title' => 'Forbidden',
	'error:403:content' => 'Sorry. You are not allowed to access the requested page.',
	'error:404:title' => 'Страница не найдена',
	'error:404:content' => 'Извините, но невозможно найти страницу, которую вы запрашиваете.',

	'upload:error:ini_size' => 'Файл, который вы пытаетесь загрузить, слишком велик.',
	'upload:error:form_size' => 'Файл, который вы пытаетесь загрузить, слишком велик.',
	'upload:error:partial' => 'Загрузка файла не была завершена.',
	'upload:error:no_file' => 'Файл не выбран ',
	'upload:error:no_tmp_dir' => 'Невозможно сохранить загруженный файл.',
	'upload:error:cant_write' => 'Невозможно сохранить загруженный файл.',
	'upload:error:extension' => 'Невозможно сохранить загруженный файл.',
	'upload:error:unknown' => 'Сбой загрузки файла',


/**
 * User details
 */

	'name' => "Отображаемое имя (Внимание, доступно публично!)",
	'email' => "Электронный адрес",
	'username' => "Логин",
	'loginusername' => "Логин или email",
	'password' => "Пароль",
	'passwordagain' => "Пароль (повтор для проверки)",
	'admin_option' => "Сделать этого пользователя администратором?",

/**
 * Access
 */

	'PRIVATE' => "Только себе",
	'LOGGED_IN' => "Зарегистрированным",
	'PUBLIC' => "ПУБЛИЧНЫЙ",
	'LOGGED_OUT' => "Вышедшие пользователи",
	'access:friends:label' => "Друзьям",
	'access' => "Доступ",
	'access:overridenotice' => "Примечание. Согласно политике группы к этому контенту смогут доступиться только члены группы.",
	'access:limited:label' => "Ограниченно",
	'access:help' => "Уровень доступа",
	'access:read' => "Доступ",
	'access:write' => "Доступ на запись",
	'access:admin_only' => "Только администраторы",
	'access:missing_name' => "Отсутствует название уровня доступа",
	'access:comments:change' => "Это обсуждение видят ограниченное количество пользователей. Вы можете его сделать более доступным другим людям.",

/**
 * Dashboard and widgets
 */

	'dashboard' => "Моя панель",
	'dashboard:nowidgets' => "Ваша информационная панель - шлюз к сайту. Нажмите 'Редактировать страницу' для добавления новых элементов, чтобы Вы были в курсе всего происходимого на сайте.",

	'widgets:add' => 'Добавить новые элементы на панель',
	'widgets:add:description' => "<b>Нажмите на элементы, которые Вы хотите добавить из Галереи элементов, переместите их туда где хотите их видеть. 
Для удаления элемента нажмите крестик в его окне.
Кстати, менять элементы можно хоть каждый день.</b>",
	'widgets:panel:close' => "Закрыть панель виджетов",
	'widgets:position:fixed' => '(увы, никак не передвинуть)',
	'widget:unavailable' => 'Вы уже добавили этот элемент',
	'widget:numbertodisplay' => 'Кол-во записей',

	'widget:delete' => 'Закрыть %s',
	'widget:edit' => 'Настройки элемента',

	'widgets' => "Элементы",
	'widget' => "Элемент",
	'item:object:widget' => "Элементы",
	'widgets:save:success' => "Элемент успешно сохранен.",
	'widgets:save:failure' => "Ошибка сохранения элемента. Попытайтесь снова.",
	'widgets:add:success' => "Элемент был добавлен",
	'widgets:add:failure' => "Не удалось добавить элемент",
	'widgets:move:failure' => "Не удается переместить элемент",
	'widgets:remove:failure' => "Не удается удалить элемент",

/**
 * Groups
 */

	'group' => "Группа",
	'item:group' => "Группы",

/**
 * Users
 */

	'user' => "Пользователь",
	'item:user' => "Пользователей",

/**
 * Friends
 */

	'friends' => "Друзья",
	'friends:yours' => "Ваши друзья",
	'friends:owned' => "Друзья пользователя %s",
	'friend:add' => "Добавить в друзья",
	'friend:remove' => "Убрать из друзей",

	'friends:add:successful' => "Вы успешно добавили пользователя %s в друзья.",
	'friends:add:failure' => "Не удается добавить пользователя %s в друзья. Пожалуйста повторите попытку.",

	'friends:remove:successful' => "Вы успешно удалили пользователя %s с Ваших друзей.",
	'friends:remove:failure' => "Не удается удалить пользователя %s с Ваших друзей. Пожалуйста, повторите попытку.",

	'friends:none' => "Этот пользователь еще никого не добавил в друзья.",
	'friends:none:you' => "Вы еще никого не добавили в друзья. Ищите пользователей с интересами, подобными Вашим, чтобы добавить их в друзья.",

	'friends:none:found' => "Друзей не найдено.",

	'friends:of:none' => "Никто еще не добавил этого пользователя в друзья.",
	'friends:of:none:you' => "Никто еще не добавил Вас в друзья. Добавляйте информацию, заполните Ваш профиль, чтобы другие пользователи нашли Вас.",

	'friends:of:owned' => "Чей друг",

	'friends:of' => "Чей друг",
	'friends:collections' => "Группы друзей",
	'collections:add' => "Новая группа",
	'friends:collections:add' => "Создать группу друзей",
	'friends:addfriends' => "Добавить друзей",
	'friends:collectionname' => "Название группы",
	'friends:collectionfriends' => "Друзей в группе",
	'friends:collectionedit' => "Редактировать эту группу",
	'friends:nocollections' => "У Вас еще нет групп.",
	'friends:collectiondeleted' => "Ваша группа удалена.",
	'friends:collectiondeletefailed' => "Не удается удалить группу. У Вас не прав доступа, или возникла другая ошибка.",
	'friends:collectionadded' => "Ваша группа успешно создана",
	'friends:nocollectionname' => "Вы должны назвать группу перед ее созданием.",
	'friends:collections:members' => "Пользователи в группе",
	'friends:collections:edit' => "Редактировать группу",
	'friends:collections:edited' => "Изменение сохранено.",
	'friends:collection:edit_failed' => 'Не удалось сохранить изменение :(',

	'friendspicker:chararray' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',

	'avatar' => 'Фото',
	'avatar:noaccess' => "Вам нельзя редактировать аватар этого пользователя",
	'avatar:create' => 'Сохранить изменения',
	'avatar:edit' => 'Редактировать аватар  ',
	'avatar:preview' => 'Просмотр миниатюры',
	'avatar:upload' => 'Загрузить новую фотографию',
	'avatar:current' => 'Текущия фотография',
	'avatar:remove' => 'Удалите свой аватар и установите иконку по умолчанию',
	'avatar:crop:title' => 'Редактирование миниатюры',
	'avatar:upload:instructions' => "Вы можете загрузить сюда собственную фотографию. Вы можете изменять её так часто, как хотелось бы. Поддерживаются форматы (GIF, JPG или PNG)",
	'avatar:create:instructions' => 'Выделите область на основной фотографии, которая будет отображаться в миниатюрах на сайте.Предварительный просмотр миниатюры появиться в окне справа.Когда вас устроит ваша миниатюра, нажмите внизу кнопку \'<b>Сохранить изменения</b>\'.Эта миниатюра будет использоваться по всему сайту в качестве аватара.',
	'avatar:upload:success' => 'Фотография загруженна :D',
	'avatar:upload:fail' => 'Неудалось загрузить фотографию =\\',
	'avatar:resize:fail' => 'Изменение размера миниатюры не удалось',
	'avatar:crop:success' => 'Успешное изменение миниатюры :D',
	'avatar:crop:fail' => 'Изменение миниатюры не удалось =\\',
	'avatar:remove:success' => 'Удаление аватара завершилось успешно.',
	'avatar:remove:fail' => 'Удаление аватара прошло с ошибкой',

	'profile:edit' => 'Редактировать профиль',
	'profile:aboutme' => "О себе",
	'profile:description' => "Напишите о себе :",
	'profile:briefdescription' => "Надпись под именем",
	'profile:location' => "Город",
	'profile:skills' => "Деятельность",
	'profile:interests' => "Интересы(вводить через запятую)",
	'profile:contactemail' => "Контактный email",
	'profile:phone' => "Домашний телефон",
	'profile:mobile' => "Мобильный телефон",
	'profile:website' => "Личный сайт",
	'profile:twitter' => "Twitter",
	'profile:saved' => "Ваши изменения были успешно сохранены.",

	'profile:field:text' => 'Короткий текст',
	'profile:field:longtext' => 'Область большого текста',
	'profile:field:tags' => 'Интересы',
	'profile:field:url' => 'Web адрес',
	'profile:field:email' => 'Электронный адрес',
	'profile:field:location' => 'Директория',
	'profile:field:date' => 'Дата',

	'admin:appearance:profile_fields' => 'Редактирование Профиля',
	'profile:edit:default' => 'Сбросить профиль?',
	'profile:label' => "Название строки",
	'profile:type' => "Тип строки",
	'profile:editdefault:delete:fail' => 'Сбой удаления поля профиля',
	'profile:editdefault:delete:success' => 'Профиль по умолчанию удален!',
	'profile:defaultprofile:reset' => 'Профиль сброшен по умолчанию.',
	'profile:resetdefault' => 'Перезагрузить профиль по умолчанию',
	'profile:resetdefault:confirm' => 'Вы уверены, что хотите удалить свои уникальные поля профиля?',
	'profile:explainchangefields' => "Вы можете заменить существующие поля профиля используя форму ниже. \n\n Можете изменить поле профиля, например, 'Любимая комманда', потом выбрать тип поля (пр. текст, ссылка, теги), и нажать на кнопку 'Добавить'. Чтобы изменить порядок полей перетащите его мышкой. Для редактирования - нажмите на текст. \n\n В любое время вы можете сбросить порядок полей по умлочанию, но вы потеряете всю информацию, которую вводили.",
	'profile:editdefault:success' => 'Пункт успешно добавлен в профиль',
	'profile:editdefault:fail' => 'Профиль по умолчанию не может быть сохранен',
	'profile:field_too_long' => 'Информация профиля не может быть сохранена, потому что секция "%s" слишком большая.',
	'profile:noaccess' => "У вас нет прав, чтобы редактировать этот профиль.",
	'profile:invalid_email' => '%s должен быть корректный email адрес',


/**
 * Feeds
 */
	'feed:rss' => 'Подписаться на канал',
/**
 * Links
 */
	'link:view' => 'ссылка',
	'link:view:all' => 'Просмотреть все',


/**
 * River
 */
	'river' => "Лента но",
	'river:friend:user:default' => "%s добавил(а) в друзья %s",
	'river:update:user:avatar' => '%s изменил(а) аватар',
	'river:update:user:profile' => '%s обновил их профиль',
	'river:noaccess' => 'У вас нет разрешения для просмотра',
	'river:posted:generic' => '%s написал(а)',
	'riveritem:single:user' => 'пользователь',
	'riveritem:plural:user' => 'пользователи',
	'river:ingroup' => 'в группе %s',
	'river:none' => 'Нет активности',
	'river:update' => 'Обновить для %s',
	'river:delete' => 'Удалить этот пункт активности',
	'river:delete:success' => 'Элемент был удалён',
	'river:delete:fail' => 'Элемент не может быть удалён',
	'river:subject:invalid_subject' => 'Ошибочный пользователь',
	'activity:owner' => 'Посмотреть активность',

	'river:widget:title' => "Лента",
	'river:widget:description' => "Нет записей",
	'river:widget:type' => "Активность",
	'river:widgets:friends' => 'Друзей',
	'river:widgets:all' => 'На всем сайте',

/**
 * Notifications
 */
	'notifications:usersettings' => "Настройки уведомления",
	'notification:method:email' => 'Электронный адрес',

	'notifications:usersettings:save:ok' => "Настройки уведомления успешно сохранены.",
	'notifications:usersettings:save:fail' => "Возникла ошибка при сохранении настроек уведомления.",

	'notification:subject' => 'Уведомление о %s',
	'notification:body' => 'Смотреть новую активность в %s',

/**
 * Search
 */

	'search' => "Поиск",
	'searchtitle' => "Поиск: %s",
	'users:searchtitle' => "Поиск пользователей: %s",
	'groups:searchtitle' => "Поиск групп: %s",
	'advancedsearchtitle' => "%s результатов совпадает с запросом '%s'",
	'notfound' => "Ничего не найдено",
	'next' => "Вперёд",
	'previous' => "Назад",

	'viewtype:change' => "Изменить вид вывода",
	'viewtype:list' => "Список",
	'viewtype:gallery' => "Галерея",

	'tag:search:startblurb' => "Записи, совпадающие с запросом '%s':",

	'user:search:startblurb' => "Пользователи, совпадающие с запросом '%s:",
	'user:search:finishblurb' => "Подробнее",

	'group:search:startblurb' => "Группы, совпадающие с запросом '%s':",
	'group:search:finishblurb' => "Нажмите для продолжения.",
	'search:go' => 'Искать',
	'userpicker:only_friends' => 'Только друзья',

/**
 * Account
 */

	'account' => "Аккаунт",
	'settings' => "Настройки",
	'tools' => "Ещё",
	'settings:edit' => 'Редактировать настройки',

	'register' => "Регистрация",
	'registerok' => "Вы успешно зарегистрировались на %s. Для активации аккаунта, пожалуйста, потвердите Ваш электронный адрес, нажав на ссылке в письме, которое мы Вам отправили.",
	'registerbad' => "Ваша регистрация приостановлена. Логин уже существует, Ваши пароли не совпадают, или логин или пароль слишком краткие.",
	'registerdisabled' => "Регистрация отключена администратором.",
	'register:fields' => 'Требуется заполнение всех пунктов',

	'registration:notemail' => 'Почему-то нам кажется, что Вы указали неверный электронный адрес.',
	'registration:userexists' => 'Этот логин уще существует',
	'registration:usernametooshort' => 'Ваш логин должен состоять минимум из 4 символов.',
	'registration:usernametoolong' => 'Имя пользователя слишком длинное. Его максимальная длина %u символов.',
	'registration:passwordtooshort' => 'Пароль должен состоять минимум из 6 символов.',
	'registration:dupeemail' => 'Такой электронный адрес уже зарегистрирован.',
	'registration:invalidchars' => 'Простите, имя пользователя содержит запрещенные символы.',
	'registration:emailnotvalid' => 'Простите, введенный Вами электронный адрес неверен.',
	'registration:passwordnotvalid' => 'Простите, введенный Вами пароль не подходит.',
	'registration:usernamenotvalid' => 'Простите, введенный Вами логин не подходит.',

	'adduser' => "Добавить пользователя",
	'adduser:ok' => "Вы успешно добавили нового пользователя.",
	'adduser:bad' => "Не удается создать нового пользователя.",

	'user:set:name' => "Настройки аккаунта",
	'user:name:label' => "Отображаемое имя (Внимание, доступно публично!)",
	'user:name:success' => "Отображаемое имя изменено.",
	'user:name:fail' => "Не удается изменить имя в системе.",

	'user:set:password' => "Пароль аккаунта",
	'user:current_password:label' => 'Старый пароль',
	'user:password:label' => "Ваш новый пароль",
	'user:password2:label' => "Повтор нового пароля",
	'user:password:success' => "Пароль изменен",
	'user:password:fail' => "Не удается изменить пароль в системе.",
	'user:password:fail:notsame' => "Пароли не совпадают!",
	'user:password:fail:tooshort' => "Пароль должен быть длиннее.",
	'user:password:fail:incorrect_current_password' => 'Старый пароль введен неверно.',
	'user:changepassword:unknown_user' => 'Ошибочный пользователь',
	'user:changepassword:change_password_confirm' => 'Изменится Ваш пароль',

	'user:set:language' => "Настройки языка",
	'user:language:label' => "Язык интерфейса",
	'user:language:success' => "Настройки языка обновлены.",
	'user:language:fail' => "Ошибка сохранения смены языка.",

	'user:username:notfound' => 'Логин %s не найден.',

	'user:password:lost' => 'Забыли пароль?',
	'user:password:changereq:success' => 'Запрос на новый пароль выполнен успешно ',
	'user:password:changereq:fail' => 'Не может быть выполнен запрос на новый пароль ',

	'user:password:text' => 'Для генерации нового пароля, введите Ваш логин ниже. Мы вышлем Вам письмо на Ваш электронный адрес.',

	'user:persistent' => 'Запомнить меня',

	'walled_garden:welcome' => 'Добро пожаловать в',

/**
 * Administration
 */
	'menu:page:header:administer' => 'Администратор',
	'menu:page:header:configure' => 'Конфигурация',
	'menu:page:header:develop' => 'Разработка',
	'menu:page:header:default' => 'Другое',

	'admin:view_site' => 'На сайт',
	'admin:loggedin' => 'Вы вошли как %s',
	'admin:menu' => 'Меню',

	'admin:configuration:success' => "Ваши настройки сохранены.",
	'admin:configuration:fail' => "Ошибка при сохранении настроек.",
	'admin:configuration:dataroot:relative_path' => 'Нельзя установить "%s" как корневой для данных, потому что это не абсолютный путь.',
	'admin:configuration:default_limit' => 'Количество элементов на страницу должно быть минимум 1',

	'admin:unknown_section' => 'Неправильная админ Раздел',

	'admin' => "Админ",
	'admin:description' => "Панель администрирования помогает контролировать все возможности системы, от управления пользователями до настроек плагинов. Нажмите ниже чтобы начать.",

	'admin:statistics' => "Статистика",
	'admin:statistics:overview' => 'Обзор',
	'admin:statistics:server' => 'Информация сервера',
	'admin:statistics:cron' => 'Cron',
	'admin:cron:record' => 'Последние задачи Cron',
	'admin:cron:period' => 'Период Cron',
	'admin:cron:friendly' => 'Последний завершенный',
	'admin:cron:date' => 'Дата и время',

	'admin:appearance' => 'Внешний вид',
	'admin:administer_utilities' => 'Утилиты',
	'admin:develop_utilities' => 'Утилиты',
	'admin:configure_utilities' => 'Утилиты',
	'admin:configure_utilities:robots' => 'Robots.txt',

	'admin:users' => "Управление пользователями",
	'admin:users:online' => 'Сейчас в online',
	'admin:users:newest' => 'Последние зарегистрированые',
	'admin:users:admins' => 'Администраторы',
	'admin:users:add' => 'Добавить нового пользователя',
	'admin:users:description' => "Эта админ-панель позволяет контролировать настройки пользователей.Выберите опцию ниже, чтобы начать работу.",
	'admin:users:adduser:label' => "Нажмите сюда для добовления нового пользователя",
	'admin:users:opt:linktext' => "Настройка пользователей...",
	'admin:users:opt:description' => "Настройка пользователей и информацию об аккаунте.",
	'admin:users:find' => 'Найдено',

	'admin:administer_utilities:maintenance' => 'Режим обслуживания',
	'admin:upgrades' => 'Обновления',

	'admin:settings' => 'Настройки',
	'admin:settings:basic' => 'Основные настройки',
	'admin:settings:advanced' => 'Дополнительные настройки',
	'admin:site:description' => "Это панель администратора позволяет управлять глобальными настройками. Выберите опцию ниже, чтобы начать работу.",
	'admin:site:opt:linktext' => "Конфигурация сайта...",
	'admin:settings:in_settings_file' => 'Эта настройка происходит в settings.php',

	'admin:legend:security' => 'Безопасность',
	'admin:site:secret:intro' => 'Elgg использует ключ для создания безопасных токенов для различных целей.',
	'admin:site:secret_regenerated' => "Секретный ключ вашего сайта был создан заново.",
	'admin:site:secret:regenerate' => "Создать заново секретный ключ сайта",
	'admin:site:secret:regenerate:help' => "Примечание: Создание нового секретного ключа сайта может быть неудобно для пользователей - не будут работать созданные ранее: куки \"запомнить меня\",  запросы на подтверждение email адреса, коды приглашений и др.",
	'site_secret:current_strength' => 'Сильный ключ',
	'site_secret:strength:weak' => "Слабый",
	'site_secret:strength_msg:weak' => "Настоятельно рекомендуем создать заново ваш секретный ключ сайта.",
	'site_secret:strength:moderate' => "Модерировать",
	'site_secret:strength_msg:moderate' => "Мы рекомендуем Вам восстановить свою секретность на сайте для безопасности.",
	'site_secret:strength:strong' => "Сильный",
	'site_secret:strength_msg:strong' => "Ваша секретность на сайте достаточно сильная. Нет необходимости её восстанавливать.",

	'admin:dashboard' => 'Главная панель',
	'admin:widget:online_users' => 'Пользователи оnline',
	'admin:widget:online_users:help' => 'Список пользователей которые сейчас в сети',
	'admin:widget:new_users' => 'Новые пользователи',
	'admin:widget:new_users:help' => 'Список недавно зарегистрированых пользователей',
	'admin:widget:banned_users' => 'Забаненные пользователи',
	'admin:widget:banned_users:help' => 'Список забаненных пользователей',
	'admin:widget:content_stats' => 'Статистика контента',
	'admin:widget:content_stats:help' => 'Контент созданный пользователей',
	'admin:widget:cron_status' => 'статус Cron',
	'admin:widget:cron_status:help' => 'Отображение статуса хронологии последних действий завершена ',
	'widget:content_stats:type' => 'Тип контента',
	'widget:content_stats:number' => 'Номер',

	'admin:widget:admin_welcome' => 'Добро пожаловать',
	'admin:widget:admin_welcome:help' => "Краткое введение в админку",
	'admin:widget:admin_welcome:intro' =>
'Добро пожаловать!Прямо сейчас вы смотрите на приборную панель администратора.Она \'полезна для мониторинга\' того что сейчас происходит на сайте.',

	'admin:widget:admin_welcome:admin_overview' =>
"Навигация по панели администратора осуществляется при помощи меню с права. Она организована в",

	// argh, this is ugly
	'admin:widget:admin_welcome:outro' => '<br />Незабудьте проверить имеющиеся ресурсы через ссылки и удачного дня!',

	'admin:widget:control_panel' => 'Панель управления',
	'admin:widget:control_panel:help' => "Обеспечивает легкий доступ к общим элементам",

	'admin:cache:flush' => 'Сбросить все кэши',
	'admin:cache:flushed' => "Кэши этого сайта были сброшены",

	'admin:footer:faq' => 'Администрация FAQ',
	'admin:footer:manual' => 'Мануал администрации',
	'admin:footer:community_forums' => 'Форум подержки',
	'admin:footer:blog' => 'Блог',

	'admin:plugins:category:all' => 'Все плагины',
	'admin:plugins:category:active' => 'Включеные плагины',
	'admin:plugins:category:inactive' => 'Выключеные плагины',
	'admin:plugins:category:admin' => 'Плагины администратора',
	'admin:plugins:category:bundled' => 'Комплектные',
	'admin:plugins:category:nonbundled' => 'Некомплектные',
	'admin:plugins:category:content' => 'Содержащие',
	'admin:plugins:category:development' => 'Разработка',
	'admin:plugins:category:enhancement' => 'Улучшения',
	'admin:plugins:category:api' => 'Сервис/API',
	'admin:plugins:category:communication' => 'Комуникации',
	'admin:plugins:category:security' => 'Безопасность и Спам',
	'admin:plugins:category:social' => 'Социальные',
	'admin:plugins:category:multimedia' => 'Мультимедия',
	'admin:plugins:category:theme' => 'Темы',
	'admin:plugins:category:widget' => 'Виджеты',
	'admin:plugins:category:utility' => 'Утилиты',

	'admin:plugins:markdown:unknown_plugin' => 'Неизвестный плагин.',
	'admin:plugins:markdown:unknown_file' => 'Неизвестный файл.',

	'admin:notices:could_not_delete' => 'Не удалось удалить уведомления..',
	'item:object:admin_notice' => 'Замечание администратора',

	'admin:options' => 'Опции адимистратора.',

/**
 * Plugins
 */

	'plugins:disabled' => 'Плагины не загружены потому, что файл "disabled" был обнаружен в папке с модификациями.',
	'plugins:settings:save:ok' => "Настройки плагина %s успешно сохранены.",
	'plugins:settings:save:fail' => "Возникла ошибка при сохранении настроек плагина %s.",
	'plugins:usersettings:save:ok' => "Пользовательские настройки плагина %s успешно сохранены.",
	'plugins:usersettings:save:fail' => "Возникла ошибка при сохранении пользовательских настроек плагина %s.",
	'item:object:plugin' => 'Настройки конфигурации плагинов',

	'admin:plugins' => "Администрирование плагинов",
	'admin:plugins:activate_all' => 'Включить Все',
	'admin:plugins:deactivate_all' => 'Выключить Все',
	'admin:plugins:activate' => 'Включить',
	'admin:plugins:deactivate' => 'Выключить',
	'admin:plugins:description' => "Это панель администратора позволяет управлять и настраивать плагины установлены на сайте.",
	'admin:plugins:opt:linktext' => "Настройка плагинов...",
	'admin:plugins:opt:description' => "Настройка плагинов установленых на сайте. ",
	'admin:plugins:label:author' => "Автор",
	'admin:plugins:label:copyright' => "Авторское право",
	'admin:plugins:label:categories' => 'Категории',
	'admin:plugins:label:licence' => "Лицензия",
	'admin:plugins:label:website' => "URL",
	'admin:plugins:label:repository' => "Код",
	'admin:plugins:label:bugtracker' => "Послать сообщение о проблеме",
	'admin:plugins:label:donate' => "Помочь деньгами",
	'admin:plugins:label:moreinfo' => 'Подробнее',
	'admin:plugins:label:version' => 'версия',
	'admin:plugins:label:location' => 'Директория',
	'admin:plugins:label:contributors' => 'Те, кто помогали разработке',
	'admin:plugins:label:contributors:name' => 'Название',
	'admin:plugins:label:contributors:email' => 'E-mail',
	'admin:plugins:label:contributors:website' => 'Сайт',
	'admin:plugins:label:contributors:username' => 'Имя пользователя в сообществе',
	'admin:plugins:label:contributors:description' => 'Описание',
	'admin:plugins:label:dependencies' => 'Совместимость',

	'admin:plugins:warning:elgg_version_unknown' => 'В этом плагине, в файле manifest не указана совместимость версии.Скорее всего это не будет работать!',
	'admin:plugins:warning:unmet_dependencies' => 'Этот плагин имеет неправильные зависимости и не может быть активирован. Проверьте зависимости.',
	'admin:plugins:warning:invalid' => '%s это недопустимый плагин.Посмотрите <a href="http://docs.elgg.org/Invalid_Plugin">Документацию</a> для устранения неполадок.',
	'admin:plugins:warning:invalid:check_docs' => 'Прверьте <a href="http://learn.elgg.org/en/stable/appendix/faqs.html">Elgg документацию</a> по устранению неполадок.',
	'admin:plugins:cannot_activate' => 'Включение не удалось',

	'admin:plugins:set_priority:yes' => "Плагин %s успешно перемещен.",
	'admin:plugins:set_priority:no' => "Плагин %s не может быть перемещен.",
	'admin:plugins:set_priority:no_with_msg' => "Не могу изменить порядок для %s. Ошибка %s.",
	'admin:plugins:deactivate:yes' => "Плагин %s успешно выключен.",
	'admin:plugins:deactivate:no' => "Плагин %s невозможно выключить..",
	'admin:plugins:deactivate:no_with_msg' => "Не могу деактивировать %s. Ошибка %s.",
	'admin:plugins:activate:yes' => "Плагин %s успешно включен.",
	'admin:plugins:activate:no' => "Плагин %s невозможно включить.",
	'admin:plugins:activate:no_with_msg' => "Не могу активировать %s. Ошибка %s.",
	'admin:plugins:categories:all' => 'Все категории',
	'admin:plugins:plugin_website' => 'Сайт плагина',
	'admin:plugins:author' => '%s',
	'admin:plugins:version' => 'Версия %s',
	'admin:plugin_settings' => 'Настройки плагина',
	'admin:plugins:warning:unmet_dependencies_active' => 'Этот плагин активен, но имеет неудовлетворенные зависимости. Вы можете столкнуться с проблемами. См. "Подробнее" ниже.',

	'admin:plugins:dependencies:type' => 'Тип',
	'admin:plugins:dependencies:name' => 'Название',
	'admin:plugins:dependencies:expected_value' => 'Тестировался на версии',
	'admin:plugins:dependencies:local_value' => 'Актуальная версия',
	'admin:plugins:dependencies:comment' => 'Состояние',

	'admin:statistics:description' => "Это обзор статистических данных о вашем сайте.",
	'admin:statistics:opt:description' => "Просмотр статистической информации о пользователях и объектов на вашем сайте.",
	'admin:statistics:opt:linktext' => "Просмотр статистики...",
	'admin:statistics:label:basic' => "Базовая информация",
	'admin:statistics:label:numentities' => "Подробная статистика",
	'admin:statistics:label:numusers' => "Кол-во пользователей",
	'admin:statistics:label:numonline' => "Кол-во пользователей онлайн",
	'admin:statistics:label:onlineusers' => "Сейчас в сети",
	'admin:statistics:label:admins'=>"Администраторы",
	'admin:statistics:label:version' => "Версия движка",
	'admin:statistics:label:version:release' => "Реализ",
	'admin:statistics:label:version:version' => "Версия",

	'admin:server:label:php' => 'PHP',
	'admin:server:label:web_server' => 'Web сервер',
	'admin:server:label:server' => 'Сервер',
	'admin:server:label:log_location' => 'Путь к логу',
	'admin:server:label:php_version' => 'Версия PHP',
	'admin:server:label:php_ini' => 'Путь к инициализационному файлу PHP',
	'admin:server:label:php_log' => 'Лог PHP',
	'admin:server:label:mem_avail' => 'Доступная память',
	'admin:server:label:mem_used' => 'Использованная память',
	'admin:server:error_log' => "Лог ошибок веб-сервера",
	'admin:server:label:post_max_size' => 'Максимальный размер POST',
	'admin:server:label:upload_max_filesize' => 'Максимальный размер upload\'а',
	'admin:server:warning:post_max_too_small' => '(Примечание. post_max_size должен быть больше этого значения, чтобы поддерживать загрузки (uploads) такого размера',

	'admin:user:label:search' => "Поиск пользователей:",
	'admin:user:label:searchbutton' => "Искать",

	'admin:user:ban:no' => "Неудалось забанить пользователя :(",
	'admin:user:ban:yes' => "Пользователь забанен :D",
	'admin:user:self:ban:no' => "Ты не можешь забанить себя",
	'admin:user:unban:no' => "Неудалось разбанить пользователя",
	'admin:user:unban:yes' => "Пользователь разбанен :D",
	'admin:user:delete:no' => "Неудалось удалить пользователя",
	'admin:user:delete:yes' => "Пользователь %s удален",
	'admin:user:self:delete:no' => "Ты не можешь удалить себя",

	'admin:user:resetpassword:yes' => "Пароль сброшен, пользователь проинформирован.",
	'admin:user:resetpassword:no' => "Неудалось сбросить пароль.",

	'admin:user:makeadmin:yes' => "Пользователь теперь администратор.",
	'admin:user:makeadmin:no' => "Неудалось сделать пользователя администратором.",

	'admin:user:removeadmin:yes' => "Пользователь больше не администратор.",
	'admin:user:removeadmin:no' => "Неудалось удалить права администратора этого пользователя.",
	'admin:user:self:removeadmin:no' => "Вы не можете удалять ваши собственные привилегии администратора.",

	'admin:appearance:menu_items' => 'Пункты меню',
	'admin:menu_items:configure' => 'Настройка главного меню',
	'admin:menu_items:description' => 'Выберите пункты меню вы хотите показать.  Неиспользуемые элементы будут добавлены как "More" в конце списка.',
	'admin:menu_items:hide_toolbar_entries' => 'Удалить ссылки из меню?',
	'admin:menu_items:saved' => 'Пункты меню сохранены.',
	'admin:add_menu_item' => 'Добавить пункт меню',
	'admin:add_menu_item:description' => 'Заполните отображаемое имя и адрес для добавления пользовательских элементов в меню навигации.',

	'admin:appearance:default_widgets' => 'Элементы по умолчанию',
	'admin:default_widgets:unknown_type' => 'Неизвестный тип элемента',
	'admin:default_widgets:instructions' => 'Добавление, удаление,изменения положение и настроек элементов по умолчанию для выбранной странице элемента.',

	'admin:robots.txt:instructions' => "Редактируйте файл robots.txt этого сайта ниже",
	'admin:robots.txt:plugins' => "Плагины добавляют следующее к файлу robots.txt",
	'admin:robots.txt:subdir' => "Инструмент robots.txt не будет работать, потому что Elgg установлен в поддиректорию",
	'admin:robots.txt:physical' => "The robots.txt tool will not work because a physical robots.txt is present",

	'admin:maintenance_mode:default_message' => 'Этот сайт выключен на обслуживание',
	'admin:maintenance_mode:instructions' => 'Maintenance mode should be used for upgrades and other large changes to the site.
		When it is on, only admins can log in and browse the site.',
	'admin:maintenance_mode:mode_label' => 'Режим обслуживания',
	'admin:maintenance_mode:message_label' => 'Сообщение, которое демонстрируется пользователям, когда включен режим обслуживания',
	'admin:maintenance_mode:saved' => 'Установки режима обслуживания были сохранены.',
	'admin:maintenance_mode:indicator_menu_item' => 'Сайт в режиме обслуживания.',
	'admin:login' => 'Логин администратора',

/**
 * User settings
 */
		
	'usersettings:description' => "Панель пользователя позволяет управлять всеми Вашими персональными настройками, от управления пользователем до отображения плагинов. Выберите опцию ниже чтобы начать.",

	'usersettings:statistics' => "Ваша статистика",
	'usersettings:statistics:opt:description' => "Просмотр статистической информации о пользователях и обьектах на Вашем сайте.",
	'usersettings:statistics:opt:linktext' => "Статистика аккаунта",

	'usersettings:user' => "Ваши настройки",
	'usersettings:user:opt:description' => "Управление настройками пользователя.",
	'usersettings:user:opt:linktext' => "Изменить Ваши настройки",

	'usersettings:plugins' => "Плагины",
	'usersettings:plugins:opt:description' => "Управление настройками активных плагинов.",
	'usersettings:plugins:opt:linktext' => "Настройка плагинов",

	'usersettings:plugins:description' => "Эта панель позволяет управлять и настраивать персональными настройками плагинов, установленных системным администратором.",
	'usersettings:statistics:label:numentities' => "Ваши объекты",

	'usersettings:statistics:yourdetails' => "Ваши данные",
	'usersettings:statistics:label:name' => "Отображаемое имя (Внимание, доступно публично!)",
	'usersettings:statistics:label:email' => "Электронный адрес",
	'usersettings:statistics:label:membersince' => "Регистрация",
	'usersettings:statistics:label:lastlogin' => "Последний визит",

/**
 * Activity river
 */
		
	'river:all' => 'Активность на всем сайте',
	'river:mine' => 'Моя активность',
	'river:owner' => 'Активность %s',
	'river:friends' => 'Активность друзей',
	'river:select' => 'Показать %s',
	'river:comments:more' => '+%s больше',
	'river:comments:all' => 'Просмотр всех %u комментариев',
	'river:generic_comment' => 'прокомментировал %s %s',

	'friends:widget:description' => "Выводит на экран ваших друзей.",
	'friends:num_display' => "Показывать количество друзей",
	'friends:icon_size' => "Размер изображения",
	'friends:tiny' => "очень маленькое",
	'friends:small' => "маленькое",

/**
 * Icons
 */

	'icon:size' => "Размер изображения",
	'icon:size:topbar' => "Верхняя панель",
	'icon:size:tiny' => "Крошечный",
	'icon:size:small' => "Маленький",
	'icon:size:medium' => "Средний",
	'icon:size:large' => "Большой",
	'icon:size:master' => "Очень большой",
		
/**
 * Generic action words
 */

	'save' => "Сохранить",
	'reset' => 'Сброс',
	'publish' => "Сохранить",
	'cancel' => "Отмена",
	'saving' => "Сохранение ...",
	'update' => "Обновить",
	'preview' => "Предварительный просмотр",
	'edit' => "Изменить",
	'delete' => "Удалить",
	'accept' => "Принять",
	'reject' => "отказать",
	'decline' => "Decline",
	'approve' => "одобрить",
	'activate' => "Включить",
	'deactivate' => "Выключить",
	'disapprove' => "Отклонить",
	'revoke' => "Очистить",
	'load' => "Скачать",
	'upload' => "Загрузить",
	'download' => "Загрузить",
	'ban' => "Заблокировать",
	'unban' => "Разблокировать",
	'banned' => "Заблокирован",
	'enable' => "Включить",
	'disable' => "Выключить",
	'request' => "Запрос",
	'complete' => "Завершено",
	'open' => 'Открыть',
	'close' => 'Закрыть',
	'hide' => 'Скрыть',
	'show' => 'Показать',
	'reply' => "Ответить",
	'more' => 'Дальше',
	'more_info' => 'Больше информации',
	'comments' => 'Комментарии',
	'import' => 'Импорт',
	'export' => 'Экспорт',
	'untitled' => 'Без имени',
	'help' => 'Помощь',
	'send' => 'Отправить',
	'post' => 'Написать',
	'submit' => 'Подтвердить',
	'comment' => 'Комментировать',
	'upgrade' => 'Обновить',
	'sort' => 'Сортировать',
	'filter' => 'Фильтр',
	'new' => 'Новый',
	'add' => 'Добавить',
	'create' => 'Создать',
	'remove' => 'Удалить',
	'revert' => 'Откатить',

	'site' => 'Сайт',
	'activity' => 'Активность',
	'members' => 'Участники',
	'menu' => 'Меню',

	'up' => 'Вверх',
	'down' => 'Вниз',
	'top' => 'В самый верх',
	'bottom' => 'В середину',
	'right' => 'Вправо',
	'left' => 'Влево',
	'back' => 'Назад',

	'invite' => "Пригласить",

	'resetpassword' => "Сбросить пароль",
	'changepassword' => "Изменить пароль",
	'makeadmin' => "Сделать администратором",
	'removeadmin' => "Удалить администратора",

	'option:yes' => "Да",
	'option:no' => "Нет",

	'unknown' => 'Неизвестно',
	'never' => 'Никогда',

	'active' => 'активно',
	'total' => 'всего',
	
	'ok' => 'OK',
	'any' => 'Любой',
	'error' => 'Ошибка',
	
	'other' => 'Другое',
	'options' => 'Опции',
	'advanced' => 'ПРодвинутый',

	'learnmore' => "Нажмите сюда для продолжения",
	'unknown_error' => 'Неизвестная ошибка',

	'content' => "содержимое",
	'content:latest' => 'Последняя активность',
	'content:latest:blurb' => 'Или нажмите сюда для просмотра новой информации, добавленной на сайт',

	'link:text' => 'ссылка',
	
/**
 * Generic questions
 */

	'question:areyousure' => 'Вы уверены?',

/**
 * Status
 */

	'status' => 'Статус',
	'status:unsaved_draft' => 'Несохраненный черновик',
	'status:draft' => 'Черновик',
	'status:unpublished' => 'Неопубликованное',
	'status:published' => 'Опубликованное',
	'status:featured' => 'Рекомендуемые',
	'status:open' => 'Открыть',
	'status:closed' => 'Закрыта для обсуждения',

/**
 * Generic sorts
 */

	'sort:newest' => 'Новые',
	'sort:popular' => 'Популярные',
	'sort:alpha' => 'В алфавитном порядке',
	'sort:priority' => 'По приоритету',
		
/**
 * Generic data words
 */

	'title' => "Название",
	'description' => "Описание",
	'tags' => "Теги",
	'all' => "Все",
	'mine' => "Моё",

	'by' => 'от',
	'none' => 'n!o!n!e!',

	'annotations' => "Примечания",
	'relationships' => "Связи",
	'metadata' => "Метаданные",
	'tagcloud' => "Облако тегов",

	'on' => 'Включено',
	'off' => 'Выключено',

/**
 * Entity actions
 */
		
	'edit:this' => 'Править',
	'delete:this' => 'Удалить',
	'comment:this' => 'Оставить комментарий',

/**
 * Input / output strings
 */

	'deleteconfirm' => "Вы уверены что хотите удалить этот элемент?",
	'deleteconfirm:plural' => "Вы уверены, что хотите удалить эти пункты?",
	'fileexists' => "Файл уже загружен. Чтобы заменить его, выберите его ниже:",

/**
 * User add
 */

	'useradd:subject' => 'Вы успешно зарегистрировались',
	'useradd:body' => '
%s,

Вы зарегистрировались на %s. Для входа нажмите сюда:

	%s

Ваши регистрационные реквизиты:

	Логин: %s
	Пароль: %s
	
',

/**
 * System messages
 */

	'systemmessages:dismiss' => "закрыть",


/**
 * Import / export
 */
		
	'importsuccess' => "Данные импортированы успешно",
	'importfail' => "Ошибка OpenDD импорта данных.",

/**
 * Time
 */

	'friendlytime:justnow' => "только что",
	'friendlytime:minutes' => "%s минут назад",
	'friendlytime:minutes:singular' => "минуту назад",
	'friendlytime:hours' => "%s часов назад",
	'friendlytime:hours:singular' => "час назад",
	'friendlytime:days' => "%s дней назад",
	'friendlytime:days:singular' => "вчера",
	'friendlytime:date_format' => 'j F Y @ g:ia',
	
	'friendlytime:future:minutes' => "в течение %s минут",
	'friendlytime:future:minutes:singular' => "в течение минуты",
	'friendlytime:future:hours' => "в течение %s часов",
	'friendlytime:future:hours:singular' => "в течение часа",
	'friendlytime:future:days' => "в течение %s дней",
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

	'date:weekday:0' => 'Воскресенье',
	'date:weekday:1' => 'Понедельник',
	'date:weekday:2' => 'Вторник',
	'date:weekday:3' => 'Среда',
	'date:weekday:4' => 'Четверг',
	'date:weekday:5' => 'Пятница',
	'date:weekday:6' => 'Суббота',
	
	'interval:minute' => 'Каждую минуту',
	'interval:fiveminute' => 'Каждые пять минут',
	'interval:fifteenmin' => 'Каждые пятнадцать минут',
	'interval:halfhour' => 'Каждые полчаса',
	'interval:hourly' => 'Каждый час',
	'interval:daily' => 'Ежедневно',
	'interval:weekly' => 'Еженедельно',
	'interval:monthly' => 'Ежемесячно',
	'interval:yearly' => 'Ежегодно',
	'interval:reboot' => 'При перезагрузке',

/**
 * System settings
 */

	'installation:sitename' => "Имя вашего сайта:",
	'installation:sitedescription' => "Краткое описание Вашего сайта (необязательно):",
	'installation:wwwroot' => "Адрес сайта:",
	'installation:path' => "Полный путь установки:",
	'installation:dataroot' => "Полный путь к каталогу данных:",
	'installation:dataroot:warning' => "Вы должны создать этот каталог вручную. Он не должен быть в каталоге установки.",
	'installation:sitepermissions' => "По умолчанию разрешения на доступ к сайту:",
	'installation:language' => "Язык по умолчанию:",
	'installation:debug' => "Режим отладки предоставляет дополнительную информацию, которая может быть использована для диагностики неисправностей. Тем не менее, это может замедлить работу системы, моежт быть использованы только если у вас возникли проблемы:",
	'installation:debug:label' => "Уровень лога:",
	'installation:debug:none' => 'Выключить режим отладки (рекомендуется)',
	'installation:debug:error' => 'Показывать только критические ошибки',
	'installation:debug:warning' => 'Отображение ошибок и предупреждений',
	'installation:debug:notice' => 'Записывать все ошибки, предупреждения и уведомления',
	'installation:debug:info' => 'Записывать все',

	// Walled Garden support
	'installation:registration:description' => 'Регистрация пользователей включена по умолчанию. Выключите эту опцию, если вы не хотите, чтобы новые пользователи могли зарегистрироваться сами по себе.',
	'installation:registration:label' => 'Разрешить регистрацию',
	'installation:walled_garden:description' => 'Включить сайт для работы в качестве частной сети. Это не позволит, не вошедшим в систему пользователям просматривать любые страницы сайта, кроме тех, которые специально отмечены как общественности.',
	'installation:walled_garden:label' => 'Ограничить страницы зарегистрированным пользователям',

	'installation:httpslogin' => "Пользователи могут входить по HTTPS. Для этого Вам нужно будет настроить HTTPS на сервере.",
	'installation:httpslogin:label' => "Разрешить вход по HTTPS",
	'installation:view' => "Enter the view which will be used as the default for your site or leave this blank for the default view (if in doubt, leave as default(нихуя не понел о чем это...возможно для поисковых машин)):",

	'installation:siteemail' => "Электронный адрес сайта (будет использоваться для рассылки системных сообщений):",
	'installation:default_limit' => "Количество элементов на стронице по умолчанию",

	'admin:site:access:warning' => "Изменение доступа влияет только на разрешения на контент, созданный в будущем.",
	'installation:allow_user_default_access:description' => "Пользователи могут настраивать собственный уровень доступа, который будет иметь учитываться аналогичными настройками системы.",
	'installation:allow_user_default_access:label' => "Разрешить пользователям настраивать уровень доступа",

	'installation:simplecache:description' => "Простой кэш увеличивает производительность, кэшируя элементы вроде CSS и JavaScript. Рекомендуем включить данную опцию.",
	'installation:simplecache:label' => "Использовать простой кэш(рекомендуется)",

	'installation:minify:description' => "Простой кэш также может увеличивать производительность, сжимая Java-скрипты и файлы CSS (Требуется включить этот простой кэш)",
	'installation:minify_js:label' => "Сжимать Java-скрипт (рекомендуется)",
	'installation:minify_css:label' => "Сжимать CSS (рекомендуется)",

	'installation:htaccess:needs_upgrade' => "You must update your .htaccess file so that the path is injected into the GET parameter __elgg_uri (you can use install/config/htaccess.dist as a guide).",
	'installation:htaccess:localhost:connectionfailed' => "Разблокировать возможность апгрейда",
	
	'installation:systemcache:description' => "Системный кэш уменьшает время загрузки Elgg, сохраняя данные в файлах",
	'installation:systemcache:label' => "Использовать системный кэш (рекомендуется)",

	'admin:legend:system' => 'Система',
	'admin:legend:caching' => 'Кэширование',
	'admin:legend:content_access' => 'Доступ к контенту',
	'admin:legend:site_access' => 'Доступ к сайту',
	'admin:legend:debug' => 'Отладка и логгирование',

	'upgrading' => 'Обновление ...',
	'upgrade:db' => 'Ваша база данных была обновлена.',
	'upgrade:core' => 'Ваша Elgg установка была обновлена.',
	'upgrade:unlock' => 'Доступны обновления',
	'upgrade:unlock:confirm' => "База данных заблокирована для другого обновления. Запуск одновременно нескольких обновлений опасно. Вы можете продолжить, если вы уверены, что не работает другое обновление. Разблокировать?",
	'upgrade:locked' => "Обновление невозможно. Работает  другое обновление. Чтобы снять блокировку обновления, посетите раздел администрирования.",
	'upgrade:unlock:success' => "Обновление разблокирован успешно.",
	'upgrade:unable_to_upgrade' => 'Не удалось обновить.',
	'upgrade:unable_to_upgrade_info' =>
		'This installation cannot be upgraded because legacy views
		were detected in the Elgg core views directory. These views have been deprecated and need to be
		removed for Elgg to function correctly. If you have not made changes to Elgg core, you can
		simply delete the views directory and replace it with the one from the latest
		package of Elgg downloaded from <a href="http://elgg.org">elgg.org</a>.<br /><br />

		If you need detailed instructions, please visit the <a href="http://learn.elgg.org/en/stable/admin/upgrading.html">
		Upgrading Elgg documentation</a>.  If you require assistance, please post to the
		<a href="http://community.elgg.org/pg/groups/discussion/">Community Support Forums</a>.',

	'update:twitter_api:deactivated' => 'Twitter API был отключен при обновлении. Пожалуйста, включите его вручную, если требуется.',
	'update:oauth_api:deactivated' => 'OAuth API (ранее OAuth Lib) был отключен во время обновления. Пожалуйста, включите его вручную, если требуется.',
	'upgrade:site_secret_warning:moderate' => "You are encouraged to regenerate your site key to improve system security. See Configure &gt; Settings &gt; Advanced",
	'upgrade:site_secret_warning:weak' => "You are strongly encouraged to regenerate your site key to improve system security. See Configure &gt; Settings &gt; Advanced",

	'deprecated:function' => '%s() была установлена по верх %s()',

	'admin:pending_upgrades' => 'The site has pending upgrades that require your immediate attention.',
	'admin:view_upgrades' => 'View pending upgrades.',
 	'admin:upgrades' => 'Обновления',
	'item:object:elgg_upgrade' => 'Site upgrades',
	'admin:upgrades:none' => 'Your installation is up to date!',

	'upgrade:item_count' => 'There are <b>%s</b> items that need to be upgraded.',
	'upgrade:warning' => '<b>Warning:</b> On a large site this upgrade may take a significantly long time!',
	'upgrade:success_count' => 'Upgraded:',
	'upgrade:error_count' => 'Ошибки:',
	'upgrade:river_update_failed' => 'Failed to update the river entry for item id %s',
	'upgrade:timestamp_update_failed' => 'Failed to update the timestamps for item id %s',
	'upgrade:finished' => 'Апгрейд завершен',
	'upgrade:finished_with_errors' => '<p>Upgrade finished with errors. Refresh the page and try running the upgrade again.</p></p><br />If the error recurs, check the server error log for possible cause. You can seek help for fixing the error from the <a href="http://community.elgg.org/groups/profile/179063/elgg-technical-support">Technical support group</a> in the Elgg community.</p>',

	// Strings specific for the comments upgrade
	'admin:upgrades:comments' => 'Comments upgrade',
	'upgrade:comment:create_failed' => 'Failed to convert comment id %s to an entity.',
	'admin:upgrades:commentaccess' => 'Comments Access Upgrade',

	// Strings specific for the datadir upgrade
	'admin:upgrades:datadirs' => 'Data directory upgrade',

	// Strings specific for the discussion reply upgrade
	'admin:upgrades:discussion_replies' => 'Discussion reply upgrade',
	'discussion:upgrade:replies:create_failed' => 'Failed to convert discussion reply id %s to an entity.',

/**
 * Welcome
 */

	'welcome' => "Здравствуйте,",
	'welcome:user' => 'Здравствуйте, %s',

/**
 * Emails
 */
		
	'email:from' => 'Из',
	'email:to' => 'В',
	'email:subject' => 'Тема',
	'email:body' => 'Сообщение',
	
	'email:settings' => "Настройки электронного адреса",
	'email:address:label' => "Ваш электронный адрес",

	'email:save:success' => "Новый электронный адрес сохранен, отправлен запрос проверки реальности адреса.",
	'email:save:fail' => "Ваш новый электронный адрес не сохранен.",

	'friend:newfriend:subject' => "%s добавил(а) Вас в друзья.",
	'friend:newfriend:body' => "%s добавил(а) Вас в друзья.

Для просмотра профиля пользователя нажмите здесь:

	%s

Не отвечайте на это письмо.",

	'email:changepassword:subject' => "Пароль изменен!",
	'email:changepassword:body' => "Здравствуйте %s,

Ваш пароль был изменен.",

	'email:resetpassword:subject' => "Пароль изменен.",
	'email:resetpassword:body' => "Здравствуйте, %s!
			
Ваш пароль изменен на: %s",

	'email:changereq:subject' => "Просьба на изменение пароля",
	'email:changereq:body' => "Hi %s,

Somebody (from the IP address %s) has requested a password change for their account.

If you requested this, click on the link below. Otherwise ignore this email.

%s
",

/**
 * user default access
 */

	'default_access:settings' => "Ваш уровень доступа по-умолчанию",
	'default_access:label' => "Доступ по-умолчанию",
	'user:default_access:success' => "Ваш уровень доступа по-умолчанию сохранен.",
	'user:default_access:failure' => "Ваш уровень доступа по-умолчанию не может быть.",

/**
 * Comments
 */

	'comments:count' => "%s комментариев",
	'item:object:comment' => 'Комментарии',

	'river:comment:object:default' => '%s commented on %s',

	'generic_comments:add' => "Добавить комментарий",
	'generic_comments:edit' => "Редактировать комментарий",
	'generic_comments:post' => "Оставить комментарий",
	'generic_comments:text' => "Комментарий",
	'generic_comments:latest' => "Последние комментарии",
	'generic_comment:posted' => "Комментарий добавлен.",
	'generic_comment:updated' => "Комментарий был успешно обновлен",
	'generic_comment:deleted' => "Комментарий удален.",
	'generic_comment:blank' => "Извините, Вам нужно написать что-то в комментарии перед тем как его сохраненить.",
	'generic_comment:notfound' => "Извините, мы не можем найти указаный комментарий",
	'generic_comment:notfound_fallback' => "Sorry, we could not find the specified comment, but we've forwarded you to the page where it was left.",
	'generic_comment:notdeleted' => "Извините, ошибка при удалении комментария.",
	'generic_comment:failure' => "Неожиданная ошибка возникла при добавлении комментария. Попробуйте снова.",
	'generic_comment:none' => 'Комментариев нет',
	'generic_comment:title' => 'Комментарий от %s',
	'generic_comment:on' => '%s on %s',
	'generic_comments:latest:posted' => 'posted a',

	'generic_comment:email:subject' => 'У Вас новый комментарий',
	'generic_comment:email:body' => "Новый комментарий к \"%s\" от пользователя %s. А именно:

			
%s


Для ответа перейдите <a href=\"%s\">по этой ссылке</a>. Это автоматическое уведомление, не отвечайте на него как на email.",

/**
 * Entities
 */
	
	'byline' => 'От %s',
	'entity:default:strapline' => 'Создан в %s %s',
	'entity:default:missingsupport:popup' => 'Этот объект не удается корректно отобразить. Возможно ему нужен плагин, который уже удален.',

	'entity:delete:success' => 'Объект %s удален',
	'entity:delete:fail' => 'Ошибка удаления объекта %s',
	
	'entity:can_delete:invaliduser' => 'Can not check canDelete for user_guid [%s] as the user does not exist.',

/**
 * Action gatekeeper
 */

	'actiongatekeeper:missingfields' => 'В форме не найдены поля __token или __ts',
	'actiongatekeeper:tokeninvalid' => "Значение переданное формой не совпадает со значением сгенерированным сервером.",
	'actiongatekeeper:timeerror' => 'Время формы истекло, пожалуйста обновите страницу и попробуйте снова.',
	'actiongatekeeper:pluginprevents' => 'Sorry. Your form could not be submitted for an unknown reason.',
	'actiongatekeeper:uploadexceeded' => 'Размер загруженного файла(-ов) превышает лимит, установленный администратором этого сайта. ',
	'actiongatekeeper:crosssitelogin' => "Sorry, logging in from a different domain is not permitted. Please try again.",

/**
 * Word blacklists
 */

	'word:blacklist' => 'и, затем, но, она, его, её, его, one, не, также, о, сайчас, следовательно, тем не менее, все ещё, подобно, иначе, следовательно, наоборот, довольно, следовательно, кроме того, тем не менее, взамен, тем временем, соответственно, это, кажется, что, кто, чье, кто бы ни, кому либо',

/**
 * Tag labels
 */

	'tag_names:tags' => 'Теги',

/**
 * Javascript
 */

	'js:security:token_refresh_failed' => 'Невозможно соединиться с %s. Есть проблемы с экономии содержания.',
	'js:security:token_refreshed' => 'Подключение к %s. восстановлено!',
	'js:lightbox:current' => "image %s of %s",

/**
 * Miscellaneous
 */
	'elgg:powered' => "Работает на Elgg",

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
	"ru" => "Русский",
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
	"uk" => "Украинский",
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
);
