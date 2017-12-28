<?php
/**
 * JavaScript components
 */

$body = elgg_view('theme_sandbox/javascript/lightbox');
echo elgg_view_module('aside', 'Lightbox (.elgg-lightbox)', $body);

$body = elgg_view('theme_sandbox/javascript/popup');
echo elgg_view_module('aside', 'Popup (rel=popup)', $body);

$body = elgg_view('theme_sandbox/javascript/toggle');
echo elgg_view_module('aside', 'Toggle (rel=toggle)', $body);

$body = elgg_view('theme_sandbox/javascript/system_messages');
echo elgg_view_module('aside', 'System Messages and Errors', $body);

$body = elgg_view('theme_sandbox/javascript/spinner');
echo elgg_view_module('aside', 'elgg/spinner module', $body);

$body = elgg_view('theme_sandbox/javascript/user_hover_menu');
echo elgg_view_module('aside', 'User Icon with Hover Menu', $body);
