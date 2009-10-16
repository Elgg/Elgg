<?php
/**
 * Elgg system settings on initial installation
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 */

echo "<p>" . autop(elgg_echo("installation:settings:description")) . "</p>";

echo elgg_view("settings/system",array("action" => "action/systemsettings/install"));