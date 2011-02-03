<?php

	$owner = $vars['owner'];
	if ($friends = elgg_get_entities_from_relationship(array('relationship' => 'friend', 'relationship_guid' => $owner->getGUID(), 'inverse_relationship' => FALSE, 'type' => 'user', 'limit' => 9999))) {
		
?>
	
<table border="0" cellspacing="0" cellpadding="0">

<?php
		
		$col = 0;
		foreach($friends as $friend) {
			
			if ($col == 0) echo "<tr>";
			
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
			
			if ($col == 3) {
				
				$col = 0;
				echo "</tr>";
				
			}
			
			
		}
		if ($col != 3) {
			echo "</tr>";
		}
		
		
?>

</table>

<?php
		
		/*echo elgg_view('input/checkboxes',array(
		
			'internalname' => 'shares',
			'options' => $options,
			'value' => $vars['shares'],
		
		)); */
		
	}

?>