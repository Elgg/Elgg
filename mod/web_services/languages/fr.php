<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'APIException:ApiResultUnknown' => "Le résultat de l'API est d'un type inconnu, cela ne devrait jamais se produire. ",
	'APIException:MissingParameterInMethod' => "Il manque un paramètre %s à la méthode %s",
	'APIException:ParameterNotArray' => "%s ne semble pas être un tableau. ",
	'APIException:UnrecognisedTypeCast' => "Type non reconnu dans le typage (cast) \"%s\" pour la variable \"%s\" dans la méthode \"%s\" ",
	'APIException:InvalidParameter' => "Paramètre invalide trouvé pour \"%s\" dans la méthode \"%s\". ",
	'APIException:FunctionParseError' => "%s(%s) a une erreur d'analyse (parsing). ",
	'APIException:FunctionNoReturn' => "%s(%s) n'a pas retourné de valeur.",
	'APIException:APIAuthenticationFailed' => "La méthode d'appel a échoué lors de l'API Authentification",
	'APIException:UserAuthenticationFailed' => "La méthode d'appel a échoué lors de l'User Authentification",
	'APIException:MethodCallNotImplemented' => "La méthode d'appel \"%s\" n'est pas implémentée.",
	'APIException:FunctionDoesNotExist' => "La fonction pour la méthode \"%s\" n'est pas appelable",
	'APIException:AlgorithmNotSupported' => "L'algorithme \"%s\" n'est pas pris en charge ou a été désactivé.",
	'APIException:NotGetOrPost' => "La méthode de la requête doit être GET ou POST",
	'APIException:MissingAPIKey' => "Clé d'API manquante",
	'APIException:BadAPIKey' => "Mauvaise clé d'API",
	'APIException:MissingHmac' => "Entête X-Elgg-hmac manquant",
	'APIException:MissingHmacAlgo' => "Entête X-Elgg-hmac-algo manquant",
	'APIException:MissingTime' => "Entête X-Elgg-time manquant",
	'APIException:MissingNonce' => "Entête X-Elgg-nonce manquant",
	'APIException:TemporalDrift' => "X-Elgg-time est trop éloigné dans le passé ou le futur. Epoch a échoué.",
	'APIException:NoQueryString' => "Aucune donnée dans la chaîne de la requête",
	'APIException:MissingPOSTHash' => "Entête X-Elgg-posthash manquant",
	'APIException:MissingPOSTAlgo' => "Entête X-Elgg-posthash_algo manquant",
	'APIException:MissingContentType' => "Type de contenu manquant pour les données postées",
	'SecurityException:APIAccessDenied' => "Désolé, l'accès à l'API a été désactivé par l'administrateur.",
	'SecurityException:NoAuthMethods' => "Aucune méthode d'authentification n'a été trouvée qui pourrait authentifier cette demande à l'API.",
	'SecurityException:authenticationfailed' => "L'utilisateur n'a pas pu être authentifié",
	'InvalidParameterException:APIMethodOrFunctionNotSet' => "Méthode ou fonction non définie dans l'appel à expose_method()",
	'InvalidParameterException:APIParametersArrayStructure' => "La structure des paramètres du tableau est incorrecte pour l'appel à expose_method \"%s\"",
	'InvalidParameterException:UnrecognisedHttpMethod' => "Méthode http %s non reconnue pour la méthode \"%s\" de l'API",
	'SecurityException:AuthTokenExpired' => "Jeton d'authentification manquant, non valide ou périmé.",
	'SecurityException:InvalidPostHash' => "Les données de hachage du POST sont incorrectes - %s attendu mais reçu %s.",
	'SecurityException:DupePacket' => "La signature du paquet a déjà été vue.",
	'SecurityException:InvalidAPIKey' => "Clé d'API incorrecte ou manquante.",
	'NotImplementedException:CallMethodNotImplemented' => "La méthode d'appel \"%s\" n'est pas prise en charge.",
	'CallException:InvalidCallMethod' => "%s doit être appelé en utilisant \"%s\"",

	'system.api.list' => "Liste de tous les appels API disponibles sur le système.",
	'auth.gettoken' => "Cet appel à l'API permet à un utilisateur d'obtenir un jeton d'authentification d'utilisateur qui peut être utilisé pour authentifier les futurs appels à l'API. Passez-le en tant que paramètre auth_token",
	
	'admin:configure_utilities:webservices' => "Services web",
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
