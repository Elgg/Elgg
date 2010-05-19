<?php

	/**
	 * Elgg all group forum discussions page
	 * This page will show all topic dicussions ordered by last comment, regardless of which group
	 * they are part of
	 *
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */

	// Load Elgg engine
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

	// access check for closed groups
	group_gatekeeper();

	// Display them
		$area1 = elgg_view_title(elgg_echo("groups:latestdiscussion"));
		set_context('search');
		$area1 .= list_entities_from_annotations("object", "groupforumtopic", "group_topic_post", "", 40, 0, 0, false, true);
		set_context('groups');

		$body = elgg_view_layout("one_column_with_sidebar", $area1);

	// Display page
		page_draw(elgg_echo('groups:latestdiscussion'),$body);


?>