<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'APIException:MissingParameterInMethod' => "Отсутствует параметр %s в методе %s",
	'APIException:ParameterNotArray' => "%s не выглядит массивом.",
	'APIException:UnrecognisedTypeCast' => "Нераспознанный тип в %s для переменной '%s' в методе '%s'",
	'APIException:InvalidParameter' => "Неверный параметр найден для '%s' в методе '%s'.",
	'APIException:FunctionParseError' => "%s(%s) имеет ошибку парсинга.",
	'APIException:FunctionNoReturn' => "%s(%s) не вернул значения.",
	'APIException:APIAuthenticationFailed' => "Вызов метода не прошел аутентификацию API",
	'APIException:MethodCallNotImplemented' => "Вызов метода '%s' не реализован.",
	'APIException:FunctionDoesNotExist' => "Функция для метода '%s' не может быть вызвана",
	'APIException:AlgorithmNotSupported' => "Алгоритм '%s' не поддерживается или отключен.",
	'APIException:NotGetOrPost' => "Метод запроса должен быть GET или POST",
	'APIException:MissingAPIKey' => "Отсутствует ключ API",
	'APIException:BadAPIKey' => "Неверный ключ API",
	'APIException:MissingHmac' => "Отсутствует заголовок X-Elgg-hmac",
	'APIException:MissingHmacAlgo' => "Отсутствует заголовок X-Elgg-hmac-algo",
	'APIException:MissingTime' => "Отсутствует заголовок X-Elgg-time",
	'APIException:MissingNonce' => "Отсутствует заголовок X-Elgg-nonce",
	'APIException:TemporalDrift' => "X-Elgg-time слишком далеко в прошлом или будущем. Ошибка эпохи.",
	'APIException:MissingPOSTHash' => "Отсутствует заголовок X-Elgg-posthash",
	'APIException:MissingPOSTAlgo' => "Отсутствует заголовок X-Elgg-posthash_algo",
	'APIException:MissingContentType' => "Отсутствует тип контента для публикации данных",
	'APIException:InvalidCallMethod' => "%s должен вызываться с использованием '%s'",
	'APIException:CallMethodNotImplemented' => "Метод вызова '%s' в настоящее время не поддерживается.",
	'SecurityException:authenticationfailed' => "Пользователь не может быть аутентифицирован",
	'SecurityException:BannedUser' => "Эта учетная запись пользователя заблокирована, токен не может быть предоставлен.",
	'InvalidArgumentException:APIParametersArrayStructure' => "Структура массива параметров неверна для вызова метода expose '%s'",
	'DomainException:UnrecognisedHttpMethod' => "Неизвестный http метод %s для api метода '%s'",
	'SecurityException:InvalidPostHash' => "Хэш данных POST недействителен — Ожидалось %s получено %s.",
	'SecurityException:DupePacket' => "Подпись пакета уже видна.",
	'SecurityException:InvalidAPIKey' => "Недействительный или отсутствующий ключ API.",
	'BadRequestException:MissingOutputViewInViewtype' => "Отсутствует представление 'api/output' в типе представления '%s'.",
	'BadRequestException:MissingOutputViewInViewtype:DataViewsPlugin' => "Включите плагин 'data_views' чтобы добавить это представление.",
	
	'web_services:api_methods:system.api.list:get:description' => "Список всех доступных API вызовов в системе.",
	'web_services:api_methods:auth.gettoken:post:description' => "Этот API вызов позволяет пользователю получить пользовательский токен аутентификации, который можно использовать для аутентификации будущих API вызовов. Передайте его, как параметр auth_token",
	
	'admin:configure_utilities:webservices' => "Веб-сервисы",
	'admin:configure_utilities:ws_list' => "Список методов API",
	'admin:configure_utilities:ws_tokens' => "Управление токенами API",
	'webservices:menu:entity:regenerate' => "Восстановить ключи API",
	'webservices:menu:entity:enable_keys' => "Включить ключи API",
	'webservices:menu:entity:disable_keys' => "Отключить ключи API",
	
	'add:object:api_key' => "Создайте новый токен API",
	'edit:object:api_key' => "Изменить токен API: %s",
	'entity:delete:object:api_key:success' => "Токен API %s был удален",
	
	'webservices:requires_api_authentication' => "Требуется аутентификация API",
	'webservices:requires_user_authentication' => "Требуется аутентификация пользователя",
	'webservices:function' => "Внутренняя функция:",
	'webservices:parameters' => "Параметры веб-сервиса:",
	'webservices:parameters:required' => "требуется",
	'webservices:parameters:optional' => "опционально",
	
	'webservices:api_key:public' => "Открытый ключ:",
	'webservices:api_key:secret' => "Секретный ключ:",
	'webservices:api_key:secret:show' => "Показать секретный ключ",
	
	'webservices:action:api_key:edit:success' => "Токен API успешно сохранен",
	'webservices:action:api_key:regenerate:success' => "Ключи API были перегенерированы",

	'webservices:action:api_key:toggle_active:enable:success' => "Ключи API были успешно включены",
	'webservices:action:api_key:toggle_active:enable:error' => "Произошла ошибка при включении ключей API",
	'webservices:action:api_key:toggle_active:disable:success' => "Ключи API успешно отключены",
	'webservices:action:api_key:toggle_active:disable:error' => "Произошла ошибка при отключении ключей API",
	
	// plugin settings
	'web_services:settings:authentication' => "Настройки аутентификации веб API",
	'web_services:settings:authentication:description' => "Некоторые методы API требуют, чтобы внешние источники аутентифицировали себя. Этим внешним источникам необходимо предоставить пару ключей API (открытый и секретный ключ).

Обратите внимание, что по крайней мере один метод аутентификации API должен быть активен для аутентификации запросов API..",
	'web_services:settings:authentication:allow_key' => "Разрешить базовую аутентификацию с открытым ключом API",
	'web_services:settings:authentication:allow_key:help' => "Открытый ключ API можно передать в качестве параметра в запросе.",
	'web_services:settings:authentication:allow_hmac' => "Разрешить аутентификацию API заголовка HMAC",
	'web_services:settings:authentication:allow_hmac:help' => "При аутентификации HMAC в запросе необходимо передавать специальные заголовки, чтобы гарантировать подлинность запроса.",
);
