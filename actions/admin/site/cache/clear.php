<?php
/**
 * Invalidate and clear all the caches
 */

elgg_invalidate_caches();
elgg_clear_caches();

return elgg_ok_response('', elgg_echo('admin:cache:cleared'));
