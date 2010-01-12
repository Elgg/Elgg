<?php
/**
 * Elgg core search.
 * Search listing view for RSS feeds.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd <info@elgg.com>, The MITRE Corporation <http://www.mitre.org>
 * @link http://elgg.org/
 */

$entities = $vars['results']['entities'];

if (!is_array($entities) || !count($entities)) {
	return FALSE;
}

foreach ($entities as $entity) {
	if ($view = search_get_search_view($vars['params'], 'entity')) {
		$body .= elgg_view($view, array(
			'entity' => $entity,
			'params' => $vars['params'],
			'results' => $vars['results']
		));
	}
}

echo $body;