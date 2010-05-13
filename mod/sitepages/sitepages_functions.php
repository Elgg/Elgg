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
 * customizations for the $page_type site page.  The object guid
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

	$title = elgg_view_title(elgg_echo('sitepages'));
	$menu = elgg_view('sitepages/menu', array('page_type' => $page_type));

	switch ($page_type) {
		case 'front':
			$view = 'sitepages/forms/editfront';
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

	$content = elgg_view_layout('administration', $body);
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
 * Utility object to store site page information.
 */
class ElggSitePage extends ElggObject {
	public function initialise_attributes() {
		parent::initialise_attributes();

		$this->attributes['subtype'] = 'sitepages_page';
	}
}