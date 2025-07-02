<?php

$assets = [
    'logo',
];

foreach ($assets as $asset) {
    $file_src = elgg_get_data_path() . "assets/$asset.png";
    if (file_exists($file_src)) {
        $asset_src = elgg_get_simplecache_url("assets/$asset.png");
    } else {
        $asset_src = elgg_get_simplecache_url("topbar/$asset.png");
    }

    $view = elgg_view('output/img', [
        'src' => $asset_src,
        'alt' => elgg_echo("assets:$asset"),
        'class' => 'topbar-assets__preview',
    ]);

    $field = elgg_view_field([
        '#type' => 'file',
        'name' => $asset,
        'accept' => 'image/png',
    ]);

    echo elgg_view_module('info', elgg_echo("assets:$asset"), $field . $view);
}

$footer = elgg_view_field([
    '#type' => 'submit',
    'text' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
