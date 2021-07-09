<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'APIException:MissingParameterInMethod' => "Saknad parameter %s i metod %s",
	'APIException:ParameterNotArray' => "%s verkar inte vara en array.",
	'APIException:UnrecognisedTypeCast' => "Okänd typ i cast %s för variabel '%s' i metod '%s'",
	'APIException:InvalidParameter' => "Ogiltig parameter hittades för '%s' i metod '%s'.",
	'APIException:FunctionParseError' => "%s(%s) har ett tolkningsfel.",
	'APIException:FunctionNoReturn' => "%s(%s) returnerade inget värde.",
	'APIException:APIAuthenticationFailed' => "Metodanrop misslyckades med Autentiseringen av API",
	'APIException:MethodCallNotImplemented' => "Metodanrop '%s' har inte genomförts.",
	'APIException:FunctionDoesNotExist' => "Funktion för metod '%s' kan inte anropas",
	'APIException:AlgorithmNotSupported' => "Algoritm '%s' stöds inte eller har avaktiverats.",
	'APIException:NotGetOrPost' => "Begärd metod måste vara GET eller POST",
	'APIException:MissingAPIKey' => "Saknad API-nyckel",
	'APIException:BadAPIKey' => "Ogiltig API-nyckel",
	'APIException:MissingHmac' => "Saknad X-Elgg-hmac header",
	'APIException:MissingHmacAlgo' => "Saknad X-Elgg-hmac-algo header",
	'APIException:MissingTime' => "Saknad X-Elgg-time header",
	'APIException:MissingNonce' => "Saknad X-Elgg-nonce header",
	'APIException:TemporalDrift' => "X-Elgg-time är alldeles för långt bakåt i tiden eller framtiden. Epoch misslyckas.",
	'APIException:MissingPOSTHash' => "Saknad X-Elgg-posthash header",
	'APIException:MissingPOSTAlgo' => "Saknad  X-Elgg-posthash_algo header",
	'APIException:MissingContentType' => "Saknad innehållstyp för inläggsdata",
	'SecurityException:authenticationfailed' => "Användare kunde inte autentiseras",
	'InvalidParameterException:APIParametersArrayStructure' => "Parameters array structure is incorrect for call to expose method '%s'",
	'InvalidParameterException:UnrecognisedHttpMethod' => "Okänd http-metod %s för api-metod '%s'",
	'SecurityException:InvalidPostHash' => "POST-data hash är ogiltig - Förväntade %s men fick %s.",
	'SecurityException:DupePacket' => "Paketsignatur redan skickat.",
	'SecurityException:InvalidAPIKey' => "Ogiltig eller saknad API-nyckel.",
	
	'system.api.list' => "Listar alla tillgängliga API-anrop i systemet.",
	'auth.gettoken' => "Det här API-anropet låter en användare få en token för autentisering av användare, som kan användas för att autentisera framtida API-anrop. Skicka det vidare som parametern auth_token",
	
	'admin:configure_utilities:webservices' => "Webbservice",
	'admin:configure_utilities:ws_list' => "Lista API-metoder",
	'admin:configure_utilities:ws_tokens' => "Hantera tokens för API:er",
	'webservices:menu:entity:regenerate' => "Återskapa API-nycklar",
	
	'add:object:api_key' => "Skapa en ny API-token",
	'edit:object:api_key' => "Redigera API-token: %s",
	'entity:delete:object:api_key:success' => "API-token %s togs bort",
	
	'webservices:requires_api_authentication' => "Kräver API-autentisering",
	'webservices:requires_user_authentication' => "Kräver autentisering av användare",
	'webservices:function' => "Intern funktion:",
	'webservices:parameters' => "Parametrar för webbservice:",
	'webservices:parameters:required' => "krävs",
	'webservices:parameters:optional' => "valfri",
	
	'webservices:api_key:public' => "Publik nyckel:",
	'webservices:api_key:secret' => "Hemlig nyckel:",
	'webservices:api_key:secret:show' => "Visa hemlig nyckel",
	
	'webservices:action:api_key:edit:success' => "API-token sparades med lyckat resultat",
	'webservices:action:api_key:regenerate:success' => "API-nyckeln har återskapats",
	
	// plugin settings
);
