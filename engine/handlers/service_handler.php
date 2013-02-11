<?php
/**
 * Services handler.
 *
 * @deprecated 1.9 Update your .htaccess to remove the service handler
 */

require_once(dirname(dirname(__FILE__)) . "/start.php");

$handler = get_input('handler');
$request = get_input('request');

elgg_deprecated_notice("Update your .htaccess file to remove the service handler", 1.9);

service_handler($handler, $request);
