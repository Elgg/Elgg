<?php
	/**
	 * Elgg XML output pageshell
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @author Curverider Ltd
	 * @link http://elgg.org/
	 * 
	 */

	header("Content-Type: text/xml");
	// echo $vars['body'];
	
	echo "<?xml version='1.0'?>\n";
	
	if (!$owner = page_owner_entity()) {
		if (!isloggedin()) {
			exit;
		} else {
			$owner = $vars['user'];
		}
	}
	
?>
<rdf:RDF
   xml:lang="en"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
   xmlns:foaf="http://xmlns.com/foaf/0.1/"
   xmlns:ya="http://blogs.yandex.ru/schema/foaf/"
   xmlns:geo="http://www.w3.org/2003/01/geo/wgs84_pos#"
   xmlns:dc="http://purl.org/dc/elements/1.1/">
   <foaf:Person>
    <foaf:nick><?php echo $owner->username; ?></foaf:nick>
    <foaf:name><?php echo $owner->name; ?></foaf:name>
    <foaf:homepage rdf:resource="<?php echo $owner->getURL(); ?>" />
    <foaf:mbox_sha1sum><?php echo sha1("mailto:" . $owner->email); ?></foaf:mbox_sha1sum>
    <foaf:img rdf:resource="<?php echo $vars['url']; ?>pg/icon/<?php echo $owner->username; ?>/large/icon.jpg" />
	<?php

		echo $vars['body'];
	
	?>
	</foaf:Person>	   
</rdf:RDF>