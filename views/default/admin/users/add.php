<?php
/**
 * Display an add user form.
 */

$title = elgg_echo('adduser');
$body = elgg_view_form('useradd', [], ['show_admin' => true]);

echo elgg_view_module('inline', $title, $body);
