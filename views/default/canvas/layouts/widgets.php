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
		
		if (is_array($widgettypes) && sizeof($widgettypes) > 0 && $owner->canEdit()) {

			
?>

<div id="customise_editpanel">

<div id="widget_picker_gallery">
<h2><?php echo elgg_echo("widgets:gallery"); ?></h2>

<?php

	foreach($widgettypes as $handler => $widget) {

?>

<table class="draggable_widget" cellspacing="0"><tr><td width="149px">
	<h3>
		<?php echo $widget->name; ?>
		<input type="hidden" name="handler" value="<?php echo htmlentities($handler); ?>" />
		<input type="hidden" name="description" value="<?php echo htmlentities($widget->description); ?>" />
		<input type="hidden" name="guid" value="0" />
	</h3>
</td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/icon_customise_remove.gif" class="remove_me" /></a></td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/icon_customise_info.gif" class="more_info" /></a></td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/icon_customise_drag.gif" class="drag_handle" /></a></td>
</tr></table>

<?php
	}
?>

</div>


<div id="customise_page_view">
<table>
<tr>

<td width="300px">

<div id="main_widgets">
<h2><?php echo elgg_echo("widgets:maincontent"); ?></h2>
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
		<input type="hidden" name="description" value="<?php echo htmlentities($widgettypes[$widget->handler]->description); ?>" />
		<input type="hidden" name="guid" value="<?php echo $widget->getGUID(); ?>" />
	</h3>
</td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/icon_customise_remove.gif" class="remove_me" /></a></td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/icon_customise_info.gif" class="more_info" /></a></td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/icon_customise_drag.gif" class="drag_handle" /></a></td>
</tr></table>

<?php
			
		}
	}
?>
</div>
</td>

<td width="240px">
<div id="rightsidebar_widgets">
<h2><?php echo elgg_echo("widgets:sidebar"); ?></h2>

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
		<input type="hidden" name="description" value="<?php echo htmlentities($widgettypes[$widget->handler]->description); ?>" />
		<input type="hidden" name="guid" value="<?php echo $widget->getGUID(); ?>" />
	</h3>
</td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/icon_customise_remove.gif" class="remove_me" /></a></td>
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

</form>
</div><!-- /customise_editpanel -->

<!-- temporary customise button here -->
<a href="javascript:void(0);" class="toggle_customise_edit_panel"><?php echo elgg_echo("layout:customise"); ?></a>
<br />

<?php
			
		}
		
?>

<!-- right sidebar -->
<div id="layout_sidebar_right">
<div id="wrapper_sidebar_right">


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
</div><!-- /#layout_maincontent -->	

<!-- This clearing element should immediately follow the #layout_maincontent to force the #container to contain all child floats -->
<div class="clearfloat"></div>