<?php

	/**
	 * Elgg default widget layout
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * 
	 * Edited version of the widget editor to be used to set default widgets
 	 * 
 	 **/

		// set some defaults
		$owner = page_owner_entity();
		$context = $vars['context'];
		$area1widgets = false;
		$area2widgets = false;
		$area3widgets = false;

		// get available widget types
		set_context ( $context );
		$widgettypes = get_widget_types();
		set_context ( 'admin' );		
		
		// get the entities for the module
		$entities = elgg_get_entities(array('type' => 'object', 'subtype' => 'moddefaultwidgets', 'limit' => 9999));
		
		// check if the entity exists
		if(isset($entities[0])) {
			
			// get the widgets for the context
			$entity = $entities[0];
			$current_widgets =  $entity->$context;
			list($left, $middle, $right) = split('%%',$current_widgets);
			
			// split columns into seperate widgets
			$area1widgets = split('::',$left);
			$area2widgets = split('::',$middle);
			$area3widgets = split('::',$right);
		
			// clear out variables if no widgets are available
			if ($area1widgets[0] == "") $area1widgets = false;
			if ($area2widgets[0] == "") $area2widgets = false;
			if ($area3widgets[0] == "") $area3widgets = false;
		}
			
?>
<script type="text/javascript">
$(document).ready(function () {
	$('div#customise_editpanel').slideToggle("fast");
});
</script>

<div id="customise_editpanel" style="visibility:visible;">

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
		<input type="hidden" name="handler" value="<?php echo htmlentities($handler); ?>" />
		<input type="hidden" name="description" value="<?php echo htmlentities($widget->description, null, 'UTF-8'); ?>" />
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
<h2><?php echo elgg_echo('defaultwidgets:'.$context.':title'); ?></h2>
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
					$rightcolumn_widgets .= "{$widget}::0";
		?>
		
		<table class="draggable_widget" cellspacing="0"><tr><td width="149px">
			<h3>
				<?php echo $widgettypes[$widget]->name; ?>
				<input type="hidden" name="handler" value="<?php 
					echo $widget; 
				?>" />
				<input type="hidden" name="multiple" value="<?php echo $widgettypes[$widget]->multiple; ?>" />
				<input type="hidden" name="side" value="<?php echo in_array('side',$widgettypes[$widget]->positions); ?>" />
				<input type="hidden" name="main" value="<?php echo in_array('main',$widgettypes[$widget]->positions); ?>" />
				<input type="hidden" name="description" value="<?php echo htmlentities($widgettypes[$widget]->description); ?>" />
				<input type="hidden" name="guid" value="0" />
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
			$leftcolumn_widgets .= "{$widget}::0";
?>

<table class="draggable_widget" cellspacing="0"><tr><td width="149px">
	<h3>
		<?php echo $widgettypes[$widget]->name; ?>
		<input type="hidden" name="handler" value="<?php 
			echo $widget; 
		?>" />
		<input type="hidden" name="multiple" value="<?php echo $widgettypes[$widget]->multiple; ?>" />
		<input type="hidden" name="side" value="<?php echo in_array('side',$widgettypes[$widget]->positions); ?>" />
		<input type="hidden" name="main" value="<?php echo in_array('main',$widgettypes[$widget]->positions); ?>" />
		<input type="hidden" name="description" value="<?php echo htmlentities($widgettypes[$widget]->description); ?>" />
		<input type="hidden" name="guid" value="0" />
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
			$middlecolumn_widgets .= "{$widget}::0";
?>

<table class="draggable_widget" cellspacing="0"><tr><td width="149px">
	<h3>
		<?php echo $widgettypes[$widget]->name; ?>
		<input type="hidden" name="handler" value="<?php 
			echo $widget; 
		?>" />
		<input type="hidden" name="multiple" value="<?php echo $widgettypes[$widget]->multiple; ?>" />
		<input type="hidden" name="side" value="<?php echo in_array('side',$widgettypes[$widget]->positions); ?>" />
		<input type="hidden" name="main" value="<?php echo in_array('main',$widgettypes[$widget]->positions); ?>" />
		<input type="hidden" name="description" value="<?php echo htmlentities($widgettypes[$widget]->description); ?>" />
		<input type="hidden" name="guid" value="0" />
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

<form action="<?php echo $vars['url']; ?>action/defaultwidgets/update" method="post">
<textarea type="textarea" value="Left widgets"   style="display:none" name="debugField1" id="debugField1" /><?php echo $leftcolumn_widgets; ?></textarea>
<textarea type="textarea" value="Middle widgets" style="display:none" name="debugField2" id="debugField2" /><?php echo $middlecolumn_widgets; ?></textarea>
<textarea type="textarea" value="Right widgets"  style="display:none" name="debugField3" id="debugField3" /><?php echo $rightcolumn_widgets; ?></textarea>
<?php
	echo elgg_view('input/hidden',array('internalname' => '__elgg_token', 'value' => $vars['token']));
	echo elgg_view('input/hidden',array('internalname' => '__elgg_ts', 'value' => $vars['ts']));
?>
<input type="hidden" name="context" value="<?php echo $context; ?>" />
<input type="hidden" name="owner" value="<?php echo page_owner(); ?>" />
<input type="submit" value="<?php echo elgg_echo('save'); ?>" class="submit_button"  />

</form>
</div><!-- /customise_editpanel -->
