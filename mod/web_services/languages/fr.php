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
	'APIException:UnrecognisedTypeCast' => "Type non reconnu dans le typage '%s' pour la variable '%s' dans la méthode '%s' ",
	'APIException:InvalidParameter' => "Paramètre invalide trouvé pour \"%s\" dans la méthode \"%s\". ",
	'APIException:FunctionParseError' => "%s(%s) a une erreur d'analyse (parsing).",
	'APIException:FunctionNoReturn' => "%s(%s) n'a pas retourné de valeur.",
	'APIException:APIAuthenticationFailed' => "L'appel de la méthode a échoué lors de l'authentification après de l'API",
	'APIException:UserAuthenticationFailed' => "L'appel de la méthode a échoué lors de l'authentification de l'utilisateur",
	'APIException:MethodCallNotImplemented' => "L'appel de la méthode '%s' n'est pas implémenté.",
	'APIException:FunctionDoesNotExist' => "La fonction pour la méthode '%s' n'est pas appelable",
	'APIException:AlgorithmNotSupported' => "L'algorithme '%s' n'est pas pris en charge ou a été désactivé.",
	'APIException:NotGetOrPost' => "La méthode de la requête doit être GET ou POST",
	'APIException:MissingAPIKey' => "Clé d'API manquante",
	'APIException:BadAPIKey' => "Mauvaise clé d'API",
	'APIException:MissingHmac' => "Entête X-Elgg-hmac manquant",
	'APIException:MissingHmacAlgo' => "Entête X-Elgg-hmac-algo manquant",
	'APIException:MissingTime' => "Entête X-Elgg-time manquant",
	'APIException:MissingNonce' => "Entête X-Elgg-nonce manquant",
	'APIException:TemporalDrift' => "X-Elgg-time est trop éloigné dans le passé ou le futur. Epoch a échoué.",
	'APIException:NoQueryString' => "Aucune donnée dans la chaîne de requête",
	'APIException:MissingPOSTHash' => "Entête X-Elgg-posthash manquant",
	'APIException:MissingPOSTAlgo' => "Entête X-Elgg-posthash_algo manquant",
	'APIException:MissingContentType' => "Type de contenu manquant pour les données POST",
	'APIException:InvalidCallMethod' => "%s doit être appelé en utilisant '%s'",
	'APIException:CallMethodNotImplemented' => "L'appel à la méthode '%s' n'est actuellement pas supporté.",
	'SecurityException:APIAccessDenied' => "Désolé, l'accès à l'API a été désactivé par l'administrateur.",
	'SecurityException:NoAuthMethods' => "Aucune méthode d'authentification n'a été trouvée qui pourrait authentifier cette requête à l'API.",
	'SecurityException:authenticationfailed' => "L'utilisateur n'a pas pu être authentifié",
	'SecurityException:DuplicateEmailUser' => "Aucun utilisateur unique trouvé pour l'adresse email fournie. Impossible de récupérer un jeton.",
	'SecurityException:BannedUser' => "Ce compte utilisateur est banni, aucun jeton ne peut être fourni.",
	'InvalidParameterException:APIMethodOrFunctionNotSet' => "Méthode ou fonction non définie dans l'appel à expose_method()",
	'InvalidParameterException:APIParametersArrayStructure' => "La structure des paramètres du tableau est incorrecte pour l'appel pour exposer la méthode '%s'",
	'InvalidParameterException:UnrecognisedHttpMethod' => "Méthode http %s non reconnue pour la méthode '%s' de l'API",
	'SecurityException:AuthTokenExpired' => "Le jeton d'authentification est manquant, invalide ou périmé.",
	'SecurityException:InvalidPostHash' => "Le hachage des données POST est invalide - %s attendu mais reçu %s.",
	'SecurityException:DupePacket' => "La signature du paquet a déjà été vue.",
	'SecurityException:InvalidAPIKey' => "Clé d'API incorrecte ou manquante.",
	'BadRequestException:MissingOutputViewInViewtype' => "Vue 'api/output' manquante dans le viewtype '%s'.",
	'BadRequestException:MissingOutputViewInViewtype:DataViewsPlugin' => "Activez le plugin 'data_views' pour ajouter cette vue.",
	
	'system.api.list' => "Liste tous les appels d'API disponibles sur le système.",
	'auth.gettoken' => "Cet appel à l'API permet à un utilisateur d'obtenir un jeton d'authentification utilisateur qui peut être utilisé pour authentifier les futurs appels à l'API. Passez-le en tant que paramètre auth_token",
	
	'admin:configure_utilities:webservices' => "Webservices",
	'admin:configure_utilities:ws_list' => "Lister les méthodes de l'API",
	'admin:configure_utilities:ws_tokens' => "Gérer les jetons de l'API",
	'webservices:menu:entity:regenerate' => "Régénérer les clefs d'API",
	'webservices:menu:entity:enable_keys' => "Activer les clefs d'API",
	'webservices:menu:entity:disable_keys' => "Désactiver les clefs d'API",
	
	'add:object:api_key' => "Créer un nouveau jeton d'API",
	'edit:object:api_key' => "Modifier le jeton d'API : %s",
	'entity:delete:object:api_key:success' => "Le jeton d'API %s a été supprimé",
	
	'webservices:requires_api_authentication' => "Nécessite une authentification API",
	'webservices:requires_user_authentication' => "Nécessite une authentification utilisateur",
	'webservices:function' => "Fonction interne :",
	'webservices:parameters' => "Paramètres du webservice :",
	'webservices:parameters:required' => "requis",
	'webservices:parameters:optional' => "optionnel",
	
	'webservices:api_key:public' => "Clef publique :",
	'webservices:api_key:secret' => "Clef secrète :",
	'webservices:api_key:secret:show' => "Afficher la clef secrète",
	
	'webservices:action:api_key:edit:success' => "Le jeton d'API a bien été enregistré",
	'webservices:action:api_key:regenerate:success' => "Les clefs d'API ont bien été régénérées",

	'webservices:action:api_key:toggle_active:enable:success' => "Les clefs d'API ont bien été activées",
	'webservices:action:api_key:toggle_active:enable:error' => "Une erreur est survenue lors de l'activation des clefs d'API",
	'webservices:action:api_key:toggle_active:disable:success' => "Les clefs d'API ont bien été désactivées",
	'webservices:action:api_key:toggle_active:disable:error' => "Une erreur est survenue lors de la désactivation des clefs d'API",
	
	// plugin settings
	'web_services:settings:authentication' => "Paramètres d'authentification de l'API web",
	'web_services:settings:authentication:description' => "Certaines méthodes d'API nécessitent que les sources externes s'authentifient elles-même. Ces ressources externes doivent être fournies avec une paire de clefs d'API (clef publique et secrète).

Veuillez noter qu'au moins une des méthodes d'authentification doit être active afin d'authentifier les requêtes auprès de l'API.",
	'web_services:settings:authentication:allow_key' => "Permettre une authentification basique par clef publique",
	'web_services:settings:authentication:allow_key:help' => "La clef d'API publique peut être passée comme paramètre dans la requête.",
	'web_services:settings:authentication:allow_hmac' => "Autoriser l'entête HMAC d'authentification auprès de l'API",
	'web_services:settings:authentication:allow_hmac:help' => "Avec l'authentification HMAC des entêtes particuliers doivent être passés dans la requête pour assurer l'authenticité de la la requête.",
);
