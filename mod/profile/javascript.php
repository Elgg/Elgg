<?php

	/**
	 * Elgg profile JS
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Ben Werdmuller <ben@curverider.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	// Get engine
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

		echo elgg_view('profile/javascript');

?>