<?php
/**
 * Elgg sidebar menu
 *
 * @package Elgg
 * @subpackage Core
 *
 */

// Plugins can add to the sidebar menu by calling elgg_add_submenu_item()
$submenu = elgg_get_submenu();

echo $submenu;
