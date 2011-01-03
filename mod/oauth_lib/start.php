<?php
/**
 * 
 */

$oauth_lib = elgg_get_plugin_path() . 'oauth_lib/vendors/oauth/library/';

// require all vendor libraries
require_once "{$oauth_lib}OAuthDiscovery.php";
require_once "{$oauth_lib}OAuthRequest.php";
require_once "{$oauth_lib}OAuthRequester.php";
require_once "{$oauth_lib}OAuthRequestVerifier.php";
require_once "{$oauth_lib}OAuthServer.php";

require_once "{$oauth_lib}body/OAuthBodyMultipartFormdata.php";

require_once "{$oauth_lib}store/OAuthStoreAbstract.class.php";

require_once "{$oauth_lib}signature_method/OAuthSignatureMethod_HMAC_SHA1.php";
require_once "{$oauth_lib}signature_method/OAuthSignatureMethod_MD5.php";
require_once "{$oauth_lib}signature_method/OAuthSignatureMethod_PLAINTEXT.php";
require_once "{$oauth_lib}signature_method/OAuthSignatureMethod_RSA_SHA1.php";
