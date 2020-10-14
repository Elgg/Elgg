<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'APIException:ApiResultUnknown' => "Der Typ der API-Rückgabe ist unbekannt. Das sollte nicht passieren.",
	'APIException:MissingParameterInMethod' => "Fehlender Parameter %s in Methode %s.",
	'APIException:ParameterNotArray' => "%s scheint kein Feld zu sein.",
	'APIException:UnrecognisedTypeCast' => "Unbekannter Typ in Cast %s für Variable '%s' in Methode '%s'.",
	'APIException:InvalidParameter' => "Ungültiger Parameter für '%s' in Methode '%s' gefunden.",
	'APIException:FunctionParseError' => "%s(%s) ergab einen Parsing-Fehler.",
	'APIException:FunctionNoReturn' => "%s(%s) lieferte keinen Rückgabewert.",
	'APIException:APIAuthenticationFailed' => "Beim Aufruf der Methode schlug die API-Authentifizierung fehl.",
	'APIException:UserAuthenticationFailed' => "Beim Aufruf der Methode schlug die Benutzer-Authentifizierung fehl.",
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
	'APIException:NoQueryString' => "Keine Daten im Query-String.",
	'APIException:MissingPOSTHash' => "Fehlender X-Elgg-posthash Header.",
	'APIException:MissingPOSTAlgo' => "Fehlender X-Elgg-posthash_algo Header.",
	'APIException:MissingContentType' => "Content-Typ für POST-Daten fehlt.",
	'SecurityException:APIAccessDenied' => "Entschuldigung, der API-Zugriff wurde durch den Administrator deaktiviert.",
	'SecurityException:NoAuthMethods' => "Es konnte keine Authentifizierungs-Methode gefunden werden, um diesen API-Zugriff zu authentifizieren.",
	'SecurityException:authenticationfailed' => "Der Benutzer konnte nicht authentifiziert werden.",
	'InvalidParameterException:APIMethodOrFunctionNotSet' => "Die Methode oder Funktion wurde im Aufruf in expose_method() nicht gesetzt.",
	'InvalidParameterException:APIParametersArrayStructure' => "Die Parameter-Feldstruktur im Aufruf von Expose-Methode '%s' ist falsch.",
	'InvalidParameterException:UnrecognisedHttpMethod' => "Unbekannte Http-Methode %s für API-Methode '%s'.",
	'SecurityException:AuthTokenExpired' => "Entweder fehlt das Authentifizierungs-Token oder es ist ungültig oder abgelaufen.",
	'SecurityException:InvalidPostHash' => "POST-Daten-Hash ist ungültig - erwartet wurde %s aber %s erhalten.",
	'SecurityException:DupePacket' => "Packet-Signatur ist schon von früher bekannt.",
	'SecurityException:InvalidAPIKey' => "Ungültiger oder fehlender API-Schlüssel.",
	'NotImplementedException:CallMethodNotImplemented' => "Der Methoden-Aufruf '%s' wird derzeit nicht unterstützt.",
	'CallException:InvalidCallMethod' => "%s muß unter Verwendung von '%s' aufgerufen werden.",

	'system.api.list' => "Liste alle im System verfügbaren API-Aufrufe auf.",
	'auth.gettoken' => "Dieser API-Aufruf ermöglicht es einem Benutzer ein Authentifizierungs-Token zu beziehen, das für die Authentifizierung nachfolgender API-Aufrufe verwendet werden kann. Übergebe es als Parameter auth_token.",
	
	'admin:configure_utilities:webservices' => "Webservices",
	'admin:configure_utilities:ws_list' => "List API methods",
	'admin:configure_utilities:ws_tokens' => "Manage API tokens",
	'webservices:menu:entity:regenerate' => "Regenerate API keys",
	
	'add:object:api_key' => "Create a new API token",
	'edit:object:api_key' => "Edit API token: %s",
	'entity:delete:object:api_key:success' => "The API token %s was deleted",
	
	'webservices:requires_api_authentication' => "Requires API authentication",
	'webservices:requires_user_authentication' => "Requires user authentication",
	'webservices:function' => "Internal function:",
	'webservices:parameters' => "Webservice parameters:",
	'webservices:parameters:required' => "required",
	'webservices:parameters:optional' => "optional",
	
	'webservices:api_key:public' => "Public key:",
	'webservices:api_key:secret' => "Secret key:",
	'webservices:api_key:secret:show' => "Show secret key",
	
	'webservices:action:api_key:edit:success' => "API token saved successfully",
	'webservices:action:api_key:regenerate:success' => "The API keys have been regenerated",

	// plugin settings
	'web_services:settings:authentication' => "Web API authentication settings",
	'web_services:settings:authentication:description' => "Some API methods require that the external sources authenticate themselves. These external sources need to be provided with an API key pair (public and secret key).

Please note that at least one API authentication method needs to be active in order to authenticate API requests.",
	'web_services:settings:authentication:allow_key' => "Allow basic API public key authentication",
	'web_services:settings:authentication:allow_key:help' => "The API public key can be passed as a parameter in the request.",
	'web_services:settings:authentication:allow_hmac' => "Allow HMAC header API authentication",
	'web_services:settings:authentication:allow_hmac:help' => "With HMAC authentication special headers need to be passed in a request to ensure authenticity of the request.",
);
