<?php

	/**
	 * Elgg top toolbar
	 * The standard elgg top toolbar
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @author Curverider Ltd
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
		<a href="http://www.elgg.org" target="_blank"><img src="<?php echo $vars['url']; ?>_graphics/elgg_toolbar_logo.gif" /></a>
		
		<a href="<?php echo $_SESSION['user']->getURL(); ?>"><img class="user_mini_avatar" src="<?php echo $_SESSION['user']->getIcon('topbar'); ?>" /></a>
		
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
			if ($vars['user']->admin || $vars['user']->siteadmin) { 
		
		?>
		
			<a href="<?php echo $vars['url']; ?>pg/admin/" class="usersettings"><?php echo elgg_echo("admin"); ?></a>
		
		<?php
		
				}
		
		?>
	</div>


</div>


<div id="elgg_topbar_container_right">
		<a href="<?php echo $vars['url']; ?>action/logout"><small><?php echo elgg_echo('logout'); ?></small></a>
</div>

<div id="elgg_topbar_container_search">
<form id="searchform" action="<?php echo $vars['url']; ?>pg/search/" method="get">
	<input type="text" size="21" name="tag" value="<?php echo elgg_echo('search'); ?>" onclick="if (this.value=='<?php echo elgg_echo('search'); ?>') { this.value='' }" class="search_input" />
	<input type="submit" value="<?php echo elgg_echo('search:go'); ?>" class="search_submit_button" />
</form>
</div>

</div><!-- /#elgg_topbar -->

<div class="clearfloat"></div>

<?php
    }
?>