<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'APIException:ApiResultUnknown' => "El resultado de la API es del tipo desconocido, esto nunca debe pasar.",
	'APIException:MissingParameterInMethod' => "Falta parámetro %s en método %s",
	'APIException:ParameterNotArray' => "%s no parece estar en un arreglo.",
	'APIException:UnrecognisedTypeCast' => "Tipo desconocido en la conversión (cast) «%s» para la variable «%s» en el método «%s».",
	'APIException:InvalidParameter' => "Parámetro inválido encontrado por '%s' en método '%s'.",
	'APIException:FunctionParseError' => "%s(%s) tiene un error de análisis",
	'APIException:FunctionNoReturn' => "%s(%s) no retorna un valor",
	'APIException:APIAuthenticationFailed' => "Método de llamada fallo la autenticación del API",
	'APIException:UserAuthenticationFailed' => "Método de llamada fallo la autenticación del Usuario",
	'APIException:MethodCallNotImplemented' => "Método de llamada '%s' no ha sido implementado.",
	'APIException:FunctionDoesNotExist' => "La función para el método '%s' no es llamable",
	'APIException:AlgorithmNotSupported' => "El algoritmo '%s' no esta soportado o ha sido desactivado",
	'APIException:NotGetOrPost' => "El método requerido debe ser GET o POST",
	'APIException:MissingAPIKey' => "No se encuentra la clave del API",
	'APIException:BadAPIKey' => "Clave de API errónea",
	'APIException:MissingHmac' => "No se encuentra el encabezado X-Elgg-hmac",
	'APIException:MissingHmacAlgo' => "No se encuentra el encabezado X-Elgg-hmac-algo",
	'APIException:MissingTime' => "No se encuentra el encabezado X-Elgg-time",
	'APIException:MissingNonce' => "No se encuentra el encabezado X-Elgg-nonce",
	'APIException:TemporalDrift' => "X-Elgg-time esta muy atrasado o adelantado en el tiempo. Epoch falló.",
	'APIException:NoQueryString' => "No hay datos en la consulta",
	'APIException:MissingPOSTHash' => "No se encuentra el encabezado X-Elgg-posthash",
	'APIException:MissingPOSTAlgo' => "No se encuentra el encabezado X-Elgg-posthash_algo",
	'APIException:MissingContentType' => "No se encuentra el tipo de contenido para el post data",
	'SecurityException:APIAccessDenied' => "Lo sentimos, acceso al API ha sido desactivado por el administrador",
	'SecurityException:NoAuthMethods' => "No se encontraron métodos de autenticacion para autenticar la solicitud de API",
	'SecurityException:authenticationfailed' => "Usuario no pudo ser autenticado",
	'InvalidParameterException:APIMethodOrFunctionNotSet' => "Método o función no ha sido establecido en el llamado expose_method()",
	'InvalidParameterException:APIParametersArrayStructure' => "La estructura de parámetros en el arreglo es incorrecto para la llamada de exponer método '%s'",
	'InvalidParameterException:UnrecognisedHttpMethod' => "Método http %s no reconocido para el método API %s",
	'SecurityException:AuthTokenExpired' => "El token de autenticación no se encuentra, inválido o ha expirado.",
	'SecurityException:InvalidPostHash' => "La suma (hash) de los datos POST no es válida — se esperaba «%s», pero se encontró «%s».",
	'SecurityException:DupePacket' => "La firma del paquete está repetida.",
	'SecurityException:InvalidAPIKey' => "La clave del API no es válida o no está definida.",
	'NotImplementedException:CallMethodNotImplemented' => "El método de llamada «%s» no se permite actualmente.",
	'CallException:InvalidCallMethod' => "Para llamar a «%s» debe hacerlo con «%s».",

	'system.api.list' => "Muestra una lista de todas las llamadas a la API disponibles en el sistema.",
	'auth.gettoken' => "Esta llamada a la API permite a un usuario obtener un código de autenticación que puede usarse para autenticar futuras llamadas a la API. Pásela como valor del parámetro «auth_token».",
	
	'admin:configure_utilities:webservices' => "Servicios web",
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
