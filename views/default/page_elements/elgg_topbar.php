<?php
/**
 * Elgg top toolbar
 * The standard elgg top toolbar
 *
 * @package Elgg
 * @subpackage Core
 *
 */
?>

<?php
	if (isloggedin()) {
?>

<div id="elgg_topbar">

<div id="elgg_topbar_container_left">
	<div class="toolbarimages">
		<a href="http://www.elgg.org" target="_blank"><img src="<?php echo $vars['url']; ?>_graphics/elgg_toolbar_logo.gif" alt="toolbar_logo graphic" /></a>

		<a href="<?php echo get_loggedin_user()->getURL(); ?>"><img class="user_mini_avatar" src="<?php echo get_loggedin_user()->getIcon('topbar'); ?>" alt="User avatar" /></a>

	</div>
	<div class="toolbarlinks">
		<a href="<?php echo $vars['url']; ?>pg/dashboard/" class="pagelinks"><?php echo elgg_echo('dashboard'); ?></a>
	</div>
		<?php

			echo elgg_view("navigation/topbar_tools");

		?>

		<div class="toolbarlinks2">
		<?php
		//allow people to extend this top menu
		echo elgg_view('elgg_topbar/extend', $vars);
		?>

		<a href="<?php echo $vars['url']; ?>pg/settings/" class="usersettings"><?php echo elgg_echo('settings'); ?></a>

		<?php

			// The administration link is for admin or site admin users only
			if ($vars['user']->isAdmin()) {

		?>

			<a href="<?php echo $vars['url']; ?>pg/admin/" class="usersettings"><?php echo elgg_echo("admin"); ?></a>

		<?php

				}

		?>
	</div>


</div>


<div id="elgg_topbar_container_right">
		<small>
			<?php echo elgg_view('output/url', array('href' => "{$vars['url']}action/logout", 'text' => elgg_echo('logout'), 'is_action' => TRUE)); ?>
		</small>
</div>

<div id="elgg_topbar_container_search">
<?php echo elgg_view('page_elements/searchbox'); ?>
</div>

</div><!-- /#elgg_topbar -->

<div class="clearfloat"></div>

<?php
	}
