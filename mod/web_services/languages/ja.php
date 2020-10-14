<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'APIException:ApiResultUnknown' => "API Result は不明な型です。このようなことは起きないはずなのですが。。。",
	'APIException:MissingParameterInMethod' => "メソッド「 %2\$s 」のパラメタ「 %1\$s 」が欠落しています",
	'APIException:ParameterNotArray' => "%s は、配列ではないようです。",
	'APIException:UnrecognisedTypeCast' => "メソッド「 %3\$s 」の変数「 %2\$s 」のキャスト %1\$s の型がわかりません。",
	'APIException:InvalidParameter' => "メソッド「 %2\$s 」のパラメタ「 %1\$s 」が不適切な値でした。",
	'APIException:FunctionParseError' => "%s(%s) にはパース・エラーが１つあります。",
	'APIException:FunctionNoReturn' => "%s(%s) は値を返しませんでした。",
	'APIException:APIAuthenticationFailed' => "メソッドの呼び出しは API Aithentication に失敗しました。",
	'APIException:UserAuthenticationFailed' => "メソッドの呼び出しは User Authentication に失敗しました。",
	'APIException:MethodCallNotImplemented' => "メソッドの呼び出し '%s' は、実装されていません。",
	'APIException:FunctionDoesNotExist' => "メソッド '%s' の関数は呼び出し不可能です。",
	'APIException:AlgorithmNotSupported' => "アルゴリズム '%s' はサポートされていないか、利用不可となっています。",
	'APIException:NotGetOrPost' => "リクエストの手段は GET もしくは、 POST でないといけません。",
	'APIException:MissingAPIKey' => "API key がありません",
	'APIException:BadAPIKey' => "API key が間違っています",
	'APIException:MissingHmac' => "X-Elgg-hmac ヘッダがありません",
	'APIException:MissingHmacAlgo' => "X-Elgg-hmac-algo ヘッダがありません",
	'APIException:MissingTime' => "X-Elgg-time ヘッダがありません",
	'APIException:MissingNonce' => "X-Elgg-nonce ヘッダがありません",
	'APIException:TemporalDrift' => "X-Elgg-time があまりにも過去か未来過ぎます。エポック取得に失敗しました",
	'APIException:NoQueryString' => "クエリ文字列にデータがありません",
	'APIException:MissingPOSTHash' => "X-Elgg-posthash ヘッダがありません",
	'APIException:MissingPOSTAlgo' => "X-Elgg-posthash_algo ヘッダがありません",
	'APIException:MissingContentType' => "投稿データのコンテント型がありません",
	'SecurityException:APIAccessDenied' => "申し訳ありません。API へのアクセスは管理者によって使用不可に設定されています。",
	'SecurityException:NoAuthMethods' => "このAPIリクエストの認証を行うにあたって、担当するメソッドが見つかりませんでした。",
	'SecurityException:authenticationfailed' => "ユーザは認証できませんでした。",
	'InvalidParameterException:APIMethodOrFunctionNotSet' => "expose_method() において、メソッドあるいは関数が設定されていません",
	'InvalidParameterException:APIParametersArrayStructure' => "メソッド '%s' の呼び出しにおいてパラメタの配列が不正です",
	'InvalidParameterException:UnrecognisedHttpMethod' => "APIメソッド「 %2\$s 」を呼び出すための httpメソッド %1\$s が認識されませんでした",
	'SecurityException:AuthTokenExpired' => "認証トークンが欠落してるか、正しくないか、期限が切れています。",
	'SecurityException:InvalidPostHash' => "POST データハッシュが不正です - %s のはずですが %s になっています。",
	'SecurityException:DupePacket' => "パケット・シグネイチャはすでに見ました。",
	'SecurityException:InvalidAPIKey' => "API Key が不正あるいは欠如しています。",
	'NotImplementedException:CallMethodNotImplemented' => "呼び出しメソッド '%s' は現在サポートされていません。",
	'CallException:InvalidCallMethod' => "%s の呼び出しは '%s' を使用してください。",

	'system.api.list' => "このシステムでの利用可能な全APIコール一覧。",
	'auth.gettoken' => "このAPIコールはユーザ認証トークンをユーザに渡します。このトークンは今後のAPIコールの時に使用されます。 パラメータ auth_token として渡してください。",
	
	'admin:configure_utilities:webservices' => "ウェブサービス",
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
