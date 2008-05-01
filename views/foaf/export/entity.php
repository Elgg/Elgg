<?php
	/**
	 * Elgg FOAF Entity export.
	 * Displays an ElggUser entity as FOAF.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	global $CONFIG;
	
	$entity = $vars['entity'];
	
	if (!($entity instanceof ElggUser))
		exit;

?>
<rdf:RDF
  xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
  xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
  xmlns:foaf="http://xmlns.com/foaf/0.1/">

  <foaf:Person>
  	<foaf:name><?php echo $entity->name; ?></foaf:name>
  	<foaf:mbox rdf:resource="<?php echo $entity->email; ?>"/>
  	<foaf:nick><?php echo $entity->username; ?></foaf:nick>
  	
  	<!--  TODO : more -->
  	
<?php
	
	// Get friends
	$friends = get_entities_from_relationship("friend", $entity->guid);

	// Iterate through and generate foaf:knows
	if ($friends)
	{
		echo "<foaf:knows>\n";
		
		foreach ($friends as $friend)
		{
			if ($friend instanceof ElggUser) 
			{
?>
				<foaf:Person>
					<foaf:name><?php echo $friend->name; ?></foaf:name>
  					<foaf:mbox rdf:resource="<?php echo $friend->email; ?>"/>
  					<rdfs:seeAlso rdf:resource="<?php echo guid_to_uuid($friend->guid); ?>"/>
				</foaf:Person>		
<?php		
			}	
		}

		echo "</foaf:knows>\n";
	}
?>
  </foaf:Person>
</rdf:RDF>