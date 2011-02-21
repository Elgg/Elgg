<?php
/**
 * Admin sidebar -- just outputs the page menu
 */

$content = elgg_view_menu('page', array('sort_by' => 'weight'));

echo elgg_view_module('main', '', $content);