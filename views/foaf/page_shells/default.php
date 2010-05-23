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
	xmlns:geo="http://www.w3.org/2003/01/geo/wgs84_pos#"
	xmlns:dc="http://purl.org/dc/elements/1.1/">

	<rdf:Description rdf:about="">
		<rdf:type rdf:resource="http://xmlns.com/foaf/0.1/PersonalProfileDocument"/>
		<foaf:maker rdf:resource="#me"/>
		<foaf:primaryTopic rdf:resource="#me"/>
	</rdf:Description>

	<foaf:Person rdf:about="#me">
		<foaf:nick><?php echo $owner->username; ?></foaf:nick>
		<foaf:name><?php echo $owner->name; ?></foaf:name>
		<foaf:homepage rdf:resource="<?php echo $owner->getURL(); ?>" />
		<foaf:depiction rdf:resource="<?php echo elgg_format_url($owner->getIcon('large')); ?>" />
                <foaf:weblog rdf:resource="<?php echo $vars['url']; ?>pg/blog/<?php echo $owner->username; ?>" />
		<?php
			echo $vars['body'];
		?>
	</foaf:Person>
</rdf:RDF>
