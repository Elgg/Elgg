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

	if (isset($vars['entity']) && $vars['entity'] instanceof ElggEntity) {
		
		$subtype = $vars['entity']->getSubtype();
		if (empty($subtype)) $subtype = $vars['entity']->type;
		if (empty($subtype)) $subtype = "general";
		
	}

?>

<div class="river_item">

	<div class="river_<?php echo $subtype; ?>">
		<div class="river_<?php echo $vars['log']->event; ?>">
			<p class="river_<?php echo $subtype; ?>_<?php echo $vars['log']->event; ?>">
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
	</div>

</div>