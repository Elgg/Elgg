<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'APIException:ApiResultUnknown' => "Non se coñece o tipo do resultado da API, isto non debería acontecer nunca.",
	'APIException:MissingParameterInMethod' => "Falta o parámetro «%s» do método «%s».",
	'APIException:ParameterNotArray' => "«%s» non parece ser un vector",
	'APIException:UnrecognisedTypeCast' => "Non se recoñeceu o tipo na conversión «%s» da variábel «%s» no método «%s».",
	'APIException:InvalidParameter' => "Atopouse un parámetro non válido para «%s» no método «%s».",
	'APIException:FunctionParseError' => "«%s(%s)» ten un erro sintáctico.",
	'APIException:FunctionNoReturn' => "«%s(%s)» non devolveu ningún valor.",
	'APIException:APIAuthenticationFailed' => "A chamada ao método non pasou da API de autenticación.",
	'APIException:UserAuthenticationFailed' => "A chamada ao método non pasou da autenticación do usuario.",
	'APIException:MethodCallNotImplemented' => "A chamada ao método «%s» non está definida.",
	'APIException:FunctionDoesNotExist' => "Non se pode chamar á función para o método «%s».",
	'APIException:AlgorithmNotSupported' => "O algoritmo «%s» é incompatíbel ou está desactivado.",
	'APIException:NotGetOrPost' => "O método da solicitude ten que ser GET ou POST.",
	'APIException:MissingAPIKey' => "Falta a chave da API",
	'APIException:BadAPIKey' => "A chave da API non é válida.",
	'APIException:MissingHmac' => "Falta a cabeceira «X-Elgg-hmac».",
	'APIException:MissingHmacAlgo' => "Falta a cabeceira «X-Elgg-hmac-algo».",
	'APIException:MissingTime' => "Falta a cabeceira «X-Elgg-time».",
	'APIException:MissingNonce' => "Falta a cabeceira «X-Elgg-nonce».",
	'APIException:TemporalDrift' => "«X-Elgg-time» está demasiado afastado no pasado ou no futuro, produciuse un erro de época.",
	'APIException:NoQueryString' => "A cadea de consulta non ten datos",
	'APIException:MissingPOSTHash' => "Falta a cabeceira «X-Elgg-posthash».",
	'APIException:MissingPOSTAlgo' => "Falta a cabeceira «X-Elgg-posthash_algo».",
	'APIException:MissingContentType' => "O tipo de contido dos datos POST non está definido",
	'SecurityException:APIAccessDenied' => "O administrador desactivou o acceso á API.",
	'SecurityException:NoAuthMethods' => "Non se atopou ningún método de autenticación capaces de xestionar esta solicitude á API.",
	'SecurityException:authenticationfailed' => "Non foi posíbel autenticar o usuario.",
	'InvalidParameterException:APIMethodOrFunctionNotSet' => "O método ou función non está definido na chamada a «expose_method()»",
	'InvalidParameterException:APIParametersArrayStructure' => "A estrutura vector dos parámetros non é correcta na chamada a expoñer o método «%s».",
	'InvalidParameterException:UnrecognisedHttpMethod' => "Non se recoñeceu o método HTTP %s para o método «%s» da API.",
	'SecurityException:AuthTokenExpired' => "O código de autenticación non está, non é válido ou caducou.",
	'SecurityException:InvalidPostHash' => "A suma de comprobación dos datos POST non é válida, esperábase «%s» pero obtívose «%s».",
	'SecurityException:DupePacket' => "A sinatura do paquete está repetida.",
	'SecurityException:InvalidAPIKey' => "A chave da API non e válida ou non está definida.",
	'NotImplementedException:CallMethodNotImplemented' => "Nestes momentos non é posíbel chamar ao método «%s».",
	'CallException:InvalidCallMethod' => "Debe chamarse a %s usando «%s»",

	'system.api.list' => "Listar todas as chamadas á API dispoñíbeis no sistema.",
	'auth.gettoken' => "Esta chamada á API permite ao usuario obter un código de autenticación que pode usarse para autenticar chamadas futuras á API. Pasa o código como valor do parámetro «auth_token».",
	
	'admin:configure_utilities:webservices' => "Servizos web",
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
