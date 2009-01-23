<?php

	/**
	 * Elgg standard message
	 * Displays a single Elgg system message
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['object'] A system message (string)
	 */
?>

	<p>
		<?php echo nl2br($vars['object']); ?>
	</p>