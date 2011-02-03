<?php
/**
 * Helper functions for Site Pages.
 *
 * @package SitePages
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

	$page_guid = get_private_setting(elgg_get_site_entity()->guid, "sitepages:$page_type");
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

	$sitepage = new ElggSitePage();
	$sitepage->page_type = $page_type;
	$sitepage->access_id = ACCESS_PUBLIC;
	$sitepage->save();

	$site = elgg_get_site_entity();

	if ($sitepage->save() && set_private_setting($site->getGUID(), "sitepages:$page_type", $sitepage->getGUID())) {
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
function sitepages_get_edit_section_content($page_type, $entity=NULL) {
	$menu = elgg_view('sitepages/menu', array('page_type' => $page_type));

	switch ($page_type) {
		case 'front':
			$view = 'sitepages/forms/editfrontsimple';
			break;

		case 'seo':
			$view = 'sitepages/forms/editmeta';
			break;

		default:
			$view = 'sitepages/forms/edit';
			break;

	}

	$form .= elgg_view($view, array(
		'page_type' => $page_type,
		'entity' => $entity,
	));
	return $menu . $form;
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
		$body .= $sitepage->description;
	} else {
		$body .= elgg_echo('sitepages:notset');
	}

	$content = elgg_view_layout('one_sidebar', array('content' => $body));
	return $content;
}

/**
 * Utility object to store site page information.
 */
class ElggSitePage extends ElggObject {
	public function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = 'sitepages_page';
	}
}