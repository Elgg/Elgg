<?php
/**
 * OAuth libs
 *
 * @todo Pull these out into an elgg_oauth lib and use elgg_register_library().
 * @package oauth_api
 */

// require all vendor libraries
$plugin_path = dirname(__FILE__) . '/vendors/oauth/library';
require_once "$plugin_path/OAuthDiscovery.php";
require_once "$plugin_path/OAuthRequest.php";
require_once "$plugin_path/OAuthRequester.php";
require_once "$plugin_path/OAuthRequestVerifier.php";
require_once "$plugin_path/OAuthServer.php";

require_once "$plugin_path/body/OAuthBodyMultipartFormdata.php";

require_once "$plugin_path/store/OAuthStoreAbstract.class.php";

require_once "$plugin_path/signature_method/OAuthSignatureMethod_HMAC_SHA1.php";
require_once "$plugin_path/signature_method/OAuthSignatureMethod_MD5.php";
require_once "$plugin_path/signature_method/OAuthSignatureMethod_PLAINTEXT.php";
require_once "$plugin_path/signature_method/OAuthSignatureMethod_RSA_SHA1.php";
