<?php
return array(
	// menu
	'admin:develop_tools' => 'Инструменты',
	'admin:develop_tools:sandbox' => 'Песочница Темы',
	'admin:develop_tools:inspect' => 'Проверить',
	'admin:develop_tools:unit_tests' => 'Модульные Тесты',
	'admin:developers' => 'Разработчики',
	'admin:developers:settings' => 'Настройки',

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
	'developers:label:screen_log' => "Писать сообщения отладки на экран.",
	'developers:help:screen_log' => "Эта опция разрешает вывод функций elgg_log() и elgg_dump() на страницу.",
	'developers:label:show_strings' => "Показывать ключи перевода.",
	'developers:help:show_strings' => "Включает вывод ключей для перевода используемых в функции elgg_echo() вместо самого перевода.",
	'developers:label:wrap_views' => "Оборачивать views",
	'developers:help:wrap_views' => "This wraps almost every view with HTML comments. Useful for finding the view creating particular HTML.
									This can break non-HTML views in the default viewtype. See developers_wrap_views() for details.",
	'developers:label:log_events' => "Записывать events и hooks плагинов",
	'developers:help:log_events' => "Записывать events и hooks плагинов в лог. Внимание: этого очень много на любой странице.",

	'developers:debug:off' => 'Выключено',
	'developers:debug:error' => 'Ошибка',
	'developers:debug:warning' => 'Предупреждение',
	'developers:debug:notice' => 'Замечание',
	'developers:debug:info' => 'Информация',
	
	// inspection
	'developers:inspect:help' => 'Проверить конфигурацию фреймворка Elgg',

	// event logging
	'developers:event_log_msg' => "%s: '%s, %s' в %s",

	// theme sandbox
	'theme_sandbox:intro' => 'Введение',
	'theme_sandbox:breakout' => 'Вырываться из iframe',
	'theme_sandbox:buttons' => 'Кнопки',
	'theme_sandbox:components' => 'Компоненты',
	'theme_sandbox:forms' => 'Формы',
	'theme_sandbox:grid' => 'Сетка',
	'theme_sandbox:icons' => 'Иконки',
	'theme_sandbox:javascript' => 'JavaScript',
	'theme_sandbox:layouts' => 'Разметка',
	'theme_sandbox:modules' => 'Модули',
	'theme_sandbox:navigation' => 'Навигация',
	'theme_sandbox:typography' => 'Типография',

	'theme_sandbox:icons:blurb' => 'Используйте <em>elgg_view_icon($name)</em> или класс elgg-icon-$name для отображения иконок.',

	// unit tests
	'developers:unit_tests:description' => 'Elgg имеет модульные и интеграционные тесты для выявления ошибок в коде классов и функций ядра.',
	'developers:unit_tests:warning' => 'Внимание: НЕ ЗАПУСКАЙТЕ ЭТИ ТЕСТЫ НА РАБОЧЕМ САЙТЕ. Они могут повредить вашу базу данных.',
	'developers:unit_tests:run' => 'Выполнить',

	// status messages
	'developers:settings:success' => 'Настройки сохранены',
);
