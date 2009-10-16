<?php
/**
 * Elgg user main settings page.
 * Functions for adding and manipulating options on the user settings panel.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

// Description of what's going on
echo "<p>" . elgg_view('output/longtext', array('value' => elgg_echo("usersettings:description"))) . "</p>";
