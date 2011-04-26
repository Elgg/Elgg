<?php
/**
 * Navigation CSS
 */
echo elgg_view_module('info', "Tabs", elgg_view('theme_preview/navigation/tabs'));

echo elgg_view_module('info', "Pagination", elgg_view('theme_preview/navigation/pagination'));

echo elgg_view_module('info', "Site Menu (.elgg-menu-site)", elgg_view('theme_preview/navigation/site'));

echo elgg_view_module('info', "Breadcrumbs (.elgg-breadcrumbs)", elgg_view('theme_preview/navigation/breadcrumbs'));

echo elgg_view_module('info', "Page Menu (.elgg-menu-page)", elgg_view('theme_preview/navigation/page'));

echo elgg_view_module('info', "Filter Menu (.elgg-menu-filter)", elgg_view('theme_preview/navigation/filter'));

echo elgg_view_module('info', "Extras Menu (.elgg-menu-extras)", elgg_view('theme_preview/navigation/extras'));

echo elgg_view_module('info', "Owner Block Menu (.elgg-menu-owner-block)", elgg_view('theme_preview/navigation/owner_block'));

?>
