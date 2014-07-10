<?php
return array(
	'twitter_api' => 'Сервисы Twitter',

	'twitter_api:requires_oauth' => 'Сервисы Twitter требуют, чтобы плагин OAuth был включен.',

	'twitter_api:consumer_key' => 'Ключ пользователя',
	'twitter_api:consumer_secret' => 'Секретная фраза пользователя',

	'twitter_api:settings:instructions' => 'Вам необходимо получить ключ пользователя и секретную фразу пользователя от <a href="https://dev.twitter.com/apps/new" target="_blank">Twitter</a>. Заполните карту нового приложения. Выберите Browser как тип приложения и "Чтение и Запись" как тип доступа. Ссылка обратной связи: %stwitter_api/authorize',

	'twitter_api:usersettings:description' => "Связать ваш аккаунт %s с Twitter.",
	'twitter_api:usersettings:request' => "Вы должны сначала <a href=\"%s\">авторизировать</a> %s для доступа к вашему Twitter аккаунту.",
	'twitter_api:usersettings:cannot_revoke' => "Вы не можете отвязать ваш аккаунт от Twitter, так как вы не указали email адреса или пароля. <a href=\"%s\">Указать сейчас</a>.",
	'twitter_api:authorize:error' => 'Авторизация в Twitter не прошла.',
	'twitter_api:authorize:success' => 'Доступ к Twitter авторизирован.',

	'twitter_api:usersettings:authorized' => "Вы авторизировали %s для доступа к вашему Twitter аккаунту: @%s.",
	'twitter_api:usersettings:revoke' => 'Нажмите <a href="%s">здесь</a> для отмены доступа.',
	'twitter_api:usersettings:site_not_configured' => 'Администратор должен настроить Twitter перед тем, как вы сможете его использовать.',

	'twitter_api:revoke:success' => 'Доступ к Twitter был отозван.',

	'twitter_api:post_to_twitter' => "Отправлять записи микроблога пользователя в Twitter?",

	'twitter_api:login' => 'Позволять пользователям входить с помощью Twitter?',
	'twitter_api:new_users' => 'Позволять новым пользователям регистрироваться, используя их Twiiter аккаунт, даже если регистрация пользователей запрещена?',
	'twitter_api:login:success' => 'Добро пожаловать!',
	'twitter_api:login:error' => 'Не могу войти с помощью Twitter.',
	'twitter_api:login:email' => "Вы должны ввести корректный email адрес для вашего нового аккаунта %s.",

	'twitter_api:invalid_page' => 'Ошибочная страница',

	'twitter_api:deprecated_callback_url' => 'Ссылка обратной связи для Твиттера изменилась на %s. Пожалуйста, обратитесь к администратору для ее замены.',

	'twitter_api:interstitial:settings' => 'Ваши настройки',
	'twitter_api:interstitial:description' => 'Вы практически готовы к использованию %s! Нам необходимо знать несколько деталей прежде, чем продолжить. Они опциональны, но их установка позволит вам залогиниться, если сервет Твиттер упадет или вы решите отвязать ваши аккаунты.',

	'twitter_api:interstitial:username' => 'Это ваше имя пользователя. Оно Не может быть изменено. Если вы устанавливаете пароль, вы можете использовать ваше имя пользователя или ваш email для входа.',

	'twitter_api:interstitial:name' => 'Это имя, которое будут видеть другие пользователи.',

	'twitter_api:interstitial:email' => 'Ваш email адрес. Пользователи по умолчанию его не увидят.',

	'twitter_api:interstitial:password' => 'Пароль для логина, если Twitter не работает или вы решили отвязать ваш аккаунт.',
	'twitter_api:interstitial:password2' => 'Тот же пароль снова.',

	'twitter_api:interstitial:no_thanks' => 'Нет, спасибо',

	'twitter_api:interstitial:no_display_name' => 'У Вас должно быть отображаемое имя.',
	'twitter_api:interstitial:invalid_email' => 'Вы должны указать корректный email адрес или оставьте строку пустой.',
	'twitter_api:interstitial:existing_email' => 'Этот email адрес уже зарегистрирован на сайте.',
	'twitter_api:interstitial:password_mismatch' => 'Ваши пароли не совпадают.',
	'twitter_api:interstitial:cannot_save' => 'Не могу сохранить аккаунт.',
	'twitter_api:interstitial:saved' => 'Аккаунт сохранен!',
);
