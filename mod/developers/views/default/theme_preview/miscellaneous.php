<?php
/**
 * Miscellaneous and complex components
 */

$body = elgg_view('theme_preview/miscellaneous/lightbox');
echo elgg_view_module('info', 'Lightbox (.elgg-lightbox)', $body);

$body = elgg_view('theme_preview/miscellaneous/popup');
echo elgg_view_module('info', 'Popup (rel=popup)', $body);

$body = elgg_view('theme_preview/miscellaneous/toggle');
echo elgg_view_module('info', 'Toggle (rel=toggle)', $body);

$body = elgg_view('theme_preview/miscellaneous/system_messages');
echo elgg_view_module('info', 'System Messages and Errors', $body);

$body = elgg_view('theme_preview/miscellaneous/site_menu');
echo elgg_view_module('info', 'Site Menu', $body);

$body = elgg_view('theme_preview/miscellaneous/user_hover_menu');
echo elgg_view_module('info', 'User Icon with Hover Menu', $body);