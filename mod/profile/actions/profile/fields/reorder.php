<?php
/**
 * Elgg profile plugin reorder fields
 */

$ordering = get_input('fieldorder');

elgg_save_config('profile_custom_fields', $ordering);

return elgg_ok_response();
