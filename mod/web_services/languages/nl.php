<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'APIException:MissingParameterInMethod' => "Ontbrekende parameter %s in de methode %s",
	'APIException:ParameterNotArray' => "%s lijkt geen array te zijn.",
	'APIException:UnrecognisedTypeCast' => "Onherkenbaar type in cast %s voor variabele '%s' in methode '%s'",
	'APIException:InvalidParameter' => "Ongeldige parameter gevonden voor '%s' in de methode '%s'.",
	'APIException:FunctionParseError' => "%s(%s) heeft een parsing error.",
	'APIException:FunctionNoReturn' => "%s(%s) leverde geen waarde op.",
	'APIException:APIAuthenticationFailed' => "API Authenticatie mislukt voor methode aanroep ",
	'APIException:MethodCallNotImplemented' => "Methode aanroep '%s' is niet geimplementeerd.",
	'APIException:FunctionDoesNotExist' => "Functie voor methode '%s' is niet aanroepbaar",
	'APIException:AlgorithmNotSupported' => "Algorithme '%s' wordt niet ondersteund of is uitgeschakeld.",
	'APIException:NotGetOrPost' => "Methode verzoek moet GET of POST zijn",
	'APIException:MissingAPIKey' => "Ontbrekende API key",
	'APIException:BadAPIKey' => "Foute API key",
	'APIException:MissingHmac' => "Ontbrekende X-Elgg-hmac header",
	'APIException:MissingHmacAlgo' => "Ontbrekende X-Elgg-hmac-algo header",
	'APIException:MissingTime' => "Ontbrekende X-Elgg-time header",
	'APIException:MissingNonce' => "Ontbrekende X-Elgg-nonce header",
	'APIException:TemporalDrift' => "X-Elgg-time is te ver in het verleden of in de toekomst. Epoch mislukt.",
	'APIException:MissingPOSTHash' => "Ontbrekende X-Elgg-posthash header",
	'APIException:MissingPOSTAlgo' => "Ontbrekende X-Elgg-posthash_algo header",
	'APIException:MissingContentType' => "Ontbrekend content type voor post data",
	'APIException:InvalidCallMethod' => "%s moet worden aangeroepen middels '%s'",
	'APIException:CallMethodNotImplemented' => "Aanroep methode '%s' is op dit moment niet ondersteund.",
	'SecurityException:authenticationfailed' => "Gebruiker kon niet worden geauthentiseerd.",
	'SecurityException:DuplicateEmailUser' => "Geen unieke gebruiker gevonden op basis van het emailadres. Kan geen token ophalen.",
	'SecurityException:BannedUser' => "Deze gebruiker is geblokkeerd. Er kan geen token worden overhandigd.",
	'InvalidParameterException:APIParametersArrayStructure' => "Parameters array structuur is onjuist voor expost method '%s'",
	'InvalidParameterException:UnrecognisedHttpMethod' => "Onherkenbare http method %s voor api methode '%s'",
	'SecurityException:InvalidPostHash' => "POST data hash is ongeldig - Verwachte %s, maar kreeg %s.",
	'SecurityException:DupePacket' => "Packet signature is al eerder gezien",
	'SecurityException:InvalidAPIKey' => "Ongeldige of ontbrekende API Key.",
	'BadRequestException:MissingOutputViewInViewtype' => "Ontbrekende view 'api/output' in viewtype '%s'.",
	'BadRequestException:MissingOutputViewInViewtype:DataViewsPlugin' => "Activeer de 'data_views' plugin om deze view toe te voegen.",
	
	'system.api.list' => "Toont een lijst van alle beschikbare API calls in het systeem",
	'auth.gettoken' => "Deze API call levert een user authenticatie token op waarmee men in de toekomst API calls kan authentiseren. Gebruik het dan als de auth_token parameter.",
	
	'admin:configure_utilities:webservices' => "Webservices",
	'admin:configure_utilities:ws_list' => "Alle API methodes",
	'admin:configure_utilities:ws_tokens' => "Beheer API tokens",
	'webservices:menu:entity:regenerate' => "API sleutels vernieuwen",
	'webservices:menu:entity:enable_keys' => "API sleutels activeren",
	'webservices:menu:entity:disable_keys' => "API sleutels uitschakelen",
	
	'add:object:api_key' => "Maak een nieuw API token",
	'edit:object:api_key' => "Bewerk API token: %s",
	'entity:delete:object:api_key:success' => "Het API token %s is verwijderd",
	
	'webservices:requires_api_authentication' => "Vereist API authenticatie",
	'webservices:requires_user_authentication' => "Vereist gebruikers authenticatie",
	'webservices:function' => "Interne functie:",
	'webservices:parameters' => "Webservice parameters:",
	'webservices:parameters:required' => "vereist",
	'webservices:parameters:optional' => "optioneel",
	
	'webservices:api_key:public' => "Publieke sleutel:",
	'webservices:api_key:secret' => "Geheime sleutel:",
	'webservices:api_key:secret:show' => "Toon geheime sleutel",
	
	'webservices:action:api_key:edit:success' => "Het API token is succesvol opgeslagen",
	'webservices:action:api_key:regenerate:success' => "De API sleutels zijn vernieuwd",

	'webservices:action:api_key:toggle_active:enable:success' => "De API sleutels zijn succesvol geactiveerd",
	'webservices:action:api_key:toggle_active:enable:error' => "Fout opgetreden tijdens het activeren van de API sleutels",
	'webservices:action:api_key:toggle_active:disable:success' => "De API sleutels zijn succesvol uitgeschakeld",
	'webservices:action:api_key:toggle_active:disable:error' => "Fout opgetreden tijdens het uitschakelen van de API sleutels",
	
	// plugin settings
	'web_services:settings:authentication' => "Web API authenticatie instellingen",
	'web_services:settings:authentication:description' => "Sommige API methodes vereisen dat verzoeken zichzelf authenticeren. Er moet dan een API key pair (public en secrect key) worden aangeleverd.

Let er op dat er minstens één API authenticatie methode actief moet zijn om de API verzoeken te autoriseren.",
	'web_services:settings:authentication:allow_key' => "Sta basic API public key authenticatie toe",
	'web_services:settings:authentication:allow_key:help' => "De API public key wordt aangeleverd als een parameter in het verzoek",
	'web_services:settings:authentication:allow_hmac' => "Sta HMAC header API authenticatie toe",
	'web_services:settings:authentication:allow_hmac:help' => "Voor HMAC authenticatie moeten speciale headers worden meegeleverd in het API verzoek om de authenticiteit te controleren.",
);
