<?php
/**
 * JavaScript components
 */

$body = elgg_view('theme_sandbox/javascript/lightbox');
echo elgg_view_module('info', 'Lightbox (.elgg-lightbox)', $body);

$body = elgg_view('theme_sandbox/javascript/popup');
echo elgg_view_module('info', 'Popup (rel=popup)', $body);

$body = elgg_view('theme_sandbox/javascript/toggle');
echo elgg_view_module('info', 'Toggle (rel=toggle)', $body);

$body = elgg_view('theme_sandbox/javascript/system_messages');
echo elgg_view_module('info', 'System Messages and Errors', $body);

$body = elgg_view('theme_sandbox/javascript/spinner');
echo elgg_view_module('info', 'elgg/spinner module', $body);

$body = elgg_view('theme_sandbox/javascript/user_hover_menu');
echo elgg_view_module('info', 'User Icon with Hover Menu', $body);
