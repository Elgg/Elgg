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

		if (is_array($widgettypes) && sizeof($widgettypes) > 0) {
			
?>

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