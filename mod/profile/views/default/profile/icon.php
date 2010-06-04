<?php

/**
 * Elgg profile icon
 * 
 * @package ElggProfile
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.com/
 * 
 * @uses $vars['entity'] The user entity. If none specified, the current user is assumed.
 * @uses $vars['size'] The size - small, medium or large. If none specified, medium is assumed. 
 */

// Get entity
if (empty($vars['entity'])) {
	$vars['entity'] = $vars['user'];
}

if ($vars['entity'] instanceof ElggUser) {
	$name = htmlentities($vars['entity']->name, ENT_QUOTES, 'UTF-8');
	$username = $vars['entity']->username;

	if ($icontime = $vars['entity']->icontime) {
		$icontime = "{$icontime}";
	} else {
		$icontime = "default";
	}
	
	// Get size
	if (!in_array($vars['size'],array('small','medium','large','tiny','master','topbar'))) {
		$vars['size'] = 'medium';
	}
	
	// Get any align and js
	if (!empty($vars['align'])) {
		$align = " align=\"{$vars['align']}\" ";
	} else {
		$align = '';
	}

	// Override
	if (isset($vars['override']) && $vars['override'] == true) {
		$override = true;
	} else {
		$override = false;
	}
	// profile avatar drop-down menu
	if (!$override) {
	?>	
		<div class="usericon <?php echo $vars['size']; ?>">
		<div class="avatar_menu_button"><img src="<?php echo $vars['url']; ?>_graphics/spacer.gif" border="0" width="15" height="15" /></div>
		<div class="sub_menu">
			<h3 class="displayname"><a href="<?php echo $vars['entity']->getURL(); ?>"><?php echo $vars['entity']->name; ?>
			<span class="username"><?php echo "&#64;" . $vars['entity']->username; ?></span></a></h3>			
			<?php
			echo "<ul class='sub_menu_list'>";
				if (isloggedin()) {
					// if not looking at your own avatar menu
					if ($vars['entity']->getGUID() != $vars['user']->getGUID()) {
					
						// Add / Remove friend link
						$friendlinks = elgg_view('profile/menu/friendlinks',$vars);
						if (!empty($friendlinks)) {
							echo "<li class='user_menu_profile'>{$friendlinks}</li>";
						}
						// view for plugins to extend
						echo elgg_view('profile/menu/links',$vars);
					} else {
						// if looking at your own avatar menu - provide a couple of handy links
						?>
						<li class="user_menu_profile">
							<a class="edit_profile" href="<?php echo $vars['url']?>pg/profile/<?php echo $vars['entity']->username; ?>/edit/details"><?php echo elgg_echo("profile:edit"); ?></a>
						</li>
						<li class="user_menu_profile">
							<a class="edit_avatar" href="<?php echo $vars['url']?>pg/profile/<?php echo $vars['entity']->username; ?>/edit/icon"><?php echo elgg_echo("profile:editicon"); ?></a>
						</li>
						<?php
					}
					
					// if Admin is logged in, and not looking at admins own avatar menu
					if (isadminloggedin() && ($_SESSION['id']!=$vars['entity']->guid) ){
						$adminlinks = elgg_view('profile/menu/adminlinks', $vars);
						if (!empty($adminlinks)) {
							echo "<li class='user_menu_admin'>{$adminlinks}</li>";
						}
					}
		
				}
				echo "</ul>";
			?>
		</div>	
			<?php
				if ((isadminloggedin()) || (!$vars['entity']->isBanned())) {
				 ?><a href="<?php echo $vars['entity']->getURL(); ?>" class="icon" ><?php 
				}
		
	} 

	// Rounded avatar corners - CSS3 method - users avatar as background image so we can clip it with border-radius in supported browsers
	?>
	<img src="<?php echo $vars['url']; ?>_graphics/spacer.gif" border="0" <?php echo $align; ?> alt="<?php echo htmlentities($vars['entity']->name, ENT_QUOTES, 'UTF-8'); ?>" title="<?php echo htmlentities($vars['entity']->name, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $vars['js']; ?> style="background: url(<?php echo $vars['entity']->getIcon($vars['size']); ?>) no-repeat;" class="<?php echo $vars['size']; ?>" />
	<?php
	/*
	original avatar method
	<img src="<?php echo elgg_format_url($vars['entity']->getIcon($vars['size'])); ?>" border="0" <?php echo $align; ?> alt="<?php echo htmlentities($vars['entity']->name, ENT_QUOTES, 'UTF-8'); ?>" title="<?php echo htmlentities($vars['entity']->name, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $vars['js']; ?> />
	*/

	if (!$override) { 
	?>
		</a></div>
	<?php
	}
}