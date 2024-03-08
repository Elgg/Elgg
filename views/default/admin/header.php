<?php

elgg_import_esm('page/elements/topbar');

$link_contents = elgg_get_site_entity()->getDisplayName();
$link_contents .= ' ' . elgg_format_element('small', ['title' => elgg_echo('admin:header:release', [elgg_get_release()])], '[v' . elgg_get_release() . ']');

$site_link = elgg_view_url(elgg_generate_url('admin'), $link_contents);
$logo = elgg_format_element('div', ['class' => 'elgg-heading-site'], $site_link);

echo elgg_format_element('div', ['class' => 'elgg-nav-logo'], $logo);

echo elgg_format_element('div', ['class' => 'elgg-nav-button'], '<span></span><span></span><span></span>');

echo elgg_format_element('div', ['class' => 'elgg-nav-collapse'], elgg_view_menu('admin_header'));
