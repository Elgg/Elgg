<?php
/*
 * Created on Dec 1, 2007
 *
 * @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
 * @copyright Corporación Somos más - 2007
 */

global $page_owner, $USER, $CFG;

if (isset ($parameter)) {
	$selected = $page_owner;
	$field_label = __gettext("Assign to:");
	$options = array ();

	$options[$USER->ident] = __gettext("Own");
	if ($communities = get_records_select('users', 'owner = ? AND user_type = ?', array ($USER->ident,'community'))) {
		foreach ($communities as $community) {
			$options[$community->ident] = __gettext("Community") . ": " . $community->name;
		}
	}

	if ($communities = get_records_sql("SELECT u.* FROM " . $CFG->prefix . "friends f
		                                    JOIN " . $CFG->prefix . 'users u ON u.ident = f.friend 
		                                    WHERE u.user_type = ? AND u.owner <> ? AND f.owner = ?', array ('community',$USER->ident,$USER->ident))) {
		foreach ($communities as $community) {
			$options[$community->ident] = __gettext("Community") . ": " . $community->name;
		}
	}

	if ($selected == $USER->ident && count($options)>1) {
		$run_result .= templates_draw(array (
			'context' => 'databoxvertical',
			'name' => $field_label,
			'contents' => display_input_field(array (
				'assign_to',
				$selected,
				'select_associative',
				null,
				null,
				null,
				$options
			)
		)));
	}

}
?>
