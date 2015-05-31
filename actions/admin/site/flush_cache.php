<?php
/**
 * Flush all the caches
 */

elgg_flush_caches();
_elgg_services()->autoloadManager->deleteCache();

system_message(elgg_echo('admin:cache:flushed'));
forward(REFERER);