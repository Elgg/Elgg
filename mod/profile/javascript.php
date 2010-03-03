<?php

	/**
	 * Elgg profile JS
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */

	// Get engine
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

		header("Content-type: text/javascript");
		header('Expires: ' . date('r',time() + 864000));
		header("Pragma: public");
		header("Cache-Control: public");
		echo elgg_view('profile/javascript');

?>