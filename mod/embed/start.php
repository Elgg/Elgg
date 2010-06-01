<?php
/**
 * Elgg media embed plugin
 *
 * @package ElggEmbed
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.com/
 */

/**
 * Init function
 *
 */
function embed_init() {
	elgg_extend_view('css', 'embed/css');
	elgg_extend_view('js/initialise_elgg', 'embed/js');
	elgg_extend_view('metatags', 'embed/metatags');
	elgg_extend_view('input/longtext', 'embed/link', 1);

	// Page handler for the modal media embed
	register_page_handler('embed', 'embed_page_handler');
}

/**
 * Serves pages for upload and embed.
 *
 * @param $page
 */
function embed_page_handler($page) {
	if (!isset($page[0])) {
		$page[0] = 'embed';
	}

	switch($page[0]) {
		case 'upload':
			echo elgg_view('embed/upload');

			break;

		case 'embed':
		default:
			// trigger hook to get section tabs
			// use views for embed/section/
			//	listing
			//	item
			// default to embed/listing | item if not found.
			// @todo trigger for all right now. If we categorize these later we can trigger
			// for certain categories.
			$sections = trigger_plugin_hook('embed_get_sections', 'all', NULL, array());
			asort($sections, SORT_LOCALE_STRING);
			$active_section = get_input('active_section', NULL);

			echo elgg_view('embed/embed', array(
				'sections' => $sections,
				'active_section' => $active_section
			));

			break;
	}

	exit;
}

register_elgg_event_handler('init', 'system', 'embed_init');