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

	<p>
		<?php echo elgg_echo("welcome_message"); ?>
	</p>
	<?php

		echo elgg_view("account/forms/login");
	
	?>