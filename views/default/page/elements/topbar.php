<?php

elgg_import_esm('page/elements/topbar');

echo elgg_format_element('div', ['class' => 'elgg-nav-logo'], elgg_view('page/elements/header_logo'));

echo elgg_view('core/account/login_dropdown');

echo elgg_format_element('div', ['class' => 'elgg-nav-button'], '<span></span><span></span><span></span>');

$contents = elgg_format_element('div', ['class' => 'elgg-nav-search'], elgg_view('search/search_box'));
$contents .= elgg_view_menu('site', ['sort_by' => 'text']);
$contents .= elgg_view_menu('topbar');

echo elgg_format_element('div', ['class' => 'elgg-nav-collapse'], $contents);
