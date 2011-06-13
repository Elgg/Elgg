<?php

	/**
	 * Elgg profile icon
	 * 
	 * @package ElggProfile
	 * 
	 * @uses $vars['entity'] The user entity. If none specified, the current user is assumed.
	 * @uses $vars['size'] The size - small, medium or large. If none specified, medium is assumed. 
	 */

	// Get entity
		if (empty($vars['entity']))
			$vars['entity'] = $vars['user'];

		if ($vars['entity'] instanceof ElggUser) {
			
		$name = htmlentities($vars['entity']->name, ENT_QUOTES, 'UTF-8');
		$username = $vars['entity']->username;

		$title = htmlentities($vars['entity']->name, ENT_QUOTES, 'UTF-8');
		
		if ($icontime = $vars['entity']->icontime) {
			$icontime = "{$icontime}";
		} else {
			$icontime = "default";
		}
			
	// Get size
		if (!in_array($vars['size'],array('small','medium','large','tiny','master','topbar')))
			$vars['size'] = "medium";
			
	// Get any align and js
		if (!empty($vars['align'])) {
			$align = " align=\"{$vars['align']}\" ";
		} else {
			$align = "";
		}

		if ($vars['entity']->isBanned()) {
			$img_class = 'class = "profile_banned"';
			$title = elgg_echo('profile:banned');
		} else {
			$img_class = '';
		}

	// Override
		if (isset($vars['override']) && $vars['override'] == true) {
			$override = true;
		} else $override = false;
		
		if (!$override) {
		
?>
<div class="usericon">
<div class="avatar_menu_button"><img src="<?php echo $vars['url']; ?>_graphics/spacer.gif" border="0" width="15" height="15" /></div>

	<div class="sub_menu">
		<h3><a href="<?php echo $vars['entity']->getURL(); ?>"><?php echo $vars['entity']->name; ?></a></h3>
		<?php
			if (isloggedin()) {
				$actions = elgg_view('profile/menu/actions',$vars);
				if (!empty($actions)) {
					
					echo "<div class=\"item_line\">{$actions}</div>";
					
				}
				if ($vars['entity']->getGUID() == $vars['user']->getGUID()) {
					echo elgg_view('profile/menu/linksownpage',$vars);
				} else {
					echo elgg_view('profile/menu/links',$vars);
				}					
			} else {
				echo elgg_view('profile/menu/links',$vars);
			}
		?>
	</div>	
	<?php
		if ((isadminloggedin()) || (!$vars['entity']->isBanned())) {
	 ?><a href="<?php echo $vars['entity']->getURL(); ?>" class="icon" ><?php 
		}
		
	} 
	
	?><img <?php echo $img_class; ?> src="<?php echo elgg_format_url($vars['entity']->getIcon($vars['size'])); ?>" border="0" <?php echo $align; ?> alt="<?php echo htmlentities($vars['entity']->name, ENT_QUOTES, 'UTF-8'); ?>" title="<?php echo $title; ?>" <?php echo $vars['js']; ?> /><?php

		if (!$override) {
	
	?></a>
</div>

<?php

	}
		}

?>