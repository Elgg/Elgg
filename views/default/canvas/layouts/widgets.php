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
		
		if (is_array($widgettypes) && sizeof($widgettypes) > 0 && $owner->canEdit()) {
			
?>

<div id="customise_editpanel">

<div id="widget_picker_gallery">
<h2>Widget gallery</h2>

<?php

	foreach($widgettypes as $widget) {

?>

<table class="draggable_widget" cellspacing="0"><tr><td width="149px"><h3><?php echo $widget->name; ?></h3></td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/icon_customise_remove.gif" class="remove_me" /></a></td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/icon_customise_info.gif" class="more_info" /></a></td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/icon_customise_drag.gif" class="drag_handle" /></a></td>
</tr></table>

<?php

	}

?>
<br />
</div>


<div id="customise_page_view">
<table>
<tr>

<td width="300px">
<h2>Main content area widgets</h2>
<div id="main_widgets">

<table class="draggable_widget" cellspacing="0"><tr><td width="149px"><h3>Widget 1 Title</h3></td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/icon_customise_remove.gif" class="remove_me" /></a></td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/icon_customise_info.gif" class="more_info" /></a></td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/icon_customise_drag.gif" class="drag_handle" /></a></td>
</tr></table>
<table class="draggable_widget" cellspacing="0"><tr><td width="149px"><h3>Widget 2 Title megacalculatorextrodinaire</h3></td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/icon_customise_remove.gif" class="remove_me" /></a></td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/icon_customise_info.gif" class="more_info" /></a></td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/icon_customise_drag.gif" class="drag_handle" /></a></td>
</tr></table>
<table class="draggable_widget" cellspacing="0"><tr><td width="149px"><h3>Widget 3 Title</h3></td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/icon_customise_remove.gif" class="remove_me" /></a></td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/icon_customise_info.gif" class="more_info" /></a></td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/icon_customise_drag.gif" class="drag_handle" /></a></td>
</tr></table>
<table class="draggable_widget" cellspacing="0"><tr><td width="149px"><h3>Widget 4 Title</h3></td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/icon_customise_remove.gif" class="remove_me" /></a></td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/icon_customise_info.gif" class="more_info" /></a></td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/icon_customise_drag.gif" class="drag_handle" /></a></td>
</tr></table>
<table class="draggable_widget" cellspacing="0"><tr><td width="149px"><h3>Widget 5 Title</h3></td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/icon_customise_remove.gif" class="remove_me" /></a></td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/icon_customise_info.gif" class="more_info" /></a></td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/icon_customise_drag.gif" class="drag_handle" /></a></td>
</tr></table>
</div>
</td>

<td width="240px">
<div id="rightsidebar_widgets">
<h2>Right sidebar widgets</h2>
<table class="draggable_widget" cellspacing="0"><tr><td width="149px"><h3>Widget 6 Title</h3></td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/icon_customise_remove.gif" class="remove_me" /></a></td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/icon_customise_info.gif" class="more_info" /></a></td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/icon_customise_drag.gif" class="drag_handle" /></a></td>
</tr></table>
<table class="draggable_widget" cellspacing="0"><tr><td width="149px"><h3>Widget 7 Title</h3></td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/icon_customise_remove.gif" class="remove_me" /></a></td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/icon_customise_info.gif" class="more_info" /></a></td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/icon_customise_drag.gif" class="drag_handle" /></a></td>
</tr></table>
<table class="draggable_widget" cellspacing="0"><tr><td width="149px"><h3>Widget 8 Title</h3></td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/icon_customise_remove.gif" class="remove_me" /></a></td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/icon_customise_info.gif" class="more_info" /></a></td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/icon_customise_drag.gif" class="drag_handle" /></a></td>
</tr></table>
</div>
</td>

</tr>
</table>

</div><!-- /#customise_page_view -->

<input type="submit" value="Save changes" class="submit_button" />

</div><!-- /customise_editpanel -->

<!-- temporary customise button here -->
<a href="javascript:void(0);" class="toggle_customise_edit_panel">Customise Layout</a>

	<p>
		<b>Temporarily, add a widget to this page:</b><br />

<?php

		foreach($widgettypes as $handler => $widgettype) {
			$url = $vars['url'] . "action/widgets/add";
			$url .= "?handler=" . $handler;
			$url .= "&amp;context=" . get_context();
			$url .= "&amp;user=" . page_owner();
			$url .= "&amp;column=1";
			echo "<a href=\"{$url}\">{$widgettype->name}</a><br />";	
		}

?>
	</p>

<?php
			
		}
		
?>

<!-- right sidebar -->
<div id="layout_sidebar_right">
<div id="wrapper_sidebar_right">


	<?php echo $vars['area2']; ?>
	<?php

		if ($widgets = get_widgets(page_owner(),get_context(),2)) {
			if (is_array($widgets) && sizeof($widgets) > 0)
			foreach($widgets as $widget) {
				echo elgg_view_entity($widget);
			}
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

		if ($widgets = get_widgets(page_owner(),get_context(),1)) {
			if (is_array($widgets) && sizeof($widgets) > 0)
			foreach($widgets as $widget) {
				echo elgg_view_entity($widget);
			}
		}
	
	?>
	
	
</div><!-- /#wrapper_maincontent -->
<p></p><!-- necessary to avoid an ie7 bug? -->
</div><!-- /#layout_maincontent -->	
	
<!-- This clearing element should immediately follow the #layout_maincontent to force the #container to contain all child floats -->
<div class="clearfloat"></div>