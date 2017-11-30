<?php

echo elgg_view_message('success', 'Success message (.elgg-state-success)');
echo elgg_view_message('notice', 'Notice message (.elgg-state-notice)');
echo elgg_view_message('help', 'Notice message (.elgg-state-help)');
echo elgg_view_message('warning', 'Warning message (.elgg-state-warning)');
echo elgg_view_message('error', 'Error message (.elgg-state-error)');

echo elgg_view_message('success', 'Success message (.elgg-state-success) without title', ['title' => false]);
echo elgg_view_message('notice', 'Notice message (.elgg-state-notice) without title', ['title' => false]);
echo elgg_view_message('help', 'Help message (.elgg-state-help) without title', ['title' => false]);
echo elgg_view_message('warning', 'Warning message (.elgg-state-warning) without title', ['title' => false]);
echo elgg_view_message('error', 'Error message (.elgg-state-error) without title', ['title' => false]);
