<?php
/**
 * Elgg profile icon hover over
 * 
 * @package ElggProfile
 * 
 * @uses $vars['entity'] The user entity. If none specified, the current user is assumed. 
 */
?>
<li class="user_menu_name">
	<?php echo $vars['entity']->name; ?>
</li>
<?php
	echo elgg_view('profile/hoverover/actions', $vars);
	echo elgg_view('profile/hoverover/links', $vars);
?>