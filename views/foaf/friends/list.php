<?php

	/**
	 * Elgg friends list (FOAF)
	 * Lists a user's friends
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['friends'] The array of ElggUser objects
	 */

		if (is_array($vars['friends']) && sizeof($vars['friends']) > 0) {
			
			foreach($vars['friends'] as $friend) {
				
?>

    <foaf:knows>

      <foaf:Person>
        <foaf:nick><?php echo $friend->username; ?></foaf:nick>
        <foaf:member_name><?php echo $friend->name; ?></foaf:member_name>
        <rdfs:seeAlso rdf:resource="<?php echo $vars['url'] . "friends/" . $friend->username . "/?view=foaf" ?>" />
        <foaf:homepage rdf:resource="<?php echo $friend->getURL(); ?>"/>
      </foaf:Person>
    </foaf:knows>


<?php
				
			}
			
		}

?>