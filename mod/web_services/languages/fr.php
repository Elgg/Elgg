<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'APIException:MissingParameterInMethod' => "Il manque un paramètre %s à la méthode %s",
	'APIException:ParameterNotArray' => "%s ne semble pas être un tableau. ",
	'APIException:UnrecognisedTypeCast' => "Type non reconnu dans le typage %s pour la variable \"%s\" dans la méthode \"%s\"",
	'APIException:InvalidParameter' => "Paramètre invalide trouvé pour \"%s\" dans la méthode \"%s\". ",
	'APIException:FunctionParseError' => "%s(%s) a une erreur d'analyse.",
	'APIException:FunctionNoReturn' => "%s(%s) n'a pas retourné de valeur.",
	'APIException:APIAuthenticationFailed' => "La méthode d'appel a échoué lors de l'authentification auprès de l'API",
	'APIException:MethodCallNotImplemented' => "La méthode d'appel \"%s\" n'est pas implémentée.",
	'APIException:FunctionDoesNotExist' => "La fonction pour la méthode \"%s\" n'est pas appelable",
	'APIException:AlgorithmNotSupported' => "L'algorithme \"%s\" n'est pas pris en charge ou a été désactivé.",
	'APIException:NotGetOrPost' => "La méthode de la requête doit être GET ou POST",
	'APIException:MissingAPIKey' => "Clef d'API manquante",
	'APIException:BadAPIKey' => "Clef d'API invalide",
	'APIException:MissingHmac' => "Entête X-Elgg-hmac manquant",
	'APIException:MissingHmacAlgo' => "Entête X-Elgg-hmac-algo manquant",
	'APIException:MissingTime' => "Entête X-Elgg-time manquant",
	'APIException:MissingNonce' => "Entête X-Elgg-nonce manquant",
	'APIException:TemporalDrift' => "X-Elgg-time est trop éloigné dans le passé ou le futur. Epoch a échoué.",
	'APIException:MissingPOSTHash' => "Entête X-Elgg-posthash manquant",
	'APIException:MissingPOSTAlgo' => "Entête X-Elgg-posthash_algo manquant",
	'APIException:MissingContentType' => "Type de contenu manquant pour les données POST",
	'APIException:InvalidCallMethod' => "%s doit être appelé en utilisant \"%s\"",
	'APIException:CallMethodNotImplemented' => "La méthode d'appel \"%s\" n'est actuellement pas supportée.",
	'SecurityException:authenticationfailed' => "L'utilisateur n'a pas pu être authentifié",
	'SecurityException:BannedUser' => "Ce compte utilisateur est banni, aucun jeton ne peut être fourni.",
	'InvalidArgumentException:APIParametersArrayStructure' => "La structure du tableau de paramètres est incorrecte pour demander d'exposer la méthode \"%s\"",
	'DomainException:UnrecognisedHttpMethod' => "Méthode http %s non reconnue pour la méthode api \"%s\"",
	'SecurityException:InvalidPostHash' => "Le hachage des données POST est invalide - Attendait %s mais reçu %s.",
	'SecurityException:DupePacket' => "La signature du paquet a déjà été vue.",
	'SecurityException:InvalidAPIKey' => "Clef d'API incorrecte ou manquante.",
	'BadRequestException:MissingOutputViewInViewtype' => "Vue \"api/output\" manquante dans le type de vue \"%s\".",
	'BadRequestException:MissingOutputViewInViewtype:DataViewsPlugin' => "Activez le plugin \"data_views\" pour ajouter cette vue.",
	
	'web_services:api_methods:system.api.list:get:description' => "Liste tous les appels API disponibles sur le système.",
	'web_services:api_methods:auth.gettoken:post:description' => "Cet appel API permet à un utilisateur d'obtenir un jeton d'authentification utilisateur qui peut être utilisé pour authentifier les futurs appels API. Passez-le comme paramètre auth_token",
	
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
	'web_services:settings:authentication:description' => "Certaines méthodes d'API nécessitent que les sources externes s'authentifient elles-même. Ces sources externes doivent être fournies avec une paire de clefs d'API (clef publique et secrète).

Veuillez noter qu'au moins une des méthodes d'authentification API doit être active afin d'authentifier les requêtes auprès de l'API.",
	'web_services:settings:authentication:allow_key' => "Permettre une authentification basique par clef publique",
	'web_services:settings:authentication:allow_key:help' => "La clef d'API publique peut être passée comme paramètre dans la requête.",
	'web_services:settings:authentication:allow_hmac' => "Autoriser l'entête HMAC d'authentification auprès de l'API",
	'web_services:settings:authentication:allow_hmac:help' => "Avec l'authentification HMAC des entêtes particuliers doivent être passés dans la requête pour assurer l'authenticité de la la requête.",
);
