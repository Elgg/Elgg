<?php
/**
 * Elgg activity stream menu
 */
$allselect = ''; $friendsselect = ''; $mineselect = ''; $display_option = '';
switch($vars['orient']) {
	case 'all':		$allselect = 'class="selected"';
					break;
	case 'friends':	$friendsselect = 'class="selected"';
					$display_option = '&amp;display=friends';
					break;
	case 'mine':	$mineselect = 'class="selected"';
					$display_option = '&amp;display=mine';
					break;
}
?>
<ul class="submenu riverdashboard">
<?php
	if(isloggedin()){
?>
	<li <?php echo $allselect; ?> ><a href="<?php echo elgg_get_site_url(); ?>pg/activity/"><?php echo elgg_echo('all'); ?></a></li>
	<li <?php echo $friendsselect; ?> ><a href="<?php echo elgg_get_site_url(); ?>pg/activity/?display=friends"><?php echo elgg_echo('friends'); ?></a></li>
	<li <?php echo $mineselect; ?> ><a href="<?php echo elgg_get_site_url(); ?>pg/activity/?display=mine"><?php echo elgg_echo('mine'); ?></a></li>
<?php
	}
?>
</ul>