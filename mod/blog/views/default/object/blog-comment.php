<?php

	/**
	 * Elgg blog individual comment view
	 * 
	 * @package ElggBlog
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Ben Werdmuller <ben@curverider.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 * 
	 * @uses $vars['entity'] The comment to view
	 */


?>

	<li>
		
		<?php echo elgg_view("output/longtext",array("value" => $vars['entity']->value)); ?>
		<p>
			<?php
			
				if ($owner = get_entity($vars['entity']->owner_guid)) {
					echo $owner->name;
				}
			
			?>, <?php echo date("F j, g:i a",$vars['entity']->time_created); ?>
		</p>
	
	</li>