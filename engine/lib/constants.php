<?php

/**
 * Controls access levels on \ElggEntity entities, metadata, and annotations.
 *
 * @warning ACCESS_DEFAULT is a place holder for the input/access view. Do not
 * use it when saving an entity.
 *
 * @var int
 */
define('ACCESS_DEFAULT', -1);
define('ACCESS_PRIVATE', 0);
define('ACCESS_LOGGED_IN', 1);
define('ACCESS_PUBLIC', 2);
define('ACCESS_FRIENDS', -2);

/**
 * Constant to request the value of a parameter be ignored in elgg_get_*() functions
 *
 * @see elgg_get_entities()
 * @var null
 * @since 1.7
 */
define('ELGG_ENTITIES_ANY_VALUE', null);

/**
 * Constant to request the value of a parameter be nothing in elgg_get_*() functions.
 *
 * @see elgg_get_entities()
 * @var int 0
 * @since 1.7
 */
define('ELGG_ENTITIES_NO_VALUE', 0);

/**
 * Used in calls to forward() to specify the browser should be redirected to the
 * referring page.
 *
 * @see forward
 * @var int -1
 */
define('REFERRER', -1);

/**
 * Alternate spelling for REFERRER.  Included because of some bad documentation
 * in the original HTTP spec.
 *
 * @see forward()
 * @link http://en.wikipedia.org/wiki/HTTP_referrer#Origin_of_the_term_referer
 * @var int -1
 */
define('REFERER', -1);

/**
 * HTTP Response codes
 */
define('ELGG_HTTP_CONTINUE', 100);
define('ELGG_HTTP_SWITCHING_PROTOCOLS', 101);
define('ELGG_HTTP_PROCESSING', 102);// RFC2518
define('ELGG_HTTP_OK', 200);
define('ELGG_HTTP_CREATED', 201);
define('ELGG_HTTP_ACCEPTED', 202);
define('ELGG_HTTP_NON_AUTHORITATIVE_INFORMATION', 203);
define('ELGG_HTTP_NO_CONTENT', 204);
define('ELGG_HTTP_RESET_CONTENT', 205);
define('ELGG_HTTP_PARTIAL_CONTENT', 206);
define('ELGG_HTTP_MULTI_STATUS', 207); // RFC4918
define('ELGG_HTTP_ALREADY_REPORTED', 208); // RFC5842
define('ELGG_HTTP_IM_USED', 226); // RFC3229
define('ELGG_HTTP_MULTIPLE_CHOICES', 300);
define('ELGG_HTTP_MOVED_PERMANENTLY', 301);
define('ELGG_HTTP_FOUND', 302);
define('ELGG_HTTP_SEE_OTHER', 303);
define('ELGG_HTTP_NOT_MODIFIED', 304);
define('ELGG_HTTP_USE_PROXY', 305);
define('ELGG_HTTP_RESERVED', 306);
define('ELGG_HTTP_TEMPORARY_REDIRECT', 307);
define('ELGG_HTTP_PERMANENTLY_REDIRECT', 308); // RFC7238
define('ELGG_HTTP_BAD_REQUEST', 400);
define('ELGG_HTTP_UNAUTHORIZED', 401);
define('ELGG_HTTP_PAYMENT_REQUIRED', 402);
define('ELGG_HTTP_FORBIDDEN', 403);
define('ELGG_HTTP_NOT_FOUND', 404);
define('ELGG_HTTP_METHOD_NOT_ALLOWED', 405);
define('ELGG_HTTP_NOT_ACCEPTABLE', 406);
define('ELGG_HTTP_PROXY_AUTHENTICATION_REQUIRED', 407);
define('ELGG_HTTP_REQUEST_TIMEOUT', 408);
define('ELGG_HTTP_CONFLICT', 409);
define('ELGG_HTTP_GONE', 410);
define('ELGG_HTTP_LENGTH_REQUIRED', 411);
define('ELGG_HTTP_PRECONDITION_FAILED', 412);
define('ELGG_HTTP_REQUEST_ENTITY_TOO_LARGE', 413);
define('ELGG_HTTP_REQUEST_URI_TOO_LONG', 414);
define('ELGG_HTTP_UNSUPPORTED_MEDIA_TYPE', 415);
define('ELGG_HTTP_REQUESTED_RANGE_NOT_SATISFIABLE', 416);
define('ELGG_HTTP_EXPECTATION_FAILED', 417);
define('ELGG_HTTP_I_AM_A_TEAPOT', 418); // RFC2324
define('ELGG_HTTP_UNPROCESSABLE_ENTITY', 422);// RFC4918
define('ELGG_HTTP_LOCKED', 423); // RFC4918
define('ELGG_HTTP_FAILED_DEPENDENCY', 424); // RFC4918
define('ELGG_HTTP_RESERVED_FOR_WEBDAV_ADVANCED_COLLECTIONS_EXPIRED_PROPOSAL', 425); // RFC2817
define('ELGG_HTTP_UPGRADE_REQUIRED', 426);// RFC2817
define('ELGG_HTTP_PRECONDITION_REQUIRED', 428); // RFC6585
define('ELGG_HTTP_TOO_MANY_REQUESTS', 429); // RFC6585
define('ELGG_HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE', 431); // RFC6585
define('ELGG_HTTP_INTERNAL_SERVER_ERROR', 500);
define('ELGG_HTTP_NOT_IMPLEMENTED', 501);
define('ELGG_HTTP_BAD_GATEWAY', 502);
define('ELGG_HTTP_SERVICE_UNAVAILABLE', 503);
define('ELGG_HTTP_GATEWAY_TIMEOUT', 504);
define('ELGG_HTTP_VERSION_NOT_SUPPORTED', 505);
define('ELGG_HTTP_VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL', 506);// RFC2295
define('ELGG_HTTP_INSUFFICIENT_STORAGE', 507);// RFC4918
define('ELGG_HTTP_LOOP_DETECTED', 508); // RFC5842
define('ELGG_HTTP_NOT_EXTENDED', 510);// RFC2774
define('ELGG_HTTP_NETWORK_AUTHENTICATION_REQUIRED', 511); // RFC6585

/**
 * Default JSON encoding
 */
define('ELGG_JSON_ENCODING', JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);

/**
 * Tells \ElggPlugin::start() to include the start.php file.
 */
define('ELGG_PLUGIN_INCLUDE_START', 1);

/**
 * Tells \ElggPlugin::start() to automatically register the plugin's views.
 */
define('ELGG_PLUGIN_REGISTER_VIEWS', 2);

/**
 * Tells \ElggPlugin::start() to automatically register the plugin's languages.
 */
define('ELGG_PLUGIN_REGISTER_LANGUAGES', 4);

/**
 * Tells \ElggPlugin::start() to automatically register the plugin's classes.
 */
define('ELGG_PLUGIN_REGISTER_CLASSES', 8);

/**
 * Tells \ElggPlugin::start() to automatically register the plugin's actions.
 */
define('ELGG_PLUGIN_REGISTER_ACTIONS', 16);

/**
 * Tells \ElggPlugin::start() to automatically register the plugin's actions.
 */
define('ELGG_PLUGIN_REGISTER_WIDGETS', 32);

/**
 * Prefix for plugin setting names
 *
 * @todo Can't namespace these because many plugins directly call
 * private settings via $entity->$name.
 */
//define('ELGG_PLUGIN_SETTING_PREFIX', 'plugin:setting:');

/**
 * Prefix for plugin user setting names
 */
define('ELGG_PLUGIN_USER_SETTING_PREFIX', 'plugin:user_setting:');

/**
 * Internal settings prefix
 *
 * @todo This could be resolved by promoting \ElggPlugin to a 5th type.
 */
define('ELGG_PLUGIN_INTERNAL_PREFIX', 'elgg:internal:');
