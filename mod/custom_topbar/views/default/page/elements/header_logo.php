<?php

$file_src = elgg_get_data_path() . 'assets/logo.png';
if (file_exists($file_src)) {
    $asset_src = elgg_get_simplecache_url('assets/logo.png');
} else {
    $asset_src = elgg_get_simplecache_url('topbar/logo.png');
}

$logo = elgg_format_element('img', [
    'src' => $asset_src,
    'alt' => (string) elgg_get_site_entity()->getDisplayName(),
]);


echo elgg_format_element('div', [
    'class' => 'elgg-heading-site'
], elgg_view('output/url', [
    'text' => $logo,
    'href' => (string) elgg_get_site_entity()->getURL(),
    'title' => (string) elgg_get_site_entity()->getDisplayName(),
]));
