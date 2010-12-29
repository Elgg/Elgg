<?php
/**
 * Elgg profile plugin reorder fields
 *
 * @package ElggProfile
 */

$ordering = get_input('fieldorder');

$result = elgg_save_config('profile_custom_fields', $ordering);

exit;