<?php
/**
 * Miscellaneous and complex components
 */

$body = elgg_view('theme_preview/miscellaneous/lightbox');
echo elgg_view_module('theme-sandbox-demo', 'Lightbox (.elgg-lightbox)', $body);

$body = elgg_view('theme_preview/miscellaneous/popup');
echo elgg_view_module('theme-sandbox-demo', 'Popup (rel=popup)', $body);

$body = elgg_view('theme_preview/miscellaneous/toggle');
echo elgg_view_module('theme-sandbox-demo', 'Toggle (rel=toggle)', $body);

$body = elgg_view('theme_preview/miscellaneous/system_messages');
echo elgg_view_module('theme-sandbox-demo', 'System Messages and Errors', $body);

$body = elgg_view('theme_preview/miscellaneous/site_menu');
echo elgg_view_module('theme-sandbox-demo', 'Site Menu', $body);

$body = elgg_view('theme_preview/miscellaneous/user_hover_menu');
echo elgg_view_module('theme-sandbox-demo', 'User Icon with Hover Menu', $body);
