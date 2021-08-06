<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'APIException:MissingParameterInMethod' => "Fehlender Parameter %s in Methode %s.",
	'APIException:ParameterNotArray' => "%s scheint kein Feld zu sein.",
	'APIException:UnrecognisedTypeCast' => "Unbekannter Typ in Cast %s für Variable '%s' in Methode '%s'.",
	'APIException:InvalidParameter' => "Ungültiger Parameter für '%s' in Methode '%s' gefunden.",
	'APIException:FunctionParseError' => "%s(%s) ergab einen Parsing-Fehler.",
	'APIException:FunctionNoReturn' => "%s(%s) lieferte keinen Rückgabewert.",
	'APIException:APIAuthenticationFailed' => "Beim Aufruf der Methode schlug die API-Authentifizierung fehl.",
	'APIException:MethodCallNotImplemented' => "Der Methoden-Aufruf '%s' ist nicht implementiert.",
	'APIException:FunctionDoesNotExist' => "Die Funktion für die Methode '%s' kann nicht aufgerufen werden.",
	'APIException:AlgorithmNotSupported' => "Algorithmus '%s' wird nicht unterstützt oder wurde deaktiviert.",
	'APIException:NotGetOrPost' => "Die Anfrage-Methode muß GET oder POST sein.",
	'APIException:MissingAPIKey' => "Fehlender API-Schlüssel.",
	'APIException:BadAPIKey' => "Ungültiger API-Schlüssel.",
	'APIException:MissingHmac' => "Fehlender X-Elgg-hmac Header.",
	'APIException:MissingHmacAlgo' => "Fehlender X-Elgg-hmac-algo Header.",
	'APIException:MissingTime' => "Fehlender X-Elgg-time Header.",
	'APIException:MissingNonce' => "Fehlender X-Elgg-nonce Header.",
	'APIException:TemporalDrift' => "Epoch-Fehler: X-Elgg-time liegt zu weit in der Vergangenheit oder Zukunft.",
	'APIException:MissingPOSTHash' => "Fehlender X-Elgg-posthash Header.",
	'APIException:MissingPOSTAlgo' => "Fehlender X-Elgg-posthash_algo Header.",
	'APIException:MissingContentType' => "Content-Typ für POST-Daten fehlt.",
	'APIException:InvalidCallMethod' => "%s muss mit '%s' aufgerufen werden.",
	'APIException:CallMethodNotImplemented' => "Anfrage-Methode '%s' wird derzeit nicht unterstützt.",
	'SecurityException:authenticationfailed' => "Der Benutzer konnte nicht authentifiziert werden.",
	'SecurityException:DuplicateEmailUser' => "Kein eindeutiger Benutzer für angegebene Emailadresse gefunden. Token konnte daher nicht erzeugt werden.",
	'SecurityException:BannedUser' => "Dieser Benutzeraccount ist derzeit gesperrt. Daher kann kein Token bereit gestellt werden.",
	'InvalidParameterException:APIParametersArrayStructure' => "Die Parameter-Feldstruktur im Aufruf von Expose-Methode '%s' ist falsch.",
	'InvalidParameterException:UnrecognisedHttpMethod' => "Unbekannte Http-Methode %s für API-Methode '%s'.",
	'SecurityException:InvalidPostHash' => "POST-Daten-Hash ist ungültig - erwartet wurde %s aber %s erhalten.",
	'SecurityException:DupePacket' => "Packet-Signatur ist schon von früher bekannt.",
	'SecurityException:InvalidAPIKey' => "Ungültiger oder fehlender API-Schlüssel.",
	'BadRequestException:MissingOutputViewInViewtype' => "Fehlende View 'api/output' für Viewtype '%s'.",
	'BadRequestException:MissingOutputViewInViewtype:DataViewsPlugin' => "Installiere/aktiviere das 'data_views'-Plugin, um diese View hinzuzufügen.",
	
	'system.api.list' => "Liste alle im System verfügbaren API-Aufrufe auf.",
	'auth.gettoken' => "Dieser API-Aufruf ermöglicht es einem Benutzer ein Authentifizierungs-Token zu beziehen, das für die Authentifizierung nachfolgender API-Aufrufe verwendet werden kann. Übergebe es als Parameter auth_token.",
	
	'admin:configure_utilities:webservices' => "Webservices",
	'admin:configure_utilities:ws_list' => "API-Methoden anzeigen",
	'admin:configure_utilities:ws_tokens' => "API-Tokens verwalten",
	'webservices:menu:entity:regenerate' => "API-Schlüssel neu erzeugen",
	'webservices:menu:entity:enable_keys' => "API-Schlüssel aktivieren",
	'webservices:menu:entity:disable_keys' => "API-Schlüssel deaktivieren",
	
	'add:object:api_key' => "Neuen API-Token erzeugen",
	'edit:object:api_key' => "API-Token bearbeiten: %s",
	'entity:delete:object:api_key:success' => "Der API-Token %s wurde gelöscht.",
	
	'webservices:requires_api_authentication' => "API-Authentifizierung notwendig",
	'webservices:requires_user_authentication' => "Benutzer-Authentifizierung notwendig",
	'webservices:function' => "Interne Funktion:",
	'webservices:parameters' => "Webservice-Parameter:",
	'webservices:parameters:required' => "notwendig",
	'webservices:parameters:optional' => "optional",
	
	'webservices:api_key:public' => "Öffentlicher Schlüssel:",
	'webservices:api_key:secret' => "Geheimer Schlüssel:",
	'webservices:api_key:secret:show' => "Geheimen Schlüssel anzeigen",
	
	'webservices:action:api_key:edit:success' => "Der API-Token wurde gespeichert.",
	'webservices:action:api_key:regenerate:success' => "Die API-Schüssel wurden neu erzeugt.",

	'webservices:action:api_key:toggle_active:enable:success' => "Die API-Schlüssel wurden aktiviert.",
	'webservices:action:api_key:toggle_active:enable:error' => "Beim Aktivieren der API-Schlüssel ist ein Fehler aufgetreten.",
	'webservices:action:api_key:toggle_active:disable:success' => "Die API-Schlüssel wurden deaktiviert.",
	'webservices:action:api_key:toggle_active:disable:error' => "Beim Deaktivieren der API-Schlüssel ist ein Fehler aufgetreten.",
	
	// plugin settings
	'web_services:settings:authentication' => "Web API-Authentifizierungs-Einstellungen",
	'web_services:settings:authentication:description' => "Für einige API-Methoden ist es notwendig, dass sich die externen Quellen authentifizieren. Dafür ist für diese externen Quellen ein Schlüsselpaar notwendig (öffentlicher und geheimer Schlüssel).

Beachte bitte, dass mindestens eine API-Authentifizierungsmethode aktiviert sein muss, damit API-Anfragen authentifiziert werden können.",
	'web_services:settings:authentication:allow_key' => "Einfache Authentifizierung mit öffentlichem Schlüssel erlauben",
	'web_services:settings:authentication:allow_key:help' => "Der öffentliche Schlüssel kann als Parameter bei der Anfrage übergeben werden.",
	'web_services:settings:authentication:allow_hmac' => "HMAC-Header API-Authentifizierung erlauben",
	'web_services:settings:authentication:allow_hmac:help' => "Bei der HMAC-Authentifizierung ist es notwendig, dass ein spezieller Header mit der Anfrage übergeben wird, damit die Authenzität der Anfrage sichergestellt werden kann.",
);
