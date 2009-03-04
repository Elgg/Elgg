<?php

	/**
	 * Elgg default user view
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
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
