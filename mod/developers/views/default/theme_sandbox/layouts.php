<?php
/**
 * Layouts
 */

elgg_push_breadcrumb('breadcrumb 1', '#');
elgg_push_breadcrumb('breadcrumb 2');

echo elgg_view_module('theme-sandbox-demo', "one_column", elgg_view('theme_sandbox/layouts/one_column'));

echo elgg_view_module('theme-sandbox-demo', "one_sidebar", elgg_view('theme_sandbox/layouts/one_sidebar'));

echo elgg_view_module('theme-sandbox-demo', "two_sidebar", elgg_view('theme_sandbox/layouts/two_sidebar'));

echo elgg_view_module('theme-sandbox-demo', "notice", elgg_view('theme_sandbox/layouts/notice'));
