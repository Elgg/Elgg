<?php
/**
 * Elgg sidebar menu
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 */

// Plugins can add to the sidebar menu by calling elgg_add_submenu_item()
$submenu = elgg_get_submenu();

echo $submenu;
