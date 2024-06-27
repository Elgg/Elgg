<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'install:title' => 'Установка Elgg',
	'install:welcome' => 'Добро пожаловать',
	'install:requirements' => 'Проверка системных требований',
	'install:database' => 'Установка Базы данных',
	'install:settings' => 'Настройте сайт',
	'install:admin' => 'Создайте аккаунт администратора',
	'install:complete' => 'Готово',

	'install:next' => 'Далее',
	'install:refresh' => 'Обновить',
	'install:change_language' => 'Изменить язык',

	'install:welcome:instructions' => "Установка Elgg занимает 6 простых шагов и чтение этого приветствия - первый из них!

Если вы еще этого не сделали, прочитайте инструкции по установке, прилагаемые к Elgg (или перейдите по ссылке на инструкцию внизу страницы).

Если вы готовы продолжить, нажмите кнопку Далее.",
	
	'install:footer:instructions' => "Инструкции по установке",
	'install:footer:troubleshooting' => "Устранение неполадок при установке",
	'install:footer:community' => "Форумы сообщества Elgg",
	
	'install:requirements:instructions:success' => "Ваш сервер прошел проверку на соответствие требованиям.",
	'install:requirements:instructions:failure' => "Ваш сервер не прошел проверку на соответствие требованиям. После устранения перечисленных ниже проблем обновите эту страницу. Если вам нужна дополнительная помощь, обратитесь к ссылкам по устранению неполадок внизу этой страницы.",
	'install:requirements:instructions:warning' => "Ваш сервер прошел проверку требований, но есть как минимум одно предупреждение. Мы рекомендуем вам проверить страницу устранения неполадок установки для получения более подробной информации.",

	'install:require:php' => 'PHP',
	'install:require:rewrite' => 'Веб сервер',
	'install:require:settings' => 'Файл настроек',
	'install:require:database' => 'База данных',

	'install:check:root' => 'У вашего веб-сервера нет разрешения на создание файла .htaccess в корневом каталоге Elgg. У вас есть два варианта:

1. Изменить права доступа к корневому каталогу

2. Скопировать файл install/config/htaccess.dist в .htaccess',

	'install:check:php:version' => 'Elgg требуется PHP %s или выше. На этом сервере используетсы версия %s.',
	'install:check:php:extension' => 'Elgg требуется PHP расширение %s.',
	'install:check:php:extension:recommend' => 'Рекомендуется установить расширение PHP %s.',
	'install:check:php:open_basedir' => 'PHP-директива open_basedir может помешать Elgg сохранять файлы в его каталог данных.',
	'install:check:php:safe_mode' => 'Запуск PHP в безопасном режиме не рекомендуется и может вызвать проблемы с Elgg.',
	'install:check:php:arg_separator' => 'arg_separator.output должен быть & для работы Elgg, а значение вашего сервера %s',
	'install:check:php:register_globals' => 'Register globals must be turned off.',
	'install:check:php:session.auto_start' => "session.auto_start должен быть выключен для работы Elgg. Либо измените конфигурацию вашего сервера, либо добавьте эту директиву в файл .htaccess Elgg.",

	'install:check:installdir' => 'У вашего веб-сервера нет разрешения на создание файла settings.php в каталоге установки. У вас есть два варианта:

1. Изменить разрешения на каталог elgg-config вашей установки Elgg

2. Скопировать файл %s/settings.example.php в файл elgg-config/settings.php и следовать его инструкциям для установки параметров вашей базы данных.',
	'install:check:readsettings' => 'Файл настроек существует в каталоге установки, но веб-сервер не может его прочитать. Вы можете удалить файл или изменить разрешения на его чтение.',

	'install:check:php:success' => "PHP вашего сервера удовлетворяет всем требованиям Elgg.",
	'install:check:rewrite:success' => 'Проверка правил перезаписи прошла успешно.',
	'install:check:database' => 'Требования к базе данных проверяются, когда Elgg загружает свою базу данных.',

	'install:database:instructions' => "Если вы еще не создали базу данных для Elgg, сделайте это сейчас. Затем заполните приведенные ниже значения для инициализации базы данных Elgg.",
	'install:database:error' => 'Произошла ошибка при создании базы данных Elgg, и установка не может быть продолжена. Просмотрите сообщение выше и устраните все проблемы. Если вам нужна дополнительная помощь, перейдите по ссылке Install troubleshooting ниже или напишите на форумах сообщества Elgg.',

	'install:database:label:dbuser' =>  'Имя пользователя базы данных',
	'install:database:label:dbpassword' => 'Пароль от базы данных',
	'install:database:label:dbname' => 'Имя базы данных',
	'install:database:label:dbhost' => 'Хост базы данных',
	'install:database:label:dbport' => 'Номер порта базы данных',
	'install:database:label:dbprefix' => 'Префикс таблицы базы данных',
	'install:database:label:timezone' => "Часовой пояс",

	'install:database:help:dbuser' => 'Пользователь, имеющий полные привилегии к базе данных MySQL, которую вы создали для Elgg',
	'install:database:help:dbpassword' => 'Пароль для вышеуказанной учетной записи пользователя базы данных',
	'install:database:help:dbname' => 'Название базы данных Elgg',
	'install:database:help:dbhost' => 'Имя хоста сервера MySQL (обычно localhost)',
	'install:database:help:dbport' => 'Номер порта сервера MySQL (обычно 3306)',
	'install:database:help:dbprefix' => "Префикс, присваиваемый всем таблицам Elgg (обычно elgg_)",
	'install:database:help:timezone' => "Часовой пояс по умолчанию, в котором будет работать сайт",

	'install:settings:instructions' => 'Нам нужна некоторая информация о сайте, пока мы настраиваем Elgg. Если вы еще не <a href="http://learn.elgg.org/en/stable/intro/install.html#create-a-data-folder" target="_blank">создали каталог данных</a> для Elgg, вам нужно сделать это сейчас.',

	'install:settings:label:sitename' => 'Название сайта',
	'install:settings:label:siteemail' => 'Электронный адрес сайта',
	'install:database:label:wwwroot' => 'URL сайта',
	'install:settings:label:path' => 'Каталог установки Elgg',
	'install:database:label:dataroot' => 'Каталог данных',
	'install:settings:label:language' => 'Язык сайта',
	'install:settings:label:siteaccess' => 'Доступ к сайту по умолчанию',
	'install:label:combo:dataroot' => 'Elgg создает каталог данных',

	'install:settings:help:sitename' => 'Название вашего нового Elgg сайта',
	'install:settings:help:siteemail' => 'Адрес электронной почты, используемый Elgg для связи с пользователями',
	'install:database:help:wwwroot' => 'Адрес сайта (Elgg обычно угадывает его правильно)',
	'install:settings:help:path' => 'Каталог, в который вы поместили код Elgg (Elgg обычно угадывает его правильно)',
	'install:database:help:dataroot' => 'Каталог, который вы создали для Elgg, чтобы сохранять файлы (разрешения на этот каталог проверяются при нажатии кнопки Далее). Это должен быть абсолютный путь.',
	'install:settings:help:dataroot:apache' => 'У вас есть возможность создать каталог данных Elgg или ввести каталог, который вы уже создали для хранения пользовательских файлов (разрешения на этот каталог будут проверены, когда вы нажмете кнопку Далее).',
	'install:settings:help:language' => 'Язык сайта по умолчанию',
	'install:settings:help:siteaccess' => 'Уровень доступа по умолчанию для нового контента, созданного пользователем',

	'install:admin:instructions' => "Теперь пришло время создать учетную запись администратора.",

	'install:admin:label:displayname' => 'Отображаемое имя',
	'install:admin:label:email' => 'Электронный адрес',
	'install:admin:label:username' => 'Имя пользователя',
	'install:admin:label:password1' => 'Пароль',
	'install:admin:label:password2' => 'Повторите пароль',

	'install:admin:help:displayname' => 'Имя, которое отображается на сайте для этой учетной записи',
	'install:admin:help:username' => 'Имя пользователя учетной записи, используемое для входа в систему',
	'install:admin:help:password1' => "Пароль учетной записи должен быть длиной не менее %u символов",
	'install:admin:help:password2' => 'Повторите пароль для подтверждения',

	'install:admin:password:mismatch' => 'Пароль должен совпадать.',
	'install:admin:password:empty' => 'Пароль не может быть пустым.',
	'install:admin:password:tooshort' => 'Ваш пароль слишком короткий',
	'install:admin:cannot_create' => 'Невозможно создать учетную запись администратора.',

	'install:complete:instructions' => 'Теперь ваш Elgg сайт готов к использованию. Нажмите на кнопку ниже, чтобы перейти на ваш сайт.',
	'install:complete:gotosite' => 'Перейти на сайт',
	'install:complete:admin_notice' => 'Добро пожаловать на ваш сайт Elgg! Для получения дополнительной информации см. %s.',
	'install:complete:admin_notice:link_text' => 'страницы настроек',
	'install:complete:admin_notice:custom_index' => 'Мы включили плагин Front Page Demo, чтобы вы могли управлять своей главной страницей. Настройте его здесь: %s.',

	'InstallationException:CannotLoadSettings' => 'Elgg не смог загрузить файл настроек. Он не существует или проблема с правами на файл.',

	'install:success:database' => 'База данных установлена.',
	'install:success:settings' => 'Настройки сайта сохранены.',
	'install:success:admin' => 'Учетная запись администратора создана.',

	'install:error:htaccess' => 'Не удается создать .htaccess',
	'install:error:settings' => 'Невозможно создать файл настроек',
	'install:error:settings_mismatch' => 'Значение файла настроек для "%s" не соответствует заданному параметру $params. Должно быть: "%s" В действительности: "%s"',
	'install:error:databasesettings' => 'Невозможно подключиться к базе данных с данными настройками.',
	'install:error:database_prefix' => 'Недопустимые символы в префиксе базы данных',
	'install:error:mysql_version' => 'MySQL должен быть версии %s или выше. Ваш сервер использует %s.',
	'install:error:database_version' => 'База данных должна быть версии %s или выше. Ваш сервер использует %s.',
	'install:error:nodatabase' => 'Невозможно использовать базу данных %s. Она может не существовать.',
	'install:error:cannotloadtables' => 'Невозможно загрузить таблицы базы данных',
	'install:error:tables_exist' => 'В базе данных уже есть таблицы Elgg. Вам нужно либо удалить эти таблицы, либо перезапустить установку, а мы попытаемся их использовать. Чтобы перезапустить установку, уберите \'?step=database\' из URL в адресной строке браузера и нажмите Enter.',
	'install:error:readsettingsphp' => 'Невозможно прочитать /elgg-config/settings.example.php',
	'install:error:writesettingphp' => 'Невозможно записать файл /elgg-config/settings.php',
	'install:error:requiredfield' => 'Требуется %s',
	'install:error:relative_path' => 'Мы не думаем, что "%s" это абсолютный путь к каталогу данных',
	'install:error:datadirectoryexists' => 'Ваш каталог данных %s не существует.',
	'install:error:writedatadirectory' => 'Ваш каталог данных %s не доступен для записи веб-сервером.',
	'install:error:locationdatadirectory' => 'Для безопасности каталог данных %s должен находиться вне пути установки.',
	'install:error:emailaddress' => '%s не является действительным адресом электронной почты',
	'install:error:createsite' => 'Не удается создать сайт.',
	'install:error:savesitesettings' => 'Невозможно сохранить настройки сайта',
	'install:error:loadadmin' => 'Не удается загрузить пользователя - администритора.',
	'install:error:adminaccess' => 'Невозможно предоставить новой учетной записи пользователя привилегии администратора.',
	'install:error:adminlogin' => 'Невозможно автоматически войти в систему новому пользователю - администратору.',
	'install:error:rewrite:apache' => 'Мы думаем, что на вашем сервере установлен веб-сервер Apache.',
	'install:error:rewrite:nginx' => 'Мы думаем, что на вашем сервере установлен веб-сервер Nginx.',
	'install:error:rewrite:lighttpd' => 'Мы думаем, что на вашем сервере установлен веб-сервер Lighttpd.',
	'install:error:rewrite:iis' => 'Мы думаем, что на вашем сервере установлен веб-сервер IIS.',
	'install:error:rewrite:allowoverride' => "Тест перезаписи не удался, и наиболее вероятная причина заключается в том, что AllowOverride не установлен на All для каталога Elgg. Это не позволяет Apache обрабатывать файл .htaccess, который содержит правила перезаписи.
\n\nМенее вероятной причиной является то, что Apache настроен с псевдонимом для вашего каталога Elgg, и вам нужно установить RewriteBase в .htaccess. Дальнейшие инструкции содержатся в файле .htaccess в вашем каталоге Elgg.",
	'install:error:rewrite:htaccess:write_permission' => 'Ваш веб-сервер не имеет разрешения на создание файла .htaccess в каталоге Elgg. Вам нужно вручную скопировать install/config/htaccess.dist в .htaccess или изменить права доступа к директории.',
	'install:error:rewrite:htaccess:read_permission' => 'В каталоге Elgg есть файл .htaccess, но у вашего веб-сервера нет разрешения на его чтение.',
	'install:error:rewrite:htaccess:non_elgg_htaccess' => 'В каталоге Elgg есть файл .htaccess, который не был создан Elgg. Пожалуйста, удалите его.',
	'install:error:rewrite:htaccess:old_elgg_htaccess' => 'Похоже, что в каталоге Elgg есть старый файл Elgg .htaccess. Он не содержит правила перезаписи для тестирования веб-сервера.',
	'install:error:rewrite:htaccess:cannot_copy' => 'При создании файла .htaccess произошла неизвестная ошибка. Вам необходимо вручную скопировать install/config/htaccess.dist в .htaccess в директории Elgg.',
	'install:error:rewrite:altserver' => 'Тест правил перезаписи не удался. Вам необходимо настроить веб-сервер с правилами перезаписи Elgg и повторить попытку.',
	'install:error:rewrite:unknown' => 'Уф. Мы не смогли выяснить, что за веб-сервер работает на вашем сервере, и он не справился с правилами перезаписи. Мы не можем предложить никаких конкретных советов. Пожалуйста, проверьте ссылку по устранению неполадок.',
	'install:warning:rewrite:unknown' => 'Ваш сервер не поддерживает автоматическое тестирование правил перезаписи, а ваш браузер не поддерживает проверку с помощью JavaScript. Вы можете продолжить установку, но у вас могут возникнуть проблемы с вашим сайтом. Вы можете вручную проверить правила перезаписи, перейдя по этой ссылке: <a href="%s" target="_blank">тест</a>. Вы увидите слово успех, если правила работают.',
	'install:error:wwwroot' => '%s - неверный URL',

	// Bring over some error messages you might see in setup
	'exception:contact_admin' => 'Произошла неустранимая ошибка, которая была зарегистрирована. Если вы являетесь администратором сайта, проверьте файл настроек, в противном случае свяжитесь с администратором сайта, предоставив ему следующую информацию:',
	'DatabaseException:WrongCredentials' => "Elgg не смог подключиться к базе данных, используя указанные учетные данные. Проверьте файл настроек.",
);
