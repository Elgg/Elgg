<?php
/**
 * Elgg administration site main screen
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

global $CONFIG;

// Description of what's going on
echo "<div class=\"contentWrapper\">" . elgg_view('output/longtext', array('value' => elgg_echo("admin:site:description"))) . " ";

echo elgg_view("settings/system",array("action" => $CONFIG->wwwroot."action/admin/site/update_basic")); // Always want to do this first.

echo "</div>";