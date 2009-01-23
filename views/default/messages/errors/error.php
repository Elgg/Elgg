<?php

	/**
	 * Elgg error message
	 * Displays a single error message
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['object'] An error message (string)
	 */
?>

	<p>
		<?php echo autop($vars['object']); ?>
	</p>