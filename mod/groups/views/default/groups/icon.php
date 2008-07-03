<?php

	/**
	 * Elgg group icon
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 * 
	 * @uses $vars['entity'] The user entity. If none specified, the current user is assumed.
	 * @uses $vars['size'] The size - small, medium or large. If none specified, medium is assumed. 
	 */

	$group = $vars['entity'];
	
	// Get size
	if (!in_array($vars['size'],array('small','medium','large','tiny','master','topbar')))
		$vars['size'] = "medium";
			
	// Get any align and js
	if (!empty($vars['align'])) {
		$align = " align=\"{$vars['align']}\" ";
	} else {
		$align = "";
	}
	
	if ($icontime = $vars['entity']->icontime) {
		$icontime = "{$icontime}";
	} else {
		$icontime = "default";
	}
	
	$name = htmlentities($vars['entity']->title);
	
	
	
	$username = $vars['entity']->username; // TODO : How do i do an icon when we have no username?
?>

<div class="groupicon">
<div class="avatar_menu_button"><img src="<?php echo $vars['url']; ?>_graphics/avatar_menu_arrow.gif" width="15" height="15" class="arrow" /></div>

	<div class="sub_menu">
		<a href="<?php echo $vars['entity']->getURL(); ?>"><h3><?php echo $vars['entity']->title; ?></h3></a>
		<?php
			if (isloggedin()) {
				$actions = elgg_view('groups/menu/actions',$vars);
				if (!empty($actions)) {
					
					echo "<div class=\"item_line\">{$actions}</div>";
					
				}
				if ($vars['entity']->owner_guid == $vars['user']->getGUID()) {
					echo elgg_view('groups/menu/ownerlinks',$vars);
				} else {
					echo elgg_view('groups/menu/links',$vars);
				}					
			} else {
				echo elgg_view('groups/menu/links',$vars);
			}
				
		?>

	</div>	
	<a href="<?php echo $vars['entity']->getURL(); ?>" class="icon" ><img src="<?php echo $vars['url']; ?>pg/icon/<?php echo $username; ?>/<?php echo $vars['size']; ?>/<?php echo $icontime; ?>.jpg" border="0" <?php echo $align; ?> title="<?php echo $name; ?>" <?php echo $vars['js']; ?> /></a>
</div>