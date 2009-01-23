<?php

	/**
	 * Elgg exception
	 * Displays a single exception
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['object'] An exception
	 */

	global $CONFIG;
?>
<!-- 
<?php echo get_class($vars['object']); ?>: <?php echo autop($vars['object']->getMessage()); ?>


<?php if ($CONFIG->debug) { ?>
<?php

			echo print_r($vars['object'], true);
		
		?>
<?php } ?>
	
-->