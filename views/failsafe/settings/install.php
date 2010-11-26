<?php
/**
 * Elgg system settings on initial installation
 *
 * @package Elgg
 * @subpackage Core
 *
 */

echo "<p>" . autop(elgg_echo("installation:settings:description")) . "</p>";

echo elgg_view("settings/system",array("action" => "action/systemsettings/install"));