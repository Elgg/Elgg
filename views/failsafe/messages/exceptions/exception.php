<?php

	/**
	 * Elgg exception (fallback mode)
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

	<p class="messages-exception" style="background:#FDFFC3;display:block;padding:10px;">
		<span title="<?php echo get_class($vars['object']); ?>">
		<?php

			echo nl2br($vars['object']->getMessage());
		
		?>
		</span>
	</p>
	
 	<?php if ($CONFIG->debug) { ?>
	
	<p class="messages-exception-detail" style="background:#FDFFC3;display:block;padding:10px;">
		<?php

			echo nl2br(htmlentities(print_r($vars['object'], true), ENT_QUOTES, 'UTF-8'));
		
		?>
	</p>
	<?php } ?> 