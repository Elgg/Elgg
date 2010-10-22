<?php
/**
 * Elgg profile plugin edit default profile action
 *
 * @package ElggProfile
 */

admin_gatekeeper();

$field = get_input('field');
$text = get_input('value');

set_plugin_setting("admin_defined_profile_{$field}",$text,'profile');

echo $text;

exit;