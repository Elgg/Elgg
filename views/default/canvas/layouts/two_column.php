<?php

	/**
	 * Elgg two-column layout
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

?>

<!-- right sidebar -->
<div id="layout_sidebar_right">
<div id="wrapper_sidebar_right">

	<!-- temp right sidebar box + dummy content -->
	<div class="collapsable_box">
	<div class="collapsable_box_header">
	<a href="javascript:void(0);" class="toggle_box_contents">-</a><a href="javascript:void(0);" class="toggle_box_edit_panel">edit</a>
	<h1>Goals</h1></div>
	<div class="collapsable_box_editpanel"><p>Here's all the edit controls for this widget</p></div><!-- /collapsable_box_editpanel -->
	<div class="collapsable_box_content">
	   <?php echo $vars['area2']; ?>
	</div><!-- /.collapsable_box_content -->
	</div><!-- /.collapsable_box -->
	
	
	<!-- temp right sidebar box + dummy content -->
	<div class="collapsable_box">
	<div class="collapsable_box_header">
	<a href="javascript:void(0);" class="toggle_box_contents">-</a><a href="javascript:void(0);" class="toggle_box_edit_panel">edit</a>
	<h1>Tag cloud</h1></div>
	<div class="collapsable_box_editpanel"><p>Here's all the edit controls for this widget</p></div><!-- /collapsable_box_editpanel -->
	<div class="collapsable_box_content">
	   <?php echo $vars['area3']; ?>
	</div><!-- /.collapsable_box_content -->
	</div><!-- /.collapsable_box -->


</div><!-- /#wrapper_sidebar_right -->
<p></p><!-- necessary to avoid an ie7 bug? -->
</div><!-- /#layout_sidebar_right -->

    
<!-- main content -->
<div id="layout_maincontent" class="has_sidebar_right">
<div id="wrapper_maincontent">

    <?php echo $vars['area1']; ?>
	
</div><!-- /#wrapper_maincontent -->
<p></p><!-- necessary to avoid an ie7 bug? -->
</div><!-- /#layout_maincontent -->	
	
<!-- This clearing element should immediately follow the #layout_maincontent to force the #container to contain all child floats -->
<div class="clearfloat"></div>