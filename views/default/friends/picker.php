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
		
	// Initialise values
		if (!isset($vars['value'])) {
			$vars['value'] = array();
		} else {
			if (!is_array($vars['value'])) {
				$vars['value'] = (int) $vars['value'];
				$vars['value'] = array($vars['value']);
			}
		}

	// Initialise whether we're calling back or not
		if (isset($vars['callback'])) {
			$callback = $vars['callback'];
		} else {
			$callback = false;
		}
		
	// We need to count the number of friends pickers on the page.
		global $friendspicker;
		if (!isset($friendspicker)) $friendspicker = 0;
		$friendspicker++;

		$users = array();
		$activeletters = array();
		
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
		
	if (!$callback) {
			
?>

<div class="friends_picker">

<?php

	if (isset($vars['content'])) echo $vars['content'];

	
?>

	<div id="friends_picker_placeholder<?php echo $friendspicker; ?>">

<?php
	
	}
	
?>



	<div class="friendsPicker_wrapper">
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
					if (in_array($friend->getGUID(),$vars['value'])) {
						$checked = "checked = \"checked\"";
						if (!in_array($letter,$activeletters))
							$activeletters[] = $letter;
					} else {
						$checked = "";
					}

?>

			<td>
			
				<input type="checkbox" <?php echo $checked; ?> name="<?php echo $internalname; ?>[]" value="<?php echo $options[$label]; ?>" />
			
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
	
<?php

	if (!$callback) {

?>
	
	</div>
</div>


<?php

	}

?>

<script type="text/javascript">
	$(document).ready(function () {
		// initialise picker
		$("div#friendsPicker<?php echo $friendspicker; ?>").friendsPicker();
	});
</script>
<script>
	$(document).ready(function () {
	// manually add class to corresponding tab for panels that have content
<?php
	if (sizeof($activeletters) > 0)
		$chararray = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		foreach($activeletters as $letter) {
			$tab = strpos($chararray, $letter) + 1;
?>
	$("div#friendsPickerNavigation<?php echo $friendspicker - 1; ?> li.tab<?php echo $tab; ?> a").addClass("tabHasContent");
<?php
		}

?>
	});
</script>