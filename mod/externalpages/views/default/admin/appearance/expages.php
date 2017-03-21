<?php
/**
 * Admin section for editing external pages
 */

$type = get_input('type', 'about');

echo elgg_view('expages/menu', ['type' => $type]);

echo elgg_view_form('expages/edit', ['class' => 'elgg-form-settings'], ['type' => $type]);
