<?php
/**
 * Elgg profile plugin reorder fields
 *
 */

$ordering = get_input('fieldorder');

$result = elgg_save_config('profile_custom_fields', $ordering);

// called by ajax so we exit
exit;
