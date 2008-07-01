<?php

	/**
	 * Elgg widget layout
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

		$widgettypes = get_widget_types();

		$owner = page_owner_entity();
		
		$area1widgets = get_widgets(page_owner(),get_context(),1);
		$area2widgets = get_widgets(page_owner(),get_context(),2);
		
		if (empty($area1widgets) && empty($area2widgets)) {
			
			if (isset($area3)) $area1 = $area3;
			if (isset($area4)) $area2 = $area4;
			
		}
		
		if (is_array($widgettypes) && sizeof($widgettypes) > 0 && $owner->canEdit()) {

			
?>

<div id="customise_editpanel">

<div id="customise_editpanel_rhs">
<h2><?php echo elgg_echo("widgets:gallery"); ?></h2>
<div id="widget_picker_gallery">


<?php

	foreach($widgettypes as $handler => $widget) {

?>

<table class="draggable_widget" cellspacing="0"><tr><td width="149px">
	<h3>
		<?php echo $widget->name; ?>
		<input type="hidden" name="multiple" value="<?php echo $widgettypes[$widget->handler]->multiple; ?>" />
		<input type="hidden" name="side" value="<?php echo in_array('side',$widgettypes[$widget->handler]->positions); ?>" />
		<input type="hidden" name="main" value="<?php echo in_array('main',$widgettypes[$widget->handler]->positions); ?>" />
		<input type="hidden" name="handler" value="<?php echo htmlentities($handler); ?>" />
		<input type="hidden" name="description" value="<?php echo htmlentities($widget->description); ?>" />
		<input type="hidden" name="guid" value="0" />
	</h3>
</td>
<td width="17px" align="right"><!-- <a href="#"><img src="<?php echo $vars['url']; ?>_graphics/icon_customise_remove.gif" class="remove_me" /></a> --></td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/icon_customise_info.gif" class="more_info" /></a></td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/icon_customise_drag.gif" class="drag_handle" /></a></td>
</tr></table>

<?php
	}
?>

<br /><!-- bit of space at the bottom of the widget gallery -->

</div><!-- /#customise_editpanel_rhs -->
</div><!-- /#widget_picker_gallery -->


<div class="customise_editpanel_instructions">
<h2>Add widgets to your page</h2>
<p>
Choose the features you want to add to your page by dragging them from the <b>Widget gallery</b> to either the <b>Main content area</b> or the <b>Sidebar</b>, and position them where you would like them to appear.
</p>
<p>To remove a widget drag it back to the <b>Widget gallery</b>.
</p>
</div>


<div id="customise_page_view">
<table>
<tr>

<td>
<h2 class="mainwidgets"><?php echo elgg_echo("widgets:maincontent"); ?></h2>
<div id="main_widgets">

<?php
	$mainwidgetstring = "";
	if (is_array($area1widgets) && sizeof($area1widgets) > 0) {
		foreach($area1widgets as $widget) {
			if (!empty($mainwidgetstring)) {
				$mainwidgetstring .= "::";
			}
			$mainwidgetstring .= "{$widget->handler}::{$widget->getGUID()}";
?>

<table class="draggable_widget" cellspacing="0"><tr><td width="149px">
	<h3>
		<?php echo $widgettypes[$widget->handler]->name; ?>
		<input type="hidden" name="handler" value="<?php 
			echo $widget->handler; 
		?>" />
		<input type="hidden" name="multiple" value="<?php echo $widgettypes[$widget->handler]->multiple; ?>" />
		<input type="hidden" name="side" value="<?php echo in_array('side',$widgettypes[$widget->handler]->positions); ?>" />
		<input type="hidden" name="main" value="<?php echo in_array('main',$widgettypes[$widget->handler]->positions); ?>" />
		<input type="hidden" name="description" value="<?php echo htmlentities($widgettypes[$widget->handler]->description); ?>" />
		<input type="hidden" name="guid" value="<?php echo $widget->getGUID(); ?>" />
	</h3>
</td>
<td width="17px" align="right"><!-- <a href="#"><img src="<?php echo $vars['url']; ?>_graphics/icon_customise_remove.gif" class="remove_me" /></a> --></td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/icon_customise_info.gif" class="more_info" /></a></td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/icon_customise_drag.gif" class="drag_handle" /></a></td>
</tr></table>

<?php
			
		}
	}
?>
</div>
</td>

<td>

<h2><?php echo elgg_echo("widgets:sidebar"); ?></h2>
<div id="rightsidebar_widgets">

<?php
	$sidebarwidgetstring = "";
	if (is_array($area2widgets) && sizeof($area2widgets) > 0) {
		foreach($area2widgets as $widget) {
			if (!empty($sidebarwidgetstring)) {
				$sidebarwidgetstring .= "::";
			}
			$sidebarwidgetstring .= "{$widget->handler}::{$widget->getGUID()}";
?>

<table class="draggable_widget" cellspacing="0"><tr><td width="149px">
	<h3>
		<?php echo $widgettypes[$widget->handler]->name; ?>
		<input type="hidden" name="handler" value="<?php 
			echo $widget->handler; 
		?>" />
		<input type="hidden" name="multiple" value="<?php echo $widgettypes[$widget->handler]->multiple; ?>" />
		<input type="hidden" name="side" value="<?php echo in_array('side',$widgettypes[$widget->handler]->positions); ?>" />
		<input type="hidden" name="main" value="<?php echo in_array('main',$widgettypes[$widget->handler]->positions); ?>" />
		<input type="hidden" name="description" value="<?php echo htmlentities($widgettypes[$widget->handler]->description); ?>" />
		<input type="hidden" name="guid" value="<?php echo $widget->getGUID(); ?>" />
	</h3>
</td>
<td width="17px" align="right"><!-- <a href="#"><img src="<?php echo $vars['url']; ?>_graphics/icon_customise_remove.gif" class="remove_me" /></a> --></td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/icon_customise_info.gif" class="more_info" /></a></td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/icon_customise_drag.gif" class="drag_handle" /></a></td>
</tr></table>

<?php
			
		}
	}
?>

</div>
</td>

</tr>
</table>

</div><!-- /#customise_page_view -->

<form action="<?php echo $vars['url']; ?>action/widgets/reorder" method="post">
<textarea type="textarea" value="Main widgets" style="display:none" name="debugField1" id="debugField1" /><?php echo $mainwidgetstring; ?></textarea>
<textarea type="textarea" value="Right widgets" style="display:none" name="debugField2" id="debugField2" /><?php echo $sidebarwidgetstring; ?></textarea>
<input type="hidden" name="context" value="<?php echo get_context(); ?>" />
<input type="hidden" name="owner" value="<?php echo page_owner(); ?>" />
<input type="submit" value="Save changes" class="submit_button" onclick="$('a.toggle_customise_edit_panel').click();" />
<input type="button" value="Cancel" class="cancel_button" onclick="$('a.toggle_customise_edit_panel').click();" />

</form>
</div><!-- /customise_editpanel -->

<?php
			
		}
		
?>

<!-- right sidebar -->
<div id="layout_sidebar_right">
<div id="wrapper_sidebar_right">

<?php 
   if($_SESSION['user']->guid == page_owner()){
?>
	<!-- customise page button -->
	<a href="javascript:void(0);" class="toggle_customise_edit_panel">Customise Layout</a>
	<div style="clear:both;"></div>
<?php
 }
?>


	<?php if (isset($vars['area2'])) echo $vars['area2']; ?>
	<?php

		if (is_array($area2widgets) && sizeof($area2widgets) > 0)
		foreach($area2widgets as $widget) {
			echo elgg_view_entity($widget);
		}
	
	?>


</div><!-- /#wrapper_sidebar_right -->
<p></p><!-- necessary to avoid an ie7 bug? -->
</div><!-- /#layout_sidebar_right -->

    
<!-- main content -->
<div id="layout_maincontent" class="has_sidebar_right">
<div id="wrapper_maincontent">

	<?php echo $vars['area1']; ?>
	<?php

		if (is_array($area1widgets) && sizeof($area1widgets) > 0)
		foreach($area1widgets as $widget) {
			echo elgg_view_entity($widget);
		}
	
	?>
	
</div><!-- /#wrapper_maincontent -->
<p></p><!-- necessary to avoid an ie7 bug? -->
</div><!-- /#layout_maincontent .has_sidebar_right -->	

<!-- This clearing element should immediately follow the #layout_maincontent to force the #container to contain all child floats -->
<div class="clearfloat"></div>