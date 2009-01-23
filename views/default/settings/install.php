<?php

	/**
	 * Elgg system settings on initial installation
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 * 
	 */

	echo "<p>" . autop(elgg_echo("installation:settings:description")) . "</p>";

	echo elgg_view("settings/system",array("action" => "action/systemsettings/install"));

?>