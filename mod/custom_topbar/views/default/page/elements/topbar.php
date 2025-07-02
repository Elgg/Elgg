<?php

elgg_import_esm('page/elements/topbar');

echo elgg_format_element('div', ['class' => 'elgg-nav-logo'], elgg_view('page/elements/header_logo'));

echo elgg_view('core/account/login_dropdown');

$search = elgg_format_element('div', [
    'class' => 'elgg-nav-search',
], elgg_view('search/search_box'));

$site_menu = elgg_view_menu('site', [
    'sort_by' => 'text',
]);

echo elgg_format_element('div', [
    'class' => 'elgg-nav-collapse',
], $search . $site_menu);

echo elgg_view_menu('topbar');

$span = elgg_format_element('span', []);

echo elgg_format_element('div', [
    'class' => 'elgg-nav-button',
], $span . $span . $span);
