<?php

	/**
	 * Elgg river item wrapper.
	 * Wraps all river items.
	 * 
	 * @package Elgg
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

?>

<div class="river_item">

	<p class="river_<?php echo $vars['log']->object_class; ?>_<?php echo $vars['log']->event; ?>">
		<?php

			echo $vars['entry'];
		
		?>
		<span class="river_item_time">
			(<?php

				echo friendly_time($vars['log']->time_created);
			
			?>)
		</span>
	</p>

</div>