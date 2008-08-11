<?php

	/**
	 * Elgg sample welcome page (logged out)
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

?>

	<?php

		//add various views to area1
		$area1 = "<p>" . elgg_echo("welcome_message") . "</p>";
		$area1 .= elgg_view("account/forms/login");

		//send area one to the appropriate canvas layout
		$body = elgg_view_layout("one_column", $area1);

		//draw to screen
		echo $body;	
	?>