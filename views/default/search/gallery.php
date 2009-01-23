<?php

	/**
	 * Elgg gallery view
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

		$entities = $vars['entities'];
		if (is_array($entities) && sizeof($entities) > 0) {
			
?>

		<table class="search_gallery">

<?php
			
			$col = 0;
			foreach($entities as $entity) {
				if ($col == 0) {
					
					echo "<tr>";
					
				}
				echo "<td class=\"search_gallery_item\">";
				echo elgg_view_entity($entity);
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