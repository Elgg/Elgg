<?php
/**
 * Admin sidebar menu
 */

$content = elgg_view_menu('page', array('sort_by' => 'weight'));

echo elgg_view_module('main', '', $content);