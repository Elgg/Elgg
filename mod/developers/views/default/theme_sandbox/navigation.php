<?php
/**
 * Navigation CSS
 */

echo elgg_view_module('aside', "Anchors (.elgg-anchor)", elgg_view('theme_sandbox/navigation/anchors'));

echo elgg_view_module('aside', "Tabs (.elgg-tabs)", elgg_view('theme_sandbox/navigation/tabs'));

echo elgg_view_module('aside', "Pagination (.elgg-pagination)", elgg_view('theme_sandbox/navigation/pagination'));

echo elgg_view_module('aside', "Site Navbar", elgg_view('theme_sandbox/navigation/site'));

echo elgg_view_module('aside', "Breadcrumbs (.elgg-breadcrumbs)", elgg_view('theme_sandbox/navigation/breadcrumbs'));

echo elgg_view_module('aside', "Page Menu (.elgg-menu-page)", elgg_view('theme_sandbox/navigation/page'));

echo elgg_view_module('aside', "Filter Menu (.elgg-menu-filter)", elgg_view('theme_sandbox/navigation/filter'));

echo elgg_view_module('aside', "Entity Menu (.elgg-menu-entity and .elgg-menu-hz)", elgg_view('theme_sandbox/navigation/entity'));

echo elgg_view_module('aside', "Owner Block Menu (.elgg-menu-owner-block)", elgg_view('theme_sandbox/navigation/owner_block'));

echo elgg_view_module('aside', "Footer Menu (.elgg-menu-footer)", elgg_view('theme_sandbox/navigation/footer'));

echo elgg_view_module('aside', "Menu Item with AMD require", elgg_view('theme_sandbox/navigation/require'));

echo elgg_view_module('aside', "Menu Item with a dropdown submenu", elgg_view('theme_sandbox/navigation/dropdown'));

echo elgg_view_module('aside', "Menu Item with a toggled submenu", elgg_view('theme_sandbox/navigation/toggle'));

echo elgg_view_module('aside', "Simple horizontal menu", elgg_view('theme_sandbox/navigation/horizontal'));
