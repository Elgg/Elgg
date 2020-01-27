<?php
/**
 * Invalidate all caches
 */

elgg_invalidate_caches();

return elgg_ok_response('', elgg_echo('admin:cache:invalidated'));
