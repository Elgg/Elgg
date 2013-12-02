<?php
/**
 * Generate a new site secret
 */

init_site_secret();
elgg_reset_system_cache();

system_message(elgg_echo('admin:site:secret_regenerated'));

forward(REFERER);
