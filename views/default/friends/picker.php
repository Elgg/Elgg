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

	// Initialise internalname
		if (!isset($vars['internalname'])) {
			$internalname = "friend";
		} else {
			$internalname = $vars['internalname'];
		}

	// We need to count the number of friends pickers on the page.
		static $friendspicker;
		if (!isset($friendspicker)) $friendspicker = 0;
		$friendspicker++;

		$users = array();
		
	// Sort users by letter
		if (is_array($vars['entities']) && sizeof($vars['entities']))
			foreach($vars['entities'] as $user) {
				
				$letter = strtoupper(substr($user->name,0,1));
				if ($letter >= "0" && $letter <= "9") {
					$letter = "0";
				}
				if (!isset($users[$letter])) {
					$users[$letter] = array();
				}
				$users[$letter][$user->name] = $user;
				
			}
		
?>

<div class="friends_picker">
	<div id="friendsPicker<?php echo $friendspicker; ?>">
		<div class="friendsPicker_container">
<?php

	// Initialise letters
		$letter = 'A';
		while (1 == 1) {
?>
			<div class="panel" title="<?php echo $letter; ?>">
				<div class="wrapper">
					<h3><?php echo $letter; ?></h3>					
					
<?php

			if (isset($users[$letter])) {
				ksort($users[$letter]);
				
				echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
				$col = 0;
				
				foreach($users[$letter] as $friend) {
					if ($col == 0) echo "<tr>";
					
					//echo "<p>" . $user->name . "</p>";
					$label = elgg_view("profile/icon",array('entity' => $friend, 'size' => 'tiny')); 
					$options[$label] = $friend->getGUID();

?>

			<td>
			
				<input type="checkbox" name="shares[]" value="<?php echo $options[$label]; ?>" />
			
			</td>

			<td >
			
				<div style="width: 25px; margin-bottom: 15px;">
<?php

				echo $label;
			
?>
				</div>
			</td>
			<td style="width: 300px; padding: 5px;">
<?php

					echo $friend->name;
				
?>
			</td>
<?php
					
					$col++;
					if ($col == 3) echo "</tr>";
				}
				if ($col < 3) echo "</tr>";
				
				echo "</table>";
				
			}

?>
			
				</div>
			</div>
<?php			
			if ($letter == 'Z') break;
			$letter++;
		}
		
?>
		</div>
	</div>
</div>

<script type="text/javascript">
	jQuery(window).bind("load", function() {
		// initialise picker
		$("div#friendsPicker<?php echo $friendspicker; ?>").friendsPicker();
	});
</script>
<script>
	// manually add class to corresponding tab for panels that have content - needs to be automated eventually
<?php

	if (sizeof($users) > 0)
		foreach($users as $letter => $gumph) {
?>
	$("div#friendsPickerNavigation"  + j + " li.tab3 <?php echo $letter; ?>").addClass("tabHasContent");
<?php			
		}

?>
</script>