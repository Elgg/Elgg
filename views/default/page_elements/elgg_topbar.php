<?php

	/**
	 * Elgg top toolbar
	 * The standard elgg top toolbar
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 * 
	 */
?>

<?php
     if (isloggedin()) {
?>

<div id="elgg_topbar">

<div id="elgg_topbar_container_left">
	<div class="toolbarimages">
		<a href=""><img src="<?php echo $vars['url']; ?>_graphics/elgg_toolbar_logo.gif" /></a>
		<a href=""><img src="<?php echo $vars['url']; ?>_graphics/avatar_mini.gif" /></a>
		
		<!-- new icon size needed 16px square - mini -->
		<!-- <a href=""><img src="<?php echo $vars['url']; ?>pg/icon/<?php echo $_SESSION['user']->username; ?>/elgg_topbar/<?php echo $_SESSION['user']->timecreated; ?>.jpg"></a> -->
		
		
	</div>
	<div class="toolbarlinks">
		<a href="" class="loggedinuser">Pete</a>
		<a href="<?php echo $vars['url']; ?>pg/dashboard/" class="pagelinks"><?php echo elgg_echo('dashboard'); ?></a>
		<a href="" class="usersettings"><?php echo elgg_echo('settings'); ?></a>
		
		<?php
		
				// The administration link is for admin or site admin users only
				if ($vars['user']->admin || $vars['user']->siteadmin) { 
		
		?>
		
				<a href="<?php echo $vars['url']; ?>pg/admin/" class="usersettings"><?php echo elgg_echo("admin"); ?></a>
		
		<?php
		
				}
		
		?>

		
		
		
	</div>
</div>


<div id="elgg_topbar_container_right">
		<img src="<?php echo $vars['url']; ?>_graphics/elgg_toolbar_logout.gif" /><a href="<?php echo $vars['url']; ?>action/logout"><small><?php echo elgg_echo('logout'); ?></small></a>
</div>

<div id="elgg_topbar_container_search">
<form id="searchform" action="<?php echo $vars['url']; ?>search/" method="get">
	<input type="text" size="21" name="tag" value="Search" onclick="if (this.value=='Search') { this.value='' }" class="search_input" />
	<input type="submit" value="Go" class="search_submit_button" />
</form>
</div>

</div><!-- /#elgg_topbar -->


<!-- elgg user settings panel -->
<div id="elgg_topbar_panel">

<p>user settings here</p>

</div>
<!-- /#elgg_topbar_panel -->
<div style="clear:both;"></div>

<?php
    }
?>

