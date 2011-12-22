<?php
/**
 * Flush all the caches
 */

elgg_invalidate_simplecache();
elgg_filepath_cache_reset();

system_message(elgg_echo('admin:cache:flushed'));
forward(REFERER);