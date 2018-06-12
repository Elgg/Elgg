<?php
/**
 * Flush all the caches
 */

elgg_flush_caches();

return elgg_ok_response('', elgg_echo('admin:cache:flushed'));
