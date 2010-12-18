<?php
/**
 * Elgg profile plugin reorder fields
 *
 * @package ElggProfile
 */

$ordering = get_input('fieldorder');
//if (!empty($ordering))
$result = set_plugin_setting('user_defined_fields',$ordering,'profile');

exit;