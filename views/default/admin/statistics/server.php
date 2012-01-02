<?php
/**
 * Server information
 */

echo elgg_view_module('inline', elgg_echo('admin:server:label:web_server'), elgg_view('admin/statistics/server/web_server'));

echo elgg_view_module('inline', elgg_echo('admin:server:label:php'), elgg_view('admin/statistics/server/php'));
