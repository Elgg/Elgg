<?php

	$contents = array();
	$contents['all'] = 'all';
	if (!empty($vars['config']->registered_entities)) {
		foreach ($vars['config']->registered_entities as $type => $ar) {
			foreach ($vars['config']->registered_entities[$type] as $object) {
				if (!empty($object )) {
					$keyname = 'item:'.$type.':'.$object;
				} else $keyname = 'item:'.$type; 
				$contents[$keyname] = "{$type},{$object}";
			}
		}
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

<div class="contentWrapper">
	<div id="elgg_horizontal_tabbed_nav">
		<ul>
			<li><a <?php echo $allselect; ?> href="?type=<?php echo $vars['type']; ?>&content=<?php echo $vars['subtype']; ?>"><?php echo elgg_echo('all'); ?></a></li>
			<li><a <?php echo $friendsselect; ?> href="?type=<?php echo $vars['type']; ?>&display=friends&content=<?php echo $vars['subtype']; ?>"><?php echo elgg_echo('friends'); ?></a></li>
			<li><a <?php echo $mineselect; ?> href="?type=<?php echo $vars['type']; ?>&display=mine&content=<?php echo $vars['subtype']; ?>"><?php echo elgg_echo('mine'); ?></a></li>
		</ul>
	</div>
	
	<div class="riverdashboard_content_select">
		<form action="index.php">
			<select name="content">
				<?php
		
					foreach($contents as $label => $content) {
						if (("{$vars['type']},{$vars['subtype']}" == $content) ||
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
<!-- </div> -->