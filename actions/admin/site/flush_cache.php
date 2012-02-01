<?php
/**
 * Flush all the caches
 */

elgg_invalidate_simplecache();
elgg_reset_system_cache();

system_message(elgg_echo('admin:cache:flushed'));
forward(REFERER);