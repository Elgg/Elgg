<?php
/**
 * Elgg add a collection of friends
 *
 * @package Elgg
 * @subpackage Core
 */

// You need to be logged in for this one
gatekeeper();

$title = elgg_echo('friends:collectionedit');

$content = elgg_view_title($title);

//grab the collection id passed to the edit form
$collection_id = get_input('collection');

//get the full collection
$collection = get_access_collection($collection_id);

//get all members of the collection
$collection_members = get_members_of_access_collection($collection_id);

$content .= elgg_view('friends/forms/edit', array('collection' => $collection, 'collection_members' => $collection_members));

$body = elgg_view_layout('two_column_left_sidebar', '', $content);

page_draw($title, $body);
