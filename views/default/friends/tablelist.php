<?php

	/**
	 * Elgg friends picker
	 * Lists the friends picker
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['entities'] The array of ElggUser objects
	 */

		if (is_array($vars['entities'])) {
			
?>

	<table cellspacing="0" id="collectionMembersTable">
		<tr>
<?php
			$column = 0;
			foreach($vars['entities'] as $entity) {
				if (!($entity instanceof ElggEntity)) $entity = get_entity($entity);
					if ($entity instanceof ElggEntity) {
?>

			<td style="width:25px;">
			<div style="width: 25px; margin-bottom: 15px;">
				<?php echo elgg_view("profile/icon",array('entity' => $entity, 'size' => 'tiny')); ?> 
			</div>
			</td>
			<td style="width: 200px; padding: 5px;">
<?php

					echo $entity->name;
				
?>
			</td>

<?php
				
				$column++;
				if ($column == 3) {
					echo "</tr><tr>";
					$column = 0;
				}
				
			}
			
			
?>

	

<?php
			}
		if ($column < 3 && $column != 0) echo "</tr>";	
		echo "</table>";
		}
		
		if (isset($vars['content'])) echo $vars['content'];

?>