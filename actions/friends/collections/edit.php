<?php
/**
 * Friends collection edit action
 *
 * @package Elgg.Core
 * @subpackage Friends.Collections
 */

$collection_id = get_input('collection_id');
$friends = get_input('friend');

//chech the collection exists and the current user owners it
update_access_collection($collection_id, $friends);

exit;
