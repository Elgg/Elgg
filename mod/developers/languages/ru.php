<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'admin:develop_tools' => 'Инструменты',
	
	// menu
	'admin:develop_tools:inspect' => 'Проверить',
	'admin:inspect' => 'Проверить',
	'admin:develop_tools:unit_tests' => 'Модульные Тесты',
	'admin:develop_tools:entity_explorer' => 'Исследователь сущностей',
	'admin:developers' => 'Разработчики',
	'admin:developers:settings' => 'Настройки',
	'menu:entity_explorer:header' => 'Исследователь сущностей',
	'menu:developers_inspect_viewtype:header' => 'Проверка типов представлений',

	// settings
	'elgg_dev_tools:settings:explanation' => 'Управление настройками разработки и отладки. Некоторые из этих настроек также доступны на других страницах администрирования.',
	'developers:label:simple_cache' => 'Использовать простой кэш',
	'developers:help:simple_cache' => 'Выключайте этот кэш во время разработки. Иначе изменения сделанные вами в CSS и JavaSctipt будут проигнорированы.',
	'developers:label:system_cache' => 'Использовать кэш системы',
	'developers:help:system_cache' => 'Выключайте во время разработки. Иначе изменения в ваших плагинах не будут зарегистрированы.',
	'developers:label:debug_level' => "Уровень отладки",
	'developers:help:debug_level' => "Эта опция управляет количеством информации попадающей в логи. Смотрите документацию по функции elgg_log() для большей информации.",
	'developers:label:display_errors' => 'Отображать критические ошибки PHP',
	'developers:help:display_errors' => "По умолчанию Elgg в .htaccess файле подавляет вывод критических ошибок.",
	'developers:label:screen_log' => "Отладка на экран",
	'developers:help:screen_log' => "Вывод функций elgg_log() и elgg_dump()и подсчет запросов к БД на страницу.",
	'developers:show_strings:default' => "Нормальный перевод",
	'developers:show_strings:key_append' => "Ключ перевода добавлен",
	'developers:show_strings:key_only' => "Показать только ключ перевода",
	'developers:label:show_strings' => "Показывать ключи перевода",
	'developers:help:show_strings' => "Вывод ключей для перевода используемых в функции elgg_echo() вместо самого перевода.",
	'developers:label:wrap_views' => "Оборачивать views",
	'developers:help:wrap_views' => "Это оборачивает почти каждое представление комментариями HTML. Полезно для поиска представления, создающего конкретный HTML.
									Это может привести к поломке представлений, отличных от HTML, в типе представления по умолчанию.",
	'developers:label:log_events' => "Журнал событий",
	'developers:help:log_events' => "Запись событий в журнал. Предупреждение: их много на странице.",
	'developers:label:block_email' => "Блокировать все исходящие электронные письма",
	'developers:help:block_email' => "Вы можете заблокировать исходящие сообщения электронной почты обычным пользователям или всем пользователям.",
	'developers:label:forward_email' => "Пересылать все исходящие электронные письма на один адрес",
	'developers:help:forward_email' => "Все исходящие электронные письма будут отправляться на настроенный адрес электронной почты.",
	'developers:label:enable_error_log' => "Включить журнал ошибок",
	'developers:help:enable_error_log' => "Ведите отдельный журнал ошибок и сообщений, зарегистрированных в error_log(), в зависимости от настройки уровня трассировки. Журнал доступен для просмотра через интерфейс администратора.",

	'developers:block_email:forward' => 'Переслать все электронные письма',
	'developers:block_email:users' => 'Только обычные пользователи',
	'developers:block_email:all' => 'Админы и обычные пользователи',
	
	'developers:debug:off' => 'Выключено',
	'developers:debug:error' => 'Ошибка',
	'developers:debug:warning' => 'Предупреждение',
	'developers:debug:notice' => 'Замечание',
	'developers:debug:info' => 'Информация',
	
	// entity explorer
	'developers:entity_explorer:help' => 'Просмотр информации об объектах и выполнение некоторых основных действий над ними.',
	'developers:entity_explorer:guid:label' => 'Введите guid объекта для проверки',
	'developers:entity_explorer:info:attributes' => 'Атрибуты',
	'developers:entity_explorer:info:metadata' => 'Метаданные',
	'developers:entity_explorer:info:relationships' => 'Отношения',
	'developers:entity_explorer:info:owned_acls' => 'Коллекции собственного доступа',
	'developers:entity_explorer:info:acl_memberships' => 'Членство в коллекциях доступа',
	'developers:entity_explorer:delete_entity' => 'Удалить этот объект',
	'developers:entity_explorer:inspect_entity' => 'Проверить этот объект',
	'developers:entity_explorer:view_entity' => 'Посмотреть этот объект на сайте',
	
	// inspection
	'developers:inspect:actions' => 'Действия',
	'developers:inspect:events' => 'События',
	'developers:inspect:menus' => 'Меню',
	'developers:inspect:priority' => 'Приоритет',
	'developers:inspect:seeders' => 'Seeders',
	'developers:inspect:simplecache' => 'Простой кэш',
	'developers:inspect:routes' => 'Маршруты',
	'developers:inspect:views' => 'Представления',
	'developers:inspect:views:all_filtered' => "<b>Примечание!</b> Весь ввод/вывод просмотра фильтруется через эти события:",
	'developers:inspect:views:input_filtered' => "(ввод отфильтрован обработчиком событий: %s)",
	'developers:inspect:views:filtered' => "(отфильтровано обработчиком событий: %s)",
	'developers:inspect:widgets' => 'Виджеты',
	'developers:inspect:widgets:context' => 'Контекст',
	'developers:inspect:functions' => 'Функции',
	'developers:inspect:file_location' => 'Путь к файлу из Elgg корня или контроллера',
	'developers:inspect:route' => 'Название маршрута',
	'developers:inspect:path' => 'Шаблон пути',
	'developers:inspect:resource' => 'Представление ресурсов',
	'developers:inspect:handler' => 'Обработчик',
	'developers:inspect:controller' => 'Контроллер',
	'developers:inspect:file' => 'Файл',
	'developers:inspect:middleware' => 'Файл',
	'developers:inspect:handler_type' => 'Обрабатывается',
	'developers:inspect:services' => 'Сервисы',
	'developers:inspect:service:name' => 'Наименование',
	'developers:inspect:service:path' => 'Определение',
	'developers:inspect:service:class' => 'Класс',

	// event logging
	'developers:request_stats' => "Статистика запросов (не включает событие выключения)",
	'developers:event_log_msg' => "%s: '%s, %s' в %s",
	'developers:log_queries' => "Запросы к БД: %s",
	'developers:boot_cache_rebuilt' => "Загрузочный кеш был перестроен для этого запроса",
	'developers:elapsed_time' => "Прошедшее время (сек)",

	'admin:develop_tools:error_log' => 'Журнал ошибок',
	'developers:logs:empty' => 'Журнал ошибок пуст',
);
