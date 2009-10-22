<?php

	/**
	 * Elgg gallery view
	 * 
	 * @package Elgg
	 * @subpackage Core

	 * @author Curverider Ltd

	 * @link http://elgg.org/
	 */

		$entities = $vars['entities'];
		if (is_array($entities) && sizeof($entities) > 0) {
			
?>

		<table class="entity_gallery">

<?php
			
			$col = 0;
			foreach($entities as $entity) {
				if ($col == 0) {
					
					echo "<tr>";
					
				}
				echo "<td class=\"entity_gallery_item\">";
				
				$ev = elgg_view_entity($entity, $fullview);
						
				echo elgg_view('search/listing', array('entity_view' => $ev,
									   'search_types' => $entity->getVolatileData('search')));
				
				
				echo "</td>";
				$col++;
				if ($col > 3) {
					echo "</tr>";
					$col = 0;
				}					
			}
			if ($col > 0) echo "</tr>";
			
?>

		</table>

<?php
			
		}
		
?>