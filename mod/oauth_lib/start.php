<?php
/**
 * 
 */

// require all vendor libraries
require_once "{$CONFIG->pluginspath}oauth_lib/vendors/oauth/library/OAuthDiscovery.php";
require_once "{$CONFIG->pluginspath}oauth_lib/vendors/oauth/library/OAuthRequest.php";
require_once "{$CONFIG->pluginspath}oauth_lib/vendors/oauth/library/OAuthRequester.php";
require_once "{$CONFIG->pluginspath}oauth_lib/vendors/oauth/library/OAuthRequestVerifier.php";
require_once "{$CONFIG->pluginspath}oauth_lib/vendors/oauth/library/OAuthServer.php";

require_once "{$CONFIG->pluginspath}oauth_lib/vendors/oauth/library/body/OAuthBodyMultipartFormdata.php";

require_once "{$CONFIG->pluginspath}oauth_lib/vendors/oauth/library/store/OAuthStoreAbstract.class.php";

require_once "{$CONFIG->pluginspath}oauth_lib/vendors/oauth/library/signature_method/OAuthSignatureMethod_HMAC_SHA1.php";
require_once "{$CONFIG->pluginspath}oauth_lib/vendors/oauth/library/signature_method/OAuthSignatureMethod_MD5.php";
require_once "{$CONFIG->pluginspath}oauth_lib/vendors/oauth/library/signature_method/OAuthSignatureMethod_PLAINTEXT.php";
require_once "{$CONFIG->pluginspath}oauth_lib/vendors/oauth/library/signature_method/OAuthSignatureMethod_RSA_SHA1.php";
