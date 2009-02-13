<?php

	$contents = array();
	$contents['all'] = 'all';
	if (!empty($vars['config']->registered_entities['object'])) {
		foreach ($vars['config']->registered_entities['object'] as $object)
			$contents['item:object:'.$object] = $object;
	}
	
	$allselect = ''; $friendsselect = ''; $mineselect = '';
	switch($vars['orient']) {
		case '':		$allselect = 'class="riverdashboard_tabs_selected"';
						break;
		case 'friends':		$friendsselect = 'class="riverdashboard_tabs_selected"';
						break;
		case 'mine':		$mineselect = 'class="riverdashboard_tabs_selected"';
						break;
	}

?>

<div class="riverdashboard_navigation">
	<div class="riverdashboard_tabs">
		<p>
			<a <?php echo $allselect; ?> href="?content=<?php echo $vars['subtype']; ?>">All</a>
			<a <?php echo $friendsselect; ?> href="?display=friends&content=<?php echo $vars['subtype']; ?>">Friends</a>
			<a <?php echo $mineselect; ?> href="?display=mine&content=<?php echo $vars['subtype']; ?>">Mine</a>
		</p>
	</div>
	
	<div class="riverdashboard_content_select">
		<form action="index.php">
			<select name="content">
				<?php
		
					foreach($contents as $label => $content) {
						if (($vars['subtype'] == $content) ||
							(empty($vars['subtype']) && $content == 'all')) {
							$selected = 'selected="selected"';
						} else $selected = '';
						echo "<option value=\"{$content}\" {$selected}>".elgg_echo($label)."</option>";
					}
				
				?>
			</select>
			<input type="hidden" name="display" value="<?php echo htmlentities($vars['orient']); ?>" />
			<input type="submit" value="<?php echo elgg_echo('filter'); ?>" />
		</form>
	</div>
</div>