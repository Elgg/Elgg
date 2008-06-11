<?php

	/**
	 * Elgg pageshell
	 * The standard HTML page shell that everything else fits into
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['config'] The site configuration settings, imported
	 * @uses $vars['title'] The page title
	 * @uses $vars['body'] The main content of the page
	 * @uses $vars['messages'] A 2d array of various message registers, passed from system_messages()
	 */

	// Set title
		if (empty($vars['title'])) {
			$title = $vars['config']->sitename;
		} else if (empty($vars['config']->sitename)) {
			$title = $vars['title'];
		} else {
			$title = $vars['config']->sitename . ": " . $vars['title'];
		}

?>

<?php echo elgg_view('page_elements/header', $vars); ?>

<!-- main contents -->
    
<!-- display any system messages -->
<?php echo elgg_view('messages/list', array('object' => $vars['sysmessages'])); ?>


<!-- left sidebar -->
<div id="layout_sidebar_left">
<div id="wrapper_sidebar_left">
    
<?php echo elgg_view("navigation/toolbox", $vars); ?>

<?php echo $vars['sidebar']; ?>

</div><!-- /#wrapper_sidebar_left -->
</div><!-- /#layout_sidebar_left -->


<!-- right sidebar -->
<div id="layout_sidebar_right">
<div id="wrapper_sidebar_right">

	<!-- temp right sidebar box + dummy content -->
	<div class="collapsable_box">
	<div class="collapsable_box_header">
	<a href="javascript:void(0);" class="toggle_box_contents">-</a><a href="javascript:void(0);" class="toggle_box_edit_panel">edit</a>
	<h1>Right sidebar</h1></div>
	<div class="collapsable_box_editpanel"><p>Here's all the edit controls for this widget</p></div><!-- /collapsable_box_editpanel -->
	<div class="collapsable_box_content">
	<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
	Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
	Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. 
	Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum</p>
	</div><!-- /.collapsable_box_content -->
	</div><!-- /.collapsable_box -->

</div><!-- /#wrapper_sidebar_right -->
<p></p><!-- necessary to avoid an ie7 bug? -->
</div><!-- /#layout_sidebar_right -->

    
<!-- main content -->
<div id="layout_maincontent">
<div id="wrapper_maincontent">

	<h1><?php echo $title; ?></h1>
	<?php echo $vars['body']; ?>
	
</div><!-- /#wrapper_maincontent -->
<p></p><!-- necessary to avoid an ie7 bug? -->
</div><!-- /#layout_maincontent -->	
	
<!-- This clearing element should immediately follow the #layout_maincontent to force the #container to contain all child floats -->
<div class="clearfloat"></div>


<div id="layout_spotlight">
<div id="wrapper_spotlight">
	
<div class="collapsable_box no_space_after">
	<div class="collapsable_box_header">
		<a href="javascript:void(0);" class="toggle_box_contents">-</a><a href="javascript:void(0);" class="toggle_box_edit_panel">edit</a>
		<h1>Spotlight</h1>
	</div>
	<div class="collapsable_box_editpanel">
		<p>Here's all the edit controls for this widget</p>
	</div><!-- /collapsable_box_editpanel -->
	<div class="collapsable_box_content">
		<p><img src="<?php echo $vars['url']; ?>_graphics/spacer.gif" border="0" width=100% height=184px /></p>
	</div><!-- /.collapsable_box_content -->
</div><!-- /.collapsable_box -->
	
</div><!-- /#wrapper_spotlight -->
</div><!-- /#layout_spotlight -->




<!-- footer -->
<?php echo elgg_view('page_elements/footer', $vars); ?>
