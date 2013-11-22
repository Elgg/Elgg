<?php
/**
 * Generate a new site secret
 */

init_site_secret();
elgg_view_regenerate_simplecache();
elgg_filepath_cache_reset();

system_message(elgg_echo('admin:site:secret_regenerated'));

forward(REFERER);
