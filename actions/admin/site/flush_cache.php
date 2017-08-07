<?php
/**
 * Flush all the caches
 */

elgg_flush_caches();
_elgg_services()->autoloadManager->deleteCache();

return elgg_ok_response('', elgg_echo('admin:cache:flushed'));
