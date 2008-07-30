<?php

	/**
	 * Elgg friends picker
	 * Lists the friends picker
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['entities'] The array of ElggUser objects
	 */

		if (is_array($vars['entities'])) {
			
?>

	<table>
		<tr>
<?php
			
			$column = 0;
			foreach($vars['entities'] as $entity) {
				if (!($entity instanceof ElggEntity)) $entity = get_entity($entity);
					if ($entity instanceof ElggEntity) {
?>

			<td style="width:25px">
				<?php echo elgg_view("profile/icon",array('entity' => $entity, 'size' => 'tiny')); ?> 
			</td>
			<td style="width: 300px; padding: 5px;">
<?php

					echo $entity->name;
				
?>
			</td>

<?php
				
				$column++;
				if ($column > 1) {
					echo "</tr><tr>";
					$column = 0;
				}
				
			}
			if ($column > 0) echo "</tr>";
			
?>

	</table>

<?php
			}
		}

?>