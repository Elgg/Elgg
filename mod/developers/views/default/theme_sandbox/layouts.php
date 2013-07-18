<?php
/**
 * Layouts
 */

elgg_push_breadcrumb('breadcrumb 1', '#');
elgg_push_breadcrumb('breadcrumb 2');

echo elgg_view_module('theme-sandbox-demo', "One Column", elgg_view('theme_sandbox/layouts/one_column'));

echo elgg_view_module('theme-sandbox-demo', "One Sidebar", elgg_view('theme_sandbox/layouts/one_sidebar'));

echo elgg_view_module('theme-sandbox-demo', "Two Sidebar", elgg_view('theme_sandbox/layouts/two_sidebar'));
