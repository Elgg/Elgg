<?php

	/**
	 * Elgg widget wrapper
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	if ($vars['entity'] instanceof ElggObject && $vars['entity']->subtype = 'widget') {
		$handler = $vars['entity']->handler;
		$title = $vars['entity']->title;
	} else {
		$handler = "error";
		$title = elgg_echo("error"); 
	}
	
?>

	<div class="collapsable_box">
	<div class="collapsable_box_header">
	<a href="javascript:void(0);" class="toggle_box_contents">-</a><a href="javascript:void(0);" class="toggle_box_edit_panel"><?php echo elgg_echo('edit'); ?></a>
	<h1><?php

		echo $vars['entity']->title;
	
	?></h1></div>
	<div class="collapsable_box_editpanel"><?php 
		
		echo elgg_view('widgets/editwrapper', 
						array(
								'body' => elgg_view("widgets/{$handler}/edit",$vars),
								'entity' => $vars['entity']
							  )
					   ); 
		
	?></div><!-- /collapsable_box_editpanel -->
	<div class="collapsable_box_content">
		<?php echo elgg_view("widgets/{$handler}/view",$vars); ?>
	</div><!-- /.collapsable_box_content -->
	</div><!-- /.collapsable_box -->