<?php
/**
 * Flush all the caches
 *
 * @todo rename action
 */

elgg_clear_caches();

return elgg_ok_response('', elgg_echo('admin:cache:flushed'));
