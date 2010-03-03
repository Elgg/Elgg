<?php
	/**
	 * Elgg profile index
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */
	
	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

	admin_gatekeeper();
	set_context('admin');
	
	$title = elgg_view_title(elgg_echo('profile:edit:default'));
	$form = elgg_view('profile/editdefaultprofile');
	
	
	set_context('search');
	
	
	// List form elements
	$n = 0;
	$loaded_defaults = array();
	$listing .= "<div class=\"contentWrapper\">";
	while ($translation = get_plugin_setting("admin_defined_profile_$n", 'profile'))
	{
		$type = get_plugin_setting("admin_defined_profile_type_$n", 'profile');
		$listing .= elgg_view("profile/", array('value' => $translation));	
		
		$even_odd = ( 'odd' != $even_odd ) ? 'odd' : 'even';					
	
		$listing .= "<p class=\"{$even_odd}\"><b>$translation: </b>";
		$listing .= elgg_view("output/{$type}",array('value' => " [$type]"));
		$listing .= "</p>";
		
		$n++;
	}
	$listing .= "</div>";
	
	$listing .= "<div class=\"contentWrapper resetdefaultprofile\">" . elgg_view('input/form', 
		array(
			'body' => elgg_view('input/submit', array('value' => elgg_echo('profile:resetdefault'))), 
			'action' => $CONFIG->wwwroot . 'action/profile/editdefault/reset'
		)
	) . "</div>";
	
	set_context('admin');
	
	
	page_draw(elgg_echo('profile:edit:default'),elgg_view_layout("two_column_left_sidebar", '', $title . $form . $listing));
	
?>
