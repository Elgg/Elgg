<?php

	/**
	 * Elgg exception
	 * Displays a single exception
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['object'] An exception
	 */

	global $CONFIG;
?>

	<p class="messages-exception">
		<span title="<?php echo get_class($vars['object']); ?>">
		<?php

			echo nl2br($vars['object']->getMessage());
		
		?>
		</span>
	</p>
	
	<?php if ($CONFIG->debug) { ?>
	<hr />
	<p class="messages-exception-detail">
		<?php

			echo nl2br(htmlentities(print_r($vars['object'], true)));
		
		?>
	</p>
	<?php } ?>