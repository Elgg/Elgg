<?php
/**
 * Elgg default user view
 *
 * @package Elgg
 * @subpackage Core
 */

$user = $vars['entity'];
$friends = $user->getFriends(array('limit' => 0));
foreach ($friends as $friend) {
?>

<foaf:knows>
<foaf:Person>
	<foaf:nick><?php echo $friend->username; ?></foaf:nick>
	<foaf:name><?php echo $friend->name; ?></foaf:name>
	<rdfs:seeAlso rdf:resource="<?php echo $friend->getURL() . "?view=foaf" ?>" />
</foaf:Person>
</foaf:knows>

<?php

}
