<?php
/**
 * Helper functions for Site Pages.
 *
 * @package SitePages
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 */


/**
 * Returns a single object that holds information about
 * customizations for the $section site page.  The object guid
 * is stored as private data on the site entity.  This allows the pages
 * to still be searchable as standard entities.
 *
 * @param $type
 * @return mixed ElggSitePage on success, FALSE on fail
 */
function sitepages_get_sitepage_object($page_type) {
	global $CONFIG;

	$page_guid = get_private_setting($CONFIG->site->getGUID(), "sitepages:$page_type");
	$sitepage = get_entity($page_guid);

	if ($sitepage instanceof ElggSitePage || $sitepage->page_type == $page_type) {
		return $sitepage;
	}

	return FALSE;
}

/**
 * Creates a site page object.
 *
 * @param str $page_type
 * @return mixed ElggSitePage on success, FALSE on fail.
 */
function sitepages_create_sitepage_object($page_type) {
	global $CONFIG;

	$sitepage = new ElggSitePage();
	$sitepage->page_type = $page_type;
	$sitepage->access_id = ACCESS_PUBLIC;
	$sitepage->save();

	if ($sitepage->save() && set_private_setting($CONFIG->site->getGUID(), "sitepages:$page_type", $sitepage->getGUID())) {
		return $sitepage;
	}

	return FALSE;
}

/**
 * Assembles html for edit sections of site pages.
 *
 * @param str $section
 * @return str html
 */
function sitepages_get_edit_section_content($page_type) {
	set_context('admin');

	$keywords = '';

	$title = elgg_view_title(elgg_echo('sitepages'));
	$menu = elgg_view('sitepages/menu', array('page_type' => $page_type));

	switch ($page_type) {
		case 'front':
			$view = 'sitepages/forms/editfront';
			$keywords = elgg_view('sitepages/keywords');
			break;

		case 'seo':
			$view = 'sitepages/forms/editmeta';
			break;

		default:
			$view = 'sitepages/forms/edit';
			break;

	}

	$form .= elgg_view($view, array('page_type' => $page_type));
	$body = $title .  $menu . $form;

	$content = elgg_view_layout('one_column_with_sidebar', $body, $keywords);
	return $content;
}

/**
 * Assembles html for displaying site pages
 *
 * @param string $page_type
 * @return string Formatted html
 */
function sitepages_get_page_content($page_type) {
	$body = elgg_view_title(elgg_echo("sitepages:". strtolower($page_type)));

	$sitepage = sitepages_get_sitepage_object($page_type);

	if ($sitepage) {
		$body .= elgg_view('page_elements/elgg_content', array('body' => $sitepage->description));
	} else {
		$body .= elgg_view('page_elements/elgg_content', array('body' => elgg_echo('sitepages:notset')));
	}

	$content = elgg_view_layout('one_column_with_sidebar', $body);
	return $content;
}


/**
 * Used to determine how to handle special non-static keywords.
 *
 * @param unknown_type $matches
 * @return html
 */
function sitepages_parse_view_match($matches) {
	$keyword = $matches[0];
	$type = trim($matches[1]);
	$params_string = trim($matches[2]);

	switch ($type) {
		case 'entity':
			$options = sitepages_keywords_parse_entity_params($params_string);
			// must use this lower-level function because I missed refactoring
			// the list entity functions for relationships.
			// (which, since you're here, is the only function that runs through all
			// possible options for elgg_get_entities*() functions...)
			$entities = elgg_get_entities_from_relationship($options);
			$content = elgg_view_entity_list($entities, count($entities), $options['offset'],
				$options['limit'], $options['full_view'], $options['view_type_toggle'], $options['pagination']);
			break;

		case 'view':
			// parses this into an acceptable array for $vars.
			$info = sitepages_keywords_parse_view_params($params_string);
			$content = elgg_view($info['view'], $info['vars']);

			break;

	}

	return $content;
}

/**
 * Creates an array from a "name=value, name1=value2" string.
 *
 * @param $string
 * @return array
 */
function sitepages_keywords_tokenize_params($string) {
	$pairs = array_map('trim', explode(',', $string));

	$params = array();

	foreach ($pairs as $pair) {
		list($name, $value) = explode('=', $pair);

		$name = trim($name);
		$value = trim($value);
		$params[$name] = $value;
	}

	return $params;
}

/**
 *
 * @param $string
 * @return unknown_type
 */
function sitepages_keywords_parse_view_params($string) {
	$vars = sitepages_keywords_tokenize_params($string);

	// the first element key is the view
	$var_keys = array_keys($vars);
	$view = $var_keys[0];

	$info = array(
		'view' => $view,
		'vars' => $vars
	);

	return $info;

}

/**
 * Returns an options array suitable for using in elgg_get_entities()
 *
 * @param string $string "name=value, name2=value2"
 * @return array
 */
function sitepages_keywords_parse_entity_params($string) {
	$params = sitepages_keywords_tokenize_params($string);

	// handle some special cases
	if (isset($params['owner'])) {
		if ($user = get_user_by_username($params['owner'])) {
			$params['owner_guid'] = $user->getGUID();
		}
	}

	// @todo probably need to add more for
	// group -> container_guid, etc
	return $params;
}



/**
 * Utility object to store site page information.
 */
class ElggSitePage extends ElggObject {
	public function initialise_attributes() {
		parent::initialise_attributes();

		$this->attributes['subtype'] = 'sitepages_page';
	}
}