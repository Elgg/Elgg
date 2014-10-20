<?php
/**
 * Elgg 1.10.0 upgrade 2014130300
 * add_default_limit
 *
 * Adds the default_limit site config value
 */

set_config('default_limit', 10, elgg_get_site_entity()->guid);
