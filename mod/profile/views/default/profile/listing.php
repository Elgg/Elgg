<?php

	/**
	 * Elgg user display (small)
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Ben Werdmuller <ben@curverider.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 * 
	 * @uses $vars['entity'] The user entity
	 */

?>

	<div class="search_listing">
	
		<div class="search_listing_icon">
			<?php

				echo elgg_view(
						"profile/icon", array(
												'entity' => $vars['entity'],
												'size' => 'small',
											  )
					);
			
			?>
		</div>
		<div class="search_listing_info">
			<p><b><?php

				echo "<a href=\"" . $vars['entity']->getUrl() . "\">" . $vars['entity']->name . "</a>";
			
			?></b></p>
			<?php

				$location = $vars['entity']->location;
				if (!empty($location)) {
					echo "<p>" . elgg_echo("profile:location") . ": " . elgg_view("output/tags",array('value' => $vars['entity']->location)) . "</p>";
				}
			
			?>
		</div>		
	
	</div>