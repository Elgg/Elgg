<?php
/**
 * Initialize Elgg's js lib with the uncacheable data
 */

echo 'globalThis.elgg = ' . json_encode(_elgg_get_js_page_data($vars)) . ';';
