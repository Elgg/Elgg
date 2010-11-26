<?php

/**
 * Elgg widget layout
 *
 * @package Elgg
 * @subpackage Core
 */
$widgettypes = get_widget_types();

$owner = page_owner_entity();

$area1widgets = get_widgets(page_owner(),get_context(),1);
$area2widgets = get_widgets(page_owner(),get_context(),2);
$area3widgets = get_widgets(page_owner(),get_context(),3);

if (empty($area1widgets) && empty($area2widgets) && empty($area3widgets)) {
	if (isset($vars['area3'])) {
		$vars['area1'] = $vars['area3'];
	}
	if (isset($vars['area4'])) {
		$vars['area2'] = $vars['area4'];
	}
}

if ($owner && $owner->canEdit()) {

?>

<div id="customise_editpanel">

<div id="customise_editpanel_rhs">
<h2><?php echo elgg_echo("widgets:gallery"); ?></h2>
<div id="widget_picker_gallery">

<?php
	foreach($widgettypes as $handler => $widget) {
?>

<table class="draggable_widget" cellspacing="0"><tr><td>
	<h3>
		<?php echo $widget->name; ?>
		<input type="hidden" name="multiple" value="<?php if ((isset($widget->handler)) && (isset($widgettypes[$widget->handler]->multiple))) echo $widgettypes[$widget->handler]->multiple; ?>" />
		<input type="hidden" name="side" value="<?php if ((isset($widget->handler)) && (isset($widgettypes[$widget->handler])) && (is_array($widgettypes[$widget->handler]->positions))) echo in_array('side',$widgettypes[$widget->handler]->positions); ?>" />
		<input type="hidden" name="main" value="<?php if ((isset($widget->handler)) && (isset($widgettypes[$widget->handler])) && (is_array($widgettypes[$widget->handler]->positions))) echo in_array('main',$widgettypes[$widget->handler]->positions); ?>" />
		<input type="hidden" name="handler" value="<?php echo htmlentities($handler, ENT_QUOTES, 'UTF-8'); ?>" />
		<input type="hidden" name="description" value="<?php echo htmlentities($widget->description, ENT_QUOTES, 'UTF-8'); ?>" />
		<input type="hidden" name="guid" value="0" />
	</h3>
</td>
<td width="17px" align="right"></td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/spacer.gif" width="14" height="14" class="more_info" /></a></td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/spacer.gif" width="15" height="15" class="drag_handle" /></a></td>
</tr></table>

<?php
	}
?>

<br /><!-- bit of space at the bottom of the widget gallery -->

</div><!-- /#customise_editpanel_rhs -->
</div><!-- /#widget_picker_gallery -->

<div class="customise_editpanel_instructions">
<h2><?php echo elgg_echo('widgets:add'); ?></h2>
<?php echo elgg_view('output/longtext', array('value' => elgg_echo('widgets:add:description'))); ?>
</div>


<div id="customise_page_view">

<table cellspacing="0">
<tr>
	<td colspan="2" align="left" valign="top">

	<?php
	if(get_context() == "profile"){
	?>
			<h2 class="profile_box"><?php echo elgg_echo("widgets:profilebox"); ?></h2>
			<div id="profile_box_widgets">
			<p><small><?php echo elgg_echo('widgets:position:fixed'); ?></small></p>
			</div>
	<?php
	}
	?>

	</td>

	<td rowspan="2" align="left" valign="top">
		<h2><?php echo elgg_echo("widgets:rightcolumn"); ?></h2>
		<div id="rightcolumn_widgets" <?php if(get_context() == "profile")echo "class=\"long\""; ?>>
		<?php
			$rightcolumn_widgets = "";
			if (is_array($area3widgets) && sizeof($area3widgets) > 0) {
				foreach($area3widgets as $widget) {
					if (!empty($rightcolumn_widgets)) {
						$rightcolumn_widgets .= "::";
					}
					$rightcolumn_widgets .= "{$widget->handler}::{$widget->getGUID()}";
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
				<input type="hidden" name="description" value="<?php echo htmlentities($widgettypes[$widget->handler]->description, ENT_QUOTES, 'UTF-8'); ?>" />
				<input type="hidden" name="guid" value="<?php echo $widget->getGUID(); ?>" />
			</h3>
		</td>
		<td width="17px" align="right"></td>
		<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/spacer.gif" width="14" height="14" class="more_info" /></a></td>
		<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/spacer.gif" width="15" height="15" class="drag_handle" /></a></td>
		</tr></table>

		<?php

				}
			}
		?>

		</div>
	</td><!-- /rightcolumn td -->

</tr>

<tr>

<td>
<h2><?php echo elgg_echo("widgets:leftcolumn"); ?></h2>
<div id="leftcolumn_widgets">

<?php
	$leftcolumn_widgets = "";
	if (is_array($area1widgets) && sizeof($area1widgets) > 0) {
		foreach($area1widgets as $widget) {
			if (!empty($leftcolumn_widgets)) {
				$leftcolumn_widgets .= "::";
			}
			$leftcolumn_widgets .= "{$widget->handler}::{$widget->getGUID()}";
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
		<input type="hidden" name="description" value="<?php echo htmlentities($widgettypes[$widget->handler]->description, ENT_QUOTES, 'UTF-8'); ?>" />
		<input type="hidden" name="guid" value="<?php echo $widget->getGUID(); ?>" />
	</h3>
</td>
<td width="17px" align="right"></td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/spacer.gif" width="14" height="14" class="more_info" /></a></td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/spacer.gif" width="15" height="15" class="drag_handle" /></a></td>
</tr></table>

<?php

		}
	}
?>
</div>
</td>

<td>

<h2><?php echo elgg_echo("widgets:middlecolumn"); ?></h2>
<div id="middlecolumn_widgets">

<?php
	$middlecolumn_widgets = "";
	if (is_array($area2widgets) && sizeof($area2widgets) > 0) {
		foreach($area2widgets as $widget) {
			if (!empty($middlecolumn_widgets)) {
				$middlecolumn_widgets .= "::";
			}
			$middlecolumn_widgets .= "{$widget->handler}::{$widget->getGUID()}";
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
		<input type="hidden" name="description" value="<?php echo htmlentities($widgettypes[$widget->handler]->description, ENT_QUOTES, 'UTF-8'); ?>" />
		<input type="hidden" name="guid" value="<?php echo $widget->getGUID(); ?>" />
	</h3>
</td>
<td width="17px" align="right"></td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/spacer.gif" width="14" height="14" class="more_info" /></a></td>
<td width="17px" align="right"><a href="#"><img src="<?php echo $vars['url']; ?>_graphics/spacer.gif" width="15" height="15" class="drag_handle" /></a></td>
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
<textarea style="display:none" name="debugField1" id="debugField1"><?php echo $leftcolumn_widgets; ?></textarea>
<textarea style="display:none" name="debugField2" id="debugField2"><?php echo $middlecolumn_widgets; ?></textarea>
<textarea style="display:none" name="debugField3" id="debugField3"><?php echo $rightcolumn_widgets; ?></textarea>

<input type="hidden" name="context" value="<?php echo get_context(); ?>" />
<input type="hidden" name="owner" value="<?php echo page_owner(); ?>" />

<?php
$ts = time();
$token = generate_action_token($ts);
?>
<input type="hidden" name="__elgg_ts" value="<?php echo $ts; ?>" />
<input type="hidden" name="__elgg_token" value="<?php echo $token; ?>" />

<input type="submit" value="<?php echo elgg_echo('save'); ?>" class="submit_button" onclick="$('a.toggle_customise_edit_panel').click();" />
<input type="button" value="<?php echo elgg_echo('cancel'); ?>" class="cancel_button" onclick="$('a.toggle_customise_edit_panel').click();" />

</form>
</div><!-- /customise_editpanel -->

<?php

		}

?>


<table cellspacing="0" id="widget_table">
<tr>
	<td colspan="2" align="left" valign="top" height="1px">
		<!-- profile box or 'dashboard info' notice -->
<?php 
		if (isset($vars['area1'])) {
			echo $vars['area1'];
		}
?>
	</td>
	<td rowspan="2" align="left" valign="top" height="100%">
		<?php
		if (get_loggedin_userid() == page_owner()) {
		?>
		<!-- customise page button -->
		<a href="javascript:void(0);" class="toggle_customise_edit_panel"><?php echo(elgg_echo('dashboard:configure')); ?></a>
		<!-- <div style="clear:both;"></div> -->
		<?php
		}
		?>

		<div id="widgets_right">
		<?php

			if (is_array($area3widgets) && sizeof($area3widgets) > 0)
			foreach($area3widgets as $widget) {
				echo elgg_view_entity($widget);
			}

		?>

		</div><!-- /#widgets_right -->
	</td>
</tr>
<tr>
	<td align="left" valign="top">

		<!-- left widgets -->
		<div id="widgets_left">

		<?php

			if (is_array($area1widgets) && sizeof($area1widgets) > 0)
			foreach($area1widgets as $widget) {
				echo elgg_view_entity($widget);
			}

		?>

		</div><!-- /#widgets_left -->

	</td>
	<td align="left" valign="top">

		<!-- widgets middle -->
		<div id="widgets_middle">

		<?php if (isset($vars['area2'])) echo $vars['area2']; ?>
		<?php

			if (is_array($area2widgets) && sizeof($area2widgets) > 0)
			foreach($area2widgets as $widget) {
				echo elgg_view_entity($widget);
			}

		?>

		</div><!-- /#widgets_middle -->

	</td>
	</tr>
</table>
