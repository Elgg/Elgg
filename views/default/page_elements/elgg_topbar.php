<?php
/**
 * Elgg top toolbar
 * The standard elgg top toolbar
 */
?>

<?php
	if (isloggedin()) {
?>

<div id="elgg_topbar" class="clearfloat">
<div id="elgg_topbar_contents">
	<a href="http://www.elgg.org" target="_blank"><img class="site_logo" src="<?php echo $vars['url']; ?>_graphics/elgg_toolbar_logo.gif" alt="Elgg logo" /></a>
	<a href="<?php echo $_SESSION['user']->getURL(); ?>"><img class="user_mini_avatar" src="<?php echo $_SESSION['user']->getIcon('topbar'); ?>" alt="User avatar" /></a>

    <?php
	    // elgg tools menu
        echo elgg_view("navigation/topbar_tools");

		// enable elgg topbar extending
		echo elgg_view('elgg_topbar/extend', $vars);
	?>
	
	<div class="log_out">
		<?php echo elgg_view('output/url', array('href' => "{$vars['url']}action/logout", 'text' => elgg_echo('logout'), 'is_action' => TRUE)); ?>
	</div>

	<?php
		if(is_plugin_enabled('shared_access')){
	?>
	<a href="<?php echo $vars['url']; ?>pg/shared_access/home" class="shared_access"><?php echo elgg_echo('shared_access:shared_access'); ?></a>
	<?php
		}
	?>
	<a href="<?php echo $vars['url']; ?>pg/settings/" class="settings"><?php echo elgg_echo('settings'); ?></a>
	<?php
		if(is_plugin_enabled('help')){
	?>
		<a href="<?php echo $vars['url']; ?>mod/help/index.php" class="help">Help</a>
	<?php
		}
	?>
	<?php
		// The administration link is for admin or site admin users only
		if ($vars['user']->admin || $vars['user']->siteadmin) { 
	?>
		<a href="<?php echo $vars['url']; ?>pg/admin/" class="admin"><?php echo elgg_echo("admin"); ?></a>
	
	<?php
		}
	?>

</div>

</div>
<?php
    }
?>