<?php

	/**
	 * Elgg default user view
	 * 
	 * @package Elgg
	 * @subpackage Core

	 * @author Curverider Ltd

	 * @link http://elgg.org/
	 */

		$friend = $vars['entity'];

?>

    <foaf:knows>
      <foaf:Person>
        <foaf:nick><?php echo $friend->username; ?></foaf:nick>
        <foaf:member_name><?php echo $friend->name; ?></foaf:member_name>
        <foaf:mbox_sha1sum><?php echo sha1("mailto:" . $friend->email); ?></foaf:mbox_sha1sum>
        <rdfs:seeAlso rdf:resource="<?php echo $vars['url'] . "pg/friends/" . $friend->username . "/?view=foaf" ?>" />
        <foaf:homepage rdf:resource="<?php echo $friend->getURL(); ?>?view=foaf"/>
      </foaf:Person>
    </foaf:knows>
