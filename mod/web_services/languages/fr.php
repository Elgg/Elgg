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
	'admin:configure_utilities:ws_list' => "Lister les méthodes de l'API",
	'admin:configure_utilities:ws_tokens' => "Gérer les jetons de l'API",
	'webservices:menu:entity:regenerate' => "Régénérer les clefs d'API",
	
	'add:object:api_key' => "Créer un nouveau jeton d'API",
	'edit:object:api_key' => "Modifier le jeton d'API : %s",
	'entity:delete:object:api_key:success' => "Le jeton d'api %s a été supprimé",
	
	'webservices:requires_api_authentication' => "Nécessite une authentification auprès de l'API",
	'webservices:requires_user_authentication' => "Nécessite l'authentification de l'utilisateur",
	'webservices:function' => "Fonction interne :",
	'webservices:parameters' => "Paramètres du webservice :",
	'webservices:parameters:required' => "requis",
	'webservices:parameters:optional' => "optionnel",
	
	'webservices:api_key:public' => "Clef publique : ",
	'webservices:api_key:secret' => "Clef secrète : ",
	'webservices:api_key:secret:show' => "Afficher la clef secrète",
	
	'webservices:action:api_key:edit:success' => "Le jeton d'API a bien été enregistré",
	'webservices:action:api_key:regenerate:success' => "Les clefs d'API ont été régénérées",

	// plugin settings
	'web_services:settings:authentication' => "Paramètres d'authentification de l'API web",
	'web_services:settings:authentication:description' => "Certaines méthodes d'API nécessitent que les sources externes s'authentifient elles-même. Ces ressources externes doivent être fournies avec une paire de clefs d'API (clef publique et secrète).

Veuillez noter qu'au moins une des méthodes d'authentification doit être active afin d'authentifier les requêtes auprès de l'API.",
	'web_services:settings:authentication:allow_key' => "Permettre une authentification basique par clef publique",
	'web_services:settings:authentication:allow_key:help' => "La clef d'API publique peut être passée comme paramètre dans la requête.",
	'web_services:settings:authentication:allow_hmac' => "Autoriser l'entête HMAC d'authentification auprès de l'API",
	'web_services:settings:authentication:allow_hmac:help' => "Avec l'authentification HMAC des entêtes particuliers doivent être passés dans la requête pour assurer l'authenticité de la la requête.",
);
